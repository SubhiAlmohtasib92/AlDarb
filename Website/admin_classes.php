<?php require_once('Connections/conn.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO classes (class_name) VALUES (%s)",
                       GetSQLValueString($_POST['class_name'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE classes SET class_name=%s WHERE class_id=%s",
                       GetSQLValueString($_POST['class_name'], "text"),
                       GetSQLValueString($_POST['class_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_classes = "SELECT * FROM classes";
$all_classes = mysql_query($query_all_classes, $conn) or die(mysql_error());
$row_all_classes = mysql_fetch_assoc($all_classes);
$totalRows_all_classes = mysql_num_rows($all_classes);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
 
  


<H3 align="center" > ادارة الصفوف الدراسية  </H3> 

<div class="row" > 

<div class="col-md-4" >
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  
  
  <input class="form-control" placeholder="اسم الصف" required="required" type="text" name="class_name" value="" size="32" />
  
  <input type="submit" value="اضافة جديد " class="btn btn-dark " />
  


    <input type="hidden" name="MM_insert" value="form1" />
  </form>
  <p>&nbsp;</p>
</div>

<div class="col-md-8" > 
<table border="1" class="table">
 
  <?php do { ?>
    <tr>
       <td><?php echo $row_all_classes['class_name']; ?></td>
       
       
       <td><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
          
         
         <input type="text" name="class_name" value="<?php echo htmlentities($row_all_classes['class_name'], ENT_COMPAT, ''); ?>" size="32" />
         <input type="submit" value=" حفظ التعديل " class="btn btn-dark btn-sm" />
         
         
         <input type="hidden" name="MM_update" value="form2" />
         <input type="hidden" name="class_id" value="<?php echo $row_all_classes['class_id']; ?>" />
       </form>
        </td>
       
       
    </tr>
    <?php } while ($row_all_classes = mysql_fetch_assoc($all_classes)); ?>
</table></div>
</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_classes);
?>
