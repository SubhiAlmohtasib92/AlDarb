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
//


$page['title'] = 'الصفحة الرئيسية    ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
 
 
 <div class="row" > 





        
        
        
<div class="col-md-12"    >






<h1 align="center"  >  اعدادت النظام  </h1>
<hr />






<a href="admin_work_sector_cat.php" > 
  <div class="btn green col-md-3 p-4 m-1 metro flip  "> <i class="fa fa-list fa-2x"></i> <br />
    <h5 > <span class="label bottom" style="font-family:kofi; float:right"> مجالات الاختصاص  </span> </h5>
  </div>
  </a>
  
  
  <a href="admin_projects.php" > 
  <div class="btn btn-danger col-md-2 p-4 m-1 metro "> <i class="fa fa-flag fa-2x"></i> <br />
   <h5>  <span class="label bottom" style="font-family:kofi; float:right">المشاريع     </span> 
   
   </h5> </div>
    </a>
    
    
   
    <a  href="Indicators_list.php" > 
  <div class="btn orange col-md-3 p-4 m-1 metro "> <i class="fa fa-list fa-2x"></i> <br />
   <h5> <span class="label bottom" style="font-family:kofi; float:right">  مؤشرات ومقايس الاداء  </span>
   
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
 ?>
