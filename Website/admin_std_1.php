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
$query_all_users = "SELECT user_id, user_name, father_id, user_type, mobile FROM users WHERE user_type in(3,4)";
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
$totalRows_all_users = mysql_num_rows($all_users);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row" > 
  
  <div   class="col-md-6" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="#">
    <h1 align="center" > <i class="fas fa-child"></i> </h1>
    
    الطلاب        </a></div>
  
  
  
  
  <div   class="col-md-6" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_new_student.php">
    
    <h1 align="center" > <i class="fas fa-user-tie"></i> </h1>
    
    
    اولياء الامور         </a></div>
  
  
  
</div> 
        
        
        
        
           
           
           
    
<div class="row"  > 
    
    
    
    <div class="col-md-12" dir="ltr" >
     
     
     <table border="1" class="table table-striped table-lightfont dataTable no-footer" id="dataTable1" dir="rtl" role="grid" aria-describedby="dataTable1_info">
<thead class="bg-dark text-white">



            <td>user_name</td>
           <td>mobile</td>
            <td> </td>

</thead>

<tbody>
        <?php do { ?>
          <tr <?php if ($row_all_users['user_type'] == 3) {echo 'class="bg-info text-white"';} ; ?> > 
            <td><?php echo $row_all_users['user_name']; ?></td>
             <td><?php echo $row_all_users['mobile']; ?></td>
             
              <td>
              
              
               <?php if ($row_all_users['user_type'] == 3) {
				   
				   
				  ?> 
             <a href="admin_father_profile.php?id=<?php echo $row_all_users['user_id']; ?>" class="btn btn-dark text-white " > عرض الملف  </a> 
              <?php } ; ?> 
              
              
                          
               <?php if ($row_all_users['user_type'] == 4) {
				   
				   
				  ?> 
             <a href="admin_student_profile.php?id=<?php echo $row_all_users['user_id']; ?>" class="btn btn-dark text-white " > عرض الملف  </a> 
              <?php } ; ?> 
              
              
              
              
              </td>
              
              
          </tr>
          <?php } while ($row_all_users = mysql_fetch_assoc($all_users)); ?>
          
          </tbody>
      </table>
    </div> 
    
    
    </div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_users);
?>
