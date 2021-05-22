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
  $insertSQL = sprintf("INSERT INTO indicators_list (Indicator_name) VALUES (%s)",
                       GetSQLValueString($_POST['Indicator_name'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE indicators_list SET Indicator_name=%s WHERE id=%s",
                       GetSQLValueString($_POST['Indicator_name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_indecators = "SELECT * FROM indicators_list";
$all_indecators = mysql_query($query_all_indecators, $conn) or die(mysql_error());
$row_all_indecators = mysql_fetch_assoc($all_indecators);
$totalRows_all_indecators = mysql_num_rows($all_indecators);
 
//


$page['title'] = 'تعريف مقاييس اداء   ';
$page['desc'] = 'يمكنك من خلال هذه الصفحة تعريف مقايس الاداء التي سوف يتم التقييم بناء عليها  ';
 
 include('templeat_header.php');
  ?> 
  
  
  
  




<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php // include "include/all_supplaier_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" >
  
     <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" dir="rtl">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">مقياس اداء</td>
          <td><input type="text" name="Indicator_name" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="  اضافة مقياس اداء " /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
    <table border="1" class="table ">
      <tr class="bg-primary text-white">
         <td>المؤشر / المقياس </td>
        <td>تعديل </td>
      </tr>
      <?php do { ?>
        <tr>
           <td><?php echo $row_all_indecators['Indicator_name']; ?></td>
          <td><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
           
           
           
           <input class=" bg-primary text-white " type="text" name="Indicator_name" value="<?php echo htmlentities($row_all_indecators['Indicator_name'], ENT_COMPAT, ''); ?>" size="32" />
           
           <input type="submit" value="حفظ التعديل   " /> 
            <input type="hidden" name="MM_update" value="form2" />
            <input type="hidden" name="id" value="<?php echo $row_all_indecators['id']; ?>" />
          </form>
            </td>
        </tr>
        <?php } while ($row_all_indecators = mysql_fetch_assoc($all_indecators)); ?>
    </table>
  </div></div></div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_indecators);
?>
