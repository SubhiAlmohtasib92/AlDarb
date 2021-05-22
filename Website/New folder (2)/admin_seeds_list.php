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
  $insertSQL = sprintf("INSERT INTO dbo_items (arabic_name, family_no, I_Name, I_Cat_No, Notes, genus, spe) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['arabic_name'], "text"),
                       GetSQLValueString($_POST['family_no'], "int"),
                       GetSQLValueString($_POST['I_Name'], "text"),
                       GetSQLValueString($_POST['I_Cat_No'], "int"),
                       GetSQLValueString($_POST['Notes'], "text"),
                       GetSQLValueString($_POST['genus'], "text"),
                       GetSQLValueString($_POST['spe'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_seeds = "SELECT * FROM dbo_items";
$all_seeds = mysql_query($query_all_seeds, $conn) or die(mysql_error());
$row_all_seeds = mysql_fetch_assoc($all_seeds);
$totalRows_all_seeds = mysql_num_rows($all_seeds);

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM dbo_item_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);

mysql_select_db($database_conn, $conn);
$query_all_family = "SELECT * FROM dbo_family_item";
$all_family = mysql_query($query_all_family, $conn) or die(mysql_error());
$row_all_family = mysql_fetch_assoc($all_family);
$totalRows_all_family = mysql_num_rows($all_family);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'Seeds List | قائمة البذور    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>

  
  
  
  
  <div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
  
  
  <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New Seed | اضافة صنف بذور جديد</button>
  
  
  
   
  </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>
  
  
  
  
  
  
  
  
  
  <div class=" " > 
   
   
  
  
  
  <div class="element-box el-tablo" > 

<table dir="rtl" id="dataTable1"  border="1" class="table table-striped table-lightfont dataTable">
<thead class="bg-dark text-white">
   
    <th>I_NO</th>
    <th>arabic_name</th>
    <th>family_no</th>
    <th>I_Name</th>
    <th>I_Cat_No</th>
    <th>Notes</th>
    <th>genus</th>
    <th>spe</th>
</thead>
  <?php do { ?>
    <tr>
      <td><?php echo $row_all_seeds['I_NO']; ?></td>
      <td><?php echo $row_all_seeds['arabic_name']; ?></td>
      <td><?php echo $row_all_seeds['family_no']; ?></td>
      <td><?php echo $row_all_seeds['I_Name']; ?></td>
      <td><?php echo $row_all_seeds['I_Cat_No']; ?></td>
      <td><?php echo $row_all_seeds['Notes']; ?></td>
      <td><?php echo $row_all_seeds['genus']; ?></td>
      <td><?php echo $row_all_seeds['spe']; ?></td>
    </tr>
    <?php } while ($row_all_seeds = mysql_fetch_assoc($all_seeds)); ?>
</table>

</div></div>


</div>
</div>

</div>









<div class="onboarding-modal modal fade animated" id="onboardingWideTextModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">  Close </span></button>
      <div class="onboarding-side-by-side">
       <!-- <div class="onboarding-media"><img alt="" src="img/bigicon2.png" width="100px"></div> -->
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">
          New Seed | اضافة صنف بذور جديد
          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
          <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <table align="center" class="table">
            
               <tr valign="baseline">
                <td nowrap="nowrap" align="right">I_Name:</td>
                <td><input class="form-control" required="required"  type="text" name="I_Name" value="" size="32" /></td>
              </tr>
              
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Arabic_name:</td>
                <td><input  class="form-control" required="required"  type="text" name="arabic_name" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Family_no:</td>
                <td><select  name="family_no">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_all_family['family_NO']?>" ><?php echo $row_all_family['family_name']?></option>
                  <?php
} while ($row_all_family = mysql_fetch_assoc($all_family));
?>
                </select></td>
              </tr>
         
           
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">I_Cat_No:</td>
                <td><select name="I_Cat_No">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_all_cat['i_cat_no']?>" ><?php echo $row_all_cat['i_name']?></option>
                  <?php
} while ($row_all_cat = mysql_fetch_assoc($all_cat));
?>
                </select></td>
              </tr>
              <tr> </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Notes:</td>
                <td><textarea  class="form-control" required="required"   name="Notes" cols="32"></textarea></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Genus:</td>
                <td><input  class="form-control" required="required"  type="text" name="genus" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Spe:</td>
                <td><input  class="form-control" required="required"   type="text" name="spe" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input type="submit" value="Insert record" /></td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
          <p>&nbsp;</p>
        
      </div>
    </div>
  </div>
</div>
</div>






<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_seeds);

mysql_free_result($all_cat);

mysql_free_result($all_family);
?>
