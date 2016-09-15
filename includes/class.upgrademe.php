<?php 
/*
Plugin Name: upgrademe
Description: Allows auto-upgrade for plugins outside of official WordPress repository
Company: Copyright 2011 Meglio. All rights reserved
Version: 0.2
Author: Meglio (Anton Andriyevskyy) modified  by ReversedK (Ludovic Desert)
Plugin URI: https://github.com/ReversedK/wp-upgrademe
*/

if (!class_exists('Upgrademe')) {

class Upgrademe
{
	/**
	 * Stores parsed and validated data returned by unofficial APIs.
	 * @var array
	 */
	private static $data;

	private static $WP_FILTER_PREFIX = 'wpFilter_';

	public static function register()
	{
		$r = new ReflectionClass(__CLASS__);
		$methods = $r->getMethods(ReflectionMethod::IS_PUBLIC);
		foreach($methods as $m)
		{			
			/** @var ReflectionMethod $m */
			if ($m->isStatic() && strpos($m->getName(), self::$WP_FILTER_PREFIX) === 0) {
				add_filter(substr($m->getName(), strlen(self::$WP_FILTER_PREFIX)), array(get_class(), $m->getName()),
					10, $m->getNumberOfParameters());
			}
		}
	}

	public static function wpFilter_http_response($response, $args, $url)
	{
		# Control recursion
		static $recursion = false;
		if ($recursion)
			return $response;

		if (empty($response) || !is_array($response) || !isset($response['body']))
			return $response;

		# Guess if it's time to take action
		if ($url == 'http://api.wordpress.org/plugins/update-check/1.0/')
			$showTime = true;
		# Prevent failures if WordPress changes url for updates; we will detect if it still contains "update-check" token
		# and called from withing wp_update_plugins() function
		elseif (stripos($url, 'update-check') !== false)
		{
			$showTime = false;
			$trace = debug_backtrace(false);
			foreach($trace as $t)
				# http request made from within wp_update_plugins
				if (isset($t['function']) && $t['function'] == 'wp_update_plugins')
				{
					$showTime = true;
					break;
				}
			unset($trace, $t);
		}
		else
			$showTime = false;
		if (!$showTime)
			return $response;

		# Loop over plugins who provided <pluginName>_upgrademe() function and use returned url to request for up-to-date version signature.
		# Collect retrieved (only valid) data into $upgrademe
		$plugins = get_plugins();
		$upgrademe = array();
		
		foreach($plugins as $file => $info)
		{
			# Get url if function exists
			$slugName = str_replace('-', '_', basename($file, '.php'));

			# Request latest version signature from custom url (non-WP plugins repository api) && validate response variables
			$recursion = true;
			$vars = self::loadPluginData($slugName);
			$recursion = false;
			if (empty($vars))
				continue;

			$upgrademe[$file] = $vars;
		}
		if (!count($upgrademe))
			return $response;

		$body = $response['body'];

		if (!empty($body))
			$body = json_decode($body,true);
		if (empty($body))
			$body = array();
		

		foreach($upgrademe as $file => $upgradeVars)
		{
		
			# Do not override data returned by official WP plugins repository API
			if (isset($body['plugins'][$file]))	continue;

			# If new version is different then current one, only then add info
			if (!isset($plugins[$file]['Version']) || $plugins[$file]['Version'] == $upgradeVars['new_version'])
				continue;

			$upgradeInfo =  array();
			$upgradeInfo['id'] = $upgradeVars['id'];
			$upgradeInfo['slug'] = $upgradeVars['slug'];
			$upgradeInfo['new_version'] = $upgradeVars['new_version'];
			$upgradeInfo['url'] = $upgradeVars['url'];
			$upgradeInfo['package'] = $upgradeVars['package'];
			$body['plugins'][$file] = $upgradeInfo;			
		}
		$response['body'] = json_encode($body);
		return $response;
	}

	public static function wpFilter_plugins_api($value, $action, $args)
	{
		// If for some reason value available already, do not change it
		if (!empty($value))
			return $value;

		if ($action != 'plugin_information' || !is_object($args) || !isset($args->slug) || empty($args->slug))
			return $value;

		$vars = self::loadPluginData($args->slug);
		if (empty($vars))
			return $value;

		return (object)$vars['info'];
	}

	public static function wpFilter_http_request_args($args, $url)
	{
		if (strpos($url, 'wp-upgrademe') === false || !is_array($args))
			return $args;

		$args['sslverify'] = false;
		return $args;
	}

	private static function loadPluginData($slug)
	{
		if (isset(self::$data[$slug]))
			return self::$data[$slug];

		$funcName = $slug.'_upgrademe';
		if (!function_exists($funcName))
			return self::$data[$slug] = null;

		$upgradeUrl = filter_var(call_user_func($funcName), FILTER_VALIDATE_URL);
		if (empty($upgradeUrl))
			return self::$data[$slug] = null;

		# Request latest version signature from custom url (non-WP plugins repository api) && validate response variables
		$r = wp_remote_post($upgradeUrl, array('method' => 'POST', 'timeout' => 4, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true,
			'headers' => array(),	'body' => null, 'cookies' => array(), 'sslverify' => false));

		if( is_wp_error($r) || !isset($r['body']) || empty($r['body']))
			return self::$data[$slug] = null;

		$vars = json_decode($r['body'], true);
		if (empty($vars) || !is_array($vars) || count($vars) > 4
				|| !isset($vars['new_version']) || !isset($vars['url']) || !isset($vars['package']) || !isset($vars['info']))
			return self::$data[$slug] = null;

		# 2 147 483 648 - max int32
		#    16 777 215 - ffffff = max possible value of 6-letters hex
		#    50 000 000 - reasonable offset
		# Finally generate ID between 50 000 000 and 66 777 215
		$vars['id'] = 50000000 + hexdec(substr(md5($slug), 1, 6));

		$vars['slug'] = $slug;

		# Sanitize variables of "info"
		if (!is_array($vars['info']))
			$vars['info'] = array();

		$info = array();
		foreach($vars['info'] as $key => $val)
		{
			if (!in_array($key, array('name','slug','version','author','author_profile','contributors','requires','tested',
																'compatibility','rating','rating','num_ratings','downloaded','last_updated','added','homepage',
																'sections','download_link','tags')))
				continue;
			$info[$key] = $val;
		}
		$info['slug'] = $slug;
		$info['version'] = $vars['new_version'];
		$info['download_link'] = $vars['url'];
		$vars['info'] = $info;

		return self::$data[$slug] = $vars;
	}
}

add_action("plugins_loaded",'Upgrademe::register',1000);

} # class_exists()