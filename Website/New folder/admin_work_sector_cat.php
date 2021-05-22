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
  $insertSQL = sprintf("INSERT INTO work_sector_cat (cat_name) VALUES (%s)",
                       GetSQLValueString($_POST['cat_name'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO work_sector_item (item_name, item_cat) VALUES (%s, %s)",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_cat'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  header("location: admin_work_sector_cat.php");
}

mysql_select_db($database_conn, $conn);
$query_all_man_cat = "SELECT * FROM work_sector_cat";
$all_man_cat = mysql_query($query_all_man_cat, $conn) or die(mysql_error());
$row_all_man_cat = mysql_fetch_assoc($all_man_cat);
$totalRows_all_man_cat = mysql_num_rows($all_man_cat);


 
//


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?> 
  
  
  


     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/all_supplaier_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  

<div class="row card  " > 



<!--<div class="col-md-4" >



<h3 align="center" > اضافة فئة رئيسية   </h3>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  
  
  <br />

  <input class="form-control" required="required"  type="text" name="cat_name" value="" size="32" />
  
  
  
  <br />
<input type="submit" class="btn btn-primary " value="  اضافة جديد " />
   



    <input type="hidden" name="MM_insert" value="form1" />
  </form>
 
</div> -->
<div class="col-md-12" >
  <table border="1" class="table">
 
    <?php do { ?>
      <tr class="bg-primary text-white">
         <td><?php echo $row_all_man_cat['cat_name'];
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 $colname_sector_items = "-1";
if (isset($row_all_man_cat['cat_id'])) {
  $colname_sector_items = $row_all_man_cat['cat_id'];
}
mysql_select_db($database_conn, $conn);
$query_sector_items = sprintf("SELECT * FROM work_sector_item WHERE item_cat = %s", GetSQLValueString($colname_sector_items, "int"));
$sector_items = mysql_query($query_sector_items, $conn) or die(mysql_error());
$row_sector_items = mysql_fetch_assoc($sector_items);
$totalRows_sector_items = mysql_num_rows($sector_items);



 ?></td>
      </tr>
      
      
       <tr>
         <td>
         
         
          
         <p>
  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample<?php echo $row_all_man_cat['cat_id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $row_all_man_cat['cat_id']; ?>">
 اضافة جديد    </a>
   
</p>
<div class="collapse" id="collapseExample<?php echo $row_all_man_cat['cat_id']; ?>">
  <div class="card card-body">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
    
    
    
   
   <input class="form-control " required="required"  type="text" name="item_name" value="" size="32" />
   
   <input type="submit" value="اضافة جديد " />
   
      <input type="hidden" name="item_cat" value="<?php echo $row_all_man_cat['cat_id']; ?>" />
      <input type="hidden" name="MM_insert" value="form2" />
    </form>


  </div>
</div>


         
         
         <hr />
 
         
           <?php do { ?>
		 
         
         <a href="admin_work_sector_cat_edit.php?item_id=<?php echo $row_sector_items['item_id']; ?>" class="btn btn-success text-white m-1 btn-sm "  >
         <?php echo $row_sector_items['item_name']; ?>
         
         
         </a> 
         
             <?php } while ($row_sector_items = mysql_fetch_assoc($sector_items)); ?>
         </td>
      </tr>
      
      
      <?php } while ($row_all_man_cat = mysql_fetch_assoc($all_man_cat)); ?>
  </table>
</div>




</div></div></div>

</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_man_cat);

mysql_free_result($sector_items);
?>
