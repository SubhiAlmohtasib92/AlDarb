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
  $insertSQL = sprintf("INSERT INTO calender_notes (note_text, note_date, user_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['note_text'], "text"),
                       GetSQLValueString($_POST['note_date'], "date"),
                       GetSQLValueString($_SESSION['user_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_month_committee = "SELECT committees.id, committees_type.Committee_type, committees.Committee_insert_date, tenders.tender_name, committees.tender_id FROM committees, committees_type, tenders WHERE Committee_insert_date >= NOW() AND Committee_insert_date < DATE(NOW() + INTERVAL 1 MONTH) AND committees.Committee_name = committees_type.id AND committees.tender_id = tenders.id";
$month_committee = mysql_query($query_month_committee, $conn) or die(mysql_error());
$row_month_committee = mysql_fetch_assoc($month_committee);
$totalRows_month_committee = mysql_num_rows($month_committee);

mysql_select_db($database_conn, $conn);
$query_submition_tender = "SELECT * FROM awarded_tenders where submission_date >= NOW() AND submission_date < DATE(NOW() + INTERVAL 1 MONTH) ";
$submition_tender = mysql_query($query_submition_tender, $conn) or die(mysql_error());
$row_submition_tender = mysql_fetch_assoc($submition_tender);
$totalRows_submition_tender = mysql_num_rows($submition_tender);

mysql_select_db($database_conn, $conn);
$query_calender_notess = "SELECT * FROM calender_notes WHERE note_date >= NOW() AND note_date < DATE(NOW() + INTERVAL 1 MONTH)";
$calender_notess = mysql_query($query_calender_notess, $conn) or die(mysql_error());
$row_calender_notess = mysql_fetch_assoc($calender_notess);
$totalRows_calender_notess = mysql_num_rows($calender_notess);
 
//


$page['title'] = 'التقويم    ';
$page['desc'] = '         ';
 
 include('templeat_header.php');
  ?> 
  
  
  
  <style > 
  
  .fc-body .fc-day-number {
	  font-weight:900 !important ; 
	  font-size:20px !important ; 
	  }
  .fc-today {
	  
	  background-color:#16499a !important  ; 
	  color:#FFF ; 
	  
	  }
  .fc-content{
	  
	color:#FFFFFF !important  ;   
  }
  </style>




<div class="row" > 
         
         
            
  <!--<div class="col-md-3" > 
   
        
        <h3 align="center" > 
        
        اجندة اليوم 
        
        </h3>
        
         <div class="card  p-4 text-dark" > 
         
         
         <form action="" method="post" >
         
         
         <input type="date" class="form-control"  value="" /> 
         
         
         </form>
         
         
         
         </div>
        
        

   
  </div> --> 
   
   
         
  <div class="col-md-12 "> 
         
         
         
         
         
  <div class="card  p-4 text-dark"  id="tender_calender"   >


  </div></div></div>



















  <!-- The Modal -->
  <div class="modal fade text-dark " id="myModal">
    <div class="modal-dialog modal-xl ">
      <div class="modal-content">
      
       
       <div class="row modal-body " > 
       
    
       <div class="col-md-12" >
        <!-- Modal body -->
        <div class="" id="result_view">
          
        </div>
        
        </div>
       
       </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">اغلاق</button>
        </div>
        
      </div>
    </div>
  </div>
 
<?php 
//
  include('templeat_footer.php'); 
 ?>

 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 
<script>
 
 
 <?php 
 
 function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
$string = preg_replace ( "/&([\x{0600}-\x{06FF}a-zA-Z])(uml|acute|grave|circ|tilde|ring),/u", "", $string );
    $string = preg_replace ( "/[^\x{0600}-\x{06FF}a-zA-Z0-9_.-]/u", "", $string );
	
	
   return $string; // Removes special chars.
}


?>
 

 
 $('#tender_calender').fullCalendar({
	 
	/*header: { center: 'month,agendaWeek' }, */ 
	 contentHeight: 500 , 
	
	dayClick: function(date, jsEvent, view) {

   // alert('Clicked on: ' + date.format());

     $("#myModal").modal();
 
 show_details(date.format()) ; 
 
 

    // change the day's background color just for fun
  //   $(this).css('background-color', 'red');

  },
	
 

  events: [
    
     
    {
      title: '  ',
      start: '2018-07-20',
	  url:'#'
    }<?php if ($totalRows_month_committee > 0 ) { ?><?php do { ?>, {
      title: '<?php echo clean($row_month_committee['Committee_type']); ?>',
      start: '<?php echo $row_month_committee['Committee_insert_date']; ?>',
	   
	   color: 'red'
    }
	        <?php } while ($row_month_committee = mysql_fetch_assoc($month_committee)); ?>
	
	<?php } ?> 
	
	
	
	<?php if ($totalRows_submition_tender > 0 ) { ?> 
	
	
	<?php do { ?> 
	
	, {
      title: ' انهاء اعمال  ',
      start: '<?php echo $row_submition_tender['submission_date']; ?>', 
	   color: 'green'
    }
	
	<?php } while ($row_submition_tender = mysql_fetch_assoc($submition_tender)); ?>
	
	
	
	<?php } ?> 
	
	 <?php if ($totalRows_calender_notess > 0) { // Show if recordset not empty ?>
 
      <?php do { ?>
	  , {
      title: '<?php echo clean($row_calender_notess['note_text']); ?>',
	   start: '<?php echo $row_calender_notess['note_date']; ?>', 
	   color: 'blue'
    }   
        <?php } while ($row_calender_notess = mysql_fetch_assoc($calender_notess)); ?>
  
  <?php } // Show if recordset not empty ?>
	
	
  ]
});





 
 function show_details(selected_date){
	 
	 
 
	 
	 	document.getElementById('result_view').innerHTML = '<div align="center" class=" float-right mr-3 mb-3" ><div align="center"  class="loader-wrapper d-flex justify-content-center align-items-center"><div class="loader"><div class="ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div><p> جاري التحميل ... </p></div>' ; 
	
	 
	
	 $.post("ajax/calender_day_task_list.php",
  {
    selected_date: selected_date 
  },
  function(data, status){

document.getElementById('result_view').innerHTML = data ; 

  });
	

	 
	 
	 }
 
 
 
 
   
  </script>
  
  
 
  
  <?php
mysql_free_result($month_committee);

mysql_free_result($submition_tender);

mysql_free_result($calender_notess);
?>
