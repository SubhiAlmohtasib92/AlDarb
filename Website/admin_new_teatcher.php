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



$page['title'] = '?????? ???????????? ';
$page['desc'] = '?????? ?????????? ???????????? ';
 
 include('templeat_header.php');
  ?>
 


 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
#dataTable1 th,td{
  text-align:center;
}
  </style>

  <div class="row" > 






    <div data-toggle="modal" data-target="#myModal"  class="col-md-3 " style="text-align:center ;"><a class="p-3 element-box el-tablo btn btn-success" href="#">
    <h1 align="center" class="fi-plus"></h1>
             
        ??????????   ???????? ????????       </a></div>

    <br>
    <div class="col-md-12 element-box el-tablo p-2" dir="ltr" >


  

  <table border="1" class="table table-striped table-lightfont dataTable " id="dataTable1" dir="rtl" >
  <thead class="bg-dark text-white">

 
       <th>?????? ????????????</th>
       <th>??????????????</th>
        <th>?????? ???????????? </th>
        <th>    </th>
        
    </tr>
    
    </thead>
    <tbody>
    <?php do { ?>
      <tr>
         <td><?php echo $row_students_father['user_name']; ?></td>
         <td><?php echo $row_students_father['address']; ?></td>
        <td><?php echo $row_students_father['mobile']; ?></td>
            <td > 
            <div class="col md 12" style="display: flex; justify-content: center;align-items: center;">
            <div class="col md 5">
            <a class="btn btn-dark" href="=<?php echo $row_students_father['user_id']; ?>" > ?????????? ????????????         </a>  

            </div>

            <div class="col md 5">
            <a class="btn btn-dark" href="admin_teacher_cource.php?id=<?php echo $row_students_father['user_id']; ?>" >     ?????????? ????????????   </a>  

            </div>

            <div class="col md 2">

            <a id="teacherEdit"
              href="javascript:void(0)"
              style="width:100%;"
              data-toggle="modal"
              data-target="#teacherEditModal"
              data-teacherid="<?php echo $row_students_father['user_id']; ?>"
              data-teachername="<?php echo $row_students_father['user_name']; ?>"
              data-teacheraddress="<?php echo $row_students_father['address']; ?>"
              data-teachermobile="<?php echo $row_students_father['mobile']; ?>"
              > 
              <i class="fa fa-edit fa-lg"></i> </a>
            </div>

            </div>
            
            
             </td>
      </tr>
      <?php } while ($row_students_father = mysql_fetch_assoc($students_father)); ?>
  
    </tbody>
    </table>
  </div> <!--- div row ---> 







</div>





<!-- Edit Payment Modal -->

<div class="modal fade" id="teacherEditModal" tabindex="-1" role="dialog" aria-labelledby="teacherEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">??</button>
        <h5 class="modal-title" id="teacherEditLabel"  style='text-align:center;display: inline;margin: auto;'>?????????? ????????</h5>
      </div>
      <div class="modal-body">
      <div class="col-md-12" style="text-align:center;">
        <form id="editTeacherForm">
        
          <div class="form-group form-inline" >
            <label for="teacherName" class="col-sm-2 col-form-label">?????? ????????????:</label>
            <div class="col-sm-10">
            <input type="text" name="teacherName" class="form-control" id="teacherName" style="margin-right:5px;" required>
            </div>
          </div>

          <div class="form-group form-inline" >
            <label for="teacherAddress" class="col-sm-2 col-form-label">?????????? ????????????:</label>
            <div class="col-sm-10">
            <input type="text" name="teacherAddress"   class="form-control" id="teacherAddress" style="margin-right:5px;" required>
            </div>
          </div>

          <div class="form-group form-inline" >
            <label for="teacherMobile" class="col-sm-2 col-form-label">?????? ????????????:</label>
            <div class="col-sm-10">
            <input type="tel" name="teacherMobile"  maxlength="10" minlength="0" onkeypress="return onlyNumberKey(event)" class="form-control" id="teacherMobile" style="margin-right:5px;" required>
            </div>
          </div>


        

          <input id="inputTeacherId" type="hidden" class="form-control" name="inputTeacherId"  value=""/>
        </form>
        </div>
      </div>
      <div class="modal-footer" style="text-align:center;display: inline;margin: auto;">
        <button type="button" class="btn btn-danger text-white" data-dismiss="modal">??????????</button>
        <button id="saveTeacher" type="button" class="btn btn-success text-white">?????? ??????????????????</button>
      </div>
    </div>
  </div>
</div>

<!-- end of Payment Edit Modal -->

















 
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align:center;display: inline;margin: auto;">  ?????????? ????????  ???????? </h4>
      </div>
      <div class="modal-body">
        <form   autocomplete="off" action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl" >
          <table class="table" align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">?????????? ????????????</td>
              <td><input class="form-control" required="required"  type="text" name="user_name" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">?????????????? </td>
              <td><input  class="form-control" required="required"   type="text" name="address" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">??????????</td>
              <td><select name="gender" class="form-control">
                <option value="??????" <?php if (!(strcmp("??????", ""))) {echo "SELECTED";} ?>>??????</option>
                <option value="????????" <?php if (!(strcmp("????????", ""))) {echo "SELECTED";} ?>>????????</option>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">???????? ??????????</td>
              <td><input  class="form-control" required="required"  type="text" name="father_work_location" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">????????????</td>
              <td><input  class="form-control" required="required"  type="text" name="mobile" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">?????????????? ???????????? </td>
              <td><input  class="form-control" required="required"  type="text" name="additional_info" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="  ??????????   ???????? " /></td>
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


<script>


function onlyNumberKey(evt) {
         
         // Only ASCII character in that range allowed
         var ASCIICode = (evt.which) ? evt.which : evt.keyCode
         if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
             return false;
         return true;
     }


$(document).ready(function(){
$('#teacherEditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var teacherid = button.data('teacherid')
  var teachername = button.data('teachername')
  var teacheraddress = button.data('teacheraddress')
  var teachermobile = button.data('teachermobile')
  var modal = $(this)
  modal.find('#teacherName').val(teachername)
  modal.find('#teacherAddress').val(teacheraddress)
  modal.find('#teacherMobile').val(teachermobile)
  modal.find('#inputTeacherId').val(teacherid)
});


$("#saveTeacher").click(function() {

var invalidData=false;


$('#editTeacherForm input').each(
  function(index){  
      var input = $(this);
      if(input.val().length===0){
        invalidData=true;
        alert('???????????? ?????????? ???? ????????????');
        return false;
      }
  }
);

if(invalidData){
return;
}

console.log('here 1 ');
$.ajax({   
        type: "POST",
        data : $('#editTeacherForm').serialize(),
        cache: false,  
        url: "ajax/users.php?action=updateTeacher",   
        success: function(data){
         location.reload();

        }   ,error: function (request, status, error) {
        alert(request.responseText);
    }
    }); 

  });




});
</script>

<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($students_father);
?>
