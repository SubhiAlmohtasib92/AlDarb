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
  $insertSQL = sprintf("INSERT INTO users (user_name, father_id, address, account_status, user_type, reg_date, gender, stu_class, mobile, additional_info) VALUES (%s, %s, %s, %s, %s, NOW() , %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['father_id'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['account_status'], "int"),
                       GetSQLValueString($_POST['user_type'], "int"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['stu_class'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['additional_info'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  $success1 = 1 ; 
  
}

 

if (!isset($_SESSION)) {
  session_start();
}

$colname_user_info = "-1";
if (isset($_GET['id'])) {
  $colname_user_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_user_info = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_user_info, "int"));
$user_info = mysql_query($query_user_info, $conn) or die(mysql_error());
$row_user_info = mysql_fetch_assoc($user_info);
$totalRows_user_info = mysql_num_rows($user_info);

$colname_students_father = "-1";
if (isset($_GET['id'])) {
  $colname_students_father = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_students_father = sprintf("SELECT * FROM users WHERE father_id = %s", GetSQLValueString($colname_students_father, "int"));
$students_father = mysql_query($query_students_father, $conn) or die(mysql_error());
$row_students_father = mysql_fetch_assoc($students_father);
$totalRows_students_father = mysql_num_rows($students_father);

mysql_select_db($database_conn, $conn);
$query_all_class = "SELECT * FROM classes";
$all_class = mysql_query($query_all_class, $conn) or die(mysql_error());
$row_all_class = mysql_fetch_assoc($all_class);
$totalRows_all_class = mysql_num_rows($all_class);

mysql_select_db($database_conn, $conn);
$query_last_added = "SELECT * FROM users WHERE user_type = 4 ORDER BY user_id DESC";
$last_added = mysql_query($query_last_added, $conn) or die(mysql_error());
$row_last_added = mysql_fetch_assoc($last_added);
$totalRows_last_added = mysql_num_rows($last_added);
?>
<?php 
 
 include('templeat_header.php');
  ?>
  
  
  <h3 align="center" ><?php echo $row_user_info['user_name']; ?> <b style="font-size:12px; color:#FF0000;">(ولي امر)</b> </h3>  
  
  
  
  
  
  
  


<div class="row" > 






<div data-toggle="modal" data-target="#myModal"  class="col-md-3" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="#">
    <h1 align="center" class="fi-plus"></h1>
             
        اضافة     طلاب جدد      
        الى 
        <br>

        <?php echo $row_user_info['user_name']; ?>
        
          </a></div>


<div class="col-md-9 element-box el-tablo p-2 " dir="ltr" >


  

<table border="1" class="table table-striped table-lightfont dataTable " id=" " dir="rtl" >
<thead class="bg-dark text-white">

 
       <th>اسم ولي الامر</th>
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
            <td>  <a class="btn btn-dark" href="admin_student_profile.php?id=<?php echo $row_students_father['user_id']; ?>" > ملف الطالب       </a>   </td>
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
        <h4 class=" " align="center" > اضافة طالب الى  <?php echo $row_user_info['user_name']; ?></h4>
        
        
        <p align="center" > البيانات المعبئة تلقائيا هي معلومات ولي الامر .. يمكن تغيريها  </p>
      </div>
      <div class="modal-body">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl" >
          <table class="table" align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الاسم الكامل</td>
              <td><input class="form-control" required  type="text" name="user_name" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">العنوان </td>
              <td><input  class="form-control" required   type="text" name="address" value="<?php echo $row_user_info['address']; ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الجنس</td>
              <td><select name="gender">
                <option value="ذكر" <?php if (!(strcmp("ذكر", ""))) {echo "SELECTED";} ?>>ذكر</option>
                <option value="انثى" <?php if (!(strcmp("انثى", ""))) {echo "SELECTED";} ?>>انثى</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الصف</td>
              <td>
              
              <select name="stu_class" required  >
                <option value=""></option>
                 <?php
do {  
?>
                <option value="<?php echo $row_all_class['class_id']?>"><?php echo $row_all_class['class_name']?></option>
                <?php
} while ($row_all_class = mysql_fetch_assoc($all_class));
  $rows = mysql_num_rows($all_class);
  if($rows > 0) {
      mysql_data_seek($all_class, 0);
	  $row_all_class = mysql_fetch_assoc($all_class);
  }
?>
              </select>
              
              
              
              </td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">موبايل</td>
              <td><input  class="form-control" required  type="text" name="mobile" value="<?php echo $row_user_info['mobile']; ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">معلومات  الطوارئ  </td>
              <td><textarea name="additional_info" cols="32" rows="4" required class="form-control"></textarea></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="  اضافة طالب " /></td>
            </tr>
          </table>
          <input type="hidden" name="father_id" value="<?php echo $row_user_info['user_id']; ?>" />
          <input type="hidden" name="account_status" value="1" />
          <input type="hidden" name="user_type" value="4" />
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


  
  
  
  
  
  
  
  
  

<div id="stu_reg" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      
      
        <a class="close" data-dismiss="modal">×</a>
        <h5> تسجيل الطالب في الدورات   </h5>
        
        <h3 align="center" > <?php echo $row_last_added['user_name']; ?></h3> 
        
        
        
        
        
    </div>
    <div class="modal-body">
      
      
      
      
        
        
        
    </div>


</div>



  
  
  
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($students_father);

mysql_free_result($all_class);

mysql_free_result($last_added);

mysql_free_result($user_info);
?>
  
  
  
  <?php if ($success1 == 1 ) { ?> 
  
  
  <script type="text/javascript">
    $(window).on('load', function() {
        $('#stu_reg').modal('show');
    });
</script>


<?php } ?> 