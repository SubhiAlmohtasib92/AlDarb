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

$colname_all_father_stu = "-1";
if (isset($_POST['father_id'])) {
  $colname_all_father_stu = $_POST['father_id'];
}
mysql_select_db($database_conn, $conn);
$query_all_father_stu = sprintf("SELECT * FROM users WHERE father_id = %s", GetSQLValueString($colname_all_father_stu, "int"));
$all_father_stu = mysql_query($query_all_father_stu, $conn) or die(mysql_error());
$row_all_father_stu = mysql_fetch_assoc($all_father_stu);
$totalRows_all_father_stu = mysql_num_rows($all_father_stu);



 ?>

<table border="1" class="table ">
 
  <?php do { ?>
    <tr>
       <td><?php echo $row_all_father_stu['user_name']; ?></td> 
       
       
       <td>
       
       
       <?php 
	   
	   
$colname_stu_courcess = "-1";
if (isset($row_all_father_stu['user_id'])) {
  $colname_stu_courcess = $row_all_father_stu['user_id'];
}
mysql_select_db($database_conn, $conn);
$query_stu_courcess = sprintf("SELECT cource_students.id, cource_students.c_id, cource_students.cost, cource_students.insert_date, courses.c_name FROM cource_students, courses WHERE stu_id = %s AND cource_students.c_id = courses.c_id", GetSQLValueString($colname_stu_courcess, "int"));
$stu_courcess = mysql_query($query_stu_courcess, $conn) or die(mysql_error());
$row_stu_courcess = mysql_fetch_assoc($stu_courcess);
$totalRows_stu_courcess = mysql_num_rows($stu_courcess);	   
	    ?> 
       
       <table border="1" class="table ">
         <tr class="bg-dark text-white ">
         
         <td>الدورة </td>
            
           <td>تاريخ التسجيل </td>
           <td>التكلفة</td>
          <td>   المدفوع      </td>
            <td>   الباقي      </td> 
            
            <td>          </td>
         </tr>
         <?php
		 
		 $total_cost = 0 ; 
		 $total_payed= 0 ; 
		 
		 
		  do { ?>
           <tr>
           
           <td><?php echo $row_stu_courcess['c_name']; ?></td>
          
              
             <td><?php echo $row_stu_courcess['insert_date']; ?></td>
             <td><?php echo $row_stu_courcess['cost']; $total_cost+= $row_stu_courcess['cost'] ; ?></td>
               <td> 
               
               <?php 
			   
			   $colname_stu_payed = "-1";
if (isset($row_all_father_stu['user_id'])) {
  $colname_stu_payed = $row_all_father_stu['user_id'];
}
$for_cource_stu_payed = "-1";
if (isset($row_stu_courcess['id'])) {
  $for_cource_stu_payed = $row_stu_courcess['id'];
}
mysql_select_db($database_conn, $conn);
$query_stu_payed = sprintf("SELECT SUM(catch_receipt.mony) as mony  FROM catch_receipt WHERE to_student = %s AND catch_receipt.for_cource=%s", GetSQLValueString($colname_stu_payed, "int"),GetSQLValueString($for_cource_stu_payed, "int"),GetSQLValueString($for_cource_stu_payed, "int"));
$stu_payed = mysql_query($query_stu_payed, $conn) or die(mysql_error());
$row_stu_payed = mysql_fetch_assoc($stu_payed);
$totalRows_stu_payed = mysql_num_rows($stu_payed);

echo $row_stu_payed['mony'] ; 
 $total_payed+= $row_stu_payed['mony'] ; 


 ?> 
               
               
               
                      </td>
             <td> <?php echo $row_stu_courcess['cost'] -  $row_stu_payed['mony'] ; ?>        </td>
             
             
               <td><a class="btn btn-dark btn-sm text-white "  data-toggle="modal" data-target="#myModal" onclick="cr_new('<?php echo $row_all_father_stu['user_id']; ?>','<?php echo $row_stu_courcess['id']; ?>','<?php echo $row_all_father_stu['father_id']; ?>')" > اضافة دفعة  </a> </td>
             
             
           </tr>
           <?php } while ($row_stu_courcess = mysql_fetch_assoc($stu_courcess)); ?>
           
           
           <tr class="bg-info "> 
           
           <td colspan="2" > </td> 
           
           <td >
           <?php echo $total_cost ; ?>  </td> 
           
           <td ><?php echo  $total_payed ; ?>  </td> 
           
           <td >  <?php echo $total_cost - $total_payed ; ?></td> 
           <td   > </td> 
           </tr> 
           
       </table></td>
       
       
       
    
    </tr>
    <?php } while ($row_all_father_stu = mysql_fetch_assoc($all_father_stu)); ?>
</table>
<?php
mysql_free_result($all_father_stu);

mysql_free_result($stu_payed);

mysql_free_result($stu_courcess);
?>
