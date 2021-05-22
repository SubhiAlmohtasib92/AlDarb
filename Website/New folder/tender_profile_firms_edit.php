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
  $updateSQL = sprintf("UPDATE tender_submissions SET for_supplier=%s, submission_date=%s, reserved_date=%s, offer_value=%s, notes=%s WHERE id=%s",
                       GetSQLValueString($_POST['for_supplier'], "int"),
                       GetSQLValueString($_POST['submission_date'], "date"),
                       GetSQLValueString($_POST['reserved_date'], "date"),
                       GetSQLValueString($_POST['offer_value'], "double"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  
  header('location: tender_profile_firms.php?id='.$_GET['id']);
}

$colname_submition_info = "-1";
if (isset($_GET['s_id'])) {
  $colname_submition_info = $_GET['s_id'];
}
mysql_select_db($database_conn, $conn);
$query_submition_info = sprintf("SELECT * FROM tender_submissions WHERE id = %s", GetSQLValueString($colname_submition_info, "int"));
$submition_info = mysql_query($query_submition_info, $conn) or die(mysql_error());
$row_submition_info = mysql_fetch_assoc($submition_info);
$totalRows_submition_info = mysql_num_rows($submition_info);

mysql_select_db($database_conn, $conn);
$query_all_suplayers = "SELECT id, supplier_name FROM t_suppliers";
$all_suplayers = mysql_query($query_all_suplayers, $conn) or die(mysql_error());
$row_all_suplayers = mysql_fetch_assoc($all_suplayers);
$totalRows_all_suplayers = mysql_num_rows($all_suplayers);
?>
<?php 
//


$page['title'] = '  تعديل بيانات تسليم عطاء  ';
$page['desc'] = '      ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <div class="col-md-6 offset-3 card" > 
  
  
  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="table" dir="rtl" >
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المورد</td>
      <td><select class="form-control multiselect-dropdown " name="for_supplier">
        <?php 
do {  
?>
        <option value="<?php echo $row_all_suplayers['id']?>" <?php if (!(strcmp($row_all_suplayers['id'], htmlentities($row_submition_info['for_supplier'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_all_suplayers['supplier_name']?></option>
        <?php
} while ($row_all_suplayers = mysql_fetch_assoc($all_suplayers));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">تاريخ الاستلام :</td>
      <td><input class="form-control"  type="date" name="submission_date" value="<?php echo htmlentities($row_submition_info['submission_date'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">تاريخ التسليم:</td>
      <td><input  class="form-control"  type="date" name="reserved_date" value="<?php echo htmlentities($row_submition_info['reserved_date'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المبلغ المستلم</td>
      <td><input  class="form-control"  type="text" name="offer_value" value="<?php echo htmlentities($row_submition_info['offer_value'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">ملاحظات:</td>
      <td><textarea  class="form-control"  name="notes" cols="50" rows="5"><?php echo htmlentities($row_submition_info['notes'], ENT_COMPAT, ''); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value=" حفظ التعديلات " class="btn btn-primary " /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_submition_info['id']; ?>" />
</form>
<p>&nbsp;</p>



</div>


<?php 
//
  include('templeat_footer.php'); 
 ?>
<?php
mysql_free_result($submition_info);

mysql_free_result($all_suplayers);
?>
