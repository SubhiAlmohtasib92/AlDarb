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
  $insertSQL = sprintf("INSERT INTO committees_type (Committee_type) VALUES (%s)",
                       GetSQLValueString($_POST['Committee_type'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE committees_type SET Committee_type=%s WHERE id=%s",
                       GetSQLValueString($_POST['Committee_type'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_type = "SELECT * FROM committees_type";
$all_type = mysql_query($query_all_type, $conn) or die(mysql_error());
$row_all_type = mysql_fetch_assoc($all_type);
$totalRows_all_type = mysql_num_rows($all_type);
 
//


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>

<div class="row  ">
  <div class="col-md-4  ">
    <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded  ">
      <div class="card-body   ">
       
       
        
        <h3 align="center" > 
        انواع اللجان 
        
        </h3>
        
        
        يمكنك من خلال النموذج التالي اضافة وتعريف انواع اللجان 
        
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        
        
        <input type="text" name="Committee_type" value="" size="32" class="form-control" required="required"  />
        
        <br />

        <input type="submit" value="  اضافة نوع لجنة " class="btn btn-primary " />
        
     
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div align="center">
     </div>
    <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">

      <div class="card-body" dir="rtl">
      
      
      <h5 align="right" > قائمة انواع اللجان   </h5>
      
      
        <table border="1" class="table">
          <tr class="bg-primary text-white ">
            <td>نوع اللجنة </td>
          </tr>
          <?php do { ?>
            <tr>
              <td> 
                <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
             
             
             
             
                      
                      
                      
                      <input type="text" name="Committee_type" value="<?php echo htmlentities($row_all_type['Committee_type'], ENT_COMPAT, ''); ?>" size="32" />
                      
                      
                      
                      <input type="submit" value="حفظ التعديلات   " />
                     
                  <input type="hidden" name="MM_update" value="form2" />
                  <input type="hidden" name="id" value="<?php echo $row_all_type['id']; ?>" />
                </form> 
               </td>
            </tr>
            <?php } while ($row_all_type = mysql_fetch_assoc($all_type)); ?>
        </table>
      </div>
    </div>
    
    
  </div>
</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_type);
?>
