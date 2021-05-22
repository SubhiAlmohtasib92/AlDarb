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
  $insertSQL = sprintf("INSERT INTO cource_students (c_id, stu_id, cost, insert_date) VALUES (%s, %s, %s,NOW())",
                       GetSQLValueString($_POST['c_id'], "int"),
                       GetSQLValueString($_POST['stu_id'], "int"),
                       GetSQLValueString($_POST['cost'], "double"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE cource_students SET cost=%s WHERE id=%s",
                       GetSQLValueString($_POST['cost'], "double"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

if ((isset($_POST['delete_id'])) && ($_POST['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cource_students WHERE id=%s",
                       GetSQLValueString($_POST['delete_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

$colname_courcer_info = "-1";
if (isset($_GET['id'])) {
  $colname_courcer_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_courcer_info = sprintf("SELECT * FROM courses WHERE c_id = %s", GetSQLValueString($colname_courcer_info, "int"));
$courcer_info = mysql_query($query_courcer_info, $conn) or die(mysql_error());
$row_courcer_info = mysql_fetch_assoc($courcer_info);
$totalRows_courcer_info = mysql_num_rows($courcer_info);

mysql_select_db($database_conn, $conn);
$colname_all_cource_students = "-1";
if (isset($_GET['id'])) {
  $colname_all_cource_students = $_GET['id'];
}
$query_all_cource_students2 = sprintf("SELECT cource_students.stu_id  FROM cource_students, users WHERE c_id = %s AND cource_students.stu_id = users.user_id", GetSQLValueString($colname_all_cource_students, "int"));



$query_all_stu = "SELECT * FROM users WHERE user_type = 4 and user_id not in ($query_all_cource_students2) ";
$all_stu = mysql_query($query_all_stu, $conn) or die(mysql_error());
$row_all_stu = mysql_fetch_assoc($all_stu);
$totalRows_all_stu = mysql_num_rows($all_stu);

$colname_all_cource_students = "-1";
if (isset($_GET['id'])) {
  $colname_all_cource_students = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_all_cource_students = sprintf("SELECT cource_students.id, cource_students.c_id, users.user_name, cource_students.cost, cource_students.insert_date FROM cource_students, users WHERE c_id = %s AND cource_students.stu_id = users.user_id", GetSQLValueString($colname_all_cource_students, "int"));
$all_cource_students = mysql_query($query_all_cource_students, $conn) or die(mysql_error());
$row_all_cource_students = mysql_fetch_assoc($all_cource_students);
$totalRows_all_cource_students = mysql_num_rows($all_cource_students);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
<h3 align="center" > اضافة طلاب جدد الى دورة <br />
 <?php echo $row_courcer_info['c_name']; ?>
 
  </h3>
  
  
  <br />

  <div class="row" > 
  <div class="col-md-4" >
  
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">الطالب</td>
      <td><select name="stu_id" class="form-control" required>
      <option></option>
        <?php 
do {  
?>
        <option value="<?php echo $row_all_stu['user_id']?>" ><?php echo $row_all_stu['user_name']?></option>
        <?php
} while ($row_all_stu = mysql_fetch_assoc($all_stu));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">التكلفة</td>
      <td><input required="required" class="form-control " type="text" name="cost" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="  تسجيل الطالب في الدورة " /></td>
    </tr>
  </table>
  <input type="hidden" name="c_id" value="<?php echo $row_courcer_info['c_id']; ?>" />
  <input type="hidden" name="insert_date" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>



</div> 
<div class="col-md-8" >
  <?php if ($totalRows_all_cource_students > 0) { // Show if recordset not empty ?>
    <table border="1" class="table table-hover">
      <tr class="bg-dark text-white ">
        <td>اسم الطالب </td>
        <td>التكلفة </td>
        <td>تاريخ التسجيل </td>
        
        <td>تعديل التكلفة </td>   <td></td> 
        
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_all_cource_students['user_name']; ?></td>
          <td><?php echo $row_all_cource_students['cost']; ?></td>
          <td><?php echo $row_all_cource_students['insert_date']; ?></td>
          
          <td><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
            
            <input type="text" name="cost" value="<?php echo htmlentities($row_all_cource_students['cost'], ENT_COMPAT, ''); ?>" size="5" />
            
            <input type="submit" value="  حفظ " />
            
            <input type="hidden" name="MM_update" value="form2" />
            <input type="hidden" name="id" value="<?php echo $row_all_cource_students['id']; ?>" />
          </form>
          </td> 
          
          
          
          <td>
            
            <form action="" method="post" > 
              
              <input  type="hidden" name="delete_id" value="<?php echo $row_all_cource_students['id']; ?>"  /> 
              <input type="submit" value="حذف التسجيل " class="btn btn-danger btn-sm text-white "  /> 
              
            </form>   
            
            
          </td> 
          
          
          
        </tr>
        <?php } while ($row_all_cource_students = mysql_fetch_assoc($all_cource_students)); ?>
    </table>
    <?php }else{ // Show if recordset not empty ?>
    
    <div class="alert alert-info" > 
    <h3 align="center"  > لا يوجد طلاب مسجلين </h3>
    </div> 
    <?php } ?>
</div> 



</div>


<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($courcer_info);

mysql_free_result($all_stu);

mysql_free_result($all_cource_students);
?>
