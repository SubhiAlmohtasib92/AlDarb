<?php require_once('../Connections/conn.php'); ?>
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

$colname_collect_detail = "-1";
if (isset($_POST['C_NO'])) {
  $colname_collect_detail = $_POST['C_NO'];
}
mysql_select_db($database_conn, $conn);
$query_collect_detail = sprintf("SELECT dbo_collect.C_NO, dbo_collect.F_ID, dbo_farmer_inst.F_Name, dbo_items.arabic_name, dbo_collect.C_date, dbo_collect.`count`, dbo_village.V_Name, dbo_collect.C_WH_100, dbo_collect.C_Row_no, dbo_collect.C_Shelf, dbo_collect.C_Record, dbo_collect.Total_w, dbo_collect.ag_season_no, dbo_collect.generation, dbo_collect.`section`, dbo_collect.`depth`, dbo_collect.location_flag, dbo_collect.remain, dbo_collect.Notes FROM dbo_collect, dbo_farmer_inst, dbo_items, dbo_village WHERE C_NO = %s AND dbo_collect.F_ID = dbo_farmer_inst.F_ID AND dbo_collect.I_NO = dbo_items.I_NO AND dbo_collect.C_Vill_NO = dbo_village.V_NO", GetSQLValueString($colname_collect_detail, "int"));
$collect_detail = mysql_query($query_collect_detail, $conn) or die(mysql_error());
$row_collect_detail = mysql_fetch_assoc($collect_detail);
$totalRows_collect_detail = mysql_num_rows($collect_detail);
?>
<?php
mysql_free_result($collect_detail);
?>

   <?php echo $row_collect_detail['C_date']; ?>   
<div class="element-content">
  <div class="row">
  
  
 
  
  
  
    <div class="col-sm-6 col-xxxl-3 "><a class=" element-box el-tablo" href="#">
      <div class="label ">  Farmer | بواسطة الموزارع  </div>
      <div class="value"><?php echo $row_collect_detail['F_Name']; ?>  </div>
      
      
      </a></div>
    <div class="col-sm-6 col-xxxl-3"><a class="element-box el-tablo" href="#">
      <div class="label">  Item | الصنف </div>
      <div class="value"> <?php echo $row_collect_detail['arabic_name']; ?> </div>
     
      </a></div>
      
      
      
      
      
      
      
    <div class="col-sm-3 col-xxxl-3"><a class="element-box el-tablo" href="#">
      <div class="label">  الموقع </div>
      <div class="value"><?php echo $row_collect_detail['V_Name']; ?> </div>
     
     
      </a></div>
      
      
    <div class="col-sm-3 col-xxxl-3"><a class="element-box el-tablo" href="#">
      <div class="label">  100 g  </div>
      <div class="value"><?php echo $row_collect_detail['V_Name']; ?> </div>
     
     
      </a></div>
      
      
          <div class="col-sm-3 col-xxxl-3"><a class="element-box el-tablo" href="#">
      <div class="label">  الوزن الاجمالي </div>
      <div class="value"><?php echo $row_collect_detail['Total_w']; ?></div>
     
     
      </a></div>
      
      
      
          <div class="col-sm-3 col-xxxl-3"><a class="element-box el-tablo" href="#">
      <div class="label">  الموقع </div>
      <div class="value"><?php echo $row_collect_detail['V_Name']; ?> </div>
     
     
      </a></div>
      
      
      




  </div>
</div>
 
