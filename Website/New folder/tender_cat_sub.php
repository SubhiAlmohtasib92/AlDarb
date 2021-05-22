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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tender_sub_cat (cat_name, main_cat_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['cat_name'], "text"),
                       GetSQLValueString($_POST['main_cat_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tender_sub_cat SET cat_name=%s WHERE id=%s",
                       GetSQLValueString($_POST['cat_name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_cat_info = "-1";
if (isset($_GET['main'])) {
  $colname_cat_info = $_GET['main'];
}
mysql_select_db($database_conn, $conn);
$query_cat_info = sprintf("SELECT * FROM tender_cat WHERE id = %s", GetSQLValueString($colname_cat_info, "int"));
$cat_info = mysql_query($query_cat_info, $conn) or die(mysql_error());
$row_cat_info = mysql_fetch_assoc($cat_info);
$totalRows_cat_info = mysql_num_rows($cat_info);

$colname_all_sub_cat = "-1";
if (isset($_GET['main'])) {
  $colname_all_sub_cat = $_GET['main'];
}
mysql_select_db($database_conn, $conn);
$query_all_sub_cat = sprintf("SELECT * FROM tender_sub_cat WHERE main_cat_id = %s", GetSQLValueString($colname_all_sub_cat, "int"));
$all_sub_cat = mysql_query($query_all_sub_cat, $conn) or die(mysql_error());
$row_all_sub_cat = mysql_fetch_assoc($all_sub_cat);
$totalRows_all_sub_cat = mysql_num_rows($all_sub_cat);
 
//


$page['title'] = 'ادارة التصنيفات الفرعية    ';
$page['desc'] = $row_cat_info['cat_name']  ;
 
 include('templeat_header.php');
  ?> 
  
  
  
  <b style="float:right " > 
  
  <a href="tender_list.php" > العطاءات  </a>
   >> <a href="tender_cat.php" > تصنيفات  </a>
 >> 
 التصنيفات الفرعية   
  
  
  </b> 
  
  
  
  <h3 align="center" > التصنيفات الفرعية ل ( <b style="color:#FF0000;" > <?php echo $row_cat_info['cat_name']; ?> </b> ) </h3>
  
  
  
  <br />
  
  
  
  
  
  
  <div class="row" > 
  
  
  
  <div class="col-md-4" >
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">اسم التصنيف</td>
          <td><input required="required"  class=" form-control" type="text" name="cat_name" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="  اضافة جديد " /></td>
        </tr>
      </table>
      <input type="hidden" name="main_cat_id" value="<?php echo $row_cat_info['id']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
  </div>
  <div class="col-md-8" > التصنيفا المضافة
    <?php if ($totalRows_all_sub_cat > 0) { // Show if recordset not empty ?>
  <table border="1" class="table table-hover">
    <tr class="bg-primary text-white ">
      <td>#</td>
      <td>اسم التصنيف</td>
      <td> </td>
    </tr>
    <?php do { ?><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      
      
      <tr>
        <td><?php echo $row_all_sub_cat['id']; ?></td>
        <td>
          
          
          
          <input type="text" class="form-control" required="required"  name="cat_name" value="<?php echo htmlentities($row_all_sub_cat['cat_name'], ENT_COMPAT, ''); ?>" size="32" />
          
          
          <input type="hidden" name="MM_update" value="form2" />
          <input type="hidden" name="id" value="<?php echo $row_all_sub_cat['id']; ?>" />
          
          
          
          
        </td>
        <td>
          
          <input type="submit" value=" حفظ التعديلات " />
          
        </td>
      </tr>    </form>
      <?php } while ($row_all_sub_cat = mysql_fetch_assoc($all_sub_cat)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
  
  </div> 
  
  
  
  <?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($cat_info);

mysql_free_result($all_sub_cat);
?>
