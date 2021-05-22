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
session_start(); 

  

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

 

mysql_select_db($database_conn, $conn);
$query_city = "SELECT * FROM cities";
$city = mysql_query($query_city, $conn) or die(mysql_error());
$row_city = mysql_fetch_assoc($city);
$totalRows_city = mysql_num_rows($city);

$colname_supplier_info = "-1";
if (isset($_GET['id'])) {
  $colname_supplier_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplier_info = sprintf("SELECT id, supplier_name FROM t_suppliers WHERE id = %s", GetSQLValueString($colname_supplier_info, "text"));
$supplier_info = mysql_query($query_supplier_info, $conn) or die(mysql_error());
$row_supplier_info = mysql_fetch_assoc($supplier_info);
$totalRows_supplier_info = mysql_num_rows($supplier_info);

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM work_sector_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);




$page['title'] = 'تحديد مجالات عمل مورد     ';
$page['desc'] = 'يرجى الضغط على مجالات العمل الخاصة بالمورد ليتم تصنيفه حسب هذه القطاعات  ';



 
 include('templeat_header.php');
  ?>
 
 
 
 <div class="card p-5"  > 
 
 <a href="admin_work_sector_cat.php" class="btn btn-primary " > 
 

اعداد التصنيفات والقطاعات 
 </a> 
 
 
 <br />
<br />
 
 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>



</script>
<script>

function add_item(item_id){
	
	document.getElementById('status').innerHTML = 'جاري التحميل ... ' ; 
	
	
	$.post("ajax/ajax_add_item.php",
    {
         item_id: item_id ,
		action_name : 'add' ,
        s_id: <?php echo $row_supplier_info['id']; ?> 
    },
    function(data, status){
		document.getElementById('status').innerHTML = data ; 
		
		
    });
	
	}
	
	
	
	
function del_item(item_id){
	
	document.getElementById('status').innerHTML = 'جاري التحميل ... ' ; 
	
	
	$.post("ajax/ajax_add_item.php",
    {
        id: item_id ,
		action_name : 'del' ,
        s_id: <?php echo $row_supplier_info['id']; ?> 
    },
    function(data, status){
		document.getElementById('status').innerHTML = data ; 
		
		
    });
	
	}
	
	
	
	
function show_items(){
	
	$.post("ajax/ajax_add_item.php",
    {
        id: '' ,
		action_name : '' ,
        s_id: <?php echo $row_supplier_info['id']; ?> 
    },
    function(data, status){
		document.getElementById('status').innerHTML = data ; 
		
		
    });
	
	} 	
	
	
		 show_items() ; 


	
</script>



 <div   > 



  <h3 align="center" ><a href="supplier_profile.php?id=<?php echo $row_supplier_info['id']; ?>"></a><a href="supplier_profile.php?id=<?php echo $row_supplier_info['id']; ?>"><?php echo $row_supplier_info['supplier_name']; ?></a></h3> 
 
 <hr />



<div class="row" id="top0"  > 


<div class="col-md-7" >



<form action="" method="post" > 

<input onkeyup="serch_sector(this.value)" type="text" class="form-control " placeholder="ابحث عن مجال .. "  /> 


</form>




<div id="serch_result" > </div> 

<br />
<br />


  <table border="1" class="table " align="center">
   
    <?php do { ?>
      <tr class="bg-primary text-white" align="center">
        <td  align="center"><?php echo $row_all_cat['cat_name']; ?></td>
      </tr>
      
           <tr  align="center">
        <td  align="center">
		<?php mysql_select_db($database_conn, $conn);
$query_cat_items = "SELECT * FROM work_sector_item WHERE item_cat = ".$row_all_cat['cat_id'];
$cat_items = mysql_query($query_cat_items, $conn) or die(mysql_error());
$row_cat_items = mysql_fetch_assoc($cat_items);
$totalRows_cat_items = mysql_num_rows($cat_items);

?> 
 <?php do { ?>
    
    <a  onclick="add_item('<?php echo $row_cat_items['item_id']; ?>')" class="btn btn-success btn-xs  text-white  " style="margin:5px; width:45%" >  
	   <?php echo $row_cat_items['item_name']; ?>   
     
     </a> 
    <?php } while ($row_cat_items = mysql_fetch_assoc($cat_items)); ?>

        </td>
      </tr>
      
      
      <?php } while ($row_all_cat = mysql_fetch_assoc($all_cat)); ?>
  </table>
</div>




<div class="col-md-5" >

<div id="status" > </div>

 </div>
 
 
 

 
</div>







</div> 
  
  
  </div>
  
  
  
  
  
  <script > 
  
  
  	
function serch_sector(search_text){
	
	$.post("ajax/serch_sector.php",
    {
        search_text: search_text  
    },
    function(data, status){
		document.getElementById('serch_result').innerHTML = data ; 
		
		
    });
	
	} 	
	
	
	 
  
  </script>
	
	<?php 
  include('templeat_footer.php'); 
 ?>
    <?php
mysql_free_result($city);

mysql_free_result($supplier_info);

mysql_free_result($all_cat);

mysql_free_result($cat_items);
?>
