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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE dbo_village SET V_Name=%s WHERE V_NO=%s",
                       GetSQLValueString($_POST['V_Name'], "text"),
                       GetSQLValueString($_POST['V_NO'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_village = "SELECT * FROM dbo_village";
$all_village = mysql_query($query_all_village, $conn) or die(mysql_error());
$row_all_village = mysql_fetch_assoc($all_village);
$totalRows_all_village = mysql_num_rows($all_village);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = "village| المناطق     ";
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
    
    
    <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New village| اضافة </button>
    
    
    
    
    </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>
 <table dir="rtl" id="dataTable1"   class="table table-striped table-lightfont dataTable no-footer"  >
<thead class="bg-dark text-white">
      <th> </th>
      <th>V_Name</th>
   </thead>
    <?php do { ?>
      <tr>
        <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          
          <input required="required" type="text" name="V_Name" value="<?php echo htmlentities($row_all_village['V_Name'], ENT_COMPAT, ''); ?>" size="32" />
          
          <input type="submit" value="Update record" />
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="V_NO" value="<?php echo $row_all_village['V_NO']; ?>" />
        </form>
       </td>
        <td><?php echo $row_all_village['V_Name']; ?></td>
      </tr>
      <?php } while ($row_all_village = mysql_fetch_assoc($all_village)); ?>
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
 
mysql_free_result($all_village);
?>
