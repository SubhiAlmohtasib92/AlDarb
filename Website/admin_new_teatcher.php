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
  $insertSQL = sprintf("INSERT INTO users (user_name, father_id, address, account_status, user_type, reg_date, gender, father_work_location, mobile, additional_info) VALUES (%s, %s, %s, %s, %s, NOW() , %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['father_id'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['account_status'], "int"),
                       GetSQLValueString($_POST['user_type'], "int"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['father_work_location'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['additional_info'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  header("location: ".$_SERVER['PHP_SELF']) ; 
  
  
}

mysql_select_db($database_conn, $conn);
$query_students_father = "SELECT * FROM users WHERE user_type = 2";
$students_father = mysql_query($query_students_father, $conn) or die(mysql_error());
$row_students_father = mysql_fetch_assoc($students_father);
$totalRows_students_father = mysql_num_rows($students_father);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
 




<div class="row" > 






<div data-toggle="modal" data-target="#myModal"  class="col-md-3" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="#">
    <h1 align="center" class="fi-plus"></h1>
             
        اضافة   مدرس جديد       </a></div>


<div class="col-md-9 element-box el-tablo p-2 " dir="ltr" >


  

<table border="1" class="table table-striped table-lightfont dataTable " id="dataTable1" dir="rtl" >
<thead class="bg-dark text-white">

 
       <th>اسم المدرس</th>
       <th>العنوان</th>
        <th>رقم الهاتف </th>
        <th>    </th>
        
    </tr>
    
    </thead>
    <tbody>
    <?php do { ?>
      <tr>
         <td><?php echo $row_students_father['user_name']; ?></td>
         <td><?php echo $row_students_father['address']; ?></td>
        <td><?php echo $row_students_father['mobile']; ?></td>
            <td>  <a class="btn btn-dark" href="=<?php echo $row_students_father['user_id']; ?>" > الملف المالي         </a>  
            
             <a class="btn btn-dark" href="admin_teacher_cource.php?id=<?php echo $row_students_father['user_id']; ?>" >     دورات المدرس   </a>  
            
             </td>
      </tr>
      <?php } while ($row_students_father = mysql_fetch_assoc($students_father)); ?>
  
  </tbody>
  </table>
</div> 







</div>
















 
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">  اضافة مدرس  جديد </h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl" >
          <table class="table" align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الاسم الكامل</td>
              <td><input class="form-control" required="required"  type="text" name="user_name" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">العنوان </td>
              <td><input  class="form-control" required="required"   type="text" name="address" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الجنس</td>
              <td><select name="gender">
                <option value="ذكر" <?php if (!(strcmp("ذكر", ""))) {echo "SELECTED";} ?>>ذكر</option>
                <option value="انثى" <?php if (!(strcmp("انثى", ""))) {echo "SELECTED";} ?>>انثى</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">مكان العمل</td>
              <td><input  class="form-control" required="required"  type="text" name="father_work_location" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">موبايل</td>
              <td><input  class="form-control" required="required"  type="text" name="mobile" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">معلومات اضافية </td>
              <td><input  class="form-control" required="required"  type="text" name="additional_info" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="  اضافة   مدرس " /></td>
            </tr>
          </table>
          <input type="hidden" name="father_id" value="-1" />
          <input type="hidden" name="account_status" value="1" />
          <input type="hidden" name="user_type" value="2" />
          <input type="hidden" name="reg_date" value="" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        <p>&nbsp;</p>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>

  </div>
</div>




<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($students_father);
?>
