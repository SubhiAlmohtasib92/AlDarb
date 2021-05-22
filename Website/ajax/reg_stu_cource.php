<?php require_once('../Connections/conn.php'); ?>
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

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {

}

$colname_is_reg = "-1";
if (isset($_POST['c_id'])) {
  $colname_is_reg = $_POST['c_id'];
}
$stdid_is_reg = "-1";
if (isset($_POST['stu_id'])) {
  $stdid_is_reg = $_POST['stu_id'];
}
mysql_select_db($database_conn, $conn);
$query_is_reg = sprintf("SELECT * FROM cource_students WHERE c_id = %s AND cource_students.stu_id=%s ", GetSQLValueString($colname_is_reg, "int"),GetSQLValueString($stdid_is_reg, "int"));
$is_reg = mysql_query($query_is_reg, $conn) or die(mysql_error());
$row_is_reg = mysql_fetch_assoc($is_reg);
$totalRows_is_reg = mysql_num_rows($is_reg);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if($totalRows_is_reg ==0){
if ((isset($_POST["c_id"]))) {
  $insertSQL = sprintf("INSERT INTO cource_students (c_id, stu_id, cost, insert_date, reg_end_date) VALUES (%s, %s, %s, NOW(), now() + interval 1 month)",
                       GetSQLValueString($_POST['c_id'], "int"),
                       GetSQLValueString($_POST['stu_id'], "int"),
                       GetSQLValueString($_POST['cost'], "double"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
    $msg = 'تم  التسجيل' ;

}
}else{
	
	  $deleteSQL = sprintf("DELETE FROM cource_students WHERE id=%s",
                       GetSQLValueString($row_is_reg['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
  
  $msg = 'تم الغاء التسجيل' ;
	
	}
	
	
	
	
echo $msg; 
	
?>



  
<?php
mysql_free_result($is_reg);
?>
