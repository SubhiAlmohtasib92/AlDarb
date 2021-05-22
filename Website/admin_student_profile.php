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



if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
 <h3 align="center" > ملف الطالب :  <?php echo $row_student_info['user_name']; ?> </h3> 
  
  <hr />
  
  
  
   <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-success text-white"  > التسجيل في دورة جديدة  </a>
   
   
   
   
    <a onclick="cr_new('<?php echo $row_student_info['user_id']; ?>')" data-toggle="modal" data-target="#myModal2" class="btn btn-success text-white"  > اضافة سند قبض   </a>
    
    
    
    
    
  
   <h3 align="right" > 
المعلوامت الشخصية </h3> 
  
  
  <table width="100%" border="1" class="table">
  <tr class="bg-dark text-white">
     <td>رقم الهاتف</td>
    <td>العنوان</td>
    <td>الجنس</td>
    <td>تاريخ الالتحاق</td>
    <td>معلومات للطارئ </td>
  </tr>
  <tr>
     <td><?php echo $row_student_info['mobile']; ?></td>
    <td><?php echo $row_student_info['address']; ?></td>
    <td><?php echo $row_student_info['gender']; ?></td>
    <td><?php echo $row_student_info['reg_date']; ?></td>
    <td> <?php echo $row_student_info['additional_info']; ?></td>
  </tr>
</table>

  
  
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
$query_stu_courcess = sprintf("SELECT cource_students.id, cource_students.c_id, cource_students.cost, cource_students.insert_date, courses.c_name FROM cource_students, courses WHERE stu_id = %s AND cource_students.c_id = courses.c_id", GetSQLValueString($colname_stu_courcess, "int"));
$stu_courcess = mysql_query($query_stu_courcess, $conn) or die(mysql_error());
$row_stu_courcess = mysql_fetch_assoc($stu_courcess);
$totalRows_stu_courcess = mysql_num_rows($stu_courcess);	   
	    ?> 
       
       <table border="1" class="table ">
         <tr class="bg-dark text-white ">
         
         <td>الدورة </td>
            
           <td>تاريخ التسجيل </td>
           <td>التكلفة</td>


            
           <td>          </td>
         </tr>
         <?php
		 
		 $total_cost = 0 ; 
		 $total_payed= 0 ; 
		 
		 
		  do { ?>
           <tr>
           
           <td><?php echo $row_stu_courcess['c_name']; ?></td>
          
              
             <td><?php echo $row_stu_courcess['insert_date']; ?></td>
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
           
           <td colspan="2" > </td> 
           
           <td >
           <?php echo $total_cost ; ?>  </td> 
           
         
         
           <td   >
           
          
           
           
           
            </td> 
           </tr> 
           
       </table>
       
       
       
       
       
       <hr />

       
       
       
<h3 align="right" > 
الدفعات  
  

</h3>
<?php if ($totalRows_all_payments > 0) { // Show if recordset not empty ?>
  <table border="1" class="table">
    <tr>
      
      <td>التاريخ</td>
      <td>المبلغ</td>
      
      <td>وذلك عن </td> 
      
      <td>
        </td>
    </tr>
    <?php
  
  
  $sum_payment =0; 
  
   do { ?>
      <tr>
        
        <td><?php echo $row_all_payments['date_insert']; ?></td>
        <td><?php echo $row_all_payments['mony'];
	  
	  $sum_payment+=$row_all_payments['mony'] ?></td>
        
        <td><?php echo $row_all_payments['notes']; ?></td> 
        
        <td>
          <a href="cach_print.php?id=<?php echo $row_all_payments['id']; ?>" class="btn btn-dark text-white "> طباعة سند القبض  </a>
        </td>
        
              <td>
          <a href="cach_edit.php?id=<?php echo $row_all_payments['id']; ?>" class="btn btn-dark text-white ">     تعديل  </a>
        </td>
        
        
      </tr>
      <?php } while ($row_all_payments = mysql_fetch_assoc($all_payments)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>




<div class="col-md-3">


<div class="card text-white bg-info mb-3" style="max-width: 18rem;">

  <div class="card-body">
    <h5 class="card-title" align="center">الرصيد </h5>
   
   
   <table width="100%" border="1" dir="rtl" class="table">
  <tr>
    <td>مجموع الذمم</td>
    <td><?php echo $total_cost ; ?></td>
  </tr>
  <tr>
    <td>مجموع الدفعات</td>
    <td><?php echo $sum_payment ;?></td>
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

























<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
      
      
      
      
      
      
      
      <ul class="nav nav-tabs">
  <li class="active"><a class="btn  btn-dark text-white m-2" data-toggle="tab" href="#home">دورات منهجية   </a></li>
  
   <li class="active"><a class="btn  btn-dark text-white m-2" data-toggle="tab" href="#home2">دورات لا منهجية وتاسيس </a></li>
  
  
  <li><a  class="btn  btn-dark text-white m-2"   data-toggle="tab" href="#menu1">دورة صعوبة التعلم </a></li>
 </ul>

<div class="tab-content">


  <div id="home" class="tab-pane fade in active"> التسجيل في دورات منهجية
  
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


  
  
  
  
  
  
  
  
  
  
  <table class="table " border="1">
     
          <?php 
		  
		  $totalcost = 0 ; 
		  
		  do {
			  
			  $totalcost+=$row_class_sections['c_cost'] ; 
			  
			   ?>
               
               <form action="" method="post"  > 
            <tr>
              <td>
              
              <input onChange="change_values()" type="checkbox" checked name="">
              
              <input  type="hidden" name="c_id" value="<?php echo $row_class_sections['c_id']; ?>" size="32" /></td>
              <td><?php echo $row_class_sections['c_name']; ?></td>
              
              <td><input style="width:70px; " width="60" type="text" class="cost" name="cost" value="<?php echo $row_class_sections['c_cost']; ?>" size="32" /> </td>
            
            
            <td>
            
            
            <div id="r_<?php echo $row_class_sections['c_id']; ?>" > </div> 
             <button type="button"   class="btn btn-primary btn-sm " onClick="reg_stu_on_cource('<?php echo $row_class_sections['c_id']; ?>', cost.value ,'r_<?php echo $row_class_sections['c_id']; ?>')" > تسجيل  </button> </td>
            
            </tr>
            
            </form>
            <?php } while ($row_class_sections = mysql_fetch_assoc($class_sections)); 
	  
	  
	  ?>
        </table>
        
        
        
             <form > 
        <input class="form-control" placeholder="التكلفة الاجمالية " type="text" value="<?php echo $totalcost ; ?>" onKeyUp="change_values(this.value)" >
        
         </form> 
         
         
         
  
    
      
      
      <a href="admin_student_profile.php?id=<?php echo $row_last_added['user_id']; ?>" class="btn btn-dark text-white "> عرض الملف  </a>
      
      
        
         
   
  
</div>


  <div id="home2" class="tab-pane fade ">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <table align="center" dir="rtl" class="table ">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الدورة التدريبية :</td>
              <td><select name="c_id" class="form-control" required >
              <option></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_all_open_courcess['c_id']?>" ><?php echo $row_all_open_courcess['c_name']?> - (<?php echo $row_all_open_courcess['c_cost']; ?> شيكل )</option>
                <?php
} while ($row_all_open_courcess = mysql_fetch_assoc($all_open_courcess));
?>
              </select></td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">التكلفة :</td>
              <td><input class="form-control" required="required"  type="text" name="cost" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="   تسجيل الطالب " /></td>
            </tr>
          </table>
          <input type="hidden" name="stu_id" value="<?php echo $row_student_info['user_id']; ?>" />
          <input type="hidden" name="insert_date" value="" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        <p>&nbsp;</p>
        
        
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
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">  تسجيل دفعة جديدة </h4>
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

function cr_new_add(user_id , cource_id , father_id , c_value , notes ){
	
		
		 
		 if (c_value == '' ) {
			 
			 alert ('الرجاء ادخال المبلغ ') ;  
			 
			 }else {  
		 
 document.getElementById('result2').innerHTML = '' ; 
	
 $.post("ajax/cr_step_get_mony.php", {
	 user_id: user_id ,
	 cource_id: cource_id ,
	 father_id: father_id,
	   c_value : c_value , 
	   notes:notes 
	 
 }, function(data){
	 
	 
	 
	 document.getElementById('result2').innerHTML = data ; 
	 
	 get_father_stu(father_id)
 	
  });
	
	}
} 



</script>







<script type="text/javascript" > 


function reg_stu_on_cource(c_id , cost , result_id  ) {
	
	
	 
	$.post("ajax/reg_stu_cource.php", {
	 c_id:c_id,
	 cost:cost, 
	 stu_id: <?php echo $row_student_info['user_id']; ?>
	 
 }, function(data){
	 
	 
	 
	 document.getElementById(result_id).innerHTML = data ; 
	  
 	
  });
	 
	} 



</script>






<script type="text/javascript" > 


function search_stu(search_text ) {
	
	
	 
	$.post("ajax/search_stu.php", {
	 search_text:search_text 
 }, function(data){
  
	 document.getElementById('search_stu_result').innerHTML = data ; 
	 
  });
	 
	} 



</script>





<script type="text/javascript" > 

  function change_values(x){
	  
	 var checkboxes = $('input:checkbox:checked').length;
 
	$('.cost').prop("value", x/checkboxes); 
 
 
   
	
	  
	  } 


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
