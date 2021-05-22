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

mysql_select_db($database_conn, $conn);
$query_all_fathers = "SELECT * FROM users WHERE user_type = 3";
$all_fathers = mysql_query($query_all_fathers, $conn) or die(mysql_error());
$row_all_fathers = mysql_fetch_assoc($all_fathers);
$totalRows_all_fathers = mysql_num_rows($all_fathers);

 

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
  
  <form >  
  
  <h3 align="right" > 
  
  اختر ولي الامر 
    
    
    </h3> 
    
    
    
 <select class="form-control " name="from_father" onchange="get_father_stu(this.value)">
 <option></option>
   <?php
do {  
?>
   <option value="<?php echo $row_all_fathers['user_id']?>"><?php echo $row_all_fathers['user_name']?></option>
   <?php
} while ($row_all_fathers = mysql_fetch_assoc($all_fathers));
  $rows = mysql_num_rows($all_fathers);
  if($rows > 0) {
      mysql_data_seek($all_fathers, 0);
	  $row_all_fathers = mysql_fetch_assoc($all_fathers);
  }
?>
 </select>
 
 </form> 





<div id="result" > </div>








<!--

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">By_user:</td>
      <td><input type="text" name="by_user" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">From_father:</td>
      <td></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">To_student:</td>
      <td><input type="text" name="to_student" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">For_cource:</td>
      <td><input type="text" name="for_cource" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mony:</td>
      <td><input type="text" name="mony" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date_insert:</td>
      <td><input type="text" name="date_insert" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Notes:</td>
      <td><input type="text" name="notes" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>

 -->





<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

function cr_new(user_id , cource_id , father_id ){
	
		 document.getElementById('result2').innerHTML = '' ; 

	
 $.post("ajax/cr_step_get_mony.php", {
	 user_id: user_id ,
	 cource_id: cource_id ,
	 father_id: father_id  
	 
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
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_fathers);

mysql_free_result($alll_fathers);
?>
