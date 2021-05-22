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

if ((isset($_POST['delete_tender'])) && ($_POST['delete_tender'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tenders WHERE id=%s",
                       GetSQLValueString($_POST['delete_tender'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
  
    header("location: ".$_SERVER['REQUEST_URI']  ) ; 
	
	
}

$maxRows_tenders = 15;
$pageNum_tenders = 0;
if (isset($_GET['pageNum_tenders'])) {
  $pageNum_tenders = $_GET['pageNum_tenders'];
}
$startRow_tenders = $pageNum_tenders * $maxRows_tenders;

mysql_select_db($database_conn, $conn);
$query_tenders = "SELECT * FROM tenders ORDER BY id DESC";
$query_limit_tenders = sprintf("%s LIMIT %d, %d", $query_tenders, $startRow_tenders, $maxRows_tenders);
$tenders = mysql_query($query_limit_tenders, $conn) or die(mysql_error());
$row_tenders = mysql_fetch_assoc($tenders);

if (isset($_GET['totalRows_tenders'])) {
  $totalRows_tenders = $_GET['totalRows_tenders'];
} else {
  $all_tenders = mysql_query($query_tenders);
  $totalRows_tenders = mysql_num_rows($all_tenders);
}
$totalPages_tenders = ceil($totalRows_tenders/$maxRows_tenders)-1;
 
//


$page['title'] = 'قائمة العطاءات    ';
$page['desc'] = 'تحتوي هذه الصفحة على قائمة بجميع العطاءات التي تم اضافتها الى النظام  ';






 
 include('templeat_header.php');
  ?>
  
  
  
  
  
  
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/all_tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  <form action="" method="post" dir="rtl"> 


<div class="row  "> 


 
<div class="col-md-12 ">


البحث عن عطاء من خلال اسم العطاء او رقم العطاء 

<input name="search_text" onkeyup="search_tender(this.value)" type="search" class="form-control " placeholder="ابحث عن عطاء ... ">
 </div> 


 </div>




</form>
  
  
  
  <div id="search_result" > 
  
  
  
<?php if ($totalRows_tenders > 0) { // Show if recordset not empty ?>
  <table border="1" class="table table-hover ">
    <tr class="bg-primary text-white ">
      
      <td>رقم العطاء </td>
      <td>اسم العطاء </td>
      <td>تاريخ بدء التسليم</td>
      <td>تاريخ انتهاء التسليم </td>
      <td>   </td>
      
      
      <td > 
        
        
        
        
       </td>
      
    </tr>
    <?php do { ?>
      <tr>
        
        <td><?php echo $row_tenders['tender_number']; ?></td>
        <td><?php echo $row_tenders['tender_name']; ?></td>
        <td><?php echo $row_tenders['tender_start_submission']; ?></td>
        <td><?php echo $row_tenders['tender_end_submission']; ?></td>
        
        <td> <a href="tender_profile.php?id=<?php echo $row_tenders['id']; ?>" class="btn btn-primary btn-sm" > صفحة العطاء  </a></td>
        
        
        <td > 
          
          <form action="" method="post" onsubmit="return confirm('هل انت متاكد من حذف العطاء  ؟  ');"> 
            <input type="hidden" name="delete_tender" value="<?php echo $row_tenders['id']; ?>">
            <input type="submit" value="حذف العطاء " class="btn btn-danger btn-sm" style="font-size:10px; "> 
            </form>
          
          
        </td>
        
        
        
      </tr>
      <?php } while ($row_tenders = mysql_fetch_assoc($tenders)); ?>
  </table>
  <?php }else {  // Show if recordset not empty ?>
  
  <br />
<br />

  <h3 align="center" > لم يتم اضافة اي عطاء  </h3>
  
  <?php } ?> 
  
  
  </div>
<br />











</div>

</div>


<script type="text/javascript" > 

function search_tender(search_text ){
	
	
	document.getElementById('search_result').innerHTML = '<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري البحث ... </p></div>' ; 
	
	
	
	
	
	
	 $.post("ajax/search_tender.php",
  {
    search_text: search_text 
  },
  function(data, status){

document.getElementById('search_result').innerHTML = data ; 

  });
	
	
	
	} 



</script>



  <?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($tenders);
?>
