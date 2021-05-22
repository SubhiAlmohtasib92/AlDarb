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
$query_all_dest = "SELECT dbo_dist_main.rec_no, dbo_collect.F_ID, dbo_farmer_inst.F_Name, dbo_dist_main.farmer_no, dbo_dist_main.`date`, dbo_dist_main.amount, dbo_dist_main.area, dbo_dist_main.amount_to_back, dbo_dist_main.notes, dbo_items.arabic_name FROM dbo_dist_main, dbo_collect, dbo_farmer_inst, dbo_items WHERE dbo_dist_main.c_no =dbo_collect.C_NO AND dbo_collect.F_ID = dbo_farmer_inst.F_ID  AND dbo_collect.I_NO = dbo_items.I_NO";
$all_dest = mysql_query($query_all_dest, $conn) or die(mysql_error());
$row_all_dest = mysql_fetch_assoc($all_dest);
$totalRows_all_dest = mysql_num_rows($all_dest);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = " Seeds family's | عائلات البذور  ";
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
    
    
    <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New Seeds family's | اضافة عائلات البذور </button>
    
    
    
    
    </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>
  <table border="1" class="table">
    <tr>
       <td>Comming from farmer</td>
      <td>to farmer</td>
       <td>date</td>
      <td>amount</td>
      <td>area</td>
      <td>amount_to_back</td>
      <td>notes</td>
      <td>item</td>
    </tr>
    <?php do { ?>
      <tr>
         <td><?php echo $row_all_dest['F_Name']; ?></td>
        <td><?php echo $row_all_dest['farmer_no']; ?></td>
         <td><?php echo $row_all_dest['date']; ?></td>
        <td><?php echo $row_all_dest['amount']; ?></td>
        <td><?php echo $row_all_dest['area']; ?></td>
        <td><?php echo $row_all_dest['amount_to_back']; ?></td>
        <td><?php echo $row_all_dest['notes']; ?></td>
        <td><?php echo $row_all_dest['arabic_name']; ?></td>
      </tr>
      <?php } while ($row_all_dest = mysql_fetch_assoc($all_dest)); ?>
  </table>
</div></div></div>






<div class="onboarding-modal modal fade animated" id="onboardingWideTextModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">  Close </span></button>
      <div class="onboarding-side-by-side">
       <!-- <div class="onboarding-media"><img alt="" src="img/bigicon2.png" width="100px"></div> -->
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">



          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
     
     
     
     
     
     
      </div>
    </div>
  </div>
</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_dest);
?>
