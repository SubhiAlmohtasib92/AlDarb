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
  $insertSQL = sprintf("INSERT INTO supplier_notes (supplier_id, note_text, insert_date) VALUES (%s, %s, NOW())",
                       GetSQLValueString($_GET['id'], "int"),
                       GetSQLValueString($_POST['note_text'], "text") );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST['delete_id'])) && ($_POST['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM supplier_notes WHERE id=%s",
                       GetSQLValueString($_POST['delete_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE t_suppliers SET is_black_list=%s WHERE id=%s",
                       GetSQLValueString($_POST['is_black_list'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_supplaier_info = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_info = sprintf("SELECT t_suppliers. is_black_list , t_suppliers.id, t_suppliers.supplier_name, t_suppliers.supplier_Authorized_signatory, t_suppliers.supplier_license, t_suppliers.supplier_tel, t_suppliers.supplier_mobile, t_suppliers.supplier_fax, t_suppliers.supplier_box, t_suppliers.supplier_emp_count, t_suppliers.supplier_major, t_suppliers.experience_years, t_suppliers.email, t_suppliers.website, t_suppliers.main_office_location, cities.Cities_name FROM t_suppliers, cities WHERE id = %s AND t_suppliers.city = cities.Cities_id", GetSQLValueString($colname_supplaier_info, "int"));
$supplaier_info = mysql_query($query_supplaier_info, $conn) or die(mysql_error());
$row_supplaier_info = mysql_fetch_assoc($supplaier_info);
$totalRows_supplaier_info = mysql_num_rows($supplaier_info);

$colname_supplaier_sectors = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_sectors = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_sectors = sprintf("SELECT supplier_items.id, supplier_items.supplier_id, work_sector_item.item_name FROM supplier_items, work_sector_item WHERE supplier_id = %s AND supplier_items.item_id = work_sector_item.item_id", GetSQLValueString($colname_supplaier_sectors, "int"));
$supplaier_sectors = mysql_query($query_supplaier_sectors, $conn) or die(mysql_error());
$row_supplaier_sectors = mysql_fetch_assoc($supplaier_sectors);
$totalRows_supplaier_sectors = mysql_num_rows($supplaier_sectors);

$colname_supplaier_attatch = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_attatch = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_attatch = sprintf("SELECT * FROM supplier_attatch WHERE supplier_id = %s", GetSQLValueString($colname_supplaier_attatch, "int"));
$supplaier_attatch = mysql_query($query_supplaier_attatch, $conn) or die(mysql_error());
$row_supplaier_attatch = mysql_fetch_assoc($supplaier_attatch);
$totalRows_supplaier_attatch = mysql_num_rows($supplaier_attatch);

$colname_supplaier_notes = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_notes = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_notes = sprintf("SELECT * FROM supplier_notes WHERE supplier_id = %s", GetSQLValueString($colname_supplaier_notes, "int"));
$supplaier_notes = mysql_query($query_supplaier_notes, $conn) or die(mysql_error());
$row_supplaier_notes = mysql_fetch_assoc($supplaier_notes);
$totalRows_supplaier_notes = mysql_num_rows($supplaier_notes);

$maxRows_supplaier_tenders = 10;
$pageNum_supplaier_tenders = 0;
if (isset($_GET['pageNum_supplaier_tenders'])) {
  $pageNum_supplaier_tenders = $_GET['pageNum_supplaier_tenders'];
}
$startRow_supplaier_tenders = $pageNum_supplaier_tenders * $maxRows_supplaier_tenders;

$colname_supplaier_tenders = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_tenders = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_tenders = sprintf("SELECT awarded_tenders.id, tenders.tender_number, tenders.tender_name, awarded_tenders.award_date, awarded_tenders.tender_id FROM awarded_tenders, tenders WHERE awarded_supplier = %s AND awarded_tenders.tender_id = tenders.id ORDER BY awarded_tenders.id DESC", GetSQLValueString($colname_supplaier_tenders, "int"));
$query_limit_supplaier_tenders = sprintf("%s LIMIT %d, %d", $query_supplaier_tenders, $startRow_supplaier_tenders, $maxRows_supplaier_tenders);
$supplaier_tenders = mysql_query($query_limit_supplaier_tenders, $conn) or die(mysql_error());
$row_supplaier_tenders = mysql_fetch_assoc($supplaier_tenders);

if (isset($_GET['totalRows_supplaier_tenders'])) {
  $totalRows_supplaier_tenders = $_GET['totalRows_supplaier_tenders'];
} else {
  $all_supplaier_tenders = mysql_query($query_supplaier_tenders);
  $totalRows_supplaier_tenders = mysql_num_rows($all_supplaier_tenders);
}
$totalPages_supplaier_tenders = ceil($totalRows_supplaier_tenders/$maxRows_supplaier_tenders)-1;

$colname_supplaier_evaluations = "-1";
if (isset($_GET['id'])) {
  $colname_supplaier_evaluations = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_supplaier_evaluations = sprintf("SELECT evaluations.eval_id, tenders.tender_name, tenders.tender_number, work_sector_item.item_name, evaluations.period_start, evaluations.period_end, evaluations.summary, evaluations.awarded__id FROM evaluations, tenders, work_sector_item WHERE supplier_id = %s AND evaluations.tender_id = tenders.id AND evaluations.sector_id = work_sector_item.item_id", GetSQLValueString($colname_supplaier_evaluations, "int"));
$supplaier_evaluations = mysql_query($query_supplaier_evaluations, $conn) or die(mysql_error());
$row_supplaier_evaluations = mysql_fetch_assoc($supplaier_evaluations);
$totalRows_supplaier_evaluations = mysql_num_rows($supplaier_evaluations);
 
//


$page['title'] = 'صفحة مورد  ';
$page['desc'] = $row_supplaier_info['supplier_name']  ;
 
 include('templeat_header.php');
  ?>


  
  
  
  
  
<div class="row text-dark  ">
  
  
  
  
  
  
  <div class="col-md-4  ">
                                                <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded  ">
                                                    <div class="card-body   ">
                            
                            
                  <h3 class="<?php if ( $row_supplaier_info['is_black_list'] == 1 ) { echo 'bg-dark text-white ';  } ?>" align="center" > <?php echo $row_supplaier_info['supplier_name']; ?> </h3>          
                            
                            
                            
                            <table width="100%" border="0" dir="rtl" class="table">
  <tr>
    <td width="40%"> المخول بالتوقيع </td>
    <td><?php echo $row_supplaier_info['supplier_Authorized_signatory']; ?></td>
  </tr>
  <tr>
    <td>المشتغل المرخص</td>
    <td><?php echo $row_supplaier_info['supplier_license']; ?></td>
  </tr>
  <tr>
    <td>هاتف</td>
    <td><?php echo $row_supplaier_info['supplier_tel']; ?></td>
  </tr>
  <tr>
    <td>موبايل</td>
    <td><?php echo $row_supplaier_info['supplier_mobile']; ?></td>
  </tr>
  <tr>
    <td>فاكس</td>
    <td><?php echo $row_supplaier_info['supplier_fax']; ?></td>
  </tr>
  <tr>
    <td>صندوق بريد</td>
    <td><?php echo $row_supplaier_info['supplier_box']; ?></td>
  </tr>
  
  
  
  
  <tr>
    <td>بريد الكتروني</td>
    <td><a href="mailto:<?php echo $row_supplaier_info['email']; ?>" ><?php echo $row_supplaier_info['email']; ?></a> </td>
  </tr>
  <tr>
    <td>موقع الكتروني</td>
    <td><a href="http://<?php echo $row_supplaier_info['website']; ?>" target="_new"><?php echo $row_supplaier_info['website']; ?></a></td>
  </tr>
  <tr>
    <td>المحافظة</td>
    <td><?php echo $row_supplaier_info['Cities_name']; ?></td>
  </tr>
  <tr>
    <td>العنوان </td>
    <td><?php echo $row_supplaier_info['main_office_location']; ?></td>
  </tr>
</table>

                       
                     <a href="supplier__edit_info.php?id=<?php echo $row_supplaier_info['id']; ?>" class="btn btn-primary " >  تعديل البيانات الاساسية  </a>               
                                    
                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                
                     <?php if ( $row_supplaier_info['is_black_list'] == 0 ) {  ?>                           
                                                
                                                
                                                
                                                
                                                <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
                                                  
                                                  
                                                  <input type="hidden" name="is_black_list" value="1" size="32" /> 
                                                    <input type="submit" value="اضافة المورد للقائمة السوداء   " class="btn btn-dark form-control" /> 
                                                  <input type="hidden" name="MM_update" value="form2" />
                                                  <input type="hidden" name="id" value="<?php echo $row_supplaier_info['id']; ?>" />
                                                </form>
                                                
                                                
                                                <?php } else { ?> 
                                                     
                                                <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
                                                  
                                                  
                                                  <input type="hidden" name="is_black_list" value="0" size="32" /> 
                                                    <input type="submit" value="ازاله من القائمة السوداء    " class="btn btn-light form-control" /> 
                                                  <input type="hidden" name="MM_update" value="form2" />
                                                  <input type="hidden" name="id" value="<?php echo $row_supplaier_info['id']; ?>" />
                                                </form>
                                                  
                                                
                                                
                                                
                                                <?php } ?> 
                                                
                                                
                                                <p>&nbsp;</p>
  </div>
  
  
  
  
  
  
  <div class="col-md-8">
  
  
  
  
                                                 
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                 <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
                                                
                                                <div class="card-header bg-primary text-white "> مجالات عمل المورد 
                                                
                                                
                                   
                                   <div class="btn-actions-pane-left">
                                                    <a href="suppliers_sectors.php?id=<?php echo $row_supplaier_info['id']; ?>" class="btn btn-primary text-white btn-sm"> اضافة جديد <i class="nav-icon-big typcn typcn-plus" ></i> </a>
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body" dir="rtl">
                                     
                                     
                                     
                                     
                                     
                                     
                                                        <?php do { ?>
                                                        
                                                        
                                                        <b class="mb-2 mr-2 btn btn-success" style="float:right ; "><?php echo $row_supplaier_sectors['item_name']; ?><a><span class="badge badge-secondary badge-dot badge-dot-lg"> </span></a></b>
                                                        
                                                      
                                                       
                                                        
                                                          <?php } while ($row_supplaier_sectors = mysql_fetch_assoc($supplaier_sectors)); ?>


                                                    </div>
    </div>                               
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded">
                                                
                                                <div class="card-header bg-primary text-white ">وثائق المورد 
                                                  <div class="btn-actions-pane-left">
                                                    <a href="supplier_attatch.php?id=<?php echo $row_supplaier_info['id']; ?>" class="btn btn-primary text-white btn-sm"> اضافة جديد <i class="nav-icon-big typcn typcn-plus" ></i> </a>
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body">
                                                    
                                                    
                                                    
                                                        <?php do { ?>
        
        
        
                         <b class="mb-2 mr-2 btn btn-success" style="float:right ; "><a target="_new" class="text-white" href="<?php echo $row_supplaier_attatch['attatch']; ?>" >   <?php echo $row_supplaier_attatch['note']; ?>      <a><span class="badge badge-secondary badge-dot badge-dot-lg"> </span></a></b>
                                                    
                                                   
                                                          <?php } while ($row_supplaier_attatch = mysql_fetch_assoc($supplaier_attatch)); ?>

                                                    </div>
                                                </div>
                                                
                                                
                                        
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
                                                
                                                <div class="card-header">  عطاءات راسية على المورد  
                                                
                                                
                                   
                                   <div class="btn-actions-pane-left">
                                             
                                             
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body">
                                                      <?php if ($totalRows_supplaier_tenders > 0) { // Show if recordset not empty ?>
                                                      <table border="1" class="table">
                                                        <tr class="bg-primary text-white">
                                                           <td>رقم العطاء</td>
                                                          <td>اسم العطاء</td>
                                                          <td>تاريخ الترسية </td>
                                                            
                                                          <td>
                                                          
                                                         تقييمات 
                                                          
                                                          </td>
                                                        </tr>
                                                        <?php do { ?>
                                                          <tr>
                                                             <td><?php echo $row_supplaier_tenders['tender_number']; ?></td>
                                                            <td><a href="tender_profile.php?id=<?php echo $row_supplaier_tenders['tender_id']; ?>" class="btn btn-primary btn-sm"><?php echo $row_supplaier_tenders['tender_name']; ?></a></td>
                                                            <td><?php echo $row_supplaier_tenders['award_date']; ?></td>
                                                              
                                                              
                                                              
                                                            <td>
                                                                
                                                                     
                                                            </td>                           
                                                              
                                                          </tr>
                                                          <?php } while ($row_supplaier_tenders = mysql_fetch_assoc($supplaier_tenders)); ?>
                                                      </table>
                                                        <?php }else { // Show if recordset not empty ?>
                                                        
      
 <h3 align="center" > لم يتم ترسية اي عطاء للمورد  </h3>     
      
      <?php } ?>                                                   
                                                        
                                                        
                                                        
                                                        
                                                    </div>
                                                </div>
                                                <?php if ($totalRows_supplaier_evaluations > 0) { // Show if recordset not empty ?>
  <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
    
   
    
    
    <div class="card-body" dir="rtl">
    
    
    
    <h5 align="right" > تقييمات المورد   </h5>
    <hr />

    
      <table border="1" class="table " >
        <tr class="bg-primary text-white ">
          <td>الموضوع  </td>
          <td>العطاء</td>
           <td>المجال</td>
          <td>بداية فترة التقييم</td>
          <td>نهاية فترة التقييم </td>
          <td></td>
          
          
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_supplaier_evaluations['summary']; ?> </td>
            <td><?php echo $row_supplaier_evaluations['tender_name']; ?> - <?php echo $row_supplaier_evaluations['tender_number']; ?></td>
 
             <td><?php echo $row_supplaier_evaluations['item_name']; ?></td>
            <td><?php echo $row_supplaier_evaluations['period_start']; ?></td>
            <td><?php echo $row_supplaier_evaluations['period_end']; ?></td>
            
            <td> <a href="tender_awarde_evaluation.php?awarde_id=<?php echo $row_supplaier_evaluations['awarded__id']; ?>" class="btn btn-primary" >عرض التقييم </a> </td>
          </tr>
          <?php } while ($row_supplaier_evaluations = mysql_fetch_assoc($supplaier_evaluations)); ?>
      </table>
    </div>
  </div>
  <?php } // Show if recordset not empty ?>
<div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
                                                
                                                <div class="card-header"> ملاحظات على المورد 
                                                
                                                
                                   
                                   <div class="btn-actions-pane-left">
                                            
                                            
                                                </div>
                                                
                                                
                                                             
                                                </div>
                                                
                                                
                                                    <div class="card-body" id="note">
                                                      <form action="<?php echo $editFormAction; ?>#note" method="post" name="form1" id="form1">
             
              <textarea style="background-color:#FFFF99" class="form-control" placeholder="ابدأ بكتابه ملاحظات على المورد " required="required"   name="note_text" cols="50" rows="2"></textarea>
              
              <br />
                                          <input type="submit" class="btn btn-primary " value=" اضافة ملاحظة على المورد" />
                                                            
                                                            
                                                        <input type="hidden" name="supplier_id" value="" />
                                                        <input type="hidden" name="insert_date" value="" />
                                                        <input type="hidden" name="MM_insert" value="form1" />
                                                      </form>
                                                      <p>&nbsp;</p>
                                                      
                                                      <?php if ($totalRows_supplaier_notes > 0) { // Show if recordset not empty ?>
                                                      
                                                      
                                                      <table border="1" class="table">
                                              
                                              
                                                        <?php do { ?>
                                                        <tr class="bg-primary text-white ">
                                                   
                                                   
                                                            <td>
  <?php echo $row_supplaier_notes['note_text']; ?>
 </td>
                                                            <td width="100"><?php echo $row_supplaier_notes['insert_date']; ?></td>
                                                            
                                                            
                                                            
                                                   <td width="50" > 
                                                   <form action="<?php echo $editFormAction; ?>#note" method="post"
 > 
 <input type="hidden" name="delete_id" value="<?php echo $row_supplaier_notes['id']; ?>"  /> 
 <input type="submit" value="X" class="btn btn-danger btn-xs"  /> 
 
 
 
 </form>                                                   
                                                   
                                                   
                                                    </td>         
                                                            
                                                          </tr>
                                                          <?php } while ($row_supplaier_notes = mysql_fetch_assoc($supplaier_notes)); ?>
                                                      </table>
                                                      
                                                      
                                                       <?php } // Show if recordset not empty ?>
                                                       
                                                    </div>
    </div>
                                                
                                                
                                                                
                                                
                                                
                                                
                                                
                                                
                                                
  </div>
  
  
  
  
  
  
  
  
                                            
                                        </div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($supplaier_info);

mysql_free_result($supplaier_sectors);

mysql_free_result($supplaier_attatch);

mysql_free_result($supplaier_notes);

mysql_free_result($supplaier_tenders);

mysql_free_result($supplaier_evaluations);
?>
