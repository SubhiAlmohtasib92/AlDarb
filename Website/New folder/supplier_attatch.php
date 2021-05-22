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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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






if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	
	
	
	
	

if($_FILES['attatch']['name']) {
 $temp = explode(".", $_FILES['attatch']['name']);

$allowedExts = array("jpg","jpeg","png","gif","pdf","docx" ,"doc" , "xls" ,"xlsء" ,"ppt" ,"rar","zip" , "JPG" , "PNG" , "GIF","DOC" , "DOCX" , "docx");


 $extension = end($temp);
 if( !in_array($extension, $allowedExts)){
	 
	 $error = 1 ; 
	 
 }
	if( in_array($extension, $allowedExts)){ 
     $phpdate=  $row_supplier_info['id']*rand(11111,99999) ;
     $path = $_FILES['attatch']['name'] ;
	 

    $ext = pathinfo($path, PATHINFO_EXTENSION);
// echo $ext;
$u_file =$_SESSION['temp_id'].'_'.$phpdate.'.'.$ext;
$uploaddir = 'upludes/';
$uploadfile = $uploaddir . basename($u_file);
if (move_uploaded_file($_FILES['attatch']['tmp_name'], $uploadfile)) {
   // echo "File is valid, and was successfully uploaded.\n";
}else {
	}





  $insertSQL = sprintf("INSERT INTO supplier_attatch (supplier_id, attatch, note) VALUES (%s, %s, %s)",
                       GetSQLValueString($_GET['id'] , "int"),
                       GetSQLValueString($uploadfile , "text"),
                       GetSQLValueString($_POST['note'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  $success = 1 ; 
   
  
}


 }
 
 
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

$colname_user_attatch = "-1";
if (isset($_GET['id'])) {
  $colname_user_attatch = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_user_attatch = sprintf("SELECT * FROM supplier_attatch WHERE supplier_id = %s", GetSQLValueString($colname_user_attatch, "int"));
$user_attatch = mysql_query($query_user_attatch, $conn) or die(mysql_error());
$row_user_attatch = mysql_fetch_assoc($user_attatch);
$totalRows_user_attatch = mysql_num_rows($user_attatch);
 
//


$page['title'] = 'وثائق ومستندات ';
$page['desc'] = 'يتم في هذا القسم اضافة جميع الوثائق المطلوبة للمورد  ';
 
 include('templeat_header.php');
  ?> 
  
  
  
<div class="row" > 
  
  
  
  <div class="col-md-4" dir="rtl" align="right"  >
  
  
  
  


<h3 align="center"  > 
ارفاق ملفات ومستندات </h3>
 <p align="center" > 
 
 يمكن من خلال النافذه التالية ارفاق ملفات ومستندات خاصه بالمورد 
 
 
  </p>



<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" enctype="multipart/form-data" >
   
   
   <br />
<table align="center" class="table">
     <tr valign="baseline">
       <td nowrap="nowrap" align="right">اختر ملف:</td>
       <td><input class="form-control" type="file" name="attatch" value="" size="32" required="required" />
       
       <b style=" padding:5px; color:#F00; font-size:9px;" > 
      الملفات المسموحة : صور , PDF , ملفات مضغوطه 
  </b>      
       </td>
     </tr>
     <tr valign="baseline">
       <td nowrap="nowrap" align="right">وصف الملف:</td>
       <td><textarea  class="form-control"  required="required"  name="note" cols="32"></textarea ></td>
     </tr>
     <tr valign="baseline">
       <td nowrap="nowrap" align="right">&nbsp;</td>
       <td><input class="btn btn-primary " type="submit" value=" رفع الملف     " /></td>
     </tr>
   </table>
   <input type="hidden" name="supplier_id" value="" />
   <input type="hidden" name="MM_insert" value="form1" />
 </form>
 <p>&nbsp;</p>
<hr />
   
   
   
   
  
  
   </div> 
   
   
   
   
  <div class="col-md-8" > 
   
   
   
   
   <h3 align="center"> 



<?php if ($success == 1 ) { ?> 


<div class="alert btn-success" > 


<h5> تم تحميل الملف المرفق بنجاح   </h5>

 </div>


<?php } ?> 



<?php if ( $error == 1 ) { ?> 


<div class="alert btn-danger" > 


<h3>عذرا الملف المرفق غير صالح </h3>

 </div>


<?php } ?> 




</h3>





   <table border="1" class="table">
     <tr class="bg-primary text-white ">
       <td>وصف الملف المرفق </td>
       <td>الملف المرفق</td>
       
     </tr>
     <?php do { ?>
       <tr>
     <td><?php echo $row_user_attatch['note']; ?></td>
         <td>
         
         <a target="_new" class="btn btn-primary " href="<?php echo $row_user_attatch['attatch']; ?>" > عرض الملف المرفق  </a>
         
         </td>
         
       </tr>
       <?php } while ($row_user_attatch = mysql_fetch_assoc($user_attatch)); ?>
   </table>
  </div>
   
   
   
   
  
  </div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($user_attatch);
?>
