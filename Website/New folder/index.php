<?php require_once('Connections/conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['full_name'])) {
 header('location: profile.php');
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'].'@uawc-pal.org';
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "user_type";
  $MM_redirectLoginSuccess = "profile.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT user_id , user_name, user_email, password, user_type FROM users WHERE user_email=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $row = mysql_fetch_array($LoginRS);
  
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'user_type');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
    $_SESSION['full_name'] = $row['user_name'];	
		      
    $_SESSION['user_id'] = $row['user_id'];	
	
	
	
	//include 'log_function.php';
	// add_log(1,0,0 , $database_conn, $conn); 

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: profile.php");
  }
  else {
	  
	  $msg = '
	   يبدو ان البريد الالكتروني او كلمة المرور التي ادخلتها غير صحيحة .. الرجاء المحاولة مرة اخرى 

'; 
	  
	  }
}
 //


        
 
  ?>

























<!doctype html>
<html lang="en">


 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>نظام المشتريات والعطاءات </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="Fiori HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

<link href="style.css" rel="stylesheet"></head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
            <div class="app-container">
                <div class="h-100 bg-plum-plate bg-animation">
                    <div class="d-flex h-100 justify-content-center align-items-center">
                        <div class="mx-auto app-login-box col-md-8">
                            <div class="app-logo-inverse mx-auto mb-3"></div>
                            <div class="modal-dialog w-100 mx-auto">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="h5 modal-title text-center">
										
										
                                            <h4 class="mt-2">
                                                <div>  اهلا بعودتك </div>
                                                <span>الرجاء تسجيل الدخول بواسطة البريد الاكتروني وكلمة المرور </span>
												
												
												
                                            </h4>
											<?php if(isset($msg)) {
			 echo $msg ;  
			 } ?> 
			 
			 
                                        </div>
                        	<form action="<?php echo $loginFormAction; ?>" method="POST">
                                            <div class="form-row">
                                                <div class="col-md-12" dir="rtl" >
                                                    <div class="position-relative form-group"><input name="username" id="" placeholder=" البريد الاكتروني " type="text" class="form-control"></div>
                                                </div>
                                                <div class="col-md-12" dir="rtl" >
                                                    <div class="position-relative form-group"><input name="password" id="" placeholder=" كلمة المرور  "  type="password" class="form-control"></div>
                                                </div>
                                            </div>
											<input type="submit" value="تسجيل الدخول " class="btn btn-primary " >

                                        </form>
                                      </div>
                                     
                                </div>
                            </div>
                            <div class="text-center text-white opacity-8 mt-3">Copyright © UAWC 2019</div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<script type="text/javascript" src="assets/scripts/main.087c8d15f89e6bbe3f9e.js"></script></body>

 </html>












 