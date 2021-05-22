<?php
if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?>

<div class="row" >



  <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_classes.php">
    <h3 align="center">الصفوف    </h3>
    </a></div>
    
    
    
  <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_matiral.php">
    <h3 align="center">المواد الدراسية    </h3>
    </a></div>
    
    
 
    
      <div class="col-md-4" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="admin_rooms.php">
    <h3 align="center">القاعات الدراسية    </h3>
    </a></div>
    
    
 
    
    
</div>
<?php 
//
  include('templeat_footer.php'); 
 ?>
