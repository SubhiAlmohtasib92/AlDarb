<?php
if (!isset($_SESSION)) {
  session_start();
}



$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?> 
  
<h3 align="center" > اضافة طالب جديد </h3> 

<h6 class="element-header" align="right" >  الرجاء تحديد ولي امر الطالب لاضافة الطالب  </h6>
<br />



<div class="row" > 






<div class="col-md-3" style="text-align:center ;"><a class="p-3 element-box el-tablo" href="#">
            <h1 align="center" class="fi-plus"></h1>
             
        اضافة ولي امر جديد       </a></div>


<div class="col-md-9" >  



</div> 







</div>


  
  
<?php 
//
  include('templeat_footer.php'); 
 ?>
