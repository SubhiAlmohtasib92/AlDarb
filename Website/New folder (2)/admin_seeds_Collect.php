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
$query_all_collect = "SELECT dbo_collect.C_NO, dbo_collect.F_ID, dbo_farmer_inst.F_Name, dbo_items.arabic_name, dbo_collect.C_date, dbo_collect.`count`, dbo_collect.Total_w FROM dbo_collect, dbo_farmer_inst, dbo_items WHERE dbo_collect.F_ID =dbo_farmer_inst.F_ID AND dbo_collect.I_NO =dbo_items.I_NO order by dbo_collect.C_NO DESC";
$all_collect = mysql_query($query_all_collect, $conn) or die(mysql_error());
$row_all_collect = mysql_fetch_assoc($all_collect);
$totalRows_all_collect = mysql_num_rows($all_collect);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = " Seeds Collect | تجميع البذور  ";
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>
<div class="row"><div class="col-sm-12"><div class="element-wrapper">
  
  <div class="element-actions">
    
    
    <button class="mr-2 mb-2 btn btn-secondary" data-target="#onboardingWideTextModal" data-toggle="modal" type="button"> New Collect  | ادخال جديد </button>
    
    
    
    
    </div>
  
  
  <h6 class="element-header">  <?php echo $page['title'] ; ?> </h6>



<table id="tabledata"  border="1" class="table table-striped table-lightfont dataTable ">
<thead class="bg-dark text-white">


      <th class="data-order sorting_desc ">C_NO</th>
      <th  aria-sort="descending" >F_ID</th>
      <th>F_Name</th>
      <th>arabic_name</th>
      <th>C_date</th>
       <th>Total_w</th>
         <th> </th>
     
  </thead>
    <?php do { ?>
      <tr>
        <td><?php echo $row_all_collect['C_NO']; ?></td>
        <td><?php echo $row_all_collect['F_ID']; ?></td>
        <td><?php echo $row_all_collect['F_Name']; ?></td>
        <td><?php echo $row_all_collect['arabic_name']; ?></td>
        <td><?php echo $row_all_collect['C_date']; ?></td>
         <td><?php echo $row_all_collect['Total_w']; ?> g </td>
         
         <td> 
         
         <button onclick="view_detail('<?php echo $row_all_collect['C_NO']; ?>')" data-target="#detaildilog" data-toggle="modal"   class="btn btn-primary btn-sm" > Detail </button>
         
         </td>
      </tr>
      <?php } while ($row_all_collect = mysql_fetch_assoc($all_collect)); ?>
  </table>
</div></div></div>






<div class="onboarding-modal modal fade animated" id="onboardingWideTextModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">  Close </span></button>
      <div class="onboarding-side-by-side">
       <!-- <div class="onboarding-media"><img alt="" src="img/bigicon2.png" width="100px"></div> -->
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">



          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
     
     
     
     
     
     
      </div>
    </div>
  </div>
</div>









<div class="onboarding-modal modal fade animated" id="detaildilog" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">  Close </span></button>
      <div class="onboarding-side-by-side">
       <!-- <div class="onboarding-media"><img alt="" src="img/bigicon2.png" width="100px"></div> -->
        <div class="p-3 with-gradient">
          <h4 class="onboarding-title">



          </h4>
          
        </div>
      </div>
          
          <div class="onboarding-media">
     
     
     <div id="result" >    </div>
     
     
     
      </div>
    </div>
  </div>
</div>












<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_collect);
?>


<script>


$(document).ready(function() {
    $('#tabledata').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
} );


</script>






<script type="text/javascript" >


function view_detail(r_id){
	
	document.getElementById('result').innerHTML = '<h3 align="center" >  Loading ... </h3>' ; 
	
	    $.post("ajax/collect_detail.php",
    {
        C_NO: r_id 
         
    },
    function(data, status){
       
	   document.getElementById('result').innerHTML = data ; 
	   
    });
	
	
	}


</script>
