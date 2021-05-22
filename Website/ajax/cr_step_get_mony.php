<?php require_once('../Connections/conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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





$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["c_value"])) && ($_POST["c_value"] != "")) {
  $insertSQL = sprintf("INSERT INTO catch_receipt (by_user, from_father, to_student, for_cource, mony, date_insert, notes) VALUES (%s, %s, %s, %s, %s, NOW(), %s)",
                       GetSQLValueString($_SESSION['user_id'], "int"),
                       GetSQLValueString($_POST['father_id'].'-1', "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['cource_id'].'-1', "int"),
                       GetSQLValueString($_POST['c_value'], "double"),
                       GetSQLValueString($_POST['notes'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  $success = 1 ; 
}



mysql_select_db($database_conn, $conn);
$query_last_add = "SELECT * FROM catch_receipt ORDER BY id DESC";
$last_add = mysql_query($query_last_add, $conn) or die(mysql_error());
$row_last_add = mysql_fetch_assoc($last_add);
$totalRows_last_add = mysql_num_rows($last_add);



?>


<?php if ($success == 1 ) { ?> 

<div align="center" > 
<h3 align="center" > تم اضافة الدفعة بنجاح  </h3> 

<a href="cach_print.php?id=<?php echo $row_last_add['id']; ?>" class="btn btn-dark text-white " > طباعة سند القبض  </a>



<br />

<a class="btn btn-dark" href="admin_student_profile.php?id=<?php echo $_POST['user_id'];?>">عودة لملف الطالب </a>
 
 
</div> 



 <?php }else {  ?> 
 
 
 
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المبلغ :</td>
      <td><input type="text" name="mony" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ملاحظات :</td>
      <td><textarea name="notes" cols="32"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="button" value="  اضافة دفعة " onclick="cr_new_add('<?php echo $_POST['user_id'];?>','<?php echo $_POST['cource_id'];?>','<?php echo $_POST['father_id'];?>', mony.value , notes.value )" /></td>
    </tr>
  </table>
  
  
 
     
   <input type="hidden" name="from_father" value="<?php echo $_POST['father_id'];?>" />
  <input type="hidden" name="to_student" value="<?php echo $_POST['user_id'];?>" />
  <input type="hidden" name="for_cource" value="<?php echo $_POST['cource_id'];?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>



<p>&nbsp;</p>
<?php } 
mysql_free_result($last_add);
?>
