<?php require_once('Connections/conn.php'); ?>
<?php require_once('Connections/conn.php'); ?>
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

if ((isset($_GET['item_id'])) && ($_GET['item_id'] != "") && (isset($_POST['delete_id']))) {
  $deleteSQL = sprintf("DELETE FROM work_sector_item WHERE item_id=%s",
                       GetSQLValueString($_GET['item_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "admin_work_sector_cat.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE work_sector_item SET item_name=%s, item_cat=%s WHERE item_id=%s",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_cat'], "int"),
                       GetSQLValueString($_POST['item_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  
    header("location: admin_work_sector_cat.php");



}

$colname_item_info = "-1";
if (isset($_GET['item_id'])) {
  $colname_item_info = $_GET['item_id'];
}
mysql_select_db($database_conn, $conn);
$query_item_info = sprintf("SELECT * FROM work_sector_item WHERE item_id = %s", GetSQLValueString($colname_item_info, "int"));
$item_info = mysql_query($query_item_info, $conn) or die(mysql_error());
$row_item_info = mysql_fetch_assoc($item_info);
$totalRows_item_info = mysql_num_rows($item_info);

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM work_sector_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);
 
//


$page['title'] = ' تعديل    ';
$page['desc'] = '    ';
 
 include('templeat_header.php');
  ?>
  
  
  
  
  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="table" dir="rtl" >
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المجال:</td>
      <td><input type="text" name="item_name" value="<?php echo htmlentities($row_item_info['item_name'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">التصنيف:</td>
      <td><select name="item_cat">
        <?php 
do {  
?>
        <option value="<?php echo $row_all_cat['cat_id']?>" <?php if (!(strcmp($row_all_cat['cat_id'], htmlentities($row_item_info['item_cat'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_all_cat['cat_name']?></option>
        <?php
} while ($row_all_cat = mysql_fetch_assoc($all_cat));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="  حفظ التعديل " /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="item_id" value="<?php echo $row_item_info['item_id']; ?>" />
</form>
<p>&nbsp;</p>







<form action="" method="post" > 



<input type="hidden" name="delete_id" value="<?php echo $row_item_info['item_id']; ?>"  /> 

<input type="submit" value="حذف المجال " class="btn btn-danger"  /> 


</form>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($item_info);

mysql_free_result($all_cat);

mysql_free_result($item_info);
?>
