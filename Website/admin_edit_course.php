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
  $updateSQL = sprintf("UPDATE courses SET c_hours=%s, c_teatcher=%s, c_status=%s, c_cost=%s WHERE c_id=%s",
                       GetSQLValueString($_POST['c_hours'], "int"),
                       GetSQLValueString($_POST['c_teatcher'], "int"),
                       GetSQLValueString($_POST['c_status'], "int"),
                       GetSQLValueString($_POST['c_cost'], "double"),
                       GetSQLValueString($_POST['c_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_class = "SELECT * FROM classes";
$all_class = mysql_query($query_all_class, $conn) or die(mysql_error());
$row_all_class = mysql_fetch_assoc($all_class);
$totalRows_all_class = mysql_num_rows($all_class);

mysql_select_db($database_conn, $conn);
$query_all_tetchers = "SELECT * FROM users WHERE user_type = 2";
$all_tetchers = mysql_query($query_all_tetchers, $conn) or die(mysql_error());
$row_all_tetchers = mysql_fetch_assoc($all_tetchers);
$totalRows_all_tetchers = mysql_num_rows($all_tetchers);

$colname_curent_cource = "-1";
if (isset($_GET['id'])) {
  $colname_curent_cource = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_curent_cource = sprintf("SELECT * FROM courses WHERE c_id = %s", GetSQLValueString($colname_curent_cource, "int"));
$curent_cource = mysql_query($query_curent_cource, $conn) or die(mysql_error());
$row_curent_cource = mysql_fetch_assoc($curent_cource);
$totalRows_curent_cource = mysql_num_rows($curent_cource);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <h3 align="center" > 
  تعديل بيانات الدورة 
  <br />
  <?php echo $row_curent_cource['c_name']; ?>

  </h3> 
  
  <hr />
لتعديل البيانات المتعلقة باسم الدورة يجب تعطيل الدورة وفتح شعبة جديدة 
<br />


  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">عدد الساعات </td>
      <td><input type="text" name="c_hours" value="<?php echo htmlentities($row_curent_cource['c_hours'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المدرس</td>
      <td><select name="c_teatcher">
        <?php 
do {  
?>
        <option value="<?php echo $row_all_tetchers['user_id']?>" <?php if (!(strcmp($row_all_tetchers['user_id'], htmlentities($row_curent_cource['c_teatcher'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_all_tetchers['user_name']?></option>
        <?php
} while ($row_all_tetchers = mysql_fetch_assoc($all_tetchers));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">حالة الدورة</td>
      <td><select name="c_status">
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_curent_cource['c_status'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>شعبة مفتوحة</option>
        <option value="0" <?php if (!(strcmp(0, htmlentities($row_curent_cource['c_status'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>شعبة مغلقة</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">التكلفة الافتراضية</td>
      <td><input type="text" name="c_cost" value="<?php echo htmlentities($row_curent_cource['c_cost'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="  حفظ التعيلات " /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="c_id" value="<?php echo $row_curent_cource['c_id']; ?>" />
</form>
<p>&nbsp;</p>


<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_class);

mysql_free_result($all_tetchers);

mysql_free_result($curent_cource);
?>
