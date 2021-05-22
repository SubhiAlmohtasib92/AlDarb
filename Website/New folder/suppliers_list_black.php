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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_all_suppliers = 20;
$pageNum_all_suppliers = 0;
if (isset($_GET['pageNum_all_suppliers'])) {
  $pageNum_all_suppliers = $_GET['pageNum_all_suppliers'];
}
$startRow_all_suppliers = $pageNum_all_suppliers * $maxRows_all_suppliers;

mysql_select_db($database_conn, $conn);
$query_all_suppliers = "SELECT id, supplier_name, supplier_Authorized_signatory, supplier_license, supplier_tel FROM t_suppliers";
$query_limit_all_suppliers = sprintf("%s LIMIT %d, %d", $query_all_suppliers, $startRow_all_suppliers, $maxRows_all_suppliers);
$all_suppliers = mysql_query($query_limit_all_suppliers, $conn) or die(mysql_error());
$row_all_suppliers = mysql_fetch_assoc($all_suppliers);

if (isset($_GET['totalRows_all_suppliers'])) {
  $totalRows_all_suppliers = $_GET['totalRows_all_suppliers'];
} else {
  $all_all_suppliers = mysql_query($query_all_suppliers);
  $totalRows_all_suppliers = mysql_num_rows($all_all_suppliers);
}
$totalPages_all_suppliers = ceil($totalRows_all_suppliers/$maxRows_all_suppliers)-1;$maxRows_all_suppliers = 20;
$pageNum_all_suppliers = 0;
if (isset($_GET['pageNum_all_suppliers'])) {
  $pageNum_all_suppliers = $_GET['pageNum_all_suppliers'];
}
$startRow_all_suppliers = $pageNum_all_suppliers * $maxRows_all_suppliers;

mysql_select_db($database_conn, $conn);
$query_all_suppliers = "SELECT id, supplier_name, supplier_Authorized_signatory, supplier_license, supplier_tel, is_black_list FROM t_suppliers where is_black_list = 1 ";
$query_limit_all_suppliers = sprintf("%s LIMIT %d, %d", $query_all_suppliers, $startRow_all_suppliers, $maxRows_all_suppliers);
$all_suppliers = mysql_query($query_limit_all_suppliers, $conn) or die(mysql_error());
$row_all_suppliers = mysql_fetch_assoc($all_suppliers);

if (isset($_GET['totalRows_all_suppliers'])) {
  $totalRows_all_suppliers = $_GET['totalRows_all_suppliers'];
} else {
  $all_all_suppliers = mysql_query($query_all_suppliers);
  $totalRows_all_suppliers = mysql_num_rows($all_all_suppliers);
}
$totalPages_all_suppliers = ceil($totalRows_all_suppliers/$maxRows_all_suppliers)-1;

mysql_select_db($database_conn, $conn);
$query_supplaier_item = "SELECT * FROM work_sector_item";
$supplaier_item = mysql_query($query_supplaier_item, $conn) or die(mysql_error());
$row_supplaier_item = mysql_fetch_assoc($supplaier_item);
$totalRows_supplaier_item = mysql_num_rows($supplaier_item);

$queryString_all_suppliers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_all_suppliers") == false && 
        stristr($param, "totalRows_all_suppliers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_all_suppliers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_all_suppliers = sprintf("&totalRows_all_suppliers=%d%s", $totalRows_all_suppliers, $queryString_all_suppliers);
 
//


$page['title'] = 'قائمة الموردين    ';
$page['desc'] = 'تحتوي هذه الصفحة على قائمة بجميع الموردين المسجلين والمعتمدين    ';
 
 include('templeat_header.php');
  ?>





     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/all_supplaier_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
  

<form action="" method="post" dir="rtl"  > 


<div class="row" > 


<div class="col-md-4"  > 

<select name=""  class=" multiselect-dropdown form-control   ">
  <option value=""> جميع  مجال الاختصاص  </option>
  <?php
do {  
?>
  <option value="<?php echo $row_supplaier_item['item_id']?>"><?php echo $row_supplaier_item['item_name']?></option>
  <?php
} while ($row_supplaier_item = mysql_fetch_assoc($supplaier_item));
  $rows = mysql_num_rows($supplaier_item);
  if($rows > 0) {
      mysql_data_seek($supplaier_item, 0);
	  $row_supplaier_item = mysql_fetch_assoc($supplaier_item);
  }
?>
</select>

</div> 
<div class="col-md-8" ><input onkeyup="search_suppl()" type="search" class="form-control " placeholder="ابحث عن مورد ... "  />
 </div> 


 </div>




</form>






 
                                                 
                                                     
                                                    
                                                    
                                                    


<div id="search_result" > 





<table border="1" dir="rtl" class="table table-hover table-striped table-bordered dataTable dtr-inline ">
  <tr class="bg-primary text-white ">
     <td>اسم المورد</td>
    <td>الشخص المخول بالتوقيع</td>
    <td>رقم المشتغل المرخص</td>
    <td>رقم الهاتف </td>
    <td width="150" > التفاصيل  </td>
  </tr>
  <?php do { ?>
    <tr class="<?php if ( $row_all_suppliers['is_black_list'] == 1 ) { echo 'bg-dark text-white ';  } ?> " 
    
    >
       <td><?php echo $row_all_suppliers['supplier_name']; ?></td>
      <td><?php echo $row_all_suppliers['supplier_Authorized_signatory']; ?></td>
      <td><?php echo $row_all_suppliers['supplier_license']; ?></td>
      <td><?php echo $row_all_suppliers['supplier_tel']; ?></td>
   
   
   <td >   
      
      <a href="supplier_profile.php?id=<?php echo $row_all_suppliers['id']; ?>" class="btn btn-primary btn-sm" > 
      عرض صفحة المورد 
      
      
      </a> 
     </td>  
      
      
    </tr>
    <?php } while ($row_all_suppliers = mysql_fetch_assoc($all_suppliers)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_all_suppliers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_all_suppliers=%d%s", $currentPage, 0, $queryString_all_suppliers); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_all_suppliers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_all_suppliers=%d%s", $currentPage, max(0, $pageNum_all_suppliers - 1), $queryString_all_suppliers); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_all_suppliers < $totalPages_all_suppliers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_all_suppliers=%d%s", $currentPage, min($totalPages_all_suppliers, $pageNum_all_suppliers + 1), $queryString_all_suppliers); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_all_suppliers < $totalPages_all_suppliers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_all_suppliers=%d%s", $currentPage, $totalPages_all_suppliers, $queryString_all_suppliers); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>




</div>






<script type="text/javascript" > 

function search_suppl(){
	
	
	document.getElementById('search_result').innerHTML = '<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري البحث ... </p></div>' ; 
	
	
	
	
	
	} 



</script>

</div></div></div>

<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_suppliers);

mysql_free_result($supplaier_item);
?>
