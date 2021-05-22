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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE committees SET Committee_name=%s, Committee_insert_date=%s WHERE id=%s",
                       GetSQLValueString($_POST['Committee_name'], "text"),
                       GetSQLValueString($_POST['Committee_insert_date'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_commeti_info = "-1";
if (isset($_GET['c_id'])) {
  $colname_commeti_info = $_GET['c_id'];
}
mysql_select_db($database_conn, $conn);
$query_commeti_info = sprintf("SELECT * FROM committees WHERE id = %s", GetSQLValueString($colname_commeti_info, "int"));
$commeti_info = mysql_query($query_commeti_info, $conn) or die(mysql_error());
$row_commeti_info = mysql_fetch_assoc($commeti_info);
$totalRows_commeti_info = mysql_num_rows($commeti_info);

mysql_select_db($database_conn, $conn);
$query_comm_type = "SELECT * FROM committees_type";
$comm_type = mysql_query($query_comm_type, $conn) or die(mysql_error());
$row_comm_type = mysql_fetch_assoc($comm_type);
$totalRows_comm_type = mysql_num_rows($comm_type);
 
//


$page['title'] = 'تعديل بيانات لجنة    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="table" dir="rtl" >
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">اللجنة</td>
      <td><select name="Committee_name">
        <?php 
do {  
?>
        <option value="<?php echo $row_comm_type['id']?>" <?php if (!(strcmp($row_comm_type['id'], htmlentities($row_commeti_info['Committee_name'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_comm_type['Committee_type']?></option>
        <?php
} while ($row_comm_type = mysql_fetch_assoc($comm_type));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">تاريخ الانعقاد:</td>
      <td><input type="date" name="Committee_insert_date" value="<?php echo htmlentities($row_commeti_info['Committee_insert_date'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="  حفظ التعديلات " class="btn btn-primary " /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_commeti_info['id']; ?>" />
</form>
<p>&nbsp;</p>




<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($commeti_info);

mysql_free_result($comm_type);
?>
