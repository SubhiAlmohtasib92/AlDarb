<?php require_once('Connections/conn.php'); ?>
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

$colname_all_users = "-1";
if (isset($_POST['serch_user'])) {
  $colname_all_users = $_POST['serch_user'];
}else {
	 
	$colname_all_users = '%' ; 
	}
mysql_select_db($database_conn, $conn);
$query_all_users = "SELECT user_id, user_name, user_email, profile_pic, Job_title FROM users WHERE user_name LIKE '%$colname_all_users%' order by profile_pic DESC";
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
$totalRows_all_users = mysql_num_rows($all_users);
 
//


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?> 
  
  
  
  




<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/users_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
   
<form action="" method="post" > 

<div class="row" > 

<div class="col-md-9" > 
<input type="text" class="form-control" required="required" name="serch_user" placeholder="اكتب اسم المستخدم المراد البحث عنه " /> 


</div>

<div class="col-md-3 " >
<input type="submit" class="btn btn-primary col-md-12"  value="بحث عن مستخدم "  />
 </div>

</div>
</form>



<div class="row" > 

  <?php do { ?>
  
  
  
  <div class="col-md-2" > 
  
  <div class="text-center">
        <img src="<?php echo $row_all_users['profile_pic']; ?>" class="avatar img-circle img-thumbnail" alt=" " width="100%" height="50" style="height:120px; " >
        <h6> <?php echo $row_all_users['user_name']; ?> </h6>
        
      </div>
      
      
      
      </div>
      
      
      
 
      
  
    <?php } while ($row_all_users = mysql_fetch_assoc($all_users)); ?>
 
 
 </div>
 
 
  </div></div></div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_users);
?>
