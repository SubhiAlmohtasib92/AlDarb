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

mysql_select_db($database_conn, $conn);
$query_calender_programs = "SELECT cource_program.id, courses.c_name, cource_program.p_day, cource_program.from_time, cource_program.to_time, cource_program.cource_id FROM cource_program, courses WHERE cource_program.cource_id =courses.c_id AND courses.c_status=1";
$calender_programs = mysql_query($query_calender_programs, $conn) or die(mysql_error());
$row_calender_programs = mysql_fetch_assoc($calender_programs);
$totalRows_calender_programs = mysql_num_rows($calender_programs);

if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
 
 
 
 
echo   get_coming_date_from_day_arabic('الجمعة');
  ?>
<div class="card  p-4 text-dark"  id="calender"

 
 
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
 
var d = new Date();
var t = d.getDay();

get_random = function (list) {
  return list[Math.floor((Math.random()*list.length))];
} 


 
 $('#calender').fullCalendar({
	 
	/*header: { center: 'month,agendaWeek' }, */ 
	 contentHeight: 500 ,
	  header: {
     left   : 'prev,next',
     center : 'title',
     right  : 'agendaDay,agendaWeek',
	 firstHour: 8,
	  
    },
defaultView: 'agendaWeek'
	 ,
	
	dayClick: function(date, jsEvent, view) {

   // alert('Clicked on: ' + date.format());

     $("#myModal").modal();
 
 show_details(date.format()) ; 
 
 

    // change the day's background color just for fun
  //   $(this).css('background-color', 'red');

  },
	
 

  events: [
    
     
    {
      title: 'دورة لغة عربية صف خامس ابتدائي ',
      start: t ,
 	  url:'#',
	  color: '#999'
,
    }<?php 
	
		$color = 10 ; 
		
	do { ?>,{
      title: '<?php echo clean($row_calender_programs['c_name']); ?> ',
      start: '<?php echo get_coming_date_from_day_arabic($row_calender_programs['p_day']).' '.$row_calender_programs['from_time']; ?>',
	  end: '<?php echo get_coming_date_from_day_arabic($row_calender_programs['p_day']).' '.$row_calender_programs['to_time']; ?>',
	  url:'admin_Systematic_course_program.php?id=<?php echo $row_calender_programs['cource_id']; ?>',
	  color: '#fff',
	  
    }<?php } while ($row_calender_programs = mysql_fetch_assoc($calender_programs)); ?>
	
  ]
});





 
 
   
  </script>
<?php
mysql_free_result($calender_programs);
?>
