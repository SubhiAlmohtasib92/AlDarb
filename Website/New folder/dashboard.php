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

$maxRows_last_suppl = 6;
$pageNum_last_suppl = 0;
if (isset($_GET['pageNum_last_suppl'])) {
  $pageNum_last_suppl = $_GET['pageNum_last_suppl'];
}
$startRow_last_suppl = $pageNum_last_suppl * $maxRows_last_suppl;

mysql_select_db($database_conn, $conn);
$query_last_suppl = "SELECT * FROM t_suppliers ORDER BY id DESC";
$query_limit_last_suppl = sprintf("%s LIMIT %d, %d", $query_last_suppl, $startRow_last_suppl, $maxRows_last_suppl);
$last_suppl = mysql_query($query_limit_last_suppl, $conn) or die(mysql_error());
$row_last_suppl = mysql_fetch_assoc($last_suppl);

if (isset($_GET['totalRows_last_suppl'])) {
  $totalRows_last_suppl = $_GET['totalRows_last_suppl'];
} else {
  $all_last_suppl = mysql_query($query_last_suppl);
  $totalRows_last_suppl = mysql_num_rows($all_last_suppl);
}
$totalPages_last_suppl = ceil($totalRows_last_suppl/$maxRows_last_suppl)-1;

$maxRows_last_tenders = 5;
$pageNum_last_tenders = 0;
if (isset($_GET['pageNum_last_tenders'])) {
  $pageNum_last_tenders = $_GET['pageNum_last_tenders'];
}
$startRow_last_tenders = $pageNum_last_tenders * $maxRows_last_tenders;

mysql_select_db($database_conn, $conn);
$query_last_tenders = "SELECT id, tender_number, tender_name FROM tenders ORDER BY id DESC";
$query_limit_last_tenders = sprintf("%s LIMIT %d, %d", $query_last_tenders, $startRow_last_tenders, $maxRows_last_tenders);
$last_tenders = mysql_query($query_limit_last_tenders, $conn) or die(mysql_error());
$row_last_tenders = mysql_fetch_assoc($last_tenders);

if (isset($_GET['totalRows_last_tenders'])) {
  $totalRows_last_tenders = $_GET['totalRows_last_tenders'];
} else {
  $all_last_tenders = mysql_query($query_last_tenders);
  $totalRows_last_tenders = mysql_num_rows($all_last_tenders);
}
$totalPages_last_tenders = ceil($totalRows_last_tenders/$maxRows_last_tenders)-1;
 
//


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row" > 
  
  <div class="col-md-6" > 
    
    
    <div class="card-hover-shadow-2x mb-3 card shadow p-3 mb-5 bg-white rounded ">
      <div class="card-header"> اخر العطاءات المضافه </div>
      <div class="card-body">
        <table border="1" class="table">
          <tr class="bg-primary text-white ">
             
            <td>رقم العطاء</td>
            <td>اسم العطاء </td>
            
            <td></td>
          </tr>
          <?php do { ?>
            <tr>
               <td><?php echo $row_last_tenders['tender_number']; ?></td>
              <td><?php echo $row_last_tenders['tender_name']; ?></td>
              
              
              
              <td><a href="tender_profile.php?id=<?php echo $row_last_tenders['id']; ?>" class="btn btn-primary btn-sm" style="padding:2px; font-size:10px"> صفحة العطاء  </a></td>
              
              
            </tr>
            <?php } while ($row_last_tenders = mysql_fetch_assoc($last_tenders)); ?>
        </table>
    </div> </div> 
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="card-hover-shadow-2x mb-3 card shadow p-3 mb-5 bg-white rounded ">
      <div class="card-header"> اخر الموردين المضافين </div>
      <div class="card-body">
        <table border="1" class="table table-hover ">
          <tr class="bg-primary text-white ">
          
            <td>اسم المورد</td>
            <td>المسؤول</td>
       
            <td>رقم الهاتف</td>
             <td></td>
          </tr>
          <?php do { ?>
            <tr>
               <td><?php echo $row_last_suppl['supplier_name']; ?></td>
              <td><?php echo $row_last_suppl['supplier_Authorized_signatory']; ?></td>
            
              <td><?php echo $row_last_suppl['supplier_tel']; ?></td>
              
              <td>
              
              <a href="supplier_profile.php?id=<?php echo $row_last_suppl['id']; ?>" class="btn btn-primary btn-sm"> 
      عرض     
      
      
      </a>
      
      
      </td>
         
            </tr>
            <?php } while ($row_last_suppl = mysql_fetch_assoc($last_suppl)); ?>
        </table>
        
        
        
        <a href="suppliers_list.php" class="btn btn-primary btn-sm " > عرض جميع الموردين  </a>
        
        
      </div> </div>                                
    
    
    
    
    
    
    
    
   </div>
   
   
   
   <div class="col-md-6" > 
   
   
   
   
   <div id='calendar'></div>
   
   
   
   </div>
  
  
</div>


 
 <?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($last_suppl);

mysql_free_result($last_tenders);
?>



