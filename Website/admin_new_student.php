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
                       GetSQLValueString(-1, "int"),
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

$maxRows_last_added_students = 20;
$pageNum_last_added_students = 0;
if (isset($_GET['pageNum_last_added_students'])) {
  $pageNum_last_added_students = $_GET['pageNum_last_added_students'];
}
$startRow_last_added_students = $pageNum_last_added_students * $maxRows_last_added_students;

mysql_select_db($database_conn, $conn);
$query_last_added_students = "SELECT users.user_id, users.user_name, users.address, users.emergency_data, users.mobile, users.father_work_location, classes.class_name FROM users, classes WHERE user_type = 4 and account_status=1 AND users.stu_class = classes.class_id ORDER BY user_id DESC";
$query_limit_last_added_students = sprintf("%s LIMIT %d, %d", $query_last_added_students, $startRow_last_added_students, $maxRows_last_added_students);
$last_added_students = mysql_query($query_limit_last_added_students, $conn) or die(mysql_error());
$row_last_added_students = mysql_fetch_assoc($last_added_students);

if (isset($_GET['totalRows_last_added_students'])) {
  $totalRows_last_added_students = $_GET['totalRows_last_added_students'];
} else {
  $all_last_added_students = mysql_query($query_last_added_students);
  $totalRows_last_added_students = mysql_num_rows($all_last_added_students);
}
$totalPages_last_added_students = ceil($totalRows_last_added_students/$maxRows_last_added_students)-1;

?>
<?php 
 
 include('templeat_header.php');
  ?>
  
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"></style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css"></style>



<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

  






  
  <style>
  
  #studentsTable td,th {
    text-align:center;
  }




  </style>
  
  


  <div class="row" > 




<div class="row col-md-12">

<div  class="col-md-3" style="text-align:center ;"><a id="activeStudents" class="p-3 element-box el-tablo" style="border-style: solid;border-color: #ffcd16;" href="#">
    <h1 align="center" class="fi-page"></h1>
             
  الطلاب الحاليين     
          
          </a></div>



      

          <div  class="col-md-3" style="text-align:center ;"><a id="inactiveStudents" class="p-3 element-box el-tablo" href="javascript:void(0);" style="border-style: solid;border-color: #ffcd16;">
    <h1 align="center" class="fi-page"></h1>
             
    الطلاب القدامى    
          
          </a></div>

          <div data-toggle="modal" data-target="#myModal"  class="col-md-3" style="text-align:center ;"><a class="p-3 element-box el-tablo" style="border-style: solid;border-color: #ffcd16;" href="#">
    <h1 align="center" class="fi-plus"></h1>
             
        اضافة       طالب جديد       
          
          </a></div>

</div>

   


  <div class="col-md-12 element-box el-tablo p-2 " dir="ltr" >

  <div id="activeStudentsArea">
<h3 style="text-align:center;">الطلاب الحاليين</h3>
  <table border="1" class="table table-striped table-lightfont" id="dataTable3" dir="rtl" >
  
  <thead>
    <tr class="bg-dark text-white">

      <td>اسم الطالب </td>
      <td>العنوان</td>
      <td>معلومات الطوارئ</td>
      <td>موبايل</td>
      <td>معلومات ولي الامر</td>
      <td>الصف </td>
      
      <td>  </td>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
      <tr>

        <td><?php echo $row_last_added_students['user_name']; ?></td>
        <td><?php echo $row_last_added_students['address']; ?></td>
        <td><?php echo $row_last_added_students['emergency_data']; ?></td>
        <td><?php echo $row_last_added_students['mobile']; ?></td>
        <td><?php echo $row_last_added_students['father_work_location']; ?></td>
        <td><?php echo $row_last_added_students['class_name']; ?></td>
        
        <td>
              
              
                
              
              
                          
                
             <a href="admin_student_profile.php?id=<?php echo $row_last_added_students['user_id']; ?>" class="btn btn-dark text-white "> عرض الملف  </a> 
               
              
              
              
              
              </td>
              
              
      </tr>
      <?php } while ($row_last_added_students = mysql_fetch_assoc($last_added_students)); ?>
      </tbody>
  </table>

</div>
  <?php 

  mysql_select_db($database_conn, $conn);
  $query_last_added_students = "SELECT users.user_id, users.user_name, users.address, users.emergency_data, users.mobile, users.father_work_location, classes.class_name FROM users, classes WHERE user_type = 4 and account_status=0 AND users.stu_class = classes.class_id ORDER BY user_id DESC";
  $query_limit_last_added_students = sprintf("%s LIMIT %d, %d", $query_last_added_students, $startRow_last_added_students, $maxRows_last_added_students);
  $last_added_students = mysql_query($query_limit_last_added_students, $conn) or die(mysql_error());
  $row_last_added_students = mysql_fetch_assoc($last_added_students);

  ?>

<div id="inactiveStudentsArea" style="display:none;">
  <h3 style="text-align:center;">الطلاب القدامى</h3>
  <table border="1" class="table table-striped table-lightfont" id="dataTable2" dir="rtl" >
  
  <thead>
    <tr class="bg-dark text-white">

      <td>اسم الطالب </td>
      <td>العنوان</td>
      <td>معلومات الطوارئ</td>
      <td>موبايل</td>
      <td>معلومات ولي الامر</td>
      <td>الصف </td>
      
      <td>  </td>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
      <tr>

        <td><?php echo $row_last_added_students['user_name']; ?></td>
        <td><?php echo $row_last_added_students['address']; ?></td>
        <td><?php echo $row_last_added_students['emergency_data']; ?></td>
        <td><?php echo $row_last_added_students['mobile']; ?></td>
        <td><?php echo $row_last_added_students['father_work_location']; ?></td>
        <td><?php echo $row_last_added_students['class_name']; ?></td>
        
        <td>
              
              
                
              
              
                          
                
             <a href="admin_student_profile.php?id=<?php echo $row_last_added_students['user_id']; ?>" class="btn btn-dark text-white "> عرض الملف  </a> 
               
              
              
              
              
              </td>
              
              
      </tr>
      <?php } while ($row_last_added_students = mysql_fetch_assoc($last_added_students)); ?>
      </tbody>
  </table>
  
  </div>
  </div> 
 

</div>
















 
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class=" " align="center" style='text-align:center;display: inline;margin: auto;' > اضافة طالب    </h4>

      </div>
      <div class="modal-body">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl" autocomplete="off" >
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
              <td><select class="form-control" name="gender">
                <option value="ذكر" <?php if (!(strcmp("ذكر", ""))) {echo "SELECTED";} ?>>ذكر</option>
                <option value="انثى" <?php if (!(strcmp("انثى", ""))) {echo "SELECTED";} ?>>انثى</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">الصف</td>
              <td>
              
              <select name="stu_class" required  class="form-control" >
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
              <td><input type="submit" class="btn btn-success" value="  اضافة طالب " /></td>
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
      
      <h3 align="center" > تسجيل الطالب في الدورات المنهجية  </h3>
      </div>
      <div class="modal-body">
      
      
      <?php if ( $totalRows_last_added >0 ){
		  
		  $colname_class_sections = "-1";
if (isset($row_last_added['stu_class'])) {
  $colname_class_sections = $row_last_added['stu_class'];
}
mysql_select_db($database_conn, $conn);
$query_class_sections = sprintf("SELECT * FROM courses WHERE class_id = %s", GetSQLValueString($colname_class_sections, "int"));
$class_sections = mysql_query($query_class_sections, $conn) or die(mysql_error());
$row_class_sections = mysql_fetch_assoc($class_sections);
$totalRows_class_sections = mysql_num_rows($class_sections);



?>


        <a class="close" data-dismiss="modal">×</a>
       <!-- <h5> تسجيل الطالب في الدورات   </h5>
        
        <h3 align="center" > <?php echo $row_last_added['user_name']; ?></h3>
        
        <hr> -->





 
        
        

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
	  }
	  ?>
        </table>
        
        
        
        
             <form > 
        <input class="form-control" placeholder="التكلفة الاجمالية " type="text" value="<?php echo $totalcost ; ?>" onKeyUp="change_values(this.value)" >
        
         </form> 
         
         
         
  
    
      
      
      <a href="admin_student_profile.php?id=<?php echo $row_last_added['user_id']; ?>" class="btn btn-dark text-white "> عرض الملف  </a>
      
      
        
        
        
    </div>


</div>




<script type="text/javascript" > 


function reg_stu_on_cource(c_id , cost , result_id  ) {
	
	
	 
	$.post("ajax/reg_stu_cource.php", {
	 c_id:c_id,
	 cost:cost, 
	 stu_id: <?php echo $row_last_added['user_id']; ?>
	 
 }, function(data){
	 
	 
	 
	 document.getElementById(result_id).innerHTML = data ; 
	  
 	
  });
	 
	} 



</script>












<script type="text/javascript" > 

  function change_values(x){
	  
	 var checkboxes = $('input:checkbox:checked').length;
 
	$('.cost').prop("value", x/checkboxes); 
 
 
   
	
	  
	  } 


</script>

<script>

$(document).ready(function(){
    $('#dataTable3, #dataTable2 ').dataTable(
      { dom: 'Bfltip',
        "pageLength": 5,
        "lengthMenu": [[5, 10, 15,25,50, -1], [5, 10,15,25, 50, "All"]],
        "oLanguage": {
   "sSearch": "ابحث:"
 }
      }
    );


  $("#activeStudents").on('click',function(){

    $("#inactiveStudentsArea").hide();
    $("#activeStudentsArea").show();

  });

  $("#inactiveStudents").on('click',function(){
    $("#activeStudentsArea").hide();
    $("#inactiveStudentsArea").show();

  });

});

</script>
  
  
  
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($students_father);

mysql_free_result($all_class);

mysql_free_result($last_added);

mysql_free_result($class_sections);

mysql_free_result($last_added_students);

mysql_free_result($user_info);
?>
  
  
  
  <?php if ($success1 == 1 ) { ?> 
  
  
  <script type="text/javascript">
    $(window).on('load', function() {
        $('#stu_reg').modal('show');
    });
</script>





<?php } ?> 