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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE courses SET c_status=%s WHERE c_id=%s",
                       GetSQLValueString($_POST['c_status'], "int"),
                       GetSQLValueString($_POST['c_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	
	
	
	
	
	$colname_selected_class = "-1";
if (isset($_POST['class_id'])) {
  $colname_selected_class = $_POST['class_id'];
}
mysql_select_db($database_conn, $conn);
$query_selected_class = sprintf("SELECT * FROM classes WHERE class_id = %s", GetSQLValueString($colname_selected_class, "int"));
$selected_class = mysql_query($query_selected_class, $conn) or die(mysql_error());
$row_selected_class = mysql_fetch_assoc($selected_class);
$totalRows_selected_class = mysql_num_rows($selected_class);

$colname_sellected_material = "-1";
if (isset($_POST['material_id'])) {
  $colname_sellected_material = $_POST['material_id'];
}
mysql_select_db($database_conn, $conn);
$query_sellected_material = sprintf("SELECT * FROM material WHERE id = %s", GetSQLValueString($colname_sellected_material, "int"));
$sellected_material = mysql_query($query_sellected_material, $conn) or die(mysql_error());
$row_sellected_material = mysql_fetch_assoc($sellected_material);
$totalRows_sellected_material = mysql_num_rows($sellected_material);



  $insertSQL = sprintf("INSERT INTO courses (c_name, c_type, class_id, material_id, c_hours, c_teatcher, add_by, c_status, insert_date, c_cost) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW() , %s)",
                       GetSQLValueString($_POST['c_name'] , "text"),
                       GetSQLValueString($_POST['c_type'], "int"),
                       GetSQLValueString($_POST['class_id'], "int"),
                       GetSQLValueString($_POST['material_id'], "int"),
                       GetSQLValueString($_POST['c_hours'], "int"),
                       GetSQLValueString($_POST['c_teatcher'], "int"),
                       GetSQLValueString($_POST['add_by'], "int"),
                       GetSQLValueString($_POST['c_status'], "int"),
                        
                       GetSQLValueString($_POST['c_cost'], "double"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_all_teatcher = "SELECT * FROM users WHERE user_type = 2";
$all_teatcher = mysql_query($query_all_teatcher, $conn) or die(mysql_error());
$row_all_teatcher = mysql_fetch_assoc($all_teatcher);
$totalRows_all_teatcher = mysql_num_rows($all_teatcher);

mysql_select_db($database_conn, $conn);
$query_all_classes = "SELECT * FROM classes";
$all_classes = mysql_query($query_all_classes, $conn) or die(mysql_error());
$row_all_classes = mysql_fetch_assoc($all_classes);
$totalRows_all_classes = mysql_num_rows($all_classes);

mysql_select_db($database_conn, $conn);
$query_all_matiral = "SELECT * FROM material";
$all_matiral = mysql_query($query_all_matiral, $conn) or die(mysql_error());
$row_all_matiral = mysql_fetch_assoc($all_matiral);
$totalRows_all_matiral = mysql_num_rows($all_matiral);

mysql_select_db($database_conn, $conn);
$query_activ_cources = "SELECT courses.c_id, courses.c_name, courses.c_hours, users.user_name, courses.c_status, courses.insert_date, courses.c_cost  FROM courses, users WHERE courses.c_type=2 AND courses.c_teatcher = users.user_id ORDER BY courses.c_status DESC , courses.insert_date DESC";
$activ_cources = mysql_query($query_activ_cources, $conn) or die(mysql_error());
$row_activ_cources = mysql_fetch_assoc($activ_cources);
$totalRows_activ_cources = mysql_num_rows($activ_cources);



if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<h3 align="center" > الدورات الامنهجية  </h3> 


  
  <!-- Trigger the modal with a button -->
<button type="button" class="btn btn-dark   " data-toggle="modal" data-target="#myModal">   اضافة دورة جديدة </button>



<table border="1" class="table table-hover table-striped table-lightfont dataTable "  dir="rtl" >
<thead class="bg-dark text-white">
  
     <th>اسم الدورة </th>
    <th>عدد الساعات </th>
    <th>المدرس</th>
     <th>تاريخ الاضافة </th>
    <th>التكلفة </th>
     <th>برنامج الدورة  </th>
        <th>الطلاب    </th>
    <th>حالة الدورة   </th>
    
    
      <th>      </th>
    
    
    
    
 </thead>
 <tbody>
  <?php do { ?>
    <tr>
       <td <?php if( $row_activ_cources['c_status']==0 ) { echo 'class="bg-danger "' ; } ?> ><?php echo $row_activ_cources['c_name']; ?></td>
      <td><?php echo $row_activ_cources['c_hours']; ?></td>
      <td><?php echo $row_activ_cources['user_name']; ?></td>
       <td><?php echo $row_activ_cources['insert_date']; ?></td>
      <td><?php echo $row_activ_cources['c_cost']; ?></td>
      
      
      
          
         
            <td><a href="admin_Systematic_course_program.php?id=<?php echo $row_activ_cources['c_id']; ?>" target="_new"  class="btn btn-dark btn-sm  " >  البرنامج </a></td>
            
            
             
            <td><a href="admin_cource_students.php?id=<?php echo $row_activ_cources['c_id']; ?>" target="_new"  class="btn btn-dark btn-sm  " >  
            
                  <?php 
		 $colname_regesters_count = "-1";
if (isset($row_activ_cources['c_id'])) {
  $colname_regesters_count = $row_activ_cources['c_id'];
}
mysql_select_db($database_conn, $conn);
$query_regesters_count = sprintf("SELECT * FROM cource_students WHERE c_id = %s", GetSQLValueString($colname_regesters_count, "int"));
$regesters_count = mysql_query($query_regesters_count, $conn) or die(mysql_error());
$row_regesters_count = mysql_fetch_assoc($regesters_count);
$totalRows_regesters_count = mysql_num_rows($regesters_count);

echo $totalRows_regesters_count ; 
?>
      
      
       </a></td>
                
            
            
            
            
            
               <td><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
                 <select onchange="this.form.submit()"  name="c_status">
                       <option value="1" <?php if (!(strcmp(1, htmlentities($row_activ_cources['c_status'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>شعبة مفتوحة</option>
                       <option value="0" <?php if (!(strcmp(0, htmlentities($row_activ_cources['c_status'], ENT_COMPAT, '')))) {echo "SELECTED";} ?>>شعبة مغلقة</option>
                     </select>
                 
                  <input type="hidden" name="MM_update" value="form2" />
                 <input type="hidden" name="c_id" value="<?php echo $row_activ_cources['c_id']; ?>" />
               </form>
                </td>
               
               
               
               
                   <td> 
            
            <a href="admin_edit_course.php?id=<?php echo $row_activ_cources['c_id']; ?>" class="btn btn-success btn-sm text-white " >تعديل  </a>
            
            </td>  
             
             
             
    </tr>
    <?php } while ($row_activ_cources = mysql_fetch_assoc($activ_cources)); ?>
    </tbody>
</table>



















<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" dir="rtl">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" align="center">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" align="center">  فتح دورة منهجية جديدة   </h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <table align="center" class="table">
             
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">اسم الدورة :</td>
              <td>
              
              
              <input type="text" required="required" class="form-control" name="c_name" value="" />
              
              
              </td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">عدد ساعات :</td>
              <td><input  class="form-control" required   type="text" name="c_hours" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">المدرس:</td>
              <td><select  class="form-control" required  name="c_teatcher">
              <option></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_all_teatcher['user_id']?>" ><?php echo $row_all_teatcher['user_name']?></option>
                <?php
} while ($row_all_teatcher = mysql_fetch_assoc($all_teatcher));
?>
              </select></td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">تكلفة الافتراضية :</td>
              <td><input type="text" class="form-control" required  name="c_cost" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="  فتح شعبة دورة لا منهجية " /></td>
            </tr>
          </table>
          
          <input type="hidden" name="c_type" value="2" />
          <input type="hidden" name="add_by" value="<?php echo $_sesstion['user_id'];?>" />
          <input type="hidden" name="c_status" value="1" />
          <input type="hidden" name="insert_date" value="" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        <p>&nbsp;</p>
      </div>
 
    </div>

  </div>
</div>
<?php 
//
  include('templeat_footer.php'); 
 
  
?>