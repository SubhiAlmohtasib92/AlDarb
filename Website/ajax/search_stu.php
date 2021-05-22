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
$maxRows_last_added_students = 20;
$pageNum_last_added_students = 0;
if (isset($_GET['pageNum_last_added_students'])) {
  $pageNum_last_added_students = $_GET['pageNum_last_added_students'];
}
$startRow_last_added_students = $pageNum_last_added_students * $maxRows_last_added_students;

$colname_last_added_students = "-1";
if (isset($_POST['search_text'])) {
  $colname_last_added_students = $_POST['search_text'];
}
mysql_select_db($database_conn, $conn);
$query_last_added_students = sprintf("SELECT user_id, user_name, address, emergency_data, mobile, father_work_location FROM users WHERE user_name LIKE %s and user_type = 4 ", GetSQLValueString("%" . $colname_last_added_students . "%", "text"));
$query_limit_last_added_students = sprintf("%s LIMIT %d, %d", $query_last_added_students, $startRow_last_added_students, $maxRows_last_added_students);
$last_added_students = mysql_query($query_limit_last_added_students, $conn) or die(mysql_error());
$row_last_added_students = mysql_fetch_assoc($last_added_students);

if (isset($_GET['totalRows_last_added_students'])) {
  $totalRows_last_added_students = $_GET['totalRows_last_added_students'];
} else {
  $all_last_added_students = mysql_query($query_last_added_students);
  $totalRows_last_added_students = mysql_num_rows($all_last_added_students);
}
$totalPages_last_added_students = ceil($totalRows_last_added_students/$maxRows_last_added_students)-1;
	
	
	
	echo $_POST['search_text'] ; 
?>

<table border="1" class="table " dir="rtl" >
  
    <tr class="bg-dark text-white">

      <td>اسم الطالب </td>
      <td>العنوان</td>
       <td>موبايل</td>
       
      <td>  </td>
    </tr>
    <?php do { ?>
      <tr>

        <td><?php echo $row_last_added_students['user_name']; ?></td>
        <td><?php echo $row_last_added_students['address']; ?></td>
         <td><?php echo $row_last_added_students['mobile']; ?></td>
         
        <td>
              
              
                
              
              
                          
                
             <a href="admin_student_profile.php?id=<?php echo $row_last_added_students['user_id']; ?>" class="btn btn-dark text-white "> عرض الملف  </a> 
               
              
              
              
              
              </td>
              
              
      </tr>
      <?php } while ($row_last_added_students = mysql_fetch_assoc($last_added_students)); ?>
  </table>

  
<?php
mysql_free_result($last_added_students);
?>
