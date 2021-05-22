<?php require_once('Connections/conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
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

$MM_restrictGoTo = "login.php?info=access";
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

$colname_my_projects = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_my_projects = $_SESSION['user_id'];
}
mysql_select_db($database_conn, $conn);
$query_my_projects = sprintf("SELECT * FROM dbo_projects order by ProjectEnded     ", GetSQLValueString($colname_my_projects, "int"));
$my_projects = mysql_query($query_my_projects, $conn) or die(mysql_error());
$row_my_projects = mysql_fetch_assoc($my_projects);
$totalRows_my_projects = mysql_num_rows($my_projects);
 // 
 
 
 $page['title'] = ' مشاريع الاتحاد   ';
$page['desc'] = 'فيما يلي قائمة بجميع المشاريع المضافة الى قاعدة بيانات الاتحاد 
  ';
 
 
  
   
 include 'templeat_header.php';  

 ?> 

 
 
 
 
 
 
<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/projects_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
  
  

 
<h3 align="right" ></h3>
 

<table border="1" class=" table " dir="rtl" >
<thead>
  <tr class="bg-primary text-white ">
     <th>اسم المشروع بالعربي </th>
    <th>Project Name</th>
    <th>تاريخ بداية</th>
    <th>تاريخ النهاية</th>
    
     
    <th>الممول</th>
     
    
  </tr>
 
    
  <?php do { ?>
    <tr>
    
      <td  <?php if( $row_my_projects['ProjectEnded']==1) {echo 'class="bg-danger"';}   ?>><a style="color:#000000" href="user_pro_page.php?id=<?php echo $row_my_projects['ProjectNo']; ?>" > <?php echo $row_my_projects['ProjectNameAr']; ?></a></td>
    
      <td><a href="user_pro_page.php?id=<?php echo $row_my_projects['ProjectNo']; ?>" > <?php echo $row_my_projects['ProjectNameEn']; ?></a></td>
      <td><?php echo $row_my_projects['ProjectStartDate']; ?></td>
      <td><?php echo $row_my_projects['ProjectEndDate']; ?></td>
        <td><?php echo $row_my_projects['DonerName']; ?></td>
        
    </tr>
    <?php } while ($row_my_projects = mysql_fetch_assoc($my_projects)); ?>
    </tbody>
</table>



</div> 
</div> 
</div> 
<?php    include 'templeat_footer.php';

 
 
mysql_free_result($my_projects);
?>
