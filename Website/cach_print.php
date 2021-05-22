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

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_Recordset1 = sprintf("SELECT catch_receipt.id, users.user_name, catch_receipt.mony, catch_receipt.date_insert, catch_receipt.notes FROM catch_receipt, users WHERE id = %s AND catch_receipt.to_student = users.user_id ", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $conn) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
  
  
    <script language="javascript">
        function printdiv(printpage) {
            var headstr = "<html><head><title></title></head><body><br /><br /><br /><br /><br /><br />";
            var footstr = "</body>";
            var newstr = document.all.item(printpage).innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr + newstr + footstr;
            window.print();
            document.body.innerHTML = oldstr;
            return false;
        }
    </script>
  
  
      <input name="b_print" type="button" class="ipt" onClick="printdiv('div_print');" value=" طباعة  ">



  <div id="div_print" dir="rtl">
  
  
  
  <center > 
  
  
  <h1 align="center" > سند قبض  ( <?php echo $row_Recordset1['id']; ?> ) </h1> 
  
 
 <hr />



<table class="table " width="100%" border="1" dir="rtl" cellpadding="5" cellspacing="5">
  <tr>
    <td> وصلني من الطالب :</td>
    <td><?php echo $row_Recordset1['user_name']; ?></td>
  </tr>
  <tr>
    <td>مبلغ</td>
    <td><?php echo $row_Recordset1['mony']; ?> شيكل فقط  </td>
  </tr>
  <tr>
    <td>وذلك عن :</td>
    <td> <?php echo $row_Recordset1['notes']; ?></td>
  </tr>
    <tr>
    <td>بتاريخ  :</td>
    <td> <?php echo $row_Recordset1['date_insert']; ?>  </td>
  </tr>
  
</table>

   
 
 
 
 </center> 
 
  
  
   </div>

  
  
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($Recordset1);
?>
