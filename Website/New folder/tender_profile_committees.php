<?php require_once('Connections/conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

?>
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
  $insertSQL = sprintf("INSERT INTO committees (Committee_name, Committee_insert_by, Committee_insert_date , tender_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['Committee_name'], "text"),
                       GetSQLValueString($_SESSION['user_id'], "int"),
                       GetSQLValueString($_POST['Committee_insert_date'], "date"),
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
    header("location: tender_profile_committees.php?id=".$_GET['id']) ; 
	
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO committees_members (committee_id, member_name, member_info, insert_by, insert_date) VALUES (%s, %s, %s, %s, NOW())",
                       GetSQLValueString($_POST['committee_id'], "int"),
                       GetSQLValueString($_POST['member_name'], "text"),
                       GetSQLValueString($_POST['member_info'], "text"),
                       GetSQLValueString($_SESSION['user_id'], "int") );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  header("location: tender_profile_committees.php?id=".$_GET['id']) ; 
}

if ((isset($_POST['delete_comm_id'])) && ($_POST['delete_comm_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM committees WHERE id=%s",
                       GetSQLValueString($_POST['delete_comm_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

if ((isset($_POST['delete_member_id'])) && ($_POST['delete_member_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM committees_members WHERE id=%s",
                       GetSQLValueString($_POST['delete_member_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());
}

$colname_tender_info = "-1";
if (isset($_GET['id'])) {
  $colname_tender_info = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_info = sprintf("SELECT id, tender_number, tender_name FROM tenders WHERE id = %s", GetSQLValueString($colname_tender_info, "int"));
$tender_info = mysql_query($query_tender_info, $conn) or die(mysql_error());
$row_tender_info = mysql_fetch_assoc($tender_info);
$totalRows_tender_info = mysql_num_rows($tender_info);

mysql_select_db($database_conn, $conn);
$query_all_commetiee_type = "SELECT * FROM committees_type";
$all_commetiee_type = mysql_query($query_all_commetiee_type, $conn) or die(mysql_error());
$row_all_commetiee_type = mysql_fetch_assoc($all_commetiee_type);
$totalRows_all_commetiee_type = mysql_num_rows($all_commetiee_type);

$colname_tender_comm = "-1";
if (isset($_GET['id'])) {
  $colname_tender_comm = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_tender_comm = sprintf("SELECT committees.id, committees_type.Committee_type, committees.Committee_insert_date FROM committees, committees_type WHERE tender_id = %s AND committees.Committee_name = committees_type.id", GetSQLValueString($colname_tender_comm, "int"));
$tender_comm = mysql_query($query_tender_comm, $conn) or die(mysql_error());
$row_tender_comm = mysql_fetch_assoc($tender_comm);
$totalRows_tender_comm = mysql_num_rows($tender_comm);


 
//


$page['title'] = 'اللجان    ';
$page['desc'] = 'يمكن من خلال هذه الصفحة تعيين اللجان للعطاء  ';
 
 include('templeat_header.php');
  ?>
  
  
  
  
    
  
     
  
     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/tender_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
         

<div class="row  ">
  <div class="col-md-4  ">
    <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded  text-dark ">
      <div class="card-body   ">
        <h4 class="" align="center"> <?php echo $row_tender_info['tender_name']; ?></h4>
        
        
        
        <br />


<h3 align="center" > 

اضافة لجنة جديدة 
</h3>
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        
        
        اللجنة
        
        <a href="tender_committees_type.php" style="color:#FF0000; font-size:10px;" > ( انواع اللجان  ) </a>
        <br />
<select class="form-control" name="Committee_name"  required="required"  >
                <option value=""></option>
                <?php
do {  
?>
                <option value="<?php echo $row_all_commetiee_type['id']?>"><?php echo $row_all_commetiee_type['Committee_type']?></option>
                <?php
} while ($row_all_commetiee_type = mysql_fetch_assoc($all_commetiee_type));
  $rows = mysql_num_rows($all_commetiee_type);
  if($rows > 0) {
      mysql_data_seek($all_commetiee_type, 0);
	  $row_all_commetiee_type = mysql_fetch_assoc($all_commetiee_type);
  }
?>
              </select>
              
              <br />

        
     تاريخ اللجنة
     



     
       <input class="form-control" required="required"  type="date" name="Committee_insert_date" value="" />
             
             
       
       <br />

 
 <input type="submit" value="  اضافة لجنة " />
 
           
           
          <input type="hidden" name="Committee_insert_by" value="" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
  <div class="col-md-8">
  
  
  
    
              <?php if ($totalRows_tender_comm > 0) { // Show if recordset not empty ?>
<?php do { ?>
  <div class="main-card mb-3 card shadow p-3 mb-5 bg-white rounded ">
    
    <h3 align="right" >
   <a href="PDF/print/committees.php?id=<?php echo $row_tender_comm['id']; ?>" target="_new" >  
    <i class="fa fa-print" ></i> 
    </a>
    </h3>
    
    
    
    
    
    
    
    <div class="card-body" dir="rtl" align="right">
      
      
      <table width="100% " > 
        
        <tr> 
          
          <td><h4  align="right"  > <?php echo $row_tender_comm['Committee_type']; ?></h4></td>
          <td>
          
          تعقد بتاريخ 
            <?php echo $row_tender_comm['Committee_insert_date']; ?>
            
            </td>
          
          <td  align="left" >
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            <div class="btn-actions-pane-left"> 
            
            <div class="dropdown">
              <a href="#" class="dropdown-toggle btn btn-primary " data-toggle="dropdown" aria-expanded="false">
              
              <span class="fa fa-cog fa-fw"></span> خيارات  </a>
              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 33px, 0px);">
              
              
              
                <a href="tender_profile_committees_edit.php?c_id=<?php echo $row_tender_comm['id']; ?>&id=<?php echo $row_tender_info['id']; ?>" class="dropdown-item">تعديل     </a>  
                
                 
               
                
                
           <form action="" method="post"  onsubmit="return confirm('هل انت متاكد من حذف اللجنة (<?php echo $row_tender_comm['Committee_type']; ?>)');"> 
              <input type="hidden" name="delete_comm_id" value="<?php echo $row_tender_comm['id']; ?>"   />
              <input type="submit" value="حذف اللجنة " class="btn btn-danger btn-sm form-control"    /> 
              </form>
                
                
                
                
              </div>
              </div>
            
            <br>

            
            </div>
            
            
            
            
            
             
            
            </td>
          
          </tr></table>
      
      
      
      
      
      <hr />
      
      
      
      <div class="row" > 
        
        <div class="col-md-4" align="center" >
          
          اعضاء اللجنة 
          
          <hr />
          <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
            
            
            اسم العضو 
            <input class="form-control" required="required"  type="text" name="member_name" value="" size="32" />
            
            <br />
            معلومات اضافية 
            
            <textarea  class="form-control" required="required"   name="member_info" cols="32"></textarea>
            
            
            <input type="submit" value="اضافة اعضاء للجنة " />
            
            
            
            
            
            
            <input type="hidden" name="committee_id" value="<?php echo $row_tender_comm['id']; ?>" />
            <input type="hidden" name="MM_insert" value="form2" />
            </form>
          
          </div>
        <div class="col-md-8" >
        
     <?php 
	 
	 $colname_commeette_member = "-1";
if (isset($row_tender_comm['id'])) {
  $colname_commeette_member = $row_tender_comm['id'];
}
mysql_select_db($database_conn, $conn);
$query_commeette_member = sprintf("SELECT * FROM committees_members WHERE committee_id = %s", GetSQLValueString($colname_commeette_member, "int"));
$commeette_member = mysql_query($query_commeette_member, $conn) or die(mysql_error());
$row_commeette_member = mysql_fetch_assoc($commeette_member);
$totalRows_commeette_member = mysql_num_rows($commeette_member);



 ?>
     <?php if ($totalRows_commeette_member > 0) { // Show if recordset not empty ?>
  <table border="1" width="100%" class="table">
    <tr class="bg-primary text-white">
      
      <td>اسم العضو</td>
      <td>معلومات العضو</td>
      <td>تاريخ الاضافة </td>
      
      <td > </td>
      
      
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_commeette_member['member_name']; ?></td>
        <td><?php echo $row_commeette_member['member_info']; ?></td>
        <td><?php echo $row_commeette_member['insert_date']; ?></td>
        
        <td> 
          
          
          
          <form action="" method="post" onsubmit="return confirm('هل انت متاكد من حذف العضو ( <?php echo $row_commeette_member['member_name']; ?>  )');"> 
            <input type="hidden" name="delete_member_id" value="<?php echo $row_commeette_member['id']; ?>">
            <input type="submit" value="حذف   " class="btn btn-danger btn-sm" style="font-size:10px; "> 
          </form>
          
          
          
          
        </td>
      </tr>
      <?php } while ($row_commeette_member = mysql_fetch_assoc($commeette_member)); ?>
  </table>
  <?php } else { // Show if recordset not empty ?>
  
  
  <br />


<h3 align="center" > لم يتم اضافة اي عضو الى اللجنة  </h3>


<?php } ?> 
        </div>
        
        
        
        
        </div>
      
      
      
      </div>
  </div>
<?php } while ($row_tender_comm = mysql_fetch_assoc($tender_comm)); ?>
      <?php } else {  // Show if recordset not empty ?>



<br />
<br />

<h3 align="center" > لم يتم اضافة اي لجنة الى العطاء   </h3>

<?php } ?> 
    
     
  </div>
</div>


</div>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($tender_info);

mysql_free_result($all_commetiee_type);

mysql_free_result($tender_comm);

mysql_free_result($commeette_member);
?>
