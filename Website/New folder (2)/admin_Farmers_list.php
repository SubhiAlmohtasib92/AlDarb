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
  $insertSQL = sprintf("INSERT INTO dbo_farmer_inst (F_ID, F_Name, F_Tel, F_site, F_vill_No, F_type, Land_area, Notes, birth_date, farmer_work, family_count, insert_by, insert_date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW())",
                       GetSQLValueString($_POST['F_ID'], "text"),
                       GetSQLValueString($_POST['F_Name'], "text"),
                       GetSQLValueString($_POST['F_Tel'], "text"),
                       GetSQLValueString($_POST['F_site'], "text"),
                       GetSQLValueString($_POST['F_vill_No'], "int"),
                       GetSQLValueString($_POST['F_type'], "int"),
                       GetSQLValueString($_POST['Land_area'], "int"),
                       GetSQLValueString($_POST['Notes'], "text"),
                       GetSQLValueString($_POST['birth_date'], "date"),
                       GetSQLValueString($_POST['farmer_work'], "text"),
                       GetSQLValueString($_POST['family_count'], "int"),
                       GetSQLValueString($_POST['insert_by'].'0', "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_farmers = "SELECT dbo_farmer_inst.F_NO, dbo_farmer_inst.F_ID, dbo_farmer_inst.F_Name, dbo_farmer_inst.F_Tel, dbo_farmer_inst.F_site, dbo_village.V_Name, dbo_farmer_inst.Land_area, dbo_farmer_inst.Notes, dbo_farmer_inst.birth_date, dbo_farmer_inst.farmer_work, dbo_farmer_inst.family_count FROM dbo_farmer_inst, dbo_village WHERE dbo_farmer_inst.F_vill_No = dbo_village.V_NO ";
$all_farmers = mysql_query($query_all_farmers, $conn) or die(mysql_error());
$row_all_farmers = mysql_fetch_assoc($all_farmers);
$totalRows_all_farmers = mysql_num_rows($all_farmers);

mysql_select_db($database_conn, $conn);
$query_all_vilige = "SELECT * FROM dbo_village";
$all_vilige = mysql_query($query_all_vilige, $conn) or die(mysql_error());
$row_all_vilige = mysql_fetch_assoc($all_vilige);
$totalRows_all_vilige = mysql_num_rows($all_vilige);

mysql_select_db($database_conn, $conn);
$query_all_cat = "SELECT * FROM dbo_cat";
$all_cat = mysql_query($query_all_cat, $conn) or die(mysql_error());
$row_all_cat = mysql_fetch_assoc($all_cat);
$totalRows_all_cat = mysql_num_rows($all_cat);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = " Farmers List | قائمة المزارعين ";
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <style>
  .select2-dropdown{
	  min-width:200px !important ;
	  background-color:#FC3; }
  
  
 </style>
  
  
<div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
    
    
    <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New Farmer| اضافة مزارع جديد  </button>
    
    
    
    
    </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>
  <div class="element-box el-tablo" > 
  
  
<table dir="rtl" id="dataTable1"  border="1" class="table table-striped table-lightfont dataTable">
<thead class="bg-dark text-white">
      <th>F_ID | رقم الهوية</th>
      <th>F_Name | اسم المزارع</th>
      <th>F_Tel</th>
      <th>F_site</th>
      <th>V_Name</th>
 
  
   </thead>
    <?php do { ?>
      <tr>
        <td><?php echo $row_all_farmers['F_ID']; ?></td>
        <td><?php echo $row_all_farmers['F_Name']; ?></td>
        <td><?php echo $row_all_farmers['F_Tel']; ?></td>
        <td><?php echo $row_all_farmers['F_site']; ?></td>
        <td><?php echo $row_all_farmers['V_Name']; ?></td>

         
      </tr>
  
      
      
      
      <?php } while ($row_all_farmers = mysql_fetch_assoc($all_farmers)); ?>
  </table>
</div></div></div>


</div>



<div class="onboarding-modal modal fade animated" id="onboardingWideTextModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">  Close </span></button>
      <div class="onboarding-side-by-side">
       <!-- <div class="onboarding-media"><img alt="" src="img/bigicon2.png" width="100px"></div> -->
        <div class="  with-gradient">
          <h4 class="onboarding-title p-3" align="center">

اضافة مزارع 

          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
              <table align="center" class="table">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Farmer ID | رقم الهوية:</td>
                  <td><input class="form-control" required="required"  type="text" name="F_ID" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Farmer Name | الاسم الكامل:</td>
                  <td><input  class="form-control" required="required"  type="text" name="F_Name" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">F_Tel | رقم الهاتف:</td>
                  <td><input  class="form-control" required="required"  type="text" name="F_Tel" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Farmer Adrress | العنوان</td>
                  <td><input  class="form-control" required="required"  type="text" name="F_site" value="" size="32" /></td>
                </tr>
                
                
                
                
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">F_vill_No:</td>
                  <td><select style="min-width:200px !important ; " name="F_vill_No" class="select1 form-control">
                    <?php 
do {  
?>
                    <option value="<?php echo $row_all_vilige['V_NO']?>" ><?php echo $row_all_vilige['V_Name']?></option>
                    <?php
} while ($row_all_vilige = mysql_fetch_assoc($all_vilige));
?>
                  </select></td>
                </tr>
                
                
                
                
                
                
                
                <tr> </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Farmer Type:</td>
                  <td><select class="form-control  " name="F_type">
                    <?php 
do {  
?>
                    <option value="<?php echo $row_all_cat['CA_NO']?>" ><?php echo $row_all_cat['CA_Name']?></option>
                    <?php
} while ($row_all_cat = mysql_fetch_assoc($all_cat));
?>
                  </select></td>
                </tr>
                <tr> </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Land_area:</td>
                  <td><input  class="form-control" required="required"  type="number" name="Land_area" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right" valign="top">Notes:</td>
                  <td><textarea  class="form-control" required="required"  name="Notes" cols="50" rows="5"></textarea></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Birth_date:</td>
                  <td><input  class="form-control" required="required"  type="date" name="birth_date" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Farmer_work:</td>
                  <td><input  class="form-control" required="required"  type="text" name="farmer_work" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Family_count:</td>
                  <td><input  class="form-control" required="required"  type="text" name="family_count" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input type="submit" value="Insert " /></td>
                </tr>
              </table>
              <input type="hidden" name="insert_by" value="" />
              <input type="hidden" name="insert_date" value="" />
              <input type="hidden" name="MM_insert" value="form1" />
            </form>
            <p>&nbsp;</p>
          </div>
    </div>
  </div>
</div>







<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_farmers);

mysql_free_result($all_vilige);

mysql_free_result($all_cat);
?>
<script > 



$(document).ready(function() {
    $('.select1').select2();
});


 

</script>