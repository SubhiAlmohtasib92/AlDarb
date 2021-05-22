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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO users (user_name, user_email, user_mobile_office, user_mobile_internal, user_mobile_personal, user_b_d, address, major, password, additional_info, profile_pic, account_status, user_type, reg_date, gender, Job_title) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['user_email'], "text"),
                       GetSQLValueString($_POST['user_mobile_office'], "text"),
                       GetSQLValueString($_POST['user_mobile_internal'], "text"),
                       GetSQLValueString($_POST['user_mobile_personal'], "text"),
                       GetSQLValueString($_POST['user_b_d'], "date"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['major'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['additional_info'], "text"),
                       GetSQLValueString($_POST['profile_pic'], "text"),
                       GetSQLValueString($_POST['account_status'], "int"),
                       GetSQLValueString($_POST['user_type'], "int"),
                       GetSQLValueString($_POST['reg_date'], "date"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['Job_title'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

 
 

mysql_select_db($database_conn, $conn);
$query_users = "SELECT * FROM users order by profile_pic DESC ";
$users = mysql_query($query_users, $conn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<?php //
include 'templeat_header.php'; ?>







<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/users_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
  

 
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
              <table align="center" class="table">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">الاسم الكامل</td>
                  <td><input required="required" type="text" name="user_name" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">البريد الالكتروني</td>
                  <td><input required="required" type="text" name="user_email" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">رقم هاتف داخلي</td>
                  <td><input type="text" name="user_mobile_office" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">رقم الهاتف - الاتحاد</td>
                  <td><input type="text" name="user_mobile_internal" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">رقم الهاتف الشخصي</td>
                  <td><input type="text" name="user_mobile_personal" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">تاريخ الميلاد</td>
                  <td><input type="text" name="user_b_d" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">العنوان</td>
                  <td><input name="address" type="text" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">التخصص</td>
                  <td><input type="text" name="major" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">كلمة المرور</td>
                  <td><input type="text" name="password" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">معلومات اضافية</td>
                  <td><textarea name="additional_info" cols="32"></textarea></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">مستوى الصلاحية</td>
                  <td><select name="user_type">
                    <option value="1" <?php if (!(strcmp(1, 2))) {echo "SELECTED";} ?>>مسؤول نظام</option>
                    <option value="2" <?php if (!(strcmp(2, 2))) {echo "SELECTED";} ?>>موظف</option>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">الجنس</td>
                  <td><select name="gender">
                    <option value="ذكر" <?php if (!(strcmp("ذكر", ""))) {echo "SELECTED";} ?>>ذكر</option>
                    <option value="انثى" <?php if (!(strcmp("انثى", ""))) {echo "SELECTED";} ?>>انثى</option>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">الوصف الوظيفي</td>
                  <td><input type="text" name="Job_title" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input type="submit" value="اضافة مستخدم جديد " class="btn btn-success" /></td>
                </tr>
              </table>
              <input type="hidden" name="profile_pic" value="images/user.jpg" />
              <input type="hidden" name="account_status" value="1" />
              <input type="hidden" name="reg_date" value="" />
              <input type="hidden" name="MM_insert" value="form1" />
            </form>
           
          </div>
        </div>
      </div>
    
    
    
    
    
<?php include 'templeat_footer.php'; 
mysql_free_result($users);

 
?>
