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
  $loginUsername=$_POST['username'];
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
<!DOCTYPE html>
<html>
<head>
<title>Local Seed Bank </title>
<meta charset="utf-8">
<meta content="ie=edge" http-equiv="x-ua-compatible">
<meta content="template language" name="keywords">
<meta content="Tamerlan Soziev" name="author">
<meta content="Admin dashboard html template" name="description">
<meta content="width=device-width,initial-scale=1" name="viewport">
<link href="favicon.png" rel="shortcut icon">
<link href="apple-touch-icon.png" rel="apple-touch-icon">
<link href="../fast.fonts.net/cssapi/487b73f1-c2d1-43db-8526-db577e4c822b.css" rel="stylesheet">
<link href="bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
<link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link href="bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
<link href="bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
<link href="bower_components/slick-carousel/slick/slick.css" rel="stylesheet">
<link href="css/main5739.css?version=4.5.0" rel="stylesheet">
</head>
<body class="auth-wrapper">
<div class="all-wrapper menu-side with-pattern">
  <div class="auth-box-w">
    <div class="p-2 " align="center"><a href="index.php"><img alt="" src="images/logo-dark.png"></a></div>
    <h4 class="auth-header">تسجيل الدخول  </h4>
    
    
    
       <?php if(isset($msg)) {
			 echo $msg ;  
			 } ?>
             
             
             
    <form action="<?php echo $loginFormAction; ?>" method="POST">
      <div class="form-group">
        <label for="">Username</label>
        <input  name="username"   class="form-control" placeholder="Enter your username">
        <div class="pre-icon os-icon os-icon-user-male-circle"></div>
      </div>
      <div class="form-group">
        <label for="">Password</label>
        <input name="password" class="form-control" placeholder="Enter your password" type="password">
        <div class="pre-icon os-icon os-icon-fingerprint"></div>
      </div>
      <div class="buttons-w">
        <button class="btn btn-primary">  تسجيل الدخول </button>
        <div class="form-check-inline">
           
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>




