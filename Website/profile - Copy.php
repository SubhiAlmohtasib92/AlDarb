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
//

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

mysql_select_db($database_conn, $conn);
$query_calender_programs = "SELECT cource_program.id, courses.c_name, cource_program.p_day, cource_program.from_time, cource_program.to_time, cource_program.cource_id FROM cource_program, courses WHERE cource_program.cource_id =courses.c_id AND courses.c_status=1 and cource_program.p_day = '".get_day_name_from_date()."'";
$calender_programs = mysql_query($query_calender_programs, $conn) or die(mysql_error());
$row_calender_programs = mysql_fetch_assoc($calender_programs);
$totalRows_calender_programs = mysql_num_rows($calender_programs);

$maxRows_last_cr = 15;
$pageNum_last_cr = 0;
if (isset($_GET['pageNum_last_cr'])) {
  $pageNum_last_cr = $_GET['pageNum_last_cr'];
}
$startRow_last_cr = $pageNum_last_cr * $maxRows_last_cr;

mysql_select_db($database_conn, $conn);
$query_last_cr = "SELECT catch_receipt.id, users.user_name, courses.c_name, catch_receipt.mony, catch_receipt.date_insert, catch_receipt.notes FROM catch_receipt, users, courses WHERE catch_receipt.to_student = users.user_id AND catch_receipt.for_cource = courses.c_id ORDER BY id DESC";
$query_limit_last_cr = sprintf("%s LIMIT %d, %d", $query_last_cr, $startRow_last_cr, $maxRows_last_cr);
$last_cr = mysql_query($query_limit_last_cr, $conn) or die(mysql_error());
$row_last_cr = mysql_fetch_assoc($last_cr);

if (isset($_GET['totalRows_last_cr'])) {
  $totalRows_last_cr = $_GET['totalRows_last_cr'];
} else {
  $all_last_cr = mysql_query($query_last_cr);
  $totalRows_last_cr = mysql_num_rows($all_last_cr);
}
$totalPages_last_cr = ceil($totalRows_last_cr/$maxRows_last_cr)-1;

if (!isset($_SESSION)) {
  session_start();
}

$page['title'] = 'الصفحة الرئيسية    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <h3 align="center" > </h3>
  
  
  <h3 align="center" > برنامج اليوم  ( <?php echo get_day_name_from_date() ; ?> ) </h3>
   

<table border="1" class="table table hover ">
  <tr class="bg-dark text-white">
     <td>الدرس</td>
     <td>من الساعة </td>
    <td>حتى الساعة </td>
    <td>  الطلاب </td>
    
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_calender_programs['c_name']; ?></td>
      <td><?php echo $row_calender_programs['from_time']; ?></td>
      <td><?php echo $row_calender_programs['to_time']; ?></td>
      
      <td>
        <a href="admin_cource_students.php?id=<?php echo $row_calender_programs['cource_id']; ?>" target="_new" class="btn btn-dark btn-sm  ">  
         
         <?php 
		 $colname_regesters_count = "-1";
if (isset( $row_calender_programs['cource_id'])) {
  $colname_regesters_count =  $row_calender_programs['cource_id']; 
}
mysql_select_db($database_conn, $conn);
$query_regesters_count = sprintf("SELECT * FROM cource_students WHERE c_id = %s", GetSQLValueString($colname_regesters_count, "int"));
$regesters_count = mysql_query($query_regesters_count, $conn) or die(mysql_error());
$row_regesters_count = mysql_fetch_assoc($regesters_count);
$totalRows_regesters_count = mysql_num_rows($regesters_count);

echo $totalRows_regesters_count ; 
?>
          
         
          </a>
          
      </td>
      
      
    </tr>
    <?php } while ($row_calender_programs = mysql_fetch_assoc($calender_programs)); ?>
</table>







<br />
<br />
<h3 align="center" > اخر سندات القبض  </h3>
<table border="1" class="table table hover ">
  <tr class="bg-dark text-white">
  
    <td>الطالب </td>
    <td>عن الدورة </td>
    <td>المبلغ</td>
    <td>تاريخ الاضافة </td>
    <td>ملاحظات </td>  
    
    <td> </td>
  </tr>
  <?php do { ?>
    <tr>
       <td><?php echo $row_last_cr['user_name']; ?></td>
      <td><?php echo $row_last_cr['c_name']; ?></td>
      <td><?php echo $row_last_cr['mony']; ?></td>
      <td><?php echo $row_last_cr['date_insert']; ?></td>
      <td><?php echo $row_last_cr['notes']; ?></td>
      
      <td><a href="cach_print.php?id=<?php echo $row_last_cr['id']; ?>" class="btn btn-dark text-white " > طباعة سند القبض  </a></td>
      
      
      
    </tr>
    <?php } while ($row_last_cr = mysql_fetch_assoc($last_cr)); ?>
</table>
<?php 
//
    include('templeat_footer.php'); 
 
mysql_free_result($calender_programs);

mysql_free_result($last_cr);
?>
