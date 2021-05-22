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
  $updateSQL = sprintf("UPDATE tenders SET tender_number=%s, tender_name=%s, tender_start_submission=%s, tender_end_submission=%s, tender_cat=%s, tender_price=%s, tender_project=%s, tender_vat=%s, adv_cost=%s, Warranty=%s, Implementation_period=%s, TOR=%s, note=%s WHERE id=%s",
                       GetSQLValueString($_POST['tender_number'], "text"),
                       GetSQLValueString($_POST['tender_name'], "text"),
                       GetSQLValueString($_POST['tender_start_submission'], "date"),
                       GetSQLValueString($_POST['tender_end_submission'], "date"),
                       GetSQLValueString($_POST['tender_cat'], "int"),
                       GetSQLValueString($_POST['tender_price'], "double"),
                       GetSQLValueString($_POST['tender_project'], "int"),
                       GetSQLValueString($_POST['tender_vat'], "int"),
                       GetSQLValueString($_POST['adv_cost'], "double"),
                       GetSQLValueString($_POST['Warranty'], "int"),
                       GetSQLValueString($_POST['Implementation_period'], "text"),
                       GetSQLValueString($_POST['TOR'], "text"),
                       GetSQLValueString($_POST['note'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_tender_info = "-1";
if (isset($_GET['id'])) {
  $colname_tender_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_info = sprintf("SELECT * FROM tenders WHERE id = %s", GetSQLValueString($colname_tender_info, "int"));
$tender_info = mysql_query($query_tender_info, $conn) or die(mysql_error());
$row_tender_info = mysql_fetch_assoc($tender_info);
$totalRows_tender_info = mysql_num_rows($tender_info);
 
//


$colname_tender_submitions = "-1";
if (isset($_GET['id'])) {
  $colname_tender_submitions = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_submitions = sprintf("SELECT tender_submissions.id, t_suppliers.supplier_name, tender_submissions.submit_date, tender_submissions.file_attach, tender_submissions.notes FROM tender_submissions, t_suppliers WHERE tender_id = %s AND tender_submissions.for_supplier = t_suppliers.id", GetSQLValueString($colname_tender_submitions, "int"));
$tender_submitions = mysql_query($query_tender_submitions, $conn) or die(mysql_error());
$row_tender_submitions = mysql_fetch_assoc($tender_submitions);
$totalRows_tender_submitions = mysql_num_rows($tender_submitions);

mysql_select_db($database_conn, $conn);
$query_tender_sectors = "SELECT work_sector_item.item_id, work_sector_item.item_name, work_sector_cat.cat_name FROM work_sector_item, work_sector_cat WHERE work_sector_item.item_cat = work_sector_cat.cat_id";
$tender_sectors = mysql_query($query_tender_sectors, $conn) or die(mysql_error());
$row_tender_sectors = mysql_fetch_assoc($tender_sectors);
$totalRows_tender_sectors = mysql_num_rows($tender_sectors);

mysql_select_db($database_conn, $conn);
$query_all_projects = "SELECT dbo_projects.ProjectNo, dbo_projects.ProjectNameAr, dbo_projects.ProjectNameEn FROM dbo_projects";
$all_projects = mysql_query($query_all_projects, $conn) or die(mysql_error());
$row_all_projects = mysql_fetch_assoc($all_projects);
$totalRows_all_projects = mysql_num_rows($all_projects);
 
 
 


$page['title'] = 'صفحة عطاء    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
  
  
  <div class="row  ">
  
  
  
  
  
  
  <div class="col-md-4  ">
                                                <div class="main-card mb-3 card shadow p-3 mb-5 text-dark bg-white rounded  ">
                                                    <div class="card-body   ">
                            
                            
                  <h4 class="" align="center">  <?php echo $row_tender_info['tender_name']; ?></h4>          
                            
                            <table class="table" width="100%" border="1" dir="rtl" >
  <tr>
    <td>رقم العطاء</td>
    <td><?php echo $row_tender_info['tender_number']; ?></td>
  </tr>
   
  <tr>
    <td>تاريخ بدء التسليم</td>
    <td><?php echo $row_tender_info['tender_start_submission']; ?></td>
  </tr>
  <tr>
    <td>تاريخ انتهاء التسليم</td>
    <td><?php echo $row_tender_info['tender_end_submission']; ?></td>
  </tr>
  <tr>
    <td>تاريخ فتح العطاء</td>
    <td><?php echo $row_tender_info['tender_open_date']; ?></td>
  </tr>
  <tr>
    <td>تاريخ اللجنة الفنية</td>
    <td><?php echo $row_tender_info['tender_eval_date']; ?></td>
  </tr>
  <tr>
    <td>سعر العطاء</td>
    <td><?php echo $row_tender_info['tender_price']; ?></td>
  </tr>
  <tr>
    <td>صفري</td>
    <td><?php echo $row_tender_info['tender_vat']; ?></td>
  </tr>
  <tr>
    <td>تكاليف الاعلان في الجريدة</td>
    <td><?php echo $row_tender_info['adv_cost']; ?></td>
  </tr>
  <tr>
    <td>الكفاله</td>
    <td><?php echo $row_tender_info['Warranty']; ?></td>
  </tr>
  <tr>
    <td>فترة التنفيذ</td>
    <td><?php echo $row_tender_info['Implementation_period']; ?></td>
  </tr>
  <tr>
    <td>الخطوط المرجعية </td>
    <td><a class="btn btn-primary text-white" data-toggle="modal" data-target=".TOR" >View TOR</a></td>
  </tr>
  <tr>
    <td>تاريخ اضافة العطاء</td>
    <td><?php echo $row_tender_info['insert_date']; ?></td>
  </tr>
  
</table>

     
     
     
                                
                                    
                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                 
                                                
                                                
                                                <p>&nbsp;</p>
  </div>
  
  
  
  
  
  <div class="col-md-8  ">
                                                <div class="main-card mb-3 card shadow p-3 mb-5 text-dark bg-white rounded  ">
                                                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                                                    <table align="center" class="table">
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">رقم العطاء:</td>
                                                        <td><input type="text" class="form-control"  name="tender_number" value="<?php echo htmlentities($row_tender_info['tender_number'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">اسم العطاء:</td>
                                                        <td><input type="text" class="form-control"  name="tender_name" value="<?php echo htmlentities($row_tender_info['tender_name'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">بداية التسليم:</td>
                                                        <td><input type="date" class="form-control"  name="tender_start_submission" value="<?php echo htmlentities($row_tender_info['tender_start_submission'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">انتهاء التسليم:</td>
                                                        <td><input type="date" class="form-control"  name="tender_end_submission" value="<?php echo htmlentities($row_tender_info['tender_end_submission'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">التصنيف :</td>
                                                        <td><select name="tender_cat" class="form-control" >
                                                          <?php 
do {  
?>
                                                          <option value="<?php echo $row_tender_sectors['item_id']?>" <?php if (!(strcmp($row_tender_sectors['item_id'], htmlentities($row_tender_info['tender_cat'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_tender_sectors['item_name']?></option>
                                                          <?php
} while ($row_tender_sectors = mysql_fetch_assoc($tender_sectors));
?>
                                                        </select></td>
                                                      </tr>
                                                      <tr> </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">سعر العطاء :</td>
                                                        <td><input class="form-control"  type="text" name="tender_price" value="<?php echo htmlentities($row_tender_info['tender_price'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">المشروع:</td>
                                                        <td><select name="tender_project" class="form-control" >
                                                          <?php 
do {  
?>
                                                          <option value="<?php echo $row_all_projects['ProjectNo']?>" <?php if (!(strcmp($row_all_projects['ProjectNo'], htmlentities($row_tender_info['tender_project'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>><?php echo $row_all_projects['ProjectNameAr']?></option>
                                                          <?php
} while ($row_all_projects = mysql_fetch_assoc($all_projects));
?>
                                                        </select></td>
                                                      </tr>
                                                      <tr> </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">الضريبة:</td>
                                                        <td><select name="tender_vat" class="form-control" >
                                                          <option value="0" <?php if (!(strcmp(0, htmlentities($row_tender_info['tender_vat'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>غير معفي</option>
                                                          <option value="1" <?php if (!(strcmp(1, htmlentities($row_tender_info['tender_vat'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>معفي</option>
                                                        </select></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">تكلفة الاعلان في الجريدة:</td>
                                                        <td><input class="form-control"  type="text" name="adv_cost" value="<?php echo htmlentities($row_tender_info['adv_cost'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">الكفالة:</td>
                                                        <td><input class="form-control"  type="text" name="Warranty" value="<?php echo htmlentities($row_tender_info['Warranty'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">فترة التنفيذ:</td>
                                                        <td><input class="form-control"  type="text" name="Implementation_period" value="<?php echo htmlentities($row_tender_info['Implementation_period'], ENT_COMPAT, ''); ?>" size="32" /></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">TOR:</td>
                                                        <td><textarea class="form-control"  name="TOR" cols="32"><?php echo htmlentities($row_tender_info['TOR'], ENT_COMPAT, ''); ?></textarea></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">ملاحظات:</td>
                                                        <td><textarea class="form-control"  name="note" cols="32"><?php echo htmlentities($row_tender_info['note'], ENT_COMPAT, ''); ?></textarea></td>
                                                      </tr>
                                                      <tr valign="baseline">
                                                        <td nowrap="nowrap" align="right">&nbsp;</td>
                                                        <td><input type="submit" value="  تحديث البيانات " /></td>
                                                      </tr>
                                                    </table>
                                                    <input type="hidden" name="MM_update" value="form1" />
                                                    <input type="hidden" name="id" value="<?php echo $row_tender_info['id']; ?>" />
                                                  </form>
                                                  <p>&nbsp;</p>
      </div></div>
   
  
  
  
  
  
  
  
  
                                            
                                        </div>
   
   
   </div>
   
   
   
   

   </div> 
  
<?php 
//
  include('templeat_footer.php'); 
 
?>







 
  
  
  
     
     
     
     
     
     <?php mysql_free_result($tender_info);

mysql_free_result($tender_sectors);

mysql_free_result($all_projects);
 ?> 