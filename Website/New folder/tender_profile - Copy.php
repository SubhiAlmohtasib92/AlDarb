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

$colname_tender_info = "-1";
if (isset($_GET['id'])) {
  $colname_tender_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_info = sprintf("SELECT * FROM tenders WHERE id = %s", GetSQLValueString($colname_tender_info, "int"));
$tender_info = mysql_query($query_tender_info, $conn) or die(mysql_error());
$row_tender_info = mysql_fetch_assoc($tender_info);
$totalRows_tender_info = mysql_num_rows($tender_info);
 
//


$colname_tender_submitions = "-1";
if (isset($_GET['id'])) {
  $colname_tender_submitions = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_submitions = sprintf("SELECT tender_submissions.id, t_suppliers.supplier_name, tender_submissions.submit_date, tender_submissions.file_attach, tender_submissions.notes FROM tender_submissions, t_suppliers WHERE tender_id = %s AND tender_submissions.for_supplier = t_suppliers.id", GetSQLValueString($colname_tender_submitions, "int"));
$tender_submitions = mysql_query($query_tender_submitions, $conn) or die(mysql_error());
$row_tender_submitions = mysql_fetch_assoc($tender_submitions);
$totalRows_tender_submitions = mysql_num_rows($tender_submitions);
 
 
 


$page['title'] = 'صفحة عطاء    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
  
  
  <div class="row  ">
  
  
  
  
  
  
  <div class="col-md-4  ">
                                                <div class="main-card mb-3 card shadow p-3 mb-5 text-dark bg-white rounded  ">
                                                    <div class="card-body   ">
                            
                            
                  <h4 class="" align="center">  <?php echo $row_tender_info['tender_name']; ?></h4>          
                            
                            <table class="table" width="100%" border="1" dir="rtl" >
  <tr>
    <td>رقم العطاء</td>
    <td><?php echo $row_tender_info['tender_number']; ?></td>
  </tr>
   
  <tr>
    <td>تاريخ بدء التسليم</td>
    <td><?php echo $row_tender_info['tender_start_submission']; ?></td>
  </tr>
  <tr>
    <td>تاريخ انتهاء التسليم</td>
    <td><?php echo $row_tender_info['tender_end_submission']; ?></td>
  </tr>
  <tr>
    <td>تاريخ فتح العطاء</td>
    <td><?php echo $row_tender_info['tender_open_date']; ?></td>
  </tr>
  <tr>
    <td>تاريخ اللجنة الفنية</td>
    <td><?php echo $row_tender_info['tender_eval_date']; ?></td>
  </tr>
  <tr>
    <td>سعر العطاء</td>
    <td><?php echo $row_tender_info['tender_price']; ?></td>
  </tr>
  <tr>
    <td>صفري</td>
    <td><?php echo $row_tender_info['tender_vat']; ?></td>
  </tr>
  <tr>
    <td>تكاليف الاعلان في الجريدة</td>
    <td><?php echo $row_tender_info['adv_cost']; ?></td>
  </tr>
  <tr>
    <td>الكفاله</td>
    <td><?php echo $row_tender_info['Warranty']; ?></td>
  </tr>
  <tr>
    <td>فترة التنفيذ</td>
    <td><?php echo $row_tender_info['Implementation_period']; ?></td>
  </tr>
  <tr>
    <td>الخطوط المرجعية </td>
    <td><a class="btn btn-primary text-white" data-toggle="modal" data-target=".TOR" >View TOR</a></td>
  </tr>
  <tr>
    <td>تاريخ اضافة العطاء</td>
    <td><?php echo $row_tender_info['insert_date']; ?></td>
  </tr>
  
</table>

     
     
     
                    
                            

                       
                     <a href="tender_edit.php?id=<?php echo $row_tender_info['id']; ?>" class="btn btn-primary ">  تعديل بيانات العطاء </a>               
                                    
                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                 
                                                
                                                
                                                <p>&nbsp;</p>
  </div>
  
  
  
  
  
  
  <div class="col-md-8">
  
  
  
  
                                                 
           
                                   
                                                
                                                
                                                
                 <div class="main-card mb-3 card text-dark shadow p-3 mb-5 bg-white rounded ">
                                                
                                                <div class="card-header bg-primary text-white ">الشركات المتقدمة 
                                                  <div class="btn-actions-pane-left">
                                                    <a href="tender_profile_firms.php?id=<?php echo $_GET['id'];?>" class="btn btn-primary text-white btn-sm"> اضافة جديد <i class="nav-icon-big typcn typcn-plus"></i> </a>
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body" dir="rtl">
                                     
                                     
                                                                               
                                                
         <?php if ($totalRows_tender_submitions == 0) { // Show if recordset not empty ?>            
                     
                     
                     
<br />

  <h3 align="center" > لم يتم اضافة اي شركة سلمت العطاء  </h3
>

 <br />




  <?php }else { // Show if recordset not empty ?>


<table class="table table-hover" > 
<?php do { ?>


<tr >
<td > <?php echo $row_tender_submitions['supplier_name']; ?></td>
<td > <?php echo $row_tender_submitions['submit_date']; ?></td>

 </tr>

  <?php } while ($row_tender_submitions = mysql_fetch_assoc($tender_submitions)); ?>
> 

</table>



  <?php } // Show if recordset not empty ?>



                            
                                                
                                                        
                                                          

                                                    </div>
    </div>                               
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                        
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
       <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
                                                
                                                <div class="card-header bg-primary text-white ">العطاء    
                                                  <div class="btn-actions-pane-left">
              
              
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body" dir="rtl">
                                     
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
              <!--calender -->    
              
              
              
              
              
         <div id="tender_calender" > </div>
         
         
         
         
         
           
                                                          

                                                    </div>
    </div>                                         
                                                
                                                
                                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                                
                                                
                                                                
                                                
                                                
                                                
                                                
                                                
                                                
  </div>
  
  
  
  
  
  
  
  
                                            
                                        </div>
   
   
   </div>
   
   
   
   

   </div> 
  
<?php 
//
  include('templeat_footer.php'); 
 
?>







 
  <script>
 
 
 
 
 $('#tender_calender').fullCalendar({
	 
	header: { center: 'month,agendaWeek' }, // buttons for switching between views

 

  events: [
    {
      title: 'فترة العطاء',
      start:'<?php echo $row_tender_info['tender_start_submission']; ?>' ,
      end: '<?php echo $row_tender_info['tender_end_submission']; ?>' , 
	  url: 'www.anas.com'
    },
    {
      title: ' فتح العطاء ',
      start: '<?php echo $row_tender_info['tender_open_date']; ?>',
	  url:'#'
    },
     
    {
      title: ' فتح العطاء ',
      start: '2019-06-20 07:28:00',
	  url:'#'
    }
  ]
});



   
  </script>
  
  
  
     
    <div   class="modal fade bd-example-modal-lg TOR " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5   class="modal-title"   dir="rtl" align="right">TOR | الخطوط المرجعية </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 
                 <?php echo $row_tender_info['TOR']; ?>
                 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق </button>
                
            </div>
        </div>
    </div>
</div> 
     
     
     
     <?php mysql_free_result($tender_info);
 ?> 