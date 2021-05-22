<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php 
//


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 // include('templeat_header.php');
  ?> 
  
  
  
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  
  <style>
  
  
   
body {
	background: #056c86;
	background-image:url(assets/images/D8fzFM.jpg); 
 
	color: #fff;
	font-weight: 300;
	font-size: 1.5em;
	font-family: 'kofi' !important ;
	margin:0;
	padding:0;
	padding-bottom:40px;
}
.description{
  line-height:40px;
}
a {
	color: #b5a4fb;
	text-decoration: none;
}

a:hover, a:focus {
	color: #000;
}

*, *:after, *:before { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }

a, button {
	outline: none;
}
.clearfix:before,
.clearfix:after {
	content: " ";
	display: table;
}
 
.clearfix:after {
	clear: both;
}


.header{
  background: #004050;
  color: #fff;

  text-align: center;
}

/* To Navigation Style */
.cctop {
	background:#056c86;	
	width: 100%;
	text-transform: uppercase;
	font-weight: 700;
	font-size: 0.75em;
	line-height: 3.2;
}

.cctop a {
	display: inline-block;
	padding: 0 1.5em;
	text-decoration: none;
	letter-spacing: 1px;
}

.cctop span.right {
	float: right;
}

.cctop span.right a {
	display: block;
	float: left;
}

/* Header Style */

.ccheader {
	margin: 0 auto;
	padding: 2em;
	text-align: center;
}

.ccheader h1 {
	font-size: 2.625em;
    font-weight: 300;
    line-height: 1.3;
    margin: 0;
}

.ccheader h1 span {
	display: block;
	padding: 0 0 0.6em 0.1em;
	font-size: 60%;
	opacity: 0.7;
}



/* Demo Buttons Style */
.codeconvey-demo {
	padding-top: 1em;
	font-size: 0.8em;
}

.codeconvey-demo a {
	display: inline-block;
	margin: 0.5em;
	padding: 0.7em 1.1em;
	outline: none;
	border: 2px solid #fff;
	color: #fff;
	text-decoration: none;
	text-transform: uppercase;
	letter-spacing: 1px;
	font-weight: 700;
}

.codeconvey-demo a:hover,
.codeconvey-demo a.current-demo,
.codeconvey-demo a.current-demo:hover {
	border-color: #333;
	color: #333;
}

/* Wrapper Style */

.wrapper{
	margin:0 auto;	
	padding-left:12%;
	padding-right:12%;
}
.heading{
  font-size: 4em;
  font-weight: 300;
  margin-bottom: .5em;
}

.wrap{
width: 830px;
height: auto;
margin: 70px 0px;
float: left;
}
.fa{
color:#FFFFFF;
}
div[class^="btn"]{
float: left;
margin: 0 10px 10px 0;
height: 95px;
position: relative;
cursor: pointer;
transition: all .4s ease;
user-drag: element;
border: solid 2px transparent;
text-align:center;
line-height:100px;
}
div[class^="btn"]:hover{
	opacity: 0.7;
}
div[class^="btn"]:active{
	transform: scale(.98,.98);
}
.btn-big{width: 200px;}
.btn-small{width: 95px;}
.last{margin-right: 0 !important;}
.Start{
color: white;
font: normal 50px 'Yanone Kaffeesatz', sans-serif;
margin-bottom: 20px;
cursor: pointer;
user-select: none;
transition: all .3s ease;
}
.Start:hover{
  text-shadow: 0 0 4px white;
}
.space{
	margin-bottom: 110px;
}
.label{
	position: absolute;
	color: white;
	font: 500 12px sans-serif;
	left: 10px;
  user-select: none;
}
.bottom{bottom: 5px;}
.top{top: 5px;}

.red{background: #df0024;}
.blue{ background: #00a9ec;}
.orange{background: #ff9000;}
.green{background: #0e5d30;}
.purple{background: #8b0189;}
.red-light{background: #ce4e4e;}
.photo{
  background: url('http://lorempixel.com/200/95/people');
  background-position: -2px -2px;
}
.gray{
  background: #5f5f5f;
  animation: flip 6s linear infinite;
  transform: rotateX(0deg);
}
.green-bright{background: #78d204;}
.blue-nav{background: #25478e;}
.redish{background: #fe0000;}
.yellow{background: #d0d204;}


div[class^="icon"]{
	width: 45px;
	height: 45px;
	margin: 20px auto;
	background-size: 45px 45px;
}

::-webkit-scrollbar{
  width: 10px;
	height: 10px;
	cursor: pointer;	
}
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px 2px rgba(0,0,0,0.3);
    background: #007491;
}
::-webkit-scrollbar-thumb {
    background: #002f3b; 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    cursor: pointer;
}
::selection{
    background: mintcream; 
}

@keyframes flip{
  0%{
    transform: rotateX(0deg);
  }
  15%{
    transform: rotateX(360deg);  
  }
  100%{
    transform: rotateX(360deg); 
  }
}

.photo img{
  top: -4px;
  left: -4px;
  position: absolute;
  opacity: 0;
  animation: fade 8s ease-in-out infinite 8s;
  z-index: 0;
  border: solid 2px transparent;
  transition: all .3s ease;
}

.photo img:hover{
  border: solid 2px mintcream;
}

@keyframes fade{
  0%{
    opacity: 0; 
  }
  10%{
    opacity: 1;
  }
  50%{
    opacity: 1;
  }
  60%{
    opacity: 0;
  }
}

</style>
  
  
    
  
   
  <div class="wrapper metro ">
    <div class="wrap">
       
       
       <a href="" > 
      <div class="btn-big red"> <i class="fa fa-envelope fa-2x"></i> <span class="label bottom" style="font-family:kofi; float:right">البريد الالكتروني </span> </div>
      
      
      </a>
      
      
      
      <div class="btn-big blue"> <i class="fa fa-calendar fa-2x"></i> <span class="label bottom">Calendar</span> </div>
      
      
      
      
      
      <div class="btn-small orange"> <i class="fa fa-windows fa-2x"></i> <span class="label bottom">Store</span> </div>
      
      
      
      <div class="btn-big blue"> <i class="fa fa-user fa-2x"></i> <span class="label bottom">User</span> </div>
      
      
      
      <div class="btn-small green last"> <i class="fa fa-umbrella fa-2x"></i> <span class="label bottom">Application</span> </div>
      
      
      
      
      <div class="btn-small red-light"> <i class="fa fa-wrench fa-2x"></i> <span class="label bottom">Setting</span> </div>
      
      
      
      <div class="btn-big photo"> <img src="http://lorempixel.com/200/95/animals" class="img-delay-1" /> <span class="label bottom">Photos</span> </div>
      
      
      
      
      <div class="btn-big purple"> <i class="fa fa-video-camera fa-2x"></i> <span class="label bottom">Videos</span> </div>
      
      
      
      <div class="btn-big gray"> <i class="fa fa-music fa-2x"></i> <span class="label bottom">Musics</span> </div>
      
      
      
      <div class="btn-small green-bright last"> <i class="fa fa-gamepad fa-2x"></i> <span class="label bottom">Games</span> </div>
      <div class="btn-small blue"> <i class="fa fa-twitter fa-2x"></i> <span class="label bottom">twitter</span> </div>
      <div class="btn-small red-light"> <i class="fa fa-google-plus fa-2x"></i> <span class="label bottom">Google+</span> </div>
      <div class="btn-small blue-nav"> <i class="fa fa-facebook fa-2x"></i> <span class="label bottom">Facebook</span> </div>
      <div class="btn-small redish"> <i class="fa fa-fighter-jet fa-2x"></i> <span class="label bottom">Fighter Jet</span> </div>
      <div class="btn-big yellow"> <i class="fa fa-lock fa-2x"></i> <span class="label bottom">Security</span> </div>
    </div>
  </div>
</div>



 
<?php 
//
  //  include('templeat_footer.php'); 
 ?>
