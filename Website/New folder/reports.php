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

mysql_select_db($database_conn, $conn);
$query_all_suppliers = "SELECT id FROM t_suppliers";
$all_suppliers = mysql_query($query_all_suppliers, $conn) or die(mysql_error());
$row_all_suppliers = mysql_fetch_assoc($all_suppliers);
$totalRows_all_suppliers = mysql_num_rows($all_suppliers);

mysql_select_db($database_conn, $conn);
$query_year_tenders = "SELECT tenders.id FROM tenders WHERE YEAR(tenders.insert_date) = YEAR(CURDATE())  ";
$year_tenders = mysql_query($query_year_tenders, $conn) or die(mysql_error());
$row_year_tenders = mysql_fetch_assoc($year_tenders);
$totalRows_year_tenders = mysql_num_rows($year_tenders);

mysql_select_db($database_conn, $conn);
$query_users_all = "SELECT * FROM users";
$users_all = mysql_query($query_users_all, $conn) or die(mysql_error());
$row_users_all = mysql_fetch_assoc($users_all);
$totalRows_users_all = mysql_num_rows($users_all);
 
//


$page['title'] = 'تقارير      ';
$page['desc'] = 'تقراير واحصائيات  ';
 
 include('templeat_header.php');
  ?>
 
 
 <div class="row" > 





        
        
        
<div class="col-md-12"    >



 
<div class="btn   col-md-2 p-4 m-1 metro flip  "> 

<img src="images/report.png" width="90%"  /> 


</div>



<a href="suppliers_list.php" > 
  <div class="btn green col-md-3 p-4 m-1 metro flip  "> <i class="fa fa-users fa-2x"> <?php echo $totalRows_all_suppliers  ;?> </i>
  
  
  <h3 align="center" > مورد معتمد  </h3>
  
   <hr style="color:#FFFFFF; border-color:#FFFFFF ; " />
    <h5 > <span class="label bottom" style="font-family:kofi; float:right">   عرض تقارير الموردين  </span> </h5>
  </div>
  </a>
  
  
  <a href="admin_projects.php" > 
  <div class="btn btn-danger col-md-3 p-4 m-1 metro "> <i class="fa fa-list fa-2x"> <?php echo $totalRows_year_tenders ; ?> </i> 
  
    <h3 align="center" > عطاء جديد    <?php
	
	error_reporting(0); 
	 echo date('Y') ; ?>   </h3>
  
   <hr style="color:#FFFFFF; border-color:#FFFFFF ; " />
   
   
   
   <h5>  <span class="label bottom" style="font-family:kofi; float:right">عرض تقارير العطاءات      </span> 
   
   </h5> </div>
    </a>
    
    
   
    <a  href="#" > 
  <div class="btn orange col-md-3 p-4 m-1 metro "> <i class="fa fa-user fa-2x"> <?php echo $totalRows_users_all ; ?> </i>
  
  
    <h3 align="center" > موظف مضاف     </h3>
  
   <hr style="color:#FFFFFF; border-color:#FFFFFF ; " />
   
   
   
   <h5> <span class="label bottom" style="font-family:kofi; float:right">      تقرير الموظفين   </span>
   
   </h5>
    </div>
</a>    
    
    
     
   <!-- 
    
    
    
    
    <div class="btn purple col-md-2 p-4 m-1 metro "> <i class="fa fa-file fa-2x"></i> <br />
    <span class="label bottom" style="font-family:kofi; float:right">تقارير واحصائيات     </span> </div>
    
    
    
    
    
    
    
    <div class="btn green-bright col-md-2 p-4 m-1 metro "> <i class="fa fa-cog fa-2x"></i> <br />
    <span class="label bottom" style="font-family:kofi; float:right">   اعدادات  </span> </div>
    
    
   
    
 -->    
    
</div>






</div>




<script> 


function showTime(){
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var session = "AM";
    
    if(h == 0){
        h = 12;
    }
    
    if(h > 12){
        h = h - 12;
        session = "PM";
    }
    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    
    setTimeout(showTime, 1000);
    
}

showTime();

</script>
<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_suppliers);

mysql_free_result($year_tenders);

mysql_free_result($users_all);
?>
