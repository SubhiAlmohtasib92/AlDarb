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
  $updateSQL = sprintf("UPDATE t_suppliers SET supplier_name=%s, supplier_Authorized_signatory=%s, supplier_license=%s, supplier_tel=%s, supplier_mobile=%s, supplier_fax=%s, supplier_box=%s, supplier_emp_count=%s, supplier_major=%s, experience_years=%s, email=%s, website=%s, main_office_location=%s, city=%s WHERE id=%s",
                       GetSQLValueString($_POST['supplier_name'], "text"),
                       GetSQLValueString($_POST['supplier_Authorized_signatory'], "text"),
                       GetSQLValueString($_POST['supplier_license'], "text"),
                       GetSQLValueString($_POST['supplier_tel'], "text"),
                       GetSQLValueString($_POST['supplier_mobile'], "text"),
                       GetSQLValueString($_POST['supplier_fax'], "text"),
                       GetSQLValueString($_POST['supplier_box'], "text"),
                       GetSQLValueString($_POST['supplier_emp_count'], "text"),
                       GetSQLValueString($_POST['supplier_major'], "text"),
                       GetSQLValueString($_POST['experience_years'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['website'], "text"),
                       GetSQLValueString($_POST['main_office_location'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  $success=1 ; 
}

$colname_supp_info = "-1";
if (isset($_GET['id'])) {
  $colname_supp_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supp_info = sprintf("SELECT * FROM t_suppliers WHERE id = %s", GetSQLValueString($colname_supp_info, "int"));
$supp_info = mysql_query($query_supp_info, $conn) or die(mysql_error());
$row_supp_info = mysql_fetch_assoc($supp_info);
$totalRows_supp_info = mysql_num_rows($supp_info);

mysql_select_db($database_conn, $conn);
$query_citis = "SELECT * FROM cities";
$citis = mysql_query($query_citis, $conn) or die(mysql_error());
$row_citis = mysql_fetch_assoc($citis);
$totalRows_citis = mysql_num_rows($citis);
 
//


$page['title'] = 'تعديل بيانات مورد    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  

  
  <div class="card p-5" > 
  
   <?php if ($success==1) { ?>  
      
 <center>      
      
   <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                            <span class="swal2-success-line-tip"></span>
                                                            <span class="swal2-success-line-long"></span>
                                                            <div class="swal2-success-ring"></div>
                                                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                        </div>
                                                        
                            
                            
           <div class="mb-3 card text-white bg-success">
                             
                                            <div class="card-body" align="center" >لقد تم تعديل بيانات المورد الاساسية بنجاح   </div>
                              
                                        </div>     
                            
                          
                            
                            
   <a href="supplier_profile.php?id=<?php echo $row_supp_info['id']; ?>" class="btn btn-primary " > الانتقال الى صفحة المورد  </a> 
                            
               
               <br />
<br />
<br />
             
                                                        
     </center>                                        
                                                        
            
            
            
            <?php } 
			
			
		?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">اسم المورد</td>
      <td><input type="text" name="supplier_name" value="<?php echo htmlentities($row_supp_info['supplier_name'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">الشخص المخول بالتوقيع:</td>
      <td><input type="text" name="supplier_Authorized_signatory" value="<?php echo htmlentities($row_supp_info['supplier_Authorized_signatory'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">رقم المشتغل المرخص:</td>
      <td><input type="text" name="supplier_license" value="<?php echo htmlentities($row_supp_info['supplier_license'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">هاتف:</td>
      <td><input type="text" name="supplier_tel" value="<?php echo htmlentities($row_supp_info['supplier_tel'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">محمول:</td>
      <td><input type="text" name="supplier_mobile" value="<?php echo htmlentities($row_supp_info['supplier_mobile'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">فاكس:</td>
      <td><input type="text" name="supplier_fax" value="<?php echo htmlentities($row_supp_info['supplier_fax'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">صندوق بريد:</td>
      <td><input type="text" name="supplier_box" value="<?php echo htmlentities($row_supp_info['supplier_box'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">عدد العاملين:</td>
      <td><input type="text" name="supplier_emp_count" value="<?php echo htmlentities($row_supp_info['supplier_emp_count'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">التخصص:</td>
      <td><input type="text" name="supplier_major" value="<?php echo htmlentities($row_supp_info['supplier_major'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">سنوات الخبره:</td>
      <td><input type="text" name="experience_years" value="<?php echo htmlentities($row_supp_info['experience_years'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">البريد الالكتروني:</td>
      <td><input type="text" name="email" value="<?php echo htmlentities($row_supp_info['email'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">الموقع الالكتروني:</td>
      <td><input type="text" name="website" value="<?php echo htmlentities($row_supp_info['website'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">عنوان المكتب الرئيسي:</td>
      <td><input type="text" name="main_office_location" value="<?php echo htmlentities($row_supp_info['main_office_location'], ENT_COMPAT, ''); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">المدينة:</td>
      <td><select name="city">
        <?php 
do {  
?>
        <option value="<?php echo $row_citis['Cities_id']?>" <?php if (!(strcmp($row_citis['Cities_id'], htmlentities($row_supp_info['city'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_citis['Cities_name']?></option>
        <?php
} while ($row_citis = mysql_fetch_assoc($citis));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="  حفظ التعديلات " /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_supp_info['id']; ?>" />
</form>
<p>&nbsp;</p>
<br />


</div>
  <?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($supp_info);

mysql_free_result($citis);
?>
