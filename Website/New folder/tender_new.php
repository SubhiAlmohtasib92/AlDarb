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
  $insertSQL = sprintf("INSERT INTO tenders (tender_number, tender_name, tender_start_submission, tender_end_submission, tender_open_date, tender_eval_date, tender_status, tender_cat, tender_price, tender_project, tender_vat, adv_cost, Warranty, Implementation_period, TOR, insert_date, insert_user, note, attatch_file) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW() , %s, %s, %s)",
                       GetSQLValueString($_POST['tender_number'], "text"),
                       GetSQLValueString($_POST['tender_name'], "text"),
                       GetSQLValueString($_POST['tender_start_submission'], "date"),
                       GetSQLValueString($_POST['tender_end_submission'], "date"),
                       GetSQLValueString($_POST['tender_open_date'], "date"),
                       GetSQLValueString($_POST['tender_eval_date'], "date"),
                       GetSQLValueString(1 , "int"),
                       GetSQLValueString($_POST['tender_cat'], "int"),
                       GetSQLValueString($_POST['tender_price'], "double"),
                       GetSQLValueString($_POST['tender_project'], "int"),
                       GetSQLValueString(isset($_POST['tender_vat']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['adv_cost'], "double"),
                       GetSQLValueString($_POST['Warranty'], "int"),
                       GetSQLValueString($_POST['Implementation_period'], "text"),
                       GetSQLValueString($_POST['TOR'], "text"),
              
                       GetSQLValueString($_SESSION['user_id'], "int"),
                       GetSQLValueString($_POST['note'], "text"),
                       GetSQLValueString($_POST['attatch_file'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  header("location: tender_new.php?success=1");
}

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM work_sector_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);

mysql_select_db($database_conn, $conn);
$query_last_tender = "SELECT id FROM tenders ORDER BY id DESC limit 1 ";
$last_tender = mysql_query($query_last_tender, $conn) or die(mysql_error());
$row_last_tender = mysql_fetch_assoc($last_tender);
$totalRows_last_tender = mysql_num_rows($last_tender);

mysql_select_db($database_conn, $conn);
$query_uawc_projects = "SELECT ProjectNo, ProjectNameAr, ProjectNameEn FROM dbo_projects";
$uawc_projects = mysql_query($query_uawc_projects, $conn) or die(mysql_error());
$row_uawc_projects = mysql_fetch_assoc($uawc_projects);
$totalRows_uawc_projects = mysql_num_rows($uawc_projects);
 
//


$page['title'] = 'اضافة عطاء جديد ';
$page['desc'] = 'يمكنك من خلال هذه الصفحة اضافة وتعريف عطاء جديد  ';
 
 include('templeat_header.php');
  ?>
  
  
  

  <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
 
  
  
  
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/all_tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
  
   
      
     <?php if ($_GET['success']==1) { ?>  
      
 <center>      
      
   <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                            <span class="swal2-success-line-tip"></span>
                                                            <span class="swal2-success-line-long"></span>
                                                            <div class="swal2-success-ring"></div>
                                                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                        </div>
                                                        
                            
                            
           <div class="mb-3 card text-white bg-success">
                             
                                            <div class="card-body" align="center" >لقد تم اضافة بيانات العطاء الاساسية بنجاح :: يمكنك الانتقال الى صفحة العطاء  </div>
                              
                                        </div>     
                            
                          
                            
                            
   <a href="tender_profile.php?id=<?php echo $row_last_tender['id']; ?>" class="btn btn-primary " > الانتقال الى صفحة العطاء   </a> 
                            
                            
                                                        
     </center>                                        
                                                        
            
            
            
            <?php } else { ?> 
            
            
            
            
            
  
  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl">
  <div class="row" style="text-align:right" > 
  
  <div class="col-md-3 mt-2 " > 
  رقم العطاء
  <input required="required" class="form-control" type="text" name="tender_number" value="" size="32" />
  </div>
  <div class="col-md-3 mt-2 " > 
  اسم العطاء
  <input required="required" class="form-control" type="text" name="tender_name" value="" size="32" />
  </div>
  <div class="col-md-3 mt-2 " > 
  تاريخ بداية التسليم
  <input class="form-control" required="required"  type="date" name="tender_start_submission" value="" size="32" />
  </div>
  <div class="col-md-3 mt-2 " > 
  تاريخ انتهاء التسليم
  <input required="required" class="form-control" type="date" name="tender_end_submission" value="" size="32" />
  </div>
  
  
  
  
  
  
  
  
  
  
  
  <div class="col-md-3 mt-2 " > 
  تصنيف العطاء
  
  (<a href="admin_work_sector_cat.php" style="color:#F00; " >ادارة التصنيفات</a>)
  
  
  
  
  
  
  
  
  
  <select onchange="show_sub(this.value)" name="" class="form-control" required >
    <option value=""></option>
    <?php
do {  
?>
    <option value="<?php echo $row_all_cat['cat_id']?>"><?php echo $row_all_cat['cat_name']?></option>
    <?php
} while ($row_all_cat = mysql_fetch_assoc($all_cat));
  $rows = mysql_num_rows($all_cat);
  if($rows > 0) {
      mysql_data_seek($all_cat, 0);
	  $row_all_cat = mysql_fetch_assoc($all_cat);
  }
?>
  </select>
  </div>
  
  
  
  
  
  
  
    <div class="col-md-3 mt-2 "  > 

التصنيف الفرعي 


<div id="all_sub_cat" > </div>

</div>
  
  
  
  
  
  
  <div class="col-md-3 mt-2 " > 
  سعر العطاء
  <input required="required" class="form-control" type="text" name="tender_price" value="0" size="32" />
  </div>
  <div class="col-md-3 mt-2 " > 
  المشروع
  
  
  <a href="admin_projects.php" style=" color:#FF0000;" > ( ادارة المشاريع  )  </a>
  
  <select name="tender_project" class="form-control multiselect-dropdown " required >
  <option></option>
    <?php
do {  
?>
    <option value="<?php echo $row_uawc_projects['ProjectNo']?>"><?php echo $row_uawc_projects['ProjectNameAr']?></option>
    <?php
} while ($row_uawc_projects = mysql_fetch_assoc($uawc_projects));
  $rows = mysql_num_rows($uawc_projects);
  if($rows > 0) {
      mysql_data_seek($uawc_projects, 0);
	  $row_uawc_projects = mysql_fetch_assoc($uawc_projects);
  }
?>
  </select>
  </div>
  <div class="col-md-3 mt-2 " > 
  هل العطاء صفري ؟ 
   <select name="tender_vat" class="form-control">
        <option value="0" >غير معفي</option>
        <option value="1" >معفي</option>
      </select>
 </div>
  <div class="col-md-3 mt-2 " > 
  تكاليف الاعلان بالجريده
  <input class="form-control" required="required"  type="text" name="adv_cost" value="0" size="32" />
  </div>
  <div class="col-md-3 mt-2 " > 
  الكفاله
  
  <input required="required" class="form-control" type="text" name="Warranty" value="0" size="32" />
  
  
  </div>
  <div class="col-md-3 mt-2 " > 
  فتره التنفيذ
  <input class="form-control" type="text" name="Implementation_period" value="" size="32" />
  </div>
  <div class="col-md-12" > 
  <br />

  الخطوط المرجعية / المواصفات الفنية 
    <textarea id="summernote" name="TOR" cols="32" required="required" class="form-control"> </textarea>
  </div>
  <div class="col-md-12 mt-2 " > 
  ملاحظات 
  <textarea    name="note" cols="50" rows="2" class="form-control" ></textarea>
  </div>
  <div class="col-md-3 mt-2 " > 
  
  </div>
  
  
  </div>
  <br />  
       ارفاق ملف 
  
 <input type="file" name="attatch_file" value="" class="form-control"  size="32" />
 
 
 <br />

      
    

 <input type="submit" value="اضافة عطاء جديد   " class="btn btn-primary " /> 
  <input type="hidden" name="tender_status" value="" />
  <input type="hidden" name="insert_date" value="" />
  <input type="hidden" name="insert_user" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>



<?php } ?> 




</div></div></div>

<?php 
//
   include('templeat_footer.php'); 
 
mysql_free_result($all_cat);

mysql_free_result($last_tender);

mysql_free_result($uawc_projects);
?>


  
  
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
  
  
 <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
  
  


 <script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>
  

<script > 

function show_sub(main_cat){
 
	
	
	
	document.getElementById('all_sub_cat').innerHTML = '<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري البحث ... </p></div>' ; 



	 $.post("ajax/get_sub_cat.php",
  {
    main_cat: main_cat 
  },
  function(data, status){

document.getElementById('all_sub_cat').innerHTML = data ; 

  });
	

	
	
	
	
	
	}

</script>  
  