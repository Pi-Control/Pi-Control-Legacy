<?php
header('Content-type: text/css');

function set_eTagHeaders($file, $timestamp) {
	$gmt_mTime = gmdate('r', $timestamp);
 
	header('Cache-Control: public');
	header('ETag: "' . md5($timestamp . $file) . '"');
	header('Last-Modified: ' . $gmt_mTime);
 
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
		if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $file)) {
			header('HTTP/1.1 304 Not Modified');
			exit();
		}
	}
}
//"

set_eTagHeaders(__FILE__, filemtime(__FILE__));

$colors['red'] =		array('#FFEBEE', '#FFCDD2', '#EF9A9A', '#E57373', '#EF5350', '#F44336', '#E53935', '#D32F2F', '#C62828', '#B71C1C');
$colors['pink'] =		array('#FCE4EC', '#F8BBD0', '#F48FB1', '#F06292', '#EC407A', '#E91E63', '#D81B60', '#C2185B', '#AD1457', '#880E4F');
$colors['purple'] =		array('#F3E5F5', '#E1BEE7', '#CE93D8', '#BA68C8', '#AB47BC', '#9C27B0', '#8E24AA', '#7B1FA2', '#6A1B9A', '#4A148C');
$colors['deepPurple'] =	array('#EDE7F6', '#D1C4E9', '#B39DDB', '#9575CD', '#7E57C2', '#673AB7', '#5E35B1', '#512DA8', '#4527A0', '#311B92');
$colors['indigo'] =		array('#E8EAF6', '#C5CAE9', '#9FA8DA', '#7986CB', '#5C6BC0', '#3F51B5', '#3949AB', '#303F9F', '#283593', '#1A237E');
$colors['blue'] =		array('#E3F2FD', '#BBDEFB', '#90CAF9', '#64B5F6', '#42A5F5', '#2196F3', '#1E88E5', '#1976D2', '#1565C0', '#0D47A1');
$colors['lightBlue'] =	array('#E1F5FE', '#B3E5FC', '#81D4FA', '#4FC3F7', '#29B6F6', '#03A9F4', '#039BE5', '#0288D1', '#0277BD', '#01579B');
$colors['cyan'] =		array('#E0F7FA', '#B2EBF2', '#80DEEA', '#4DD0E1', '#26C6DA', '#00BCD4', '#00ACC1', '#0097A7', '#00838F', '#006064');
$colors['teal'] =		array('#E0F2F1', '#B2DFDB', '#80CBC4', '#4DB6AC', '#26A69A', '#009688', '#00897B', '#00796B', '#00695C', '#004D40');
$colors['green'] =		array('#E8F5E9', '#C8E6C9', '#A5D6A7', '#81C784', '#66BB6A', '#4CAF50', '#43A047', '#388E3C', '#2E7D32', '#1B5E20');
$colors['lightGreen'] =	array('#F1F8E9', '#DCEDC8', '#C5E1A5', '#AED581', '#9CCC65', '#8BC34A', '#7CB342', '#689F38', '#558B2F', '#33691E');
$colors['lime'] =		array('#F9FBE7', '#F0F4C3', '#E6EE9C', '#DCE775', '#D4E157', '#CDDC39', '#C0CA33', '#AFB42B', '#9E9D24', '#827717');
$colors['yellow'] =		array('#FFFDE7', '#FFF9C4', '#FFF59D', '#FFF176', '#FFEE58', '#FFEB3B', '#FDD835', '#FBC02D', '#F9A825', '#F57F17');
$colors['amber'] =		array('#FFF8E1', '#FFECB3', '#FFE082', '#FFD54F', '#FFCA28', '#FFC107', '#FFB300', '#FFA000', '#FF8F00', '#FF6F00');
$colors['orange'] =		array('#FFF3E0', '#FFE0B2', '#FFCC80', '#FFB74D', '#FFA726', '#FF9800', '#FB8C00', '#F57C00', '#EF6C00', '#E65100');
$colors['deepOrange'] =	array('#FBE9E7', '#FFCCBC', '#FFAB91', '#FF8A65', '#FF7043', '#FF5722', '#F4511E', '#E64A19', '#D84315', '#BF360C');
$colors['brown'] =		array('#EFEBE9', '#D7CCC8', '#BCAAA4', '#A1887F', '#8D6E63', '#795548', '#6D4C41', '#5D4037', '#4E342E', '#3E2723');
$colors['grey'] =		array('#FAFAFA', '#F5F5F5', '#EEEEEE', '#E0E0E0', '#BDBDBD', '#9E9E9E', '#757575', '#616161', '#424242', '#212121');
$colors['blueGrey'] =	array('#ECEFF1', '#CFD8DC', '#B0BEC5', '#90A4AE', '#78909C', '#607D8B', '#546E7A', '#455A64', '#37474F', '#263238');

$colorPallet = $colors['blue'];
?>
@charset "utf-8";
/* CSS Document */

a {
	color: <?php echo $colorPallet[9]; ?>;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

body {
	color: #333333;
	background: #EEEEEE;
	font-family: Arial, Helvetica, Verdana, sans-serif;
	font-size: 13px;
	margin: 0px;
	overflow: scroll;
	overflow-x: auto;
	padding: 0px;
}

#header {
	background: <?php echo $colorPallet[7]; ?>;
	box-shadow: 0px 0px 10px #666666;
	z-index: 50000;
}

#header-top {
	background: <?php echo $colorPallet[8]; ?>;
}

#header-top-inner {
	color: #DDDDDD;
	display: table;
	font-size: 12px;
	margin: 0px auto 0px;
	max-width: 900px;
}

#header-top-inner-row {
	display: table-row;
}

.header-top-inner-cell {
	display: table-cell;
}

.header-top-inner-cell a {
	color: #DDDDDD;
	padding: 5px;
	transition: all 100ms linear;
}

.header-top-inner-cell a:hover {
	color: #FFFFFF;
	background: <?php echo $colorPallet[7]; ?>;
	text-decoration: none;
}

.header-top-inner-username {
	text-align: right;
	padding: 5px;
	padding-right: 15px;
}

.header-top-inner-logout {
	width: 1%;
}

#inner-header {
	background: url('../img/logo.svg') left 15px center no-repeat;
	background-size: auto 60%;
	margin: 0 auto;
	max-width: 900px;
	text-align: right;
}

#header-navi {
	display: inline-block;
}

#header-navi > a, #header-navi > div > a {
	color: #FFFFFF;
	display: inline-block;
	font-weight: bold;
	text-decoration: none;
	padding: 15px 15px 11px 15px;
	border-bottom: 4px solid transparent;
	transition: all 100ms linear;
}

#header-navi > a:hover, #header-navi > div:hover > a  {
	background: <?php echo $colorPallet[8]; ?>;
	border-bottom: 4px solid <?php echo $colorPallet[9]; ?>;
}

#header-navi .navi-dropdown {
	display: inline-block;
	text-align: left;
	position: relative;
}

#header-navi .navi-dropdown-container {
	background: <?php echo $colorPallet[9]; ?>;
	opacity: 0;
	visibility: hidden;
	min-width: 100px;
	position: absolute;
	transition: 100ms linear;
	transform: translateY(-5px);
	right: 0px;
}

#header-navi .navi-dropdown:hover .navi-dropdown-container {
	opacity: 1;
	visibility: visible;
	transform: translateY(0px);
}

#header-navi .navi-dropdown-container a {
	color: #FFFFFF;
	display: block;
	font-weight: bold;
	padding: 15px;
	text-decoration: none;
	transition: all 100ms linear;
}

#header-navi .navi-dropdown-container a:hover {
	background: <?php echo $colorPallet[8]; ?>;
}

#header-mobile ~ #inner-header > label::before {
	display: none;
}

#header-mobile ~ #inner-header > label {
	display: none;
}

#content {
	margin: 0 auto;
	padding-top: 20px;
	max-width: 900px;
	min-height: 500px;
}

#footer {
	background: #FFFFFF;
	border-top: 1px solid #DDDDDD;
	min-height: 100px;
	font-size: 12px;
}

#footer-inner {
	margin: 0 auto;
	max-width: 900px;
}

#footer-table {
	margin: 20px auto;
	border-spacing: 0px;
}

#footer-table tr td, #footer-table tr th {
	padding: 4px;
}

#footer-table tr th {
	text-align: left;
	padding-bottom: 10px;
}

#footer-table tr td {
	vertical-align: top;
	padding: 0px;
}

#footer-table tr:nth-child(2) td:first-child {
	padding-right: 50px;
}

#footer-table tr th:nth-child(2) {
	padding-right: 54px;
}

#footer-table tr:nth-child(2) td a {
	display: inline-block;
	text-decoration: none;
	padding: 4px;
	transition: all 100ms linear;
}

#footer-table tr:nth-child(2) td a:hover {
	text-decoration: none;
	background: <?php echo $colorPallet[7]; ?>;
	color: #FFFFFF;
	border-radius: 2px;
}

#footer-table tr:nth-child(2) td:last-child {
	padding: 4px;
}

#footer-table tr:last-child td {
	vertical-align: bottom;
}

#footer-table tr:last-child td strong {
	display: block;
	padding: 4px;
	padding-bottom: 10px;
}

#footer-table tr:last-child td span {
	display: block;
	padding: 4px;
}

#footer-copyright {
	border-top: 1px solid #EEEEEE;
	margin: 0px auto;
	padding: 20px;
	text-align: center;
	width: 60%;
}

.box {
	background: #FFFFFF;
	border-radius: 2px;
	margin-bottom: 20px;
}

.box .inner-header {
}

.box .inner-header span {
	color: <?php echo $colorPallet[7]; ?>;
	display: inline-block;
	font-size: 18px;
	font-weight: bold;
	padding: 15px 15px 15px 15px;
}

.box .inner, .box .inner-end {
	padding: 0px 15px 15px 15px;
}

.box .inner-single {
	padding: 15px;
}

.box .inner-info {
	background: <?php echo $colorPallet[1]; ?>;
	margin-bottom: 15px;
	display: table;
	width: 100%;
	display: inline-block;
}

.box .inner-info::before {
	background: url('../img/info-icon.svg') no-repeat center center <?php echo $colorPallet[6]; ?>;
	background-size: 40% auto;
	content: " ";
	display: table-cell;
	height: 100%;
	width: 60px;
}

.box .inner-info > div {
	display: table-cell;
	padding: 15px;
	color: <?php echo $colorPallet[7]; ?>;
}

.box .inner-end {
	text-align: right;
}

.box .inner-table {
	padding: 0px 0px 15px 0px;
}

.box .inner-table > div, .box .inner-table > span, .box .inner-table > strong  {
	margin: 0px 15px 0px 15px;
}

.box .inner-navi {
	text-align: center;
}

.box .inner-navi a {
	font-weight: bold;
	padding: 10px;
	display: block;
	transition: all 100ms linear;
}

.box .inner-navi a:hover {
	background: <?php echo $colorPallet[8]; ?>;
	text-decoration: none;
	color: #FFFFFF;
}

.container-600 {
	max-width: 600px;
}

.sidebar {
	float: right;
	width: 280px;
}

.table {
	border-spacing: 0px;
	width: 100%;
}

.table th {
	background: <?php echo $colorPallet[6]; ?>;
	color: #FFFFFF;
	padding: 7px;
	text-align: left;
}

.table td {
	padding: 7px;
	text-align: left;
}

.table-2-column td {
	width: 50%;
}

.table-form td:first-child {
	font-weight: bold;
	width: 25%;
}

.table tr:nth-child(odd) {
	background: <?php echo $colorPallet[0]; ?>;
}

.table-form tr:nth-child(odd) {
	background: #FFFFFF;
}

.table-form tr:nth-child(even) {
	background: <?php echo $colorPallet[0]; ?>;
}

.table-overview-system {
	border: 0px;
	border-spacing: 0px;
	width: 100%;
}

.table-overview-system td {
	padding: 0px;
	width: 49%;
}

.table-overview-system td:nth-child(2) {
	width: 2%;
}

.table-overview-system td button {
	width: 100%;
}

.table-borderless td:first-child, .table-borderless th:first-child {
	padding-left: 15px;
	
}

.table-borderless td:last-child, .table-borderless th:last-child {
	padding-right: 15px;
}

td.table-center, th.table-center {
	text-align: center;
}

.rotate-icon {
	animation: rotate 0.9s linear infinite;
	-moz-animation: rotate 0.9s linear infinite;
	-webkit-animation: rotate 0.9s linear infinite;
}

.progressbar {
	background: #DDDDDD;
	border-radius: 2px;
	font-size: 0px;
	text-align: left;
}

.progressbar > div {
	background: <?php echo $colorPallet[5]; ?>;
	border-radius: 2px;
	color: #FFFFFF;
	font-size: 11px;
	line-height: 17px;
	text-align: center;
	font-weight: normal;
	display: inline-block;
}

.progressbar > div:hover {
	background: <?php echo $colorPallet[6]; ?>;
}

.show-more {
	display: inline;
	font-weight: bold;
	padding: 6px;
	transition: all 100ms linear;
}

.show-more:hover {
	background: <?php echo $colorPallet[8]; ?>;
	border-radius: 2px;
	color: #FFFFFF;
	text-decoration: none;
}

.text-align-center {
	text-align: center;
}

.error, .success, .info {
	display: table;
	width: 100%;
	border: 0px;
	pointer-events: none;
	border-radius: 2px;
}

.error::before, .success::before, .info::before {
	display: table-cell;
	width: 60px;
	text-align: center;
	vertical-align: middle;
	font-size: 35px;
	font-weight: bold;
	padding-top: 2px;
	border-top-left-radius: 2px;
	border-bottom-left-radius: 2px;
}

.error::before {
	content: "!";
	background: #F44336;
	color: #D32F2F;
}

.success::before {
	content: "\2713";
	background: #009688;
	color: #00796B;
}

.info::before {
	content: "!?";
	background: #FFC107;
	color: #FFA000;
}

.error > div, .success > div, .info > div {
	display: table-cell;
	vertical-align: middle;
}

.error .inner-header span {
	color: #F44336;
}

.success .inner-header span {
	color: #009688;
}

.info .inner-header span {
	color: #FFC107;
}

.error .inner-header::before, .success .inner-header::before, .info .inner-header::before, .inner-cross::before {
	content: "\2715";
	float: right;
	margin: 15px;
	cursor: pointer;
	pointer-events: all;
}

.inner-cross::before {
	margin: -2px 0px 0px 0px;
}

.google-controls .google-visualization-controls-rangefilter-thumblabel {
	font-size: 14px;
	font-weight: normal;
	color: #666666;
	line-height: 27px;
	height: 27px;
}

.google-controls .google-visualization-controls-slider-horizontal {
	background-color: <?php echo $colorPallet[1]; ?>;
	border: 0;
	outline: 0;
	border-radius: 3px;
	position: relative;
	height: 5px;
	top: -0.2em;
	margin: 0 0.3em;
}

.google-controls .google-visualization-controls-slider-horizontal .google-visualization-controls-slider-thumb {
	background-color: <?php echo $colorPallet[7]; ?>;
	border: 1px solid <?php echo $colorPallet[7]; ?>;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	top: -6px;
}

.google-controls .google-visualization-controls-slider-horizontal:hover .google-visualization-controls-slider-thumb,
.google-controls .google-visualization-controls-slider-horizontal:active .google-visualization-controls-slider-thumb {
	background-color: #FFFFFF;
	cursor: pointer;
	cursor: col-resize;
}

.google-controls .google-visualization-controls-slider-horizontal .google-visualization-controls-slider-handle {
	background-color: <?php echo $colorPallet[3]; ?>;
	opacity: 1;
	height: 5px;
}

.google-controls .google-visualization-controls-slider-horizontal:hover .google-visualization-controls-slider-handle,
.google-controls .google-visualization-controls-slider-horizontal:focus .google-visualization-controls-slider-handle {
	cursor: pointer;
}

.settings-overview-flex-container {
	padding: 0;
	margin: 0;
	display: -webkit-box;
	display: -moz-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	-webkit-flex-flow: row wrap;
	flex-flow: row wrap;
	justify-content: space-around;
}

.settings-overview-flex-container > .settings-overview-flex-box {
	min-width: 120px;
	width: 27%;
	border: 1px solid <?php echo $colorPallet[7]; ?>;
	border-radius: 2px;
	display: block;
	font-weight: bold;
	padding: 20px;
	text-align: center;
	text-decoration: none;
	transition: all 100ms linear;
	margin: 5px;
}

.settings-overview-flex-container > .settings-overview-flex-box:hover {
	background: <?php echo $colorPallet[8]; ?>;
	border: 1px solid <?php echo $colorPallet[8]; ?>;
	color: #FFFFFF;
}

.clear-both {
	clear: both;
}

.text-center {
	text-align: center;
}

.padding-0, .box .padding-0 {
	padding: 0px;
}

img {
	/*display: block;*/
}

.flex-container {
	padding: 0;
	margin: 0;
	display: -webkit-box;
	display: -moz-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	-webkit-flex-flow: row wrap;
	flex-flow: row wrap;
	justify-content: space-around;
}

.flex-container > .flex-box-refresh {
	width: 100%;
	display: table;
}

.flex-container > .flex-box-refresh a {
	text-decoration: none;
	display: table-cell;
	font-size: 0px;
}

.flex-container > .flex-box-refresh img {
	width: 16px;
	height: 16px;
}

.flex-container > .flex-box-refresh > div {
	display: table-cell;
	width: 100%;
	vertical-align: middle;
	padding-right: 15px;
}

.flex-container > .flex-box-refresh > div .refresh-bar {
	height: 2px;
	background: <?php echo $colorPallet[7]; ?>;
	width: 0%;
}

.flex-container > .flex-box {
	min-width: 120px;
	width: 25%;
	font-weight: bold;
	font-size: 15px;
	text-align: center;
	margin: 10px 10px;
	padding: 10px 10px;
	border-radius: 2px;
}

.flex-container > .flex-box strong {
	font-size: 13px;
	padding-bottom: 7px;
	font-weight: normal;
	display: block;
	color: #999999;
}

.settings-shortcut-icon {
	text-decoration: none;
	float: right;
	margin: 15px;
}

.settings-shortcut-icon img {
	width: 16px;
	height: 16px;
}

.go-back-icon {
	text-decoration: none;
	float: right;
	margin: 15px;
}

.go-back-icon img {
	width: 16px;
	height: 16px;
}

.svg-small {
	width: 16px;
	height: 16px;
}

.svg-middle {
	vertical-align: middle;
}

.svg-negative-margin {
	margin: -3px;
}

.svg-negative-margin-vertical {
	margin: -3px auto;
}

.button-small {
	padding: 2px 5px;
}

.red {
	color: #F44336;
}

.green {
	color: #009688;
}

.svg-control-pen, .svg-control-cross, .svg-control-plus {
	background: url('../img/control-icons.svg') no-repeat;
	background-size: auto 100%;
	display: block;
	height: 16px;
	width: 16px;
}

.svg-control-pen {
	background-position: 0px;
}

.svg-control-cross {
	background-position: -16px;
}

.svg-control-plus {
	background-position: -32px;
}

/* *********************************************** */

input[type="text"], input[type="password"] {
	border: 1px solid #DDDDDD;
	border-radius: 2px;
	padding: 5px;
	margin: 2px 0px;
	transition: all 100ms linear;
}

input[type="text"]:hover, input[type="password"]:hover  {
	border: 1px solid #CCCCCC;
}

input[type="text"]:focus, input[type="password"]:focus  {
	border: 1px solid <?php echo $colorPallet[8]; ?>;
	outline: none;
}

input[type="button"], button, input[type="submit"] {
	background: none;
	border: 1px solid <?php echo $colorPallet[7]; ?>;
	border-radius: 2px;
	color: <?php echo $colorPallet[8]; ?>;
	cursor: pointer;
	padding: 5px 10px 5px 10px;
	transition: all 100ms linear;
}

input[type="button"]:hover, button:hover, input[type="submit"]:hover {
	background: <?php echo $colorPallet[8]; ?>;
	border: 1px solid <?php echo $colorPallet[8]; ?>;
	color: #FFFFFF;
}

input[type="button"]:focus, button:focus, input[type="submit"]:focus {
	background: <?php echo $colorPallet[9]; ?>;
	border: 1px solid <?php echo $colorPallet[9]; ?>;
	color: #FFFFFF;
	outline: none;
}

label.checkbox {
	display: inline-block;
	cursor: pointer;
	position: relative;
	padding-left: 25px;
	margin: 3px 15px 3px 0px;
	font-size: 13px;
	transition: all 100ms linear;
}

label.only-checkbox {
	padding-left: 0px;
}

label.checkbox::before {
	content: "";
	display: inline-block;
	width: 14px;
	height: 14px;
	margin-right: 10px;
	position: absolute;
	left: 0;
	bottom: 1px;
	border: 1px solid #DDD;
	background: #FFFFFF;
	transition: all 100ms linear;
}

label.checkbox::before {
	margin: 3px 3px -1px 0px;
	border-radius: 2px;
}

input[type=checkbox]:checked + label.checkbox::before {
	border: 1px solid <?php echo $colorPallet[9]; ?>;
	background: <?php echo $colorPallet[9]; ?>;
	content: "\2713";
	font-size: 13px;
	color: #fff;
	text-align: center;
	line-height: 13px;
}

input[type=checkbox] {
	display: none;
}

@media screen and (max-width: 899px) {
	
	.container-600 {
		max-width: inherit;
	}

	.sidebar {
		float: inherit;
		width: auto !important;
	}
	
}

@media screen and (max-width: 600px) {
	
	.header-top-inner-cell a {
		padding: 10px;
	}
	
	.header-top-inner-username {
		padding: 10px;
	}
	
	#header-mobile ~ #inner-header {
		min-height: 55px;
		text-align: left;
		background-position: right 15px center;
	}
	
	#header-navi {
		display: none;
	}
	
	#header-navi .navi-dropdown-container {
		right: auto;
	}
	
	#header-mobile ~ #inner-header > label {
		display: inline-block;
		line-height: 55px;
		font-size: 25px;
		color: #FFFFFF;
		margin: 0px;
		vertical-align: top;
		padding: 0px 20px;
	}
	
	#header-mobile:checked ~ #inner-header {
		background-position: right 15px top 15px;
		background-size: 30% auto;
	}
	
	#header-mobile:checked ~ #inner-header > #header-navi {
		display: inline-block;
	}
	
	#header-mobile:checked ~ #inner-header > #header-navi a {
		display: block;
		font-size: 15px;
	}
	
	#header-mobile:checked ~ #inner-header > #header-navi .navi_dropdown {
		display: block;
	}
	
	#footer-table {
		margin: 20px 10px;
	}
	
	input[type=text], input[type=password] {
		width: auto !important;
	}
	
}

@media screen and (max-width: 450px) {
	
	#footer-table tr:nth-child(2) td:first-child {
		padding-right: 30px;
	}
	
	#footer-table tr th:nth-child(2) {
		padding-right: 34px;
	}
	
	#footer-table tr td:last-child {
		width: 20%;
	}
	
	#footer-table tr:nth-child(2) td a {
		padding: 10px 4px;
	}
}

/* Netzwerk */
.svg-network-signal-0, .svg-network-signal-25, .svg-network-signal-50, .svg-network-signal-75, .svg-network-signal-100, .svg-network-signal-disabled, .svg-network-signal-wire {
	background: url('../img/network-signal-icons.svg') no-repeat;
	background-size: auto 100%;
	display: block;
	height: 16px;
	width: 16px;
}

.svg-network-signal-0 {
	background-position: 0px;
}

.svg-network-signal-25 {
	background-position: -16px;
}

.svg-network-signal-50 {
	background-position: -32px;
}

.svg-network-signal-75 {
	background-position: -48px;
}

.svg-network-signal-100 {
	background-position: -64px;
}

.svg-network-signal-disabled {
	background-position: -80px;
}

.svg-network-signal-wire {
	background-position: -96px;
}

@media screen and (max-width: 410px) {
	
	.responsive-network-wlan-table tr th:nth-child(5), .responsive-network-wlan-table tr td:nth-child(5) {
		display: none;
	}
	
}

/* SSH-Login */
input[name=ssh-login] {
	display: none;
}

input[name=ssh-login] + label .inner-table {
	position: relative;
}

input[name=ssh-login] + label .inner-table table th span {
	font-weight: normal;
}

input[name=ssh-login] + label .inner-table table td {
	opacity: 0.3;
}

input[name=ssh-login]:checked + label .inner-table table td {
	opacity: 1;
}

input[name=ssh-login]:checked + label .inner-table strong + span {
	display: none;
}

input[name=ssh-login]:checked + label .inner-table .ssh-login-table-clickable-area {
	display: none;
}

.ssh-login-table-clickable-area {
	position: absolute;
	top: 0px;
	right: 0px;
	bottom: 0px;
	left: 0px;
	z-index: 10;
}

.divider {
	position: relative;
	width: 70%;
	height: 16px;
	margin: 0px auto 0px;
}

.divider > div:first-child {
	position: absolute;
	background: #E1E1E1;
	height: 2px;
	top: 50%;
	margin-top: -1px;
	width: 100%;
	z-index: 5;
}

.divider > div:last-child {
	background: #FFFFFF;
	z-index: 10;
	position: absolute;
	height: 16px;
	top: 50%;
	margin-top: -8px;
	left: 50%;
	margin-left: -30px;
	width: 60px;
	text-align: center;
}

/* LOGIN */

.login-body {
	background: <?php echo $colorPallet[6]; ?>; /* 7 */
	height: 100%;
}

.login-wrapper {
	height: 100%;
	width: 400px;
	left: 50%;
	position: relative;
	margin-left: -200px;
	min-height: 300px;
	display: table;
}

.login-container {
	height: 90%;
	display: table-row;
}

.login-container-inner {
	display: table-cell;
	vertical-align: middle;
}

.login-logo {
	background: url('../img/logo.svg') center bottom no-repeat;
	background-size: 60% auto;
	height: 60px;
	margin: 0px auto 0px;
	margin-bottom: 20px;
	width: 300px;
}

.login-table {
	width: 300px;
	margin: 0px auto 0px;
	border-spacing: 5px;
}

input.login-input-text {
	width: 100% !important;
	box-sizing: border-box;
	padding: 15px;
	background: <?php echo $colorPallet[8]; ?>;
	border: 1px solid <?php echo $colorPallet[8]; ?>;
	color: #FFFFFF;
	margin: 0px;
}

input.login-input-text:hover  {
	border: 1px solid <?php echo $colorPallet[4]; ?>;
}

input.login-input-text:focus  {
	border: 1px solid <?php echo $colorPallet[9]; ?>;
}

label.login-input-checkbox::before {
	background: none;
	border: 1px solid <?php echo $colorPallet[3]; ?>;
}

label.login-input-checkbox {
	color: <?php echo $colorPallet[1]; ?>;
}

input.login-input-button {
	background: none;
	color: <?php echo $colorPallet[1]; ?>;
	border: 1px solid <?php echo $colorPallet[3]; ?>;
	padding: 10px 20px 10px 20px;
}

.login-footer {
	height: 10%;
	display: table-row;
}

.login-footer-inner {
	display: table-cell;
	vertical-align: bottom;
	text-align: center;
	padding: 5px;
	font-size: 11px;
	color: <?php echo $colorPallet[8]; ?>;
}

.login-error {
	background: rgba(255, 255, 255, 0.5);
	border-radius: 2px;
	color: #B71C1C;
	font-weight: bold;
	margin: 0px auto 0px;
	margin-bottom: 11px;
	padding: 20px;
	text-align: center;
	width: 248px;
}

::-webkit-input-placeholder { /* WebKit, Blink, Edge */
	color: <?php echo $colorPallet[4]; ?>;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
	color: <?php echo $colorPallet[4]; ?>;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
	color: <?php echo $colorPallet[4]; ?>;
}
:-ms-input-placeholder { /* Internet Explorer 10-11 */
	color: <?php echo $colorPallet[4]; ?>;
}