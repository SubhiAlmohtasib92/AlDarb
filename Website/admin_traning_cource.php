<?php require_once('Connections/conn.php'); ?>
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

mysql_select_db($database_conn, $conn);
$query_Recordset1 = "SELECT courses.c_id, courses.c_name, courses.c_type, users.user_name  FROM courses, users WHERE c_status = 1 AND courses.c_teatcher =users.user_id ORDER BY c_type ASC";
$Recordset1 = mysql_query($query_Recordset1, $conn) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);




if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
 
  


<div class="col-sm-12">
    <div class="element-wrapper">
      <div class="element-actions"> </div>
      <h6 class="element-header" align="center">  فتح دورة تدريبية جديدة </h6>
      <div class="element-content">
        <div class="row">
         
         
          <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_Systematic_courses.php">

            <h3 align="center">دورات منهجية </h3>
              </a></div>
              
              
              
              
              
              
              
                    <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_fundimintal_courses.php">

            <h3 align="center">دورات تأسيس  </h3>
              </a></div>
              
              
              
                    <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_nonSystematic_courses.php">

            <h3 align="center">دورات لا منهجية  </h3>
              </a></div>
              
              
              
              
              
              


          
          
       
       
        </div>
      </div>
    </div>
  </div>






<div class="element-actions"> </div>
      <h6 class="element-header" align="center">  الدورات الحالية  </h6>
<table border="1" class="table table-hover ">
        <tr class="bg-dark text-white">
           <td>اسم الدورة</td>
          <td>نوع الدورة</td>
          <td>المدرس</td>
            <td>البرنامج</td>
            
            
           
         <td>الطلاب</td> 
          
          
  </tr>
        <?php do { ?>
          <tr>
             <td><?php echo $row_Recordset1['c_name']; ?></td>
            <td><?php echo $row_Recordset1['c_type']; ?></td>
            <td><?php echo $row_Recordset1['user_name']; ?></td>
            
           <td>
           
           <table border="0">
  
  <?php 
  
 $colname_c_program = "-1";
if (isset($row_Recordset1['c_id'])) {
  $colname_c_program = $row_Recordset1['c_id'];
}
mysql_select_db($database_conn, $conn);
$query_c_program = sprintf("SELECT * FROM cource_program WHERE cource_id = %s", GetSQLValueString($colname_c_program, "int"));
$c_program = mysql_query($query_c_program, $conn) or die(mysql_error());
$row_c_program = mysql_fetch_assoc($c_program);
$totalRows_c_program = mysql_num_rows($c_program);

  
  
  
  do { ?>
    <tr>
       <td><?php echo $row_c_program['p_day']; ?></td>
      <td><?php echo $row_c_program['from_time']; ?> - <?php echo $row_c_program['to_time']; ?></td>
      </tr>
    <?php } while ($row_c_program = mysql_fetch_assoc($c_program)); ?>
</table>
           
           
           
           </td>
           
           
         <td>
         
         
         <a href="admin_cource_students.php?id=<?php echo $row_Recordset1['c_id']; ?>" target="_new" class="btn btn-dark btn-sm  ">  
         
         <?php 
		 $colname_regesters_count = "-1";
if (isset($row_Recordset1['c_id'])) {
  $colname_regesters_count = $row_Recordset1['c_id'];
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
          <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
      </table>

<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($Recordset1);

mysql_free_result($c_program);

mysql_free_result($regesters_count);
?>
