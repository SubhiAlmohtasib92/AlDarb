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
  $insertSQL = sprintf("INSERT INTO dbo_projects (ProjectNameAr, ProjectNameEn, ProjectStartDate, ProjectEndDate, TotalBudget, MonyType, DonerName, PartnerName, PenificiariesNo, ProjectTypeNo, ProjectEnded, ProjectEndedDate, ManagerNo, SupervisorNo, AccounterNo, HaderNo, ProjectSector, ProjectDetails, CommunicatorNo, DonerPercent, UAWCPercent, FarmerPercent, OthersPercent) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ProjectNameAr'], "text"),
                       GetSQLValueString($_POST['ProjectNameEn'], "text"),
                       GetSQLValueString($_POST['ProjectStartDate'], "text"),
                       GetSQLValueString($_POST['ProjectEndDate'], "text"),
                       GetSQLValueString($_POST['TotalBudget'], "text"),
                       GetSQLValueString($_POST['MonyType'], "text"),
                       GetSQLValueString($_POST['DonerName'], "text"),
                       GetSQLValueString($_POST['PartnerName'], "text"),
                       GetSQLValueString($_POST['PenificiariesNo'], "int"),
                       GetSQLValueString($_POST['ProjectTypeNo'], "int"),
                       GetSQLValueString(0 , "int"),
                       GetSQLValueString('', "text"),
                       GetSQLValueString( $_SESSION['user_id'], "int"),
                       GetSQLValueString('-1', "int"),
                       GetSQLValueString('-1', "int"),
                       GetSQLValueString('-1', "int"),
                       GetSQLValueString($_POST['ProjectSector'], "text"),
                       GetSQLValueString($_POST['ProjectDetails'], "text"),
                       GetSQLValueString($_POST['CommunicatorNo'], "int"),
                       GetSQLValueString($_POST['DonerPercent'], "text"),
                       GetSQLValueString($_POST['UAWCPercent'], "text"),
                       GetSQLValueString($_POST['FarmerPercent'], "text"),
                       GetSQLValueString($_POST['OthersPercent'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  $success = 1 ; 
  
}

mysql_select_db($database_conn, $conn);
$query_currency = "SELECT * FROM dbo_currency";
$currency = mysql_query($query_currency, $conn) or die(mysql_error());
$row_currency = mysql_fetch_assoc($currency);
$totalRows_currency = mysql_num_rows($currency);




mysql_select_db($database_conn, $conn);
$query_users = "SELECT * FROM users";
$users = mysql_query($query_users, $conn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<?php //
include 'templeat_header.php'; ?>









 
 
 
<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/projects_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
  
  
  
  
  

<div class="row" >
  <?php if (isset($success) and $success == 1 ) {
		?>
  <div class="text-dark">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>تم بنجاح!</h4>
    <p>لقد تم اضافة مشروع <b style="color:#FF0000" > <?php echo $_POST['ProjectNameAr'] ; ?> </b> بنجاح .</p>
  </div>
  <?php
		} else { 
    ?>
   
  
                  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                    <table align="center" class="table" >
                      <tr valign="baseline">
                        <td nowrap align="right">اسم المشروع باللغة العربية </td>
                        <td><input  class="form-control"  type="text" name="ProjectNameAr" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">اسم المشروع باللغة الانجليزية:</td>
                        <td><input  class="form-control"  type="text" name="ProjectNameEn" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">تاريخ بداية المشروع:</td>
                        <td><input  data-input="datepicker" data-view="2"  class="form-control"  type="date" name="ProjectStartDate" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">تاريخ انتهاء المشروع:</td>
                        <td><input  data-input="datepicker" data-view="2"   class="form-control"  type="date" name="ProjectEndDate" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">ميزانية المشروع :</td>
                        <td><input  class="form-control"  type="number"  name="TotalBudget" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">عملة المشروع:</td>
                        <td><select name="MonyType"  >
                            <option></option>
                            <?php 
do {  
?>
                            <option value="<?php echo $row_currency['Currency_name']?>" ><?php echo $row_currency['Currency_name']?></option>
                            <?php
} while ($row_currency = mysql_fetch_assoc($currency));
?>
                          </select></td>
                      <tr valign="baseline">
                        <td nowrap align="right">اسم الممول:</td>
                        <td><input  class="form-control"  type="text" name="DonerName" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">اسماء الشركاء </td>
                        <td><textarea name="PartnerName" cols="32" ="" class="form-control"></textarea></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">عدد المستفيدين:</td>
                        <td><input  class="form-control"  type="number" name="PenificiariesNo" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">نوع المشروع:</td>
                        <td><select name="ProjectTypeNo" ="" >
                            <option></option>
                            <?php 
do {  
?>
                            <option value="<?php echo $row_pro_type['ProjectTypeNo']?>" ><?php echo $row_pro_type['ProjectTypeName']?></option>
                            <?php
} while ($row_pro_type = mysql_fetch_assoc($pro_type));
?>
                          </select></td>
                      <tr valign="baseline">
                        <td nowrap align="right">فئة المشروع:</td>
                        <td><input  class="form-control"  type="text" name="ProjectSector" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">تفاصيل حول المشروع:</td>
                        <td><textarea name="ProjectDetails" cols="32" ="" class="form-control"></textarea></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">نسبة الممول:</td>
                        <td><input  class="form-control"  type="text" name="DonerPercent" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">نسبة الاتحاد:</td>
                        <td><input  class="form-control"  type="text" name="UAWCPercent" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">نسبة المزارع:</td>
                        <td><input  class="form-control"  type="text" name="FarmerPercent" value="" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">نسب المساهمات اخرى:</td>
                        <td><textarea name="OthersPercent" cols="32" ="" class="form-control"></textarea></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">&nbsp;</td>
                        <td><input  class="btn btn-primary  "  type="submit" value="اضافة مشروع جديد"></td>
                      </tr>
                    </table>
                    <input  class="form-control"  type="hidden" name="MM_insert" value="form1">
                  </form>
                   
    <?php } ?>
  </div>
  
  
  
  
  
  
  
  
  
  </div> 
  </div> 
  </div> 
  
  <?php include 'templeat_footer.php'; ?>
<?php
mysql_free_result($currency);

mysql_free_result($pro_type);

mysql_free_result($users);
?>
