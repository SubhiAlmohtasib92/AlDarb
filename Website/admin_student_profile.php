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







$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO catch_receipt (by_user, from_father, to_student, for_cource, mony, date_insert, notes) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['by_user'], "int"),
                       GetSQLValueString($_POST['from_father'], "int"),
                       GetSQLValueString($_POST['to_student'], "int"),
                       GetSQLValueString($_POST['for_cource'], "int"),
                       GetSQLValueString($_POST['mony'], "double"),
                       GetSQLValueString($_POST['date_insert'], "date"),
                       GetSQLValueString($_POST['notes'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}





if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cource_students (c_id, stu_id, cost, insert_date) VALUES (%s, %s, %s,NOW())",
                       GetSQLValueString($_POST['c_id'], "int"),
                       GetSQLValueString($_POST['stu_id'], "int"),
                       GetSQLValueString($_POST['cost'], "double") );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST['delete_reg'])) && ($_POST['delete_reg'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cource_students WHERE id=%s",
                       GetSQLValueString($_POST['delete_reg'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO courses (c_name, c_type, c_hours, c_teatcher, add_by, c_status, insert_date, c_cost) VALUES (%s, %s, %s, %s, %s, %s, NOW() , %s)",
                       GetSQLValueString($_POST['c_name'], "text"),
                       GetSQLValueString($_POST['c_type'], "int"),
                       GetSQLValueString($_POST['c_hours'], "int"),
                       GetSQLValueString($_POST['c_teatcher'], "int"),
                       GetSQLValueString('1', "int"),
                       GetSQLValueString($_POST['c_status'], "int"),
                       GetSQLValueString($_POST['c_cost'], "double"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  mysql_select_db($database_conn, $conn);
$query_last_add = "SELECT c_id FROM courses ORDER BY c_id DESC limit 1 ";
$last_add = mysql_query($query_last_add, $conn) or die(mysql_error());
$row_last_add = mysql_fetch_assoc($last_add);
$totalRows_last_add = mysql_num_rows($last_add);
  
    $insertSQL = sprintf("INSERT INTO cource_students (c_id, stu_id, cost, insert_date) VALUES (%s, %s, %s,NOW())",
                       GetSQLValueString($row_last_add['c_id'], "int"),
                       GetSQLValueString($_GET['id'], "int"),
                       GetSQLValueString($_POST['c_cost'], "double") );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  header("location: admin_Systematic_course_program.php?id=".$row_last_add['c_id'] ) ;
}

$colname_student_cources = "-1";
if (isset($_GET['id'])) {
  $colname_student_cources = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_student_cources = sprintf("SELECT cource_students.id, courses.c_name, cource_students.cost, cource_students.insert_date, cource_students.reg_end_date, cource_students.c_id FROM cource_students, courses WHERE stu_id = %s AND cource_students.c_id = courses.c_id", GetSQLValueString($colname_student_cources, "int"));
$student_cources = mysql_query($query_student_cources, $conn) or die(mysql_error());
$row_student_cources = mysql_fetch_assoc($student_cources);
$totalRows_student_cources = mysql_num_rows($student_cources);

$colname_student_info = "-1";
if (isset($_GET['id'])) {
  $colname_student_info = $_GET['id'];
}

mysql_select_db($database_conn, $conn);
$query_student_info = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_student_info, "int"));
$student_info = mysql_query($query_student_info, $conn) or die(mysql_error());
$row_student_info = mysql_fetch_assoc($student_info);
$totalRows_student_info = mysql_num_rows($student_info);

mysql_select_db($database_conn, $conn);
$query_all_open_courcess = "SELECT * FROM courses WHERE c_status = 1 and c_type <> 1  ORDER BY c_name ASC";
$all_open_courcess = mysql_query($query_all_open_courcess, $conn) or die(mysql_error());
$row_all_open_courcess = mysql_fetch_assoc($all_open_courcess);
$totalRows_all_open_courcess = mysql_num_rows($all_open_courcess);

mysql_select_db($database_conn, $conn);
$query_all_teatcher = "SELECT * FROM users WHERE user_type = 2";
$all_teatcher = mysql_query($query_all_teatcher, $conn) or die(mysql_error());
$row_all_teatcher = mysql_fetch_assoc($all_teatcher);
$totalRows_all_teatcher = mysql_num_rows($all_teatcher);

$colname_all_payments = "-1";
if (isset($_GET['id'])) {
  $colname_all_payments = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_all_payments = sprintf("SELECT * FROM catch_receipt WHERE to_student = %s", GetSQLValueString($colname_all_payments, "int"));
$all_payments = mysql_query($query_all_payments, $conn) or die(mysql_error());
$row_all_payments = mysql_fetch_assoc($all_payments);
$totalRows_all_payments = mysql_num_rows($all_payments);


mysql_select_db($database_conn, $conn);
$query_all_class = "SELECT * FROM classes";
$all_class = mysql_query($query_all_class, $conn) or die(mysql_error());
$row_all_class = mysql_fetch_assoc($all_class);
$totalRows_all_class = mysql_num_rows($all_class);




if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <style>
  #studentInfo td,th,tr {
  text-align: center;
}

#paymentsTable td, th,tr{
  text-align: center;
}

#coursesTable td,tr {
  text-align: center;
  font-size: 15px;
}

#coursesTable th{
  font-size: 13px;
}

#summaryTable td {
  text-align: center;
}
#systematicCourses td,tr{
  text-align: center;
  font-size: 15px;
}
#systematicCourses th{
  font-size: 13px;
}

#menu-outer {
	height: 84px;
	background: url(images/bar-bg.jpg) repeat-x;
}

.table {
	display: table;   /* Allow the centering to work */
	margin: 0 auto;
}

ul#horizontal-list {
	min-width: 696px;
	list-style: none;
	padding-top: 20px;
	}
	ul#horizontal-list li {
		display: inline;
    width:100%;
	}

  ul#horizontal-list li  a{
    width:100%;
	}


  </style>
  
 <h3 id="studentFullName" align="center" > ملف الطالب :  <?php echo $row_student_info['user_name']; ?> </h3> 
  
  <hr />
  
  
  
   <a href="" id="addNewCourses" data-toggle="modal" data-target="#myModal" class="btn btn-success text-white"  > التسجيل في دورة جديدة  </a>
   
   
   
   
    <a onclick="cr_new('<?php echo $row_student_info['user_id']; ?>')" data-toggle="modal" data-target="#myModal2" class="btn btn-success text-white"  > اضافة سند قبض   </a>
    
    
    
    
    
  
   <h3 align="right" > 
المعلومات الشخصية </h3> 
  
  <div id="studentDiv">
  <table width="100%" border="1" class="table" id="studentInfo">
  <tr class="bg-dark text-white">
     <td>رقم الهاتف</td>
    <td>العنوان</td>
    <td>الجنس</td>
    <td >تاريخ الالتحاق</td>
    <td >معلومات الطوارئ </td>
    <td >حالة الحساب</td>
    <td >تعديل </td>
  </tr>
  <tr>
     <td>  <?php echo $row_student_info['mobile']; ?></td>
    <td><?php echo $row_student_info['address']; ?></td>
    <td><?php echo $row_student_info['gender']; ?></td>
    <td><?php echo $row_student_info['reg_date']; ?></td>
    <td> <?php echo $row_student_info['additional_info']; ?></td>
    <td class="btn btn-<?php if ($row_student_info['account_status']==1) {echo 'success' ;} else { echo 'danger';} ?> text-white" style="width:80%; margin-top:5px;margin-left:5px;margin-right:5px;"> <?php if ($row_student_info['account_status']==1) {echo 'فعال ' ;} else { echo 'غير فعال';} ?> </td>
    <td style="text-align: center;"> 
    <a 
     data-toggle="modal" 
     data-target="#editStudentModal"
     href="javascript:void(0)"
     data-username='<?php echo $row_student_info['user_name']; ?>'
     data-userid='<?php echo $row_student_info['user_id']; ?>'
     data-mobile='<?php echo $row_student_info['mobile']; ?>'
     data-status='<?php echo $row_student_info['account_status']; ?>'
     data-address='<?php echo $row_student_info['address']; ?>'
     data-gender='<?php echo $row_student_info['gender']; ?>'
     data-regdate='<?php echo $row_student_info['reg_date']; ?>'
     data-additionalinfo='<?php echo $row_student_info['additional_info']; ?>'
     data-studentclass='<?php echo $row_student_info['stu_class']; ?>'
       >
       <i class="fa fa-edit fa-2x"></i>
        </a> </td>
  </tr>
</table>
</div>
  
  
  <br />
<br />



<div class="row">
<div class="col-md-9">
  
   <h3 align="right" > 
  الدورات المسجل بها 
  
  

</h3> 
  
<?php /* 
  
<table border="1" class="table table-hover">
  <tr class="bg-dark text-white ">
     <td>اسم الدورة</td>
     
     
    <td>التكلفة </td>
     
    <td>التكلفة </td>
    <td>تاريخ الالتحاق </td>
  </tr>
  <?php do { ?>
    <tr>
       <td><a href="admin_cource_students.php?id=<?php echo $row_student_cources['c_id']; ?>" class="btn btn-dark text-white" > <?php echo $row_student_cources['c_name']; ?> </a> </td>
       
       <td>   
           <table border="0">
  
  <?php 
  
 $colname_c_program = "-1";
if (isset($row_student_cources['c_id'])) {
  $colname_c_program = $row_student_cources['c_id'];
}
mysql_select_db($database_conn, $conn);
$query_c_program = sprintf("SELECT * FROM cource_program WHERE cource_id = %s", GetSQLValueString($colname_c_program, "int"));
$c_program = mysql_query($query_c_program, $conn) or die(mysql_error());
$row_c_program = mysql_fetch_assoc($c_program);
$totalRows_c_program = mysql_num_rows($c_program);

  
  
  
  do { ?>
    <tr>
       <td><?php echo $row_c_program['p_day']; ?></td>
      <td><?php echo $row_c_program['from_time']; ?> - <?php echo $row_c_program['to_time']; ?></td>
      </tr>
    <?php } while ($row_c_program = mysql_fetch_assoc($c_program)); ?>
</table></td>
       
       
      <td><?php echo $row_student_cources['cost']; ?></td>
      <td><?php echo $row_student_cources['insert_date']; ?></td>  
      
      <td><?php echo $row_student_cources['reg_end_date']; ?></td>
    </tr>
    <?php } while ($row_student_cources = mysql_fetch_assoc($student_cources)); ?>
</table>









*/ ?> 



















<?php 

	   
$colname_stu_courcess = "-1";
if (isset($row_student_info['user_id'])) {
  $colname_stu_courcess = $row_student_info['user_id'];
}
mysql_select_db($database_conn, $conn);
$query_stu_courcess = sprintf("SELECT cource_students.id, cource_students.c_id, cource_students.cost, cource_students.insert_date, courses.c_name,reg_end_date FROM cource_students, courses WHERE stu_id = %s AND cource_students.c_id = courses.c_id", GetSQLValueString($colname_stu_courcess, "int"));
$stu_courcess = mysql_query($query_stu_courcess, $conn) or die(mysql_error());
$row_stu_courcess = mysql_fetch_assoc($stu_courcess);
$totalRows_stu_courcess = mysql_num_rows($stu_courcess);	   
	    ?> 
       
       <table border="1" class="table " id="coursesTable">
       <thead >
       <tr class="bg-dark text-white " style="font-size:25px;">
       <th>الدورة</th>
       <th>تاريخ التسجيل</th>
       <th>تاريخ الانتهاء</th>
       <th>التكلفة</th>
       <th>تعديل</th>
       </tr>
       </thead>
         
         <?php
		 
		 $total_cost = 0 ; 
		 $total_payed= 0 ; 
		 
		 
		  do { ?>
           <tr>
           
           <td><?php echo $row_stu_courcess['c_name']; ?></td>
          
              
             <td><?php echo $row_stu_courcess['insert_date']; ?></td>
             <td><?php echo $row_stu_courcess['reg_end_date']; ?></td>
             <td><?php echo $row_stu_courcess['cost']; $total_cost+= $row_stu_courcess['cost'] ; ?></td>
            
            
             
             
             <td>
               
               
                
                
                <form  onsubmit="return confirm('هل انت متاكد من حذف تسجيل الطالب في الدورة ');" action="" method="post" > 
                
                <input type="hidden" value="<?php echo $row_student_cources['id']; ?>" name="delete_reg" />
                <input type="submit" value="حذف التسجيل "  class="btn btn-danger btn-sm text-white  " />
                
                </form> 
                
                
             </td>
             
             
           </tr>
           <?php } while ($row_stu_courcess = mysql_fetch_assoc($stu_courcess)); ?>
           
           
           <tr class="bg-info "> 
           
           <td colspan="3" > </td> 
           
           <td >
           <?php echo $total_cost ; ?>  </td> 
           
         
         
           <td>
           
          
           
           
           
            </td> 
           </tr> 
           
       </table>
       
       
       
       
       
       <hr />

       
       
       
<h3 align="right" > 
الدفعات  
  

</h3>
<?php if ($totalRows_all_payments > 0) { // Show if recordset not empty ?>
  <table border="1" class="table" id="paymentsTable">
    <tr class="bg-dark text-white ">
      
      <td>التاريخ</td>
      <td>المبلغ</td>
      <td>وذلك عن </td> 
    
      <td>
      طباعة
        </td>

        <td>
        تعديل
        </td>
    </tr>
    <?php
  
  
  $sum_payment =0; 
  
   do { ?>
      <tr>
        
        <td><?php echo date('m/d/Y',strtotime($row_all_payments['date_insert'])) ; ?></td>
        <td><?php echo $row_all_payments['mony'];
	  
	  $sum_payment+=$row_all_payments['mony'] ?></td>
        
        <td><?php echo $row_all_payments['notes']; ?></td> 
        
        <td>
          <a href="cach_print.php?id=<?php echo $row_all_payments['id']; ?>" class="btn btn-dark text-white "> طباعة سند القبض  </a>
        </td>
        <td>
        <div class="col-md-12" style="display: flex; justify-content: center;align-items: center;">
        <div class="col-md-6">
        <a id="paymentEdit"
         href="javascript:void(0)"
         style="width:100%;"
         data-toggle="modal"
         data-target="#paymentEditModal"
         data-paymentid="<?php echo $row_all_payments['id']; ?>"
         data-paymentinsertdate="<?php echo date('m/d/Y',strtotime($row_all_payments['date_insert'])) ; ?>"
         data-paymentamount="<?php echo $row_all_payments['mony']; ?>"
         data-paymentnotes="<?php echo $row_all_payments['notes']; ?>"> 
         <i class="fa fa-edit fa-lg"></i> </a>
        </div>
        
        <div class="col-md-6">
        <a id="paymentDelete" name="paymentDelete" href="javascript:void(0)" onclick='javascript:myFunction("<?php echo $row_all_payments["id"]; ?>");' style="width:100%;"> <i class="fa fa-trash fa-lg"></i> </a>
        </div>

        </div>
        </td>
        
        
      </tr>
      <?php } while ($row_all_payments = mysql_fetch_assoc($all_payments)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>




<div class="col-md-3">


<div class="card text-white bg-info mb-3" style="max-width: 18rem;">

  <div class="card-body">
   <table width="100%" border="1" dir="rtl" class="table" id="summaryTable">
   <tr class="bg-dark text-white ">
   <td colspan="2">الرصيد</td>
   </tr>
  <tr >
    <td>مجموع الذمم</td>
    <td><?php echo $total_cost ; ?></td>
  </tr>
  <tr>
    <td>مجموع الدفعات</td>
    <td><?php  if(is_null($sum_payment)) {echo '0';} else {echo $sum_payment;}  ;?></td>
  </tr>
  <tr>
    <td>الرصيد </td>
    <td><?php echo $sum_payment- $total_cost   ;?></td>
  </tr>
</table>

   
   
   
  </div>
</div>

</div>



</div>



<!-- Edit Payment Modal -->

<div class="modal fade" id="paymentEditModal" tabindex="-1" role="dialog" aria-labelledby="paymentEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">×</button>
        <h5 class="modal-title" id="paymentEditLabel"  style='text-align:center;display: inline;margin: auto;'>تعديل دفعة</h5>
      </div>
      <div class="modal-body">
      <div class="col-md-12" style="text-align:center;">
        <form id="editPaymentForm">
        
          <div class="form-group form-inline" >
            <label for="paymentAmount" class="col-sm-2 col-form-label">المبلغ: </label>
            <div class="col-sm-10">
            <input type="number" name="paymentAmount"  min="0" oninput="validity.valid||(value='');"  class="form-control" id="paymentAmount" style="margin-right:5px;" required>
            </div>
          </div>

          <div class="form-group form-inline" > 
            <label for="paymentDate" class="col-sm-2 col-form-label" >تاريخ الدفعة</label>
            <div class="col-sm-10">
            <input type="date" name="paymentDate" style="width:100%;" class="form-control" id="paymentDate" value="" required>
            </div>
          </div>

          <div class="form-group form-inline">
            <label for="paymentNote" class="col-sm-2 col-form-label" >ملاحظات:</label>
            <div class="col-sm-10">
            <textarea class="form-control" name="paymentNote" style="width:100%;" id="paymentNote" style="margin-right:5px;" required></textarea>
            </div>
          </div>

          <input id="inputPaymentId" type="hidden" class="form-control" name="inputPaymentId"  value=""/>
        </form>
        </div>
      </div>
      <div class="modal-footer" style="text-align:center;display: inline;margin: auto;">
        <button type="button" class="btn btn-danger text-white" data-dismiss="modal">اغلاق</button>
        <button id="savePayment" type="button" class="btn btn-success text-white">حفظ المعلومات</button>
      </div>
    </div>
  </div>
</div>

<!-- end of Payment Edit Modal -->












<!-- Modal -->
<div id="editStudentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4  style='text-align:center;display: inline;margin: auto;' > تعديل طالب    </h4>
      </div>

      
      <div class="modal-body">
       
       <form id="editForm" autocomplete="off">
          <table class="table" align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الاسم الكامل</td>
              <td><input id="editStudentFullName" class="form-control" required  type="text" name="user_name" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">العنوان </td>
              <td><input id="editStudentAddress"  class="form-control" required   type="text" name="address" value="<?php echo $row_user_info['address']; ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الجنس</td>
              <td><select name="gender" class="form-control" style='width:100%;' id="editStudentGender">
                <option value="ذكر" <?php if (!(strcmp("ذكر", ""))) {echo "SELECTED";} ?>>ذكر</option>
                <option value="انثى" <?php if (!(strcmp("انثى", ""))) {echo "SELECTED";} ?>>انثى</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الصف</td>
              <td>
              
              <select name="stu_class" id="editStudentClass"  class="form-control" required  style='width:100%;' >
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
              <td nowrap="nowrap" align="right">حالة الحساب</td>
              <td>
              
              <select name="studentStatus" id="editStudentStatus"  class="form-control" required  style='width:100%;' >
                <option value="1">فعال</option>
                <option  value="0">غير فعال</option>
              </select>
              </td>
            </tr>

            <tr valign="baseline">
              <td nowrap="nowrap" align="right">موبايل</td>
              <td><input  id="editStudentMobile" class="form-control" required  type="text" name="mobile" value="<?php echo $row_user_info['mobile']; ?>" size="32" /></td>
            </tr>

            <tr valign="baseline">
              <td nowrap="nowrap" align="right">تاريخ الالتحاق</td>
              <td><input  style="text-align:right;"   id="editStudentRegDate" class="form-control" required  type="date" name="regdate" value="<?php echo $row_user_info['mobile']; ?>" size="32" /></td>
            </tr>


            <tr valign="baseline">
              <td nowrap="nowrap" align="right">معلومات  الطوارئ  </td>
              <td><textarea id="editStudentAdditionalInfo" name="additional_info" cols="32" rows="4" required class="form-control"></textarea></td>
            </tr>
          </table>
          
          <input id="editStudent" style="text-align:center;display: inline;margin: auto;" type="button" class="btn btn-success text-white" data-dismiss="modal" value="حفظ المعلومات" />
          <input id="userid" type="hidden" class="form-control" name="userid"  value="<?php echo $row_student_info['user_id']; ?>"/>

          </form>
        <p>&nbsp;</p>
      </div>
     
    </div>

  </div>
</div>











<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">


  <div class="modal-dialog modal-lg ">

 

    <!-- Modal content-->
    <div class="modal-content">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style='text-align:center;display: inline;margin: auto;'>  تسجيل دورة جديدة </h4>
      </div>


      <div class="modal-body">


       <ul class="nav nav-tabs">
  <li class="active"></li>
  
  <li class="active"><a class="btn  btn-dark text-white m-3" id="systmaticCourses" data-toggle="tab" href="#home">دورات منهجية </a></li>

   <li class="active"><a class="btn  btn-dark text-white m-3" data-toggle="tab" id="NonSystmaticCourses" href="#home2">دورات لا منهجية وتاسيس </a></li>
  
  
  <li><a  class="btn  btn-dark text-white m-3"   data-toggle="tab" href="#menu1">دورة صعوبة التعلم </a></li>
 </ul> 

<div class="tab-content">


  <div id="home" class="tab-pane fade in active"> 
  <p style="text-align:center;padding-top:5px;"> التسجيل في دورات المنهجية </p>
  
  <?php 
 
  
		  $colname_class_sections = "-1";
if (isset($row_student_info['stu_class'])) {
  $colname_class_sections = $row_student_info['stu_class'];
}
mysql_select_db($database_conn, $conn);
$query_class_sections = sprintf("SELECT * FROM courses WHERE class_id = %s", GetSQLValueString($colname_class_sections, "int"));
$class_sections = mysql_query($query_class_sections, $conn) or die(mysql_error());
$row_class_sections = mysql_fetch_assoc($class_sections);
$totalRows_class_sections = mysql_num_rows($class_sections);






?> 

<table class="table " border="1" id="systematicCourses">

<thead>
<tr class="bg-dark text-white">
<th > <input id="selectAllSystematicCourses"  type="checkbox" checked name="selectAllSystematicCourses"> تحديد الكل</th>
<th >اسم الدورة</th>
<th>التكلفة</th>
<th>تاريخ البدء</th>
<th>تاريخ الانتهاء</th>
</tr>
</thead>
<tbody>
<?php 
		  $totalcost = 0 ; 
		  
		  do {
			  
			  $totalcost+=$row_class_sections['c_cost'] ; 
			  
			   ?>
         <tr>

         <form action="" method="post"  autocomplete="off" > 
         <td> <input class="id" id="<?php echo $row_class_sections['c_id']; ?>" name="<?php echo $row_class_sections['c_id']; ?>"  type="checkbox" checked> </td>
         <td><?php echo $row_class_sections['c_name']; ?></td>
         <td><input id="courseCost<?php echo $row_class_sections['c_id']; ?>" style="width:70px;" type="number" class="cost" name="cost" value="<?php echo $row_class_sections['c_cost']; ?>" size="32" /> </td>
         <td><input id="startDate<?php echo $row_class_sections['c_id']; ?>" type="date" class="startDate" name="startDate" value="<?php echo date('Y-m-d'); ?>" size="32" /> </td>
         <td><input id="endDate<?php echo $row_class_sections['c_id']; ?>" type="date" class="endDate" name="endDate" value="<?php echo date('Y-m-d',strtotime("+1 months", strtotime(date("y-m-d")))); ?>" size="32" /> </td>
        
               
               
            </form>
            </tr>
            <?php } while ($row_class_sections = mysql_fetch_assoc($class_sections)); 
	  
	  
	  ?>
    </tbody>

    <tfoot style="margin-top:5px;">
    <tr>
      
      <td colspan="1" style="border: 0;text-align:center;"> <button id="registerAllChecked" style="width:100%;" class="btn btn-primary btn-md">تسجيل المحدد</button></td>
      <th id="total"  style="border: 0;text-align:left;"></th>
      <td style="display: flex; justify-content: center;align-items: center;">
      <input  id="totalCost" type="number" style="width:70px; " class="totalCost"  value="<?php echo $totalcost ; ?>"  />

 </td>
    </tr>
   </tfoot>

        </table>

<div align=right>
<a href="admin_student_profile.php?id=<?php echo $row_student_info['user_id']; ?>" class="btn btn-dark text-white fa-lg"> عرض الملف  </a>
</div>
</div>


<!--- Non Systematic Courses ---> 
<div id="home2" class="tab-pane fade">

<p style="text-align:center;padding-top:5px;"> التسجيل في دورات اللامنهجية </p>
  
  <?php 
 
  

mysql_select_db($database_conn, $conn);
$query_class_sections = sprintf("SELECT * FROM courses WHERE c_status = 1 and c_type <> 1");
$class_sections = mysql_query($query_class_sections, $conn) or die(mysql_error());
$row_class_sections = mysql_fetch_assoc($class_sections);
$totalRows_class_sections = mysql_num_rows($class_sections);






?> 

<table class="table " border="1" id="nonsystematicCourses">

<thead>
<tr class="bg-dark text-white">
<th > <input id="nonselectAllSystematicCourses"  type="checkbox" checked name="nonselectAllSystematicCourses"> تحديد الكل</th>
<th >اسم الدورة</th>
<th>التكلفة</th>
<th>تاريخ البدء</th>
<th>تاريخ الانتهاء</th>
</tr>
</thead>
<tbody>
<?php 
		  $totalcost = 0 ; 
		  
		  do {
			  
			  $totalcost+=$row_class_sections['c_cost'] ; 
			  
			   ?>
         <tr>

         <form action="" method="post"  autocomplete="off" > 
         <td> <input class="id" id="<?php echo $row_class_sections['c_id']; ?>" name="<?php echo $row_class_sections['c_id']; ?>"  type="checkbox" checked> </td>
         <td><?php echo $row_class_sections['c_name']; ?></td>
         <td><input id="noncourseCost<?php echo $row_class_sections['c_id']; ?>" style="width:70px;" type="number" class="noncost" name="noncost" value="<?php echo $row_class_sections['c_cost']; ?>" size="32" /> </td>
         <td><input id="nonstartDate<?php echo $row_class_sections['c_id']; ?>" type="date" class="nonstartDate" name="nonstartDate" value="<?php echo date('Y-m-d'); ?>" size="32" /> </td>
         <td><input id="nonendDate<?php echo $row_class_sections['c_id']; ?>" type="date" class="nonendDate" name="nonendDate" value="<?php echo date('Y-m-d',strtotime("+1 months", strtotime(date("y-m-d")))); ?>" size="32" /> </td>
        
               
               
            </form>
            </tr>
            <?php } while ($row_class_sections = mysql_fetch_assoc($class_sections)); 
	  
	  
	  ?>
    </tbody>

    <tfoot style="margin-top:5px;">
    <tr>
      
      <td colspan="1" style="border: 0;text-align:center;"> <button id="nonregisterAllChecked" style="width:100%;" class="btn btn-primary btn-md">تسجيل المحدد</button></td>
      <th id="nontotal"  style="border: 0;text-align:left;"></th>
      <td style="display: flex; justify-content: center;align-items: center;">
      <input  id="nontotalCost" type="number" style="width:70px; " class="nontotalCost"  value="<?php echo $totalcost ; ?>"  />

 </td>
    </tr>
   </tfoot>

        </table>

<div align=right>
<a href="admin_student_profile.php?id=<?php echo $row_student_info['user_id']; ?>" class="btn btn-dark text-white fa-lg"> عرض الملف  </a>
</div>

</div>

  <div id="menu1" class="tab-pane fade">
    <h3 align="center">  دورة صعوبة التعلم  </h3>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center" dir="rtl" class="table">
           <tr valign="baseline">
          <td nowrap="nowrap" align="right">اسم الدورة   </td>
          <td><input name="c_name"  class="form-control " required  type="text" value="صعوبة تعلم : <?php echo $row_student_info['user_name']; ?>" size="32" /></td>
        </tr>  
        
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">عدد الساعات</td>
          <td><input class="form-control " required  type="text" name="c_hours" value="" size="32" /></td>
        </tr>
        
        
        
        
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">المدرس</td>
          <td><select name="c_teatcher"  class="form-control " required  >
          <option></option>
            <?php
do {  
?>
            <option value="<?php echo $row_all_teatcher['user_id']?>"><?php echo $row_all_teatcher['user_name']?></option>
            <?php
} while ($row_all_teatcher = mysql_fetch_assoc($all_teatcher));
  $rows = mysql_num_rows($all_teatcher);
  if($rows > 0) {
      mysql_data_seek($all_teatcher, 0);
	  $row_all_teatcher = mysql_fetch_assoc($all_teatcher);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">التكلفة </td>
          <td><input  class="form-control " required   type="text" name="c_cost" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="تسجيل   " /></td>
        </tr>
      </table>
      
      <input type="hidden" name="c_type" value="4" />
      <input type="hidden" name="add_by" value="" />
      <input type="hidden" name="c_status" value="1" />
      <input type="hidden" name="insert_date" value="" />
      <input type="hidden" name="MM_insert" value="form2" />
    </form>
  
  
  
  
  </div>
  
</div>




      </div>
      <div class="modal-footer">
       
      </div>
    </div>

  </div>
</div>

      
      
      
      
      
      
      
      
      
      
      
      
      
      

<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style='text-align:center;display: inline;margin: auto;'>  تسجيل دفعة جديدة </h4>
      </div>
      <div class="modal-body" id="result2">
 
 
 
      </div>
      <div class="modal-footer">

      </div>
    </div>

  </div>
</div>











<script type="text/javascript" > 

function get_father_stu(father_id){
	
 $.post("ajax/cr_step2.php", {father_id: father_id}, function(data){
	 
	 console.log(data);
	 
	 document.getElementById('result').innerHTML = data ; 
 	
  });
	
	}




</script>




<script type="text/javascript" > 

function cr_new(user_id   ){
	
		 document.getElementById('result2').innerHTML = '' ; 

	
 $.post("ajax/cr_step_get_mony.php", {
	 user_id: user_id  
	 
 }, function(data){
	 
	 
	 
	 document.getElementById('result2').innerHTML = data ; 
 	
  });
	
	}




</script>

<script type="text/javascript" > 

function cr_new_add(user_id , cource_id , father_id , c_value , notes,receiptDate ){
	
		 if (c_value == '' ) {
			 alert ('الرجاء ادخال المبلغ ');
      }else {  
		 
 document.getElementById('result2').innerHTML = '' ; 
	
 $.post("ajax/cr_step_get_mony.php", {
	 user_id: user_id ,
	 cource_id: cource_id ,
	 father_id: father_id,
	   c_value : c_value , 
	   notes:notes ,
     receiptDate:receiptDate
	 
 }, function(data){
	 
	 
	 
	 document.getElementById('result2').innerHTML = data ; 
	 
	 get_father_stu(father_id)
 	
  });
	
	}
} 



</script>


<script type="text/javascript" > 


function myFunction(paymentId){
 
   if (confirm("هل انت متأكد من حذف الدفعة؟")) {
 
 $.ajax({   
     type: "POST",
     data : {
       paymentId:paymentId
     },
     cache: false,  
     url: "ajax/Payments.php?action=delete",   
     success: function(data){
       location.reload();

     },error: function (request, status, error) {
     alert(request.responseText);
 }
 }); //end of post call
 } // end of if statment 



}
function search_stu(search_text ) {
	
	
	 
	$.post("ajax/search_stu.php", {
	 search_text:search_text 
 }, function(data){
  
	 document.getElementById('search_stu_result').innerHTML = data ; 
	 
  });
	 
	} 



</script>






  
  
  
  <script>
  

  $(document).ready(function(){

    $('#editStudentModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) 
  console.log(button);
  var userid = button.data('userid') 
  var username = button.data('username') 
  var mobile = button.data('mobile')
  var status = button.data('status')
  var address = button.data('address')
  var gender = button.data('gender')
  var regdate = button.data('regdate')
  var additionalInfo = button.data('additionalinfo')
  var studentclass = button.data('studentclass')
  
  var modal = $(this)
  modal.find('#editStudentFullName').val(username)
  modal.find('#editStudentAddress').val(address)
  modal.find('#editStudentGender').val(gender)
  modal.find('#editStudentClass').val(studentclass)
  modal.find('#editStudentMobile').val(mobile)
  modal.find('#editStudentStatus').val(status)
  modal.find('#editStudentAdditionalInfo').val(additionalInfo)
  modal.find('#editStudentRegDate').val(regdate)
  modal.find('#userid').val(userid);

 

});


$("#editStudent").on('click',function(){

  var invalidData=false;

  $('#editForm input,#editForm textarea').each(
    function(index){  
        var input = $(this);
        if(input.val().length===0){
          invalidData=true;
          alert('الرجاء تعبئة كل الحقول');
          console.log(input.name);
        }
    }
);

if(invalidData){
  return;
}


$.ajax({   
        type: "POST",
        data : $('#editForm').serialize(),
        cache: false,  
        url: "ajax/users.php?action=updateStudent",   
        success: function(data){
          location.reload();

        }   ,error: function (request, status, error) {
        alert(request.responseText);
    }
    });   

});



$("#savePayment").click(function() {

  var invalidData=false;


  $('#editPaymentForm input, #editPaymentForm textarea').each(
    function(index){  
        var input = $(this);
        if(input.val().length===0){
          invalidData=true;
          alert('الرجاء تعبئة كل الحقول');
          console.log(input.attr('name'));
          return false;
        }
    }
);

if(invalidData){
  return;
}


$.ajax({   
        type: "POST",
        data : $('#editPaymentForm').serialize(),
        cache: false,  
        url: "ajax/Payments.php?action=update",   
        success: function(data){
          location.reload();

        }   ,error: function (request, status, error) {
        alert(request.responseText);
    }
    }); 

    });

$('#paymentEditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  
  var paymentId = button.data('paymentid')
  var paymentAmount = button.data('paymentamount')
  var paymentNotes = button.data('paymentnotes')

  var date=new Date(button.data('paymentinsertdate'));
  var day = ("0" + date.getDate()).slice(-2);
  var month = ("0" + (date.getMonth() + 1)).slice(-2);
  var today = date.getFullYear()+"-"+(month)+"-"+(day) ;

  var modal = $(this)
  modal.find('#paymentAmount').val(paymentAmount)
  modal.find('#paymentDate').val(today)
  modal.find('#paymentNote').val(paymentNotes)
  modal.find('#inputPaymentId').val(paymentId)
});


$('#systematicCourses  input.id[type="checkbox"]').on('click',function() {
$('#selectAllSystematicCourses ').prop('checked', false);
});

$("#selectAllSystematicCourses").on('click',function(e){

var table= $(e.target).closest('table');
    $('td input:checkbox',table).prop('checked',this.checked);
});

$("#registerAllChecked ").on('click',function(e){

  var selected = new Array();
$('#systematicCourses input.id[type="checkbox"]:checked').each(function() {
    selected.push($(this).attr('id'));
});

if(selected.length===0){
  alert('الرجاء اختيار دورات للتسجيل');
  return false;
}

//selected.shift();
var arr = [];
for(var i = 0; i < selected.length; i++) {
    var obj = {}; 
    obj['courseId'] = selected[i];
    obj['courseStartDate'] = $("#startDate"+selected[i]).val();
    obj['courseEndtDate'] = $("#endDate"+selected[i]).val();
    obj['courseCost'] = $("#courseCost"+selected[i]).val();
    arr.push(obj);
}


$.ajax({   
        type: "POST",
        data : {
          studentId:'<?php echo $row_student_info['user_id']; ?>',
          courses:JSON.stringify(arr)
        },
        cache: false,  
        url: "ajax/Courses.php?action=insertCoursesForStudent",   
        success: function(data){
          location.reload();
        }   ,error: function (request, status, error) {
        alert(request.responseText);
    }
    }); 

  //  });

});


$('#systematicCourses').on('change', 'input.cost[type="number"]', function () {
    var total = 0;
    $.each($('input.cost[type=number]'),function(){
      console.log($(this));
      total=total+Number($(this).val());
      });
    $("#totalCost ").val(total);
});


$('#systematicCourses').on('change', 'input.totalCost[type="number"]', function () {
  var total_input=0;
  var courseCost=0;
  
    $.each($('input.cost[type=number]'),function(){
      console.log($(this));
      total_input=total_input+1;
      });
  
 
  if( total_input >0){
    courseCost=Number($("#totalCost").val())/total_input;
    }
    $.each($('input.cost[type=number]'),function(){
      $(this).val(courseCost);
      });
});//end of systematicCourses on change 


$('#nonsystematicCourses  input.id[type="checkbox"]').on('click',function() {
$('#nonselectAllSystematicCourses ').prop('checked', false);
});

$("#nonselectAllSystematicCourses").on('click',function(e){

var table= $(e.target).closest('table');
    $('td input:checkbox',table).prop('checked',this.checked);
});

$("#nonregisterAllChecked ").on('click',function(e){

  var selected = new Array();
$('#nonsystematicCourses input.id[type="checkbox"]:checked').each(function() {
    selected.push($(this).attr('id'));
});

if(selected.length===0){
  alert('الرجاء اختيار دورات للتسجيل');
  return false;
}

//selected.shift();
var arr = [];
for(var i = 0; i < selected.length; i++) {
    var obj = {}; 
    obj['courseId'] = selected[i];
    obj['courseStartDate'] = $("#nonstartDate"+selected[i]).val();
    obj['courseEndtDate'] = $("#nonendDate"+selected[i]).val();
    obj['courseCost'] = $("#noncourseCost"+selected[i]).val();
    arr.push(obj);
}


$.ajax({   
        type: "POST",
        data : {
          studentId:'<?php echo $row_student_info['user_id']; ?>',
          courses:JSON.stringify(arr)
        },
        cache: false,  
        url: "ajax/Courses.php?action=insertCoursesForStudent",   
        success: function(data){
          location.reload();
        }   ,error: function (request, status, error) {
        alert(request.responseText);
    }
    }); 

  //  });

});


$('#nonsystematicCourses').on('change', 'input.noncost[type="number"]', function () {
    var total = 0;
    $.each($('input.noncost[type=number]'),function(){
      total=total+Number($(this).val());
      });
    $("#nontotalCost ").val(total);
});


$('#nonsystematicCourses').on('change', 'input.nontotalCost[type="number"]', function () {
  var total_input=0;
  var courseCost=0;
  console.log('here');
    $.each($('input.noncost[type=number]'),function(){
      total_input=total_input+1;
      });
  
 
  if( total_input >0){
    courseCost=Number($("#nontotalCost").val())/total_input;
    }
    $.each($('input.noncost[type=number]'),function(){
      $(this).val(courseCost);
      });
});//end of nonsystematicCourses on change 



});//end of jquery 
  
  </script>
  


      
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($student_cources);

mysql_free_result($student_info);

mysql_free_result($all_open_courcess);

mysql_free_result($all_teatcher);

mysql_free_result($stu_avilabil_class);

mysql_free_result($all_payments);

mysql_free_result($last_add);
?>
