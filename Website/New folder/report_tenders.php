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

mysql_select_db($database_conn, $conn);
$query_all_suppliers = "SELECT id, supplier_name FROM t_suppliers ORDER BY supplier_name ASC";
$all_suppliers = mysql_query($query_all_suppliers, $conn) or die(mysql_error());
$row_all_suppliers = mysql_fetch_assoc($all_suppliers);
$totalRows_all_suppliers = mysql_num_rows($all_suppliers);

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM work_sector_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);

mysql_select_db($database_conn, $conn);
$query_all_projects = "SELECT * FROM dbo_projects";
$all_projects = mysql_query($query_all_projects, $conn) or die(mysql_error());
$row_all_projects = mysql_fetch_assoc($all_projects);
$totalRows_all_projects = mysql_num_rows($all_projects);

error_reporting(0);
 
//


$page['title'] = 'تقارير العطاءات    ';
$page['desc'] = 'يمكنك من خلال هذه الصفحة تصميم التقرير المناسب فيما يخص العطاءات  ';
 
 include('templeat_header.php');
  ?> 
  
  
  <style > 
  
  .card-link{
	  color:#FFF ; 
	  }
  
  
  </style>
  
  
  <div class="row" > 
  <div class="col-md-3" > 
  

  <form >   
  
  <div id="accordion"  class="text-dark" dir="rtl" style="text-align:right ; " >
    <div class="card">
      <div class="card-header bg-primary  text-white">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="card-link" aria-expanded="true">
         حسب الفترة الزمنية 
        </a>
      </div>

      <div id="collapseOne" class="collapse show">
        <div class="card-body">
  
  من تاريخ 
  <input name="from_date" onchange="tender_report(from_date.value , to_date.value , supplier_id.value , tender_main_cat.value , tender_cat.value , status.value , project.value )" type="date" class="form-control" value="<?php echo date("Y");?>-01-01"  /> 
  
  
  الى تاريخ 
   <input onchange="tender_report(from_date.value , to_date.value , supplier_id.value , tender_main_cat.value , tender_cat.value , status.value , project.value )"   type="date" name="to_date" class="form-control" value="<?php echo date("Y-m-d");?>"  /> 
  
  

          </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header bg-primary  text-white ">
        <a class="card-link collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">
حسب المورد       </a>
      </div>
      <div id="collapseTwo" class="collapse" style="">
        <div class="card-body">
        
        
        <select onchange="tender_report(from_date.value , to_date.value , supplier_id.value , tender_main_cat.value , tender_cat.value , status.value , project.value )"  name="supplier_id" class="form-control" >
        <option value="-1" > جميع الموردين  </option>
          <?php
do {  
?>
          <option value="<?php echo $row_all_suppliers['id']?>"><?php echo $row_all_suppliers['supplier_name']?></option>
          <?php
} while ($row_all_suppliers = mysql_fetch_assoc($all_suppliers));
  $rows = mysql_num_rows($all_suppliers);
  if($rows > 0) {
      mysql_data_seek($all_suppliers, 0);
	  $row_all_suppliers = mysql_fetch_assoc($all_suppliers);
  }
?>
        </select>
        
        
        
        </div>
      </div>
    </div>
    
    
    
    
    <div class="card">
      <div class="card-header bg-primary  text-white ">
        <a class="card-link collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false">
        حسب مجال العمل 
        </a>
      </div>
      <div id="collapseThree" class="collapse" style="">
        <div class="card-body">
       
       
<select onchange="tender_report(from_date.value , to_date.value , supplier_id.value , tender_main_cat.value , tender_cat.value , status.value , project.value )"   name="tender_main_cat" class="form-control" required >
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
  
  
  <br />
 

  
  
  <div id="all_sub_cat" >
  
  <select class="form-control" name="tender_cat" > 
  
  
   
  
  
  </select>
  
   </div>




  
       
       
        </div>
      </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    <div class="card">
      <div class="card-header bg-primary  text-white ">
        <a class="card-link collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false">
حسب حالة العطاء         </a>
      </div>
      <div id="collapse5" class="collapse" style="">
        <div class="card-body">



<select onchange="tender_report(from_date.value , to_date.value , supplier_id.value , tender_main_cat.value , tender_cat.value , status.value , project.value )"   name="status" class="form-control" >
  <option value="-1">الكل</option>
  <option value="1">قيد المتابعة</option>
  <option value="2">منتهي</option>
</select>



        </div>
      </div>
    </div>
    
    
    
    <div class="card">
      <div class="card-header bg-primary  text-white ">
        <a class="card-link collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false">
       حسب المشروع 
        </a>
      </div>
      <div id="collapse6" class="collapse" style="">
        <div class="card-body"> 
        
        <select name="project" class="form-control" >
        <option value="-1" >جميع المشاريع </option>
          <?php
do {  
?>
          <option value="<?php echo $row_all_projects['ProjectNo']?>"><?php echo $row_all_projects['ProjectNameAr']?></option>
          <?php
} while ($row_all_projects = mysql_fetch_assoc($all_projects));
  $rows = mysql_num_rows($all_projects);
  if($rows > 0) {
      mysql_data_seek($all_projects, 0);
	  $row_all_projects = mysql_fetch_assoc($all_projects);
  }
?>
        </select>
        
        
        </div>
      </div>
    </div>
    
    
    
    
  </div>
  
  
  
  
    </form>
  
  
  
  
  
  
  
  
  
  
  
  
 

</div> 



  <div class="col-md-9" > 
  
  
<div class="card border-info mb-3"  >
  <div class="card-header">Header</div>
  <div class="card-body text-info" >



<div id="result_view" > 

<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري التحميل ... </p></div>

</div>


   </div>
</div>

</div>

</div> 
<strong></strong>






















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




<script type="text/javascript" > 




function tender_report(from_date , to_date , supplier_id , tender_main_cat , tender_cat , status , project){
	
	
	
		 
	 	document.getElementById('result_view').innerHTML = '<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري التحميل ... </p></div>' ; 
	
	 
	
	 $.post("ajax/tender_report.php",
  {
    from_date: from_date ,
	to_date: to_date ,
	supplier_id: supplier_id ,
	tender_main_cat: tender_main_cat ,
	tender_cat: tender_cat ,
	status: status ,
	project: project ,
	
  },
  function(data, status){

document.getElementById('result_view').innerHTML = data ; 

  });
	




show_sub(tender_main_cat)


	
	}




</script>



<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_suppliers);

mysql_free_result($all_projects);
?>
