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
  $updateSQL = sprintf("UPDATE dbo_cat SET CA_Name=%s WHERE CA_NO=%s",
                       GetSQLValueString($_POST['CA_Name'], "text"),
                       GetSQLValueString($_POST['CA_NO'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO dbo_cat (CA_Name) VALUES (%s)",
                       GetSQLValueString($_POST['CA_Name'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_farmer_types = "SELECT * FROM dbo_cat";
$farmer_types = mysql_query($query_farmer_types, $conn) or die(mysql_error());
$row_farmer_types = mysql_fetch_assoc($farmer_types);
$totalRows_farmer_types = mysql_num_rows($farmer_types);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = "   Farmers Type |   انواع المزارعين  ";
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
    
    
    <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New  | اضافة     </button>
    
    
    
    
    </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>
  <table border="1" class="table">
    <tr class="bg-dark text-white ">
      <td> </td>
      <td>CA_Name</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      
      
          <input type="text" name="CA_Name" value="<?php echo htmlentities($row_farmer_types['CA_Name'], ENT_COMPAT, ''); ?>" size="32" />
          
          
          <input type="submit" value="Update " />
          
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="CA_NO" value="<?php echo $row_farmer_types['CA_NO']; ?>" />
        </form>
        
        
        
           </td>
        <td><?php echo $row_farmer_types['CA_Name']; ?></td>
      </tr>
      <?php } while ($row_farmer_types = mysql_fetch_assoc($farmer_types)); ?>
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

اضافة نوع جديد من المزارعين 

          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
            <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
              <table align="center" class="table">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">CA_Name:</td>
                  <td><input class="form-control" required="required"  type="text" name="CA_Name" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input type="submit" value="Insert record" /></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form2" />
            </form>
            <p>&nbsp;</p>
          </div>
    </div>
  </div>
</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($farmer_types);
?>
