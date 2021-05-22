<?php
if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?> 
  


<H3 align="center" > ادارة الصفوف الدراسية  </H3> 

<div class="row" > 

<div class="col-md-4" > </div>

<div class="col-md-8" > </div>


</div>


  
 
<?php 
//
  include('templeat_footer.php'); 
 ?>
