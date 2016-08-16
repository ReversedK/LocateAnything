<!-- HTML -->
<div id="locate-anything-template-wrapper">
<div id="bloc1">
[filters]
[navlist]
</div>
<div id="bloc2">
[map]
</div>
</div>

<!-- CSS -->
<style>
#bloc1 {
    width:33%;
    float: left;
    background:#fff;   
    font-family: Verdana;
    font-style: 16px;
    padding:0;
    border:1px solid #eaeaea;

}

#bloc2 {
    width:67%;
    float: left;
    clear: none;
}

/** MAP CONTAINER */
.leaflet-container {    
    height: 1000px !important;
    float: left;
    width: 100%;
}

/** FILTERS */

/* checkboxes */
.LA_filters_checkbox {
font-size: 14px;
float: left;
margin-right:5px;
}





/* Main filter wrapper*/
.category-filters-container {
margin: 0;
padding: 15px;
float: left;
width: 100%;
background-color: #db6a34;
color: #eee;
}

/* filter container */
.category-filters-container li {
    padding-bottom: 1em;
    float: left;
    width: 38%;
    padding: 10px;
    min-width: 150px;
    margin: 0 1em;
}

/* filter title*/
.category-filters-container li b {
width: 100% !important;
float: left;
margin-bottom: 5px;
font-family: sans-serif;
font-size: 14px;
}


/** NAV LIST */

/* outer wrapper*/
.map-nav-wrapper {
height: 809px;
overflow: auto;
width: 100%;
}
/* inner wrapper*/
.map-nav-item-wrapper {padding: 15px}
.map-nav-item-wrapper div{
  text-align:justify;
  margin: 0;
  float: left; 
  font-size:13px;
}

/* list item*/
.map-nav-item {
    background: #ffffff none repeat scroll 0 0;
    border: none !important;
    box-shadow: none !important;
    border-bottom: 1px solid #eaeaea !important;
    color: #111111;
    cursor: pointer;
    float: left;
    font-size: 15px !important;   
    padding:  10px;
    text-decoration: none;
    float: left;
    margin: 0;
    padding-left: 0px
}

/* list item active*/
.map-nav-item:hover,.map-nav-item.focus {
    color:inherit !important;
    background-color: #f4f9fc;
   }

.map-nav-item:last-child { border-bottom:0 !important;}

/* list item styles */
.map-nav-item b { 
    color: #2d5be3;
    cursor: pointer;
    text-decoration: none;
    text-transform: capitalize;
    width:100%;
    font-size: 20px !important;
    font-weight: normal;
    float: left;
}

.map-nav-item span { width:100%;float: left;color: #aeb4b6;font-size: 11px;}
.map-nav-item img {float:right;max-width:25%;}

/* Mask for list img */
.map-nav-item-wrapper div#mask {
width: 100%;
height: 100%;
overflow: hidden;
margin-left: 10px;
float: right;
}

.map-nav-item-wrapper div#mask img{
width: 100%
}


/*
.map-nav-item-wrapper div#mask {
width: 100%;
height: 100%;
overflow: hidden;
margin-left: 10px;
float: left;
margin-bottom: 10px
}*/



.map-nav-pagination {
margin-left: 10px
}
.locate-anything-page-nav {
    font-family: Verdana;
    font-size: 12px !important;
    float: left;
color: #2d5be3;
margin-right: 5px;
width:auto;
}

/** TOKENIZE  */
.TokensContainer li { min-width: 0;
    width: auto;
    height: auto !important;
}
div.Tokenize {
width: 90%;
max-width:15em;
}
#locate-anything-template-wrapper {
    overflow: hidden;
    width: 100%;  
}
</style>