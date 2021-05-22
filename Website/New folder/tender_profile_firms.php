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
	
	
	
	
	
	if($_FILES['file_attach']['name']) {

	
	  $path = $_FILES['file_attach']['name'] ;
$ext = pathinfo($path, PATHINFO_EXTENSION);
// echo $ext;
$u_file = rand(1111111,9999999999).'_'.$_SESSION['user_id'].'_'.$_GET['id'].'.'.$ext;
$uploaddir = 'upludes/tender_firms/';
$uploadfile = $uploaddir . basename($u_file);
if (move_uploaded_file($_FILES['file_attach']['tmp_name'], $uploadfile)) {
}else {
	
	
	}
	}
	
	
	
	
	
  $insertSQL = sprintf("INSERT INTO tender_submissions (tender_id, for_supplier, by_user, submit_date, file_attach, notes , submission_date , reserved_date , offer_value) VALUES (%s, %s, %s, NOW(), %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tender_id'], "int"),
                       GetSQLValueString($_POST['for_supplier'], "int"),
                       GetSQLValueString($_SESSION['user_id'], "int"),
        
		
                       GetSQLValueString($uploadfile , "text"),
                       GetSQLValueString($_POST['notes'], "text")
					   
			,
                       GetSQLValueString($_POST['submission_date'], "date")
					   		   
			,
                       GetSQLValueString($_POST['reserved_date'], "date")
					 		   
			,
                       GetSQLValueString($_POST['offer_value'], "date")
					 		   
					   );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
    header("location: ".$_SERVER['REQUEST_URI']  ) ; 


}

if ((isset($_POST['delete_tender_sumition'])) && ($_POST['delete_tender_sumition'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tender_submissions WHERE id=%s",
                       GetSQLValueString($_POST['delete_tender_sumition'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

$colname_tender_info = "-1";
if (isset($_GET['id'])) {
  $colname_tender_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_info = sprintf("SELECT id, tender_number, tender_name, tender_price FROM tenders WHERE id = %s", GetSQLValueString($colname_tender_info, "int"));
$tender_info = mysql_query($query_tender_info, $conn) or die(mysql_error());
$row_tender_info = mysql_fetch_assoc($tender_info);
$totalRows_tender_info = mysql_num_rows($tender_info);



$colname_tender_submitions = "-1";
if (isset($_GET['id'])) {
  $colname_tender_submitions = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_submitions = sprintf("SELECT tender_submissions.id, t_suppliers.supplier_name, tender_submissions.submit_date, tender_submissions.file_attach, tender_submissions.notes ,tender_submissions.submission_date ,tender_submissions.reserved_date ,tender_submissions.offer_value, tender_submissions.file_attach FROM tender_submissions, t_suppliers WHERE tender_id = %s AND tender_submissions.for_supplier = t_suppliers.id", GetSQLValueString($colname_tender_submitions, "int"));
$tender_submitions = mysql_query($query_tender_submitions, $conn) or die(mysql_error());
$row_tender_submitions = mysql_fetch_assoc($tender_submitions);
$totalRows_tender_submitions = mysql_num_rows($tender_submitions);


 mysql_select_db($database_conn, $conn);
$query_all_suplaires = "SELECT id, supplier_name FROM t_suppliers ORDER BY supplier_name ASC";
$all_suplaires = mysql_query($query_all_suplaires, $conn) or die(mysql_error());
$row_all_suplaires = mysql_fetch_assoc($all_suplaires);
$totalRows_all_suplaires = mysql_num_rows($all_suplaires);
//


$page['title'] = 'اضافة شركات الى العطاء     ';
$page['desc'] = $row_tender_info['tender_name']  ;
 
 include('templeat_header.php');
  ?>
  
  
  
  
    
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
  
  
  <div class="row  ">
  

  
  
  
  
  <div class="col-md-4  ">
  
 
 

                                                <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded text-dark ">
                                                    <div class="card-body   ">
                            
    
     
     
     <br />
<br />

     
     <center> 
     اضافة شركات متقدمة للعطاء 
     <hr />
     
          </center>  
          <div align="right"  > 
     
     <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl"  enctype="multipart/form-data" >
     
      الشركة المسلمة للعطاء  
     
     <select name="for_supplier"  required="required"  class=" multiselect-dropdown form-control " >
     <option ></option>
             <?php 
do {  
?>
             <option value="<?php echo $row_all_suplaires['id']?>" ><?php echo $row_all_suplaires['supplier_name']?></option>
             <?php
} while ($row_all_suplaires = mysql_fetch_assoc($all_suplaires));
?>
          </select>
      
      
      <br />

      ارفاق ملف 
     <input class="form-control" type="file" name="file_attach" value="" size="32" />
 
 <br />


  
 
 تاريخ التسليم 
 <input type="date" name="submission_date" class="form-control"  /> 
  
 
 <br />



تاريخ الاستلام 
<input type="date" name="reserved_date" class="form-control"  /> 
 

<br />



المبلغ المستلم    

<input type="number" name="offer_value" class="form-control"  /> 


<b style="color:#F00; font-size:9px ;  " > 

ملاحظة : سعر العطاء <?php echo $row_tender_info['tender_price']; ?> 

</b>

<br />

 
<br />




 ملاحظات 
 
 <textarea name="notes" cols="32" class="form-control"></textarea>  

 
 <br />


<input type="submit" class="btn btn-primary " value=" اضافة شركة " />




       <input type="hidden" name="tender_id" value="<?php echo $row_tender_info['id']; ?>" />
       <input type="hidden" name="by_user" value="" />
       <input type="hidden" name="submit_date" value="" />
       <input type="hidden" name="MM_insert" value="form1" />
     </form>
     <p>&nbsp;</p>
    
               
               
               </div>          
                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                 
                                                
                                                
                                                <p>&nbsp;</p>
  </div>
  
  
  
  
  
  
<div class="col-md-8">
  
  
  
  
                                                 
     




                 
                                     
                                                                               
                                                
         <?php if ($totalRows_tender_submitions == 0) { // Show if recordset not empty ?>            
                     
                     
                     
<br />

  <h3 align="center" > لم يتم اضافة اي شركة سلمت العطاء  </h3
>

 <br />




  <?php }else { // Show if recordset not empty ?>



<h3 align="center" > الشركات التي سلمت العطاء   </h3>


<?php do { ?>
                     
      <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded  text-dark ">
                                                
                                                 
                                                
                                                
                                                    <div class="card-body" dir="rtl">
                          
                          
       
       
       
       <div class="row" > 
      
       <div class="col-md-8" align="right" >
       
       
       <h5 align="right" > 
       <b>
       <?php echo $row_tender_submitions['supplier_name']; ?>
       </b>
       
       </h5> 
       


       
       
       
       
        </div>
        
        
        <div class="col-md-4" >
        
        
        
        
        
        
        
        
        <div class="btn-actions-pane-left"> 
            
            <div class="dropdown ">
              <a href="#" class="dropdown-toggle btn btn-primary " data-toggle="dropdown" aria-expanded="true">
              
              <span class="fa fa-cog fa-fw"></span> خيارات  </a>
              <div class="dropdown-menu " x-placement="bottom-start" >
              
              
              
                <a href="tender_profile_firms_edit.php?s_id=<?php echo $row_tender_submitions['id']; ?>&id=<?php echo $row_tender_info['id']; ?>"  class="dropdown-item"  >تعديل     </a>  
                
                 
                
                
                
                
                
                
                
                
                <form enctype="multipart/form-data"  action="" method="post" onsubmit="return confirm(' هل انت متاكد من ازالة المورد (<?php echo $row_tender_submitions['supplier_name']; ?>) من  <?php echo $row_tender_info['tender_name']; ?> ');"> 
            <input type="hidden" name="delete_tender_sumition" value="<?php echo $row_tender_submitions['id']; ?>">
            <input  type="submit" value="ازالة من القائمة    " class="btn btn-danger    col-md-12 "> 
          </form>
                
                
                
                
              </div>
              </div>
            
            <br />

            
            </div>
        
        
        
        
        
        
        
        
        
        
        
                                    
                                    
                  
        
        
        
        
        
        
        
        
         </div>
        
     
     
       
       </div>                   
                          
                          




       <table width="100%" border="1" dir="rtl" class="table" >
  <tr>
    <td>تاريخ الاستلام : <?php echo $row_tender_submitions['submission_date']; ?> </td>
    <td>تاريخ التسليم : <?php echo $row_tender_submitions['reserved_date']; ?> </td>
    <td>المبلغ : <?php echo $row_tender_submitions['offer_value']; ?> </td>
  </tr>
</table>








<?php if ($row_tender_submitions['notes']!= '' ) {?>


 <b style="text-align:right ; float:right; " > ملاحظات :   
<?php echo $row_tender_submitions['notes']; ?></b>

<br />

<?php } ?> 




<?php if ($row_tender_submitions['file_attach']!= '' ) {?>



<a href="<?php echo $row_tender_submitions['file_attach']; ?>" class="btn btn-success " download  > تحميل الملف المرفق   </a> 


<?php } ?> 

<br />




<b style="color:#F00; font-size:10px" > اضيف بتاريخ : <?php echo $row_tender_submitions['submit_date']; ?>  </b>
</div></div>

  <?php } while ($row_tender_submitions = mysql_fetch_assoc($tender_submitions)); ?>


  <?php } // Show if recordset not empty ?>



                            
                                                
                                                        
                                                          

                                                    </div>
    </div>


</div>

</div>
   
  
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($tender_info);

mysql_free_result($tender_submitions);

mysql_free_result($all_suplaires);


?>

