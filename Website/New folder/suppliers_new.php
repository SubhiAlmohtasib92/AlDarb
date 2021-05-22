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
  $insertSQL = sprintf("INSERT INTO t_suppliers (supplier_name, supplier_Authorized_signatory, supplier_license, supplier_tel, supplier_mobile, supplier_fax, supplier_box, supplier_emp_count, supplier_major, experience_years, email, website, main_office_location, city, temp_session) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['supplier_name'], "text"),
                       GetSQLValueString($_POST['supplier_Authorized_signatory'], "text"),
                       GetSQLValueString($_POST['supplier_license'], "text"),
                       GetSQLValueString($_POST['supplier_tel'], "text"),
                       GetSQLValueString($_POST['supplier_mobile'], "text"),
                       GetSQLValueString($_POST['supplier_fax'], "text"),
                       GetSQLValueString($_POST['supplier_box'], "text"),
                       GetSQLValueString($_POST['supplier_emp_count'], "text"),
                       GetSQLValueString(0 , "text"),
                       GetSQLValueString($_POST['experience_years'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['website'], "text"),
                       GetSQLValueString($_POST['main_office_location'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString(0 , "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  header('location: suppliers_new.php?success=1') ;
}

mysql_select_db($database_conn, $conn);
$query_city = "SELECT * FROM cities";
$city = mysql_query($query_city, $conn) or die(mysql_error());
$row_city = mysql_fetch_assoc($city);
$totalRows_city = mysql_num_rows($city);




?>
<?php
mysql_select_db($database_conn, $conn);
$query_last_supplaier = "SELECT id FROM t_suppliers ORDER BY id DESC limit 1 ";
$last_supplaier = mysql_query($query_last_supplaier, $conn) or die(mysql_error());
$row_last_supplaier = mysql_fetch_assoc($last_supplaier);
$totalRows_last_supplaier = mysql_num_rows($last_supplaier);
 
//


$page['title'] = 'اضافة مورد جديد    ';
$page['desc'] = 'يمكن من خلال هذه الصفحة تعريف مورد جديد واضافة جميع الوثائق المطلوبة    ';
 
 include('templeat_header.php');
  ?> 
  



     <div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
                   <?php include "include/all_supplaier_menu.php" ; ?>

   
   </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
         
         
         
  <div class="card  p-4 text-dark" > 
  
  
  
  
      
      
     <?php if ($_GET['success']==1) { ?>  
      
 <center>      
      
   <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                            <span class="swal2-success-line-tip"></span>
                                                            <span class="swal2-success-line-long"></span>
                                                            <div class="swal2-success-ring"></div>
                                                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                        </div>
                                                        
                            
                            
           <div class="mb-3 card text-white bg-success">
                             
                                            <div class="card-body" align="center" >لقد تم اضافة بيانات المورد الاساسية بنجاح :: يرجى الانتقال الى صفحة المورد لاضافة مجالات العمل والفروع والبيانات الاخرى </div>
                              
                                        </div>     
                            
                          
                            
                            
   <a href="supplier_profile.php?id=<?php echo $row_last_supplaier['id']; ?>" class="btn btn-primary " > الانتقال الى صفحة المورد  </a> 
                            
                            
                                                        
     </center>                                        
                                                        
            
            
            
            <?php } else { ?> 
            
            
            
            
                                                        
     <form id="signup" method="post" name="form1" action="<?php echo $editFormAction; ?>">
    
      
      
                                                
  
 
  
  <div class="col-md-12" > 
   
    
    <div class="row" align="right">
    
    
    
    
    
    <div class="col-md-3" >
    
    اسم المورد / الشركة
    
    <input required="required" name="supplier_name" type="text" ="" class="form-control" value="" size="32" />
     </div>
    
    
    
    <div class="col-md-3" > 
    اسم الشخص المخول بالتوقيع
    
    <input type="text"    class="form-control"  name="supplier_Authorized_signatory" value="" size="32">
    
    </div>
    
    
    
        <div class="col-md-3" > 
    المشتغل المرخص
    <input type="text"   class="form-control"  name="supplier_license" value="" size="32">
    
    </div>
    
     
    
        <div class="col-md-3" > 
    رقم الهاتف
    <input type="text"   class="form-control"  name="supplier_tel" value="" size="32">
    
    
    </div>
    
    
    
        <div class="col-md-3" > 
    رقم المحمول
    <input type="text"   class="form-control"  name="supplier_mobile" value="" size="32">
    
    
    </div>
    
    
    
     
        <div class="col-md-3" > 
    رقم الفاكس
    
    <input type="text"   class="form-control"  name="supplier_fax" value="" size="32">
    </div>
    
     
        <div class="col-md-3" > 
    صندوق البريد
    <input type="text"   class="form-control"  name="supplier_box" value="" size="32">
    
    </div>
    
    
    
            <div class="col-md-3" > 
    عدد الموظفين 
    
    <input type="text"   class="form-control"  name="supplier_emp_count" value="" size="32">
    
    </div>
    
    
    
            <div class="col-md-3" > 
    
    عدد سنوات الخبره
    
    <input type="text"   class="form-control"  name="experience_years" value="" size="32">
    
    </div>
    
    
    
    
    
    
    
            <div class="col-md-3" > 
    
    
    البريد الالكتروني 
    <input type="email"   class="form-control"  name="email" value="" size="32">
    
    </div>
    
    
     
      
      
            <div class="col-md-3" > 
    
    الموقع الالكتروني
    <input type="text"   class="form-control"  name="website" value="" size="32">
    
    </div>
    
    <div class="col-md-3" > 
    
    
    المدينة
     <select name="city" class="form-control" >
            <?php 
do {  
?>
            <option value="<?php echo $row_city['Cities_id']?>" ><?php echo $row_city['Cities_name']?></option>
            <?php
} while ($row_city = mysql_fetch_assoc($city));
?>
          </select>
    
    </div>
      
      <div class="col-md-12" > 
    
    
    عنوان المكتب الرئيسي 
    <textarea name="main_office_location" cols="32"  class="form-control"></textarea>
    
    </div>
    
     
      
            
       
      <div class="col-md-3" >
            <br />
<br />
 
    
    <input class="btn btn-primary " type="submit" value=" حفظ ومتابعة " >
    
    
    </div>
    
    
      
  

  
  
  </div> 
  </div> 
  
        <input type="hidden" name="MM_insert" value="form1">



    </form> 
    
    
    <?php } ?>
    
    
    
    </div></div></div>
    <?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($city);

mysql_free_result($last_supplaier);
?>
