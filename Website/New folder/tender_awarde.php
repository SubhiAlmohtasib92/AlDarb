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
  $insertSQL = sprintf("INSERT INTO awarded_tenders (tender_id, awarded_supplier, award_date, submission_date, submission_note, insert_date, insert_by) VALUES (%s, %s, %s, %s, %s, NOW() , %s)",
                       GetSQLValueString($_GET['id'], "int"),
                       GetSQLValueString($_POST['awarded_supplier'], "int"),
                       GetSQLValueString($_POST['award_date'], "date"),
                       GetSQLValueString($_POST['submission_date'], "date"),
                       GetSQLValueString($_POST['submission_note'], "text") ,
                       GetSQLValueString($_SESSION['user_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
    header("location: ".$_SERVER['REQUEST_URI']  ) ; 



}

if ((isset($_POST['delete_tender_awwarde'])) && ($_POST['delete_tender_awwarde'] != "")) {
  $deleteSQL = sprintf("DELETE FROM awarded_tenders WHERE id=%s",
                       GetSQLValueString($_POST['delete_tender_awwarde'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
  
    header("location: ".$_SERVER['REQUEST_URI']  ) ; 



}

if ((isset($_POST['delete_attach'])) && ($_POST['delete_attach'] != "")) {
  $deleteSQL = sprintf("DELETE FROM awarded_tenders_attach WHERE id=%s",
                       GetSQLValueString($_POST['delete_attach'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
    header("location: ".$_SERVER['REQUEST_URI']  ) ; 


}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	
	
	
	
	
	
	if($_FILES['attach_file']['name']) {

 
  $path = $_FILES['attach_file']['name'] ;
$ext = pathinfo($path, PATHINFO_EXTENSION);
// echo $ext;
$u_file = rand(1111111,9999999999).'_'.$_SESSION['user_id'].'_'.$_GET['id'].$ext;
$uploaddir = 'upludes/awarded_tender_attach/';
$uploadfile = $uploaddir . basename($u_file);
if (move_uploaded_file($_FILES['attach_file']['tmp_name'], $uploadfile)) {
}else {
	
	
	}
	}
	
	
	
  $insertSQL = sprintf("INSERT INTO awarded_tenders_attach (awarded_id, tender_id, attach_type, attach_file, notes, insert_date, insert_by) VALUES (%s, %s, %s, %s, %s,NOW(), %s)",
                       GetSQLValueString($_POST['awarded_id'], "int"),
                       GetSQLValueString($_GET['id'], "int"),
                       GetSQLValueString($_POST['attach_type'], "text"),
                       GetSQLValueString($uploadfile , "text"),
                       GetSQLValueString($_POST['notes'], "text"),
           
                       GetSQLValueString($_SESSION['user_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  header("location: ".$_SERVER['REQUEST_URI']  ) ; 
}

$colname_all_tender_supplaiers = "-1";
if (isset($_GET['id'])) {
  $colname_all_tender_supplaiers = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_all_tender_supplaiers = sprintf("SELECT tender_submissions.id, t_suppliers.supplier_name, tender_submissions.for_supplier FROM tender_submissions, t_suppliers WHERE tender_id = %s AND tender_submissions.for_supplier = t_suppliers.id", GetSQLValueString($colname_all_tender_supplaiers, "int"));
$all_tender_supplaiers = mysql_query($query_all_tender_supplaiers, $conn) or die(mysql_error());
$row_all_tender_supplaiers = mysql_fetch_assoc($all_tender_supplaiers);
$totalRows_all_tender_supplaiers = mysql_num_rows($all_tender_supplaiers);

$colname_awward_supplaier = "-1";
if (isset($_GET['id'])) {
  $colname_awward_supplaier = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_awward_supplaier = sprintf("SELECT awarded_tenders.id, awarded_tenders.award_date, awarded_tenders.submission_date, awarded_tenders.submission_note, awarded_tenders.insert_date, t_suppliers.supplier_name FROM awarded_tenders, t_suppliers WHERE tender_id = %s AND awarded_tenders.awarded_supplier = t_suppliers.id", GetSQLValueString($colname_awward_supplaier, "int"));
$awward_supplaier = mysql_query($query_awward_supplaier, $conn) or die(mysql_error());
$row_awward_supplaier = mysql_fetch_assoc($awward_supplaier);
$totalRows_awward_supplaier = mysql_num_rows($awward_supplaier);


//


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





$page['title'] = "ترسية العطاء "  ;
$page['desc'] = "يمكن من خلال هذه الصفحة ترسية العطاء على احد المردين الذين قامو بتسليم العطاء ";
 
 include('templeat_header.php');
  ?>
  
  
  
  
  
     
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
<div class="row  ">
  <div class="col-md-4  " align="right" >
    <div class="main-card text-dark mb-3 card shadow p-3 mb-5 bg-white rounded  ">
      <div class="card-body   ">
        <h4 class="" align="center"> <?php echo $row_tender_info['tender_name']; ?></h4>
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          
          
          
          
          اختر مورد 
          <br />
يظهر في القائمة الموردين الذين قامو بتسليم العطاء 
          
          <select name="awarded_supplier" class="form-control" required >
          
          <option></option>
          
                <?php 
do {  
?>
                <option value="<?php echo $row_all_tender_supplaiers['for_supplier']; ?>" ><?php echo $row_all_tender_supplaiers['supplier_name']?></option>
                <?php
} while ($row_all_tender_supplaiers = mysql_fetch_assoc($all_tender_supplaiers));
?>
              </select>
              
              
             
             
             <br />
 
          
         
         تاريخ الترسية 
         
     
     <input class="form-control" required="required" type="date" name="award_date" value="" size="32" />
         
         
         
         <br />

تاريخ التسليم 

         
         
         
         
         
         
    <input class="form-control" required="required"  type="date" name="submission_date" value="" size="32" />
    
    
    
    
    
    <br />

ملاحظات


<textarea class="form-control" name="submission_note" cols="32"></textarea>



<br />






<input class="btn btn-primary " type="submit" value="اضافة ترسية " />



          <input type="hidden" name="tender_id" value="" />
          <input type="hidden" name="insert_date" value="" />
          <input type="hidden" name="insert_by" value="" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        
        
        
         </div>
    </div>
  </div>
  <div class="col-md-8">
  
    <?php if ($totalRows_awward_supplaier > 0) { // Show if recordset not empty ?>
<div>
    
    
    <?php do { ?>
      <div class="main-card text-dark mb-3 card shadow p-3 mb-5 bg-white rounded ">
        <div class="card-header   ">
          
          <?php echo $row_awward_supplaier['supplier_name']; ?>
          
          <div class="btn-actions-pane-left"> 
            
            <div class="dropdown">
              <a href="#" class="dropdown-toggle btn btn-primary " data-toggle="dropdown">خيارات </a>
              <div class="dropdown-menu">
                <a href="#" data-toggle="modal" data-target="#myModal"  class="dropdown-item" onclick="get_attach_form('<?php echo $row_awward_supplaier['id']; ?>','1')">اضافة امر مباشرة </a>  
                
                 <a href="#" data-toggle="modal" data-target="#myModal"  class="dropdown-item" onclick="get_attach_form('<?php echo $row_awward_supplaier['id']; ?>','2')">اضافة اتفاقية المورد    </a>
                
                <a href="#" data-toggle="modal" data-target="#myModal"  class="dropdown-item" onclick="get_attach_form('<?php echo $row_awward_supplaier['id']; ?>','3')">اضافة ملحق اتفاق </a>
                
                
                
                <a href="tender_awarde_evaluation.php?awarde_id=<?php echo $row_awward_supplaier['id']; ?>" class="dropdown-item">تقييم المورد      </a>
                
                
                
                <form action="" method="post" onsubmit="return confirm(' هل انت متاكد من الغاء الترسية على المورد  <?php echo $row_awward_supplaier['supplier_name']; ?> ? ');"> 
                  <input type="hidden" name="delete_tender_awwarde" value="<?php echo $row_awward_supplaier['id']; ?>">
                  <input type="submit" value="  الغاء الترسية       " class="btn btn-danger  form-control" > 
                  </form>
                
                
                
              </div>
              </div>
            
            
            
            </div>
          </div>
        <div class="card-body" dir="rtl">
          
          
          <table width="100%" border="1" >
  <tr>
    <td>تاريخ الترسية : <?php echo $row_awward_supplaier['award_date']; ?></td>
    <td>تاريخ انهاء الاعمال : <?php echo $row_awward_supplaier['submission_date']; ?></td>
  </tr>
  <tr > 
  <td colspan="2" ><?php echo $row_awward_supplaier['submission_note']; ?> </td> 
  
  </tr>
 
</table>
          <table   class="table">
          
        
            <?php 
			
			
			
$colname_awward_attachments = "-1";
if (isset($row_awward_supplaier['id'] )) {
  $colname_awward_attachments = $row_awward_supplaier['id'];
}
mysql_select_db($database_conn, $conn);
$query_awward_attachments = sprintf("SELECT * FROM awarded_tenders_attach WHERE awarded_id = %s", GetSQLValueString($colname_awward_attachments, "int"));
$awward_attachments = mysql_query($query_awward_attachments, $conn) or die(mysql_error());
$row_awward_attachments = mysql_fetch_assoc($awward_attachments);
$totalRows_awward_attachments = mysql_num_rows($awward_attachments);
 			
			if ($totalRows_awward_attachments > 0 ) { 
			
			do { ?>
              <tr>
        
              
                <td><?php echo $row_awward_attachments['attach_type']; ?></td>
                <td><a target="_new" href="<?php echo $row_awward_attachments['attach_file']; ?>" class="btn btn-primary btn-sm" > عرض الملف </a></td>
                <td><?php echo $row_awward_attachments['notes']; ?></td>
                <td><?php echo $row_awward_attachments['insert_date']; ?></td>
                
                
                
                <td> 
                
                <form action="" method="post" onsubmit="return confirm('هل انت متاكد من حذف الملف ؟  ');"> 
              <input type="hidden" name="delete_attach" value="<?php echo $row_awward_attachments['id']; ?>">
              <input type="submit" value="حذف الملف " class="btn btn-danger btn-sm" style="font-size:10px; "> 
              </form>
              
              
                
                 </td>
               
              </tr>
              <?php } while ($row_awward_attachments = mysql_fetch_assoc($awward_attachments)); 
			  
			} 
			  
			  ?>
          </table>
          
          
          
          
          
          <?php 
		  
		  $colname_awarded_evaluation = "-1";
if (isset( $row_awward_supplaier['id'])) {
  $colname_awarded_evaluation =   $row_awward_supplaier['id'];
} 
mysql_select_db($database_conn, $conn);
$query_awarded_evaluation = sprintf("SELECT eval_id, summary, period_start, period_end FROM evaluations WHERE awarded__id = %s", GetSQLValueString($colname_awarded_evaluation, "int"));
$awarded_evaluation = mysql_query($query_awarded_evaluation, $conn) or die(mysql_error());
$row_awarded_evaluation = mysql_fetch_assoc($awarded_evaluation);
$totalRows_awarded_evaluation = mysql_num_rows($awarded_evaluation);




 ?>
          
          
          <h5 align="right" > تقييمات المورد  </h5>
          
         <a href="tender_awarde_evaluation.php?awarde_id=<?php echo  $row_awward_supplaier['id']; ; ?>" class="btn btn-success " > تفاصيل التقييمات  </a> 
          <?php if ($totalRows_awarded_evaluation > 0) { // Show if recordset not empty ?>
          
          
  <table border="1" class="table ">
    <tr class="bg-primary text-white  ">
      
      <td>ملخص التقييم</td>
      <td>بداية فترة التقييم</td>
      <td>نهاية فترة التقييم </td>
      <td> نتيجة التقييم  </td>
    </tr>
    <?php do { 
	
	
	
	
	
	
	$colname_evaluation_result = "-1";
if (isset($row_awarded_evaluation['eval_id'])) {
  $colname_evaluation_result =$row_awarded_evaluation['eval_id'];
}
mysql_select_db($database_conn, $conn);
$query_evaluation_result = sprintf("SELECT avg(evaluation_indicator_value.evaluation_value) FROM evaluation_indicator_value WHERE evaluation_id = %s", GetSQLValueString($colname_evaluation_result, "int"));
$evaluation_result = mysql_query($query_evaluation_result, $conn) or die(mysql_error());
$row_evaluation_result = mysql_fetch_assoc($evaluation_result);
$totalRows_evaluation_result = mysql_num_rows($evaluation_result);



	?>
      <tr>
        
        <td><?php echo $row_awarded_evaluation['summary']; ?></td>
        <td><?php echo $row_awarded_evaluation['period_start']; ?></td>
        <td><?php echo $row_awarded_evaluation['period_end']; ?></td>
        
        
        
        <td > 
        
        <?php echo number_format($row_evaluation_result['avg(evaluation_indicator_value.evaluation_value)']*10); ?>%
        
        
        </td>
      </tr>
      <?php } while ($row_awarded_evaluation = mysql_fetch_assoc($awarded_evaluation)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
        </div>
      </div>
      <?php } while ($row_awward_supplaier = mysql_fetch_assoc($awward_supplaier)); ?>
    
    
    
  </div>
  <?php } else {  // Show if recordset not empty ?>
  
  <h3 align="center" > 
  
  
  
  <br />
<br />


 لم يتم الترسية على اي مورد حتى اللحظة  </h3>
  
  <?php } ?> 
  </div>
</div>

</div>









 

<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_tender_supplaiers);

mysql_free_result($awward_supplaier);

mysql_free_result($awward_attachments);

mysql_free_result($awarded_evaluation);

mysql_free_result($evaluation_result);
?>



















<!-- The Modal -->
<div class="modal  fade " id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      

      <!-- Modal body -->
      <div class="modal-body" id="result" dir="rtl" >
 
 
 
      </div>
 

    </div>
  </div>
</div>




<script type="text/javascript" > 

function get_attach_form(id, attach_type){ 
	
    document.getElementById('result').innerHTML = "جاري التحميل ... " ; 
	
	  $.post("ajax/get_form.php",
  {
    id: id,
    attach_type: attach_type 
  },
  function(data, status){
   
   document.getElementById('result').innerHTML = data ; 
  });
	
	
	}


</script>



