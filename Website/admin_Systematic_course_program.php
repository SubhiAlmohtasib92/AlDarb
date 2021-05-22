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
  $insertSQL = sprintf("INSERT INTO cource_program (cource_id, p_day, from_time, to_time, room) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cource_id'], "int"),
                       GetSQLValueString($_POST['p_day'], "text"),
                       GetSQLValueString($_POST['from_time'], "date"),
                       GetSQLValueString($_POST['to_time'], "date"),
                       GetSQLValueString($_POST['room'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST['delete_id'])) && ($_POST['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cource_program WHERE id=%s",
                       GetSQLValueString($_POST['delete_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_Recordset1 = sprintf("SELECT * FROM courses WHERE c_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $conn) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_conn, $conn);
$query_all_rooms = "SELECT * FROM rooms";
$all_rooms = mysql_query($query_all_rooms, $conn) or die(mysql_error());
$row_all_rooms = mysql_fetch_assoc($all_rooms);
$totalRows_all_rooms = mysql_num_rows($all_rooms);

mysql_select_db($database_conn, $conn);
$query_all_dayes = "SELECT * FROM days";
$all_dayes = mysql_query($query_all_dayes, $conn) or die(mysql_error());
$row_all_dayes = mysql_fetch_assoc($all_dayes);
$totalRows_all_dayes = mysql_num_rows($all_dayes);

$colname_cource_program = "-1";
if (isset($_GET['id'])) {
  $colname_cource_program = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_cource_program = sprintf("SELECT cource_program.id, cource_program.p_day, cource_program.from_time, cource_program.to_time, rooms.room_name FROM cource_program, rooms WHERE cource_id = %s AND cource_program.room = rooms.room_id", GetSQLValueString($colname_cource_program, "int"));
$cource_program = mysql_query($query_cource_program, $conn) or die(mysql_error());
$row_cource_program = mysql_fetch_assoc($cource_program);
$totalRows_cource_program = mysql_num_rows($cource_program);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <h3 align="center" > 
  
  تحديد برنامج <br />
 
  
  
  <?php echo $row_Recordset1['c_name']; ?>
  
  
  </h3> 
  
  <hr />

  
  
  
  <div class="row" > 
  
  
  <div class="col-md-4" >
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" class="table ">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">كل يوم :</td>
          <td><select class="form-control" required  name="p_day">
          <option></option>
            <?php 
do {  
?>
            <option value="<?php echo $row_all_dayes['name']?>" ><?php echo $row_all_dayes['name']?></option>
            <?php
} while ($row_all_dayes = mysql_fetch_assoc($all_dayes));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">من الساعة:</td>
          <td><input type="time" name="from_time" value="" size="32" class="form-control" required="required"  /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">الى الساعة:</td>
          <td><input  class="form-control" required="required"  type="time" name="to_time" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">القاعة :</td>
          <td><select name="room"  class="form-control" required >
          <option></option>
          
            <?php 
do {  
?>
            <option value="<?php echo $row_all_rooms['room_id']?>" ><?php echo $row_all_rooms['room_name']?></option>
            <?php
} while ($row_all_rooms = mysql_fetch_assoc($all_rooms));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="اضافة الى البرنامج " /></td>
        </tr>
      </table>
      <input type="hidden" name="cource_id" value="<?php echo $row_Recordset1['c_id']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
  </div>
  
  <div class="col-md-8" >
    <?php if ($totalRows_cource_program > 0) { // Show if recordset not empty ?>
      <table border="1" class="table ">
        <tr class="bg-dark text-white" >
          <td>اليوم</td>
          <td>من تاريخ</td>
          <td>الى تاريخ</td>
          <td>القاعة </td>
          
          <td>  </td>
          
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_cource_program['p_day']; ?></td>
            <td><?php echo $row_cource_program['from_time']; ?></td>
            <td><?php echo $row_cource_program['to_time']; ?></td>
            <td><?php echo $row_cource_program['room_name']; ?></td>
            
            <td>
              
              
              <form action="" method="post" > 
                <input type="hidden" name="delete_id" value="<?php echo $row_cource_program['id']; ?>"  /> 
                <input type="submit" class="btn btn-danger btn-sm" value="X"  /> 
              </form>
            </td>
            
            
          </tr>
          <?php } while ($row_cource_program = mysql_fetch_assoc($cource_program)); ?>
      </table>
      <?php }else{ // Show if recordset not empty ?>
      
      <div class="alert alert-info" > 
      <h3 align="center" > لم يتم تحديد البرنامج حتى اللحظة  </h3> 
      </div>
      
      <?php } ?> 
<?php 
	
 
	
	 ?> 
  </div>
  
  
  
  
  </div>
  
  
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($Recordset1);

mysql_free_result($all_rooms);

mysql_free_result($all_dayes);

mysql_free_result($cource_program);
?>
