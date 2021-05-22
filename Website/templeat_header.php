<!DOCTYPE html>
<html>
<head>
<title>مركز الدرب التعليمي </title>
<meta charset="utf-8">
<meta content="ie=edge" http-equiv="x-ua-compatible">
<meta content="Tamerlan Soziev" name="author">
<meta content="Admin dashboard html template" name="description">
<meta content="width=device-width,initial-scale=1" name="viewport">
<link href="favicon.png" rel="shortcut icon">
<link href="apple-touch-icon.png" rel="apple-touch-icon">
<link href="../fast.fonts.net/cssapi/487b73f1-c2d1-43db-8526-db577e4c822b.css" rel="stylesheet">
<link href="bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
<link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link href="bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
<link href="bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
<link href="bower_components/slick-carousel/slick/slick.css" rel="stylesheet">
<link href="css/main5739.css?version=4.5.0" rel="stylesheet">
<link href="bower_components/dragula.js/dist/dragula.min.css" rel="stylesheet">
<link href="icon_fonts_assets/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

<link href="icon_fonts_assets/feather/style.css" rel="stylesheet">

<link href="icon_fonts_assets/foundation-icon-font/foundation-icons.css" rel="stylesheet">

<link href="fontawesome/css/all.css" rel="stylesheet">

  <script defer src="fontawesome/js/all.js"></script> <!--load all styles -->



</head>





<body class="menu-position-side menu-side-right full-screen"  >
<div class="all-wrapper solid-bg-all">
  <!--<div class="search-with-suggestions-w">
    <div class="search-with-suggestions-modal">
      <div class="element-search">
        <input class="search-suggest-input" placeholder="Start typing to search...">
        <div class="close-search-suggestions"><i class="os-icon os-icon-x"></i></div>
      </div>
       
       
       
    </div>
  </div> -->
  <div class="layout-w">
     
    <div class="menu-w selected-menu-color-light menu-activated-on-hover menu-has-selected-link color-scheme-dark color-style-default sub-menu-color-bright menu-position-side menu-side-left menu-layout-compact sub-menu-style-over">
      <div class="logo-w"><a class="logo" href="index.php">
        <div class="logo-element"></div>
        <div class="logo-label"> مركز الدرب التعليمي</div>
        </a></div>
        
        
        
        
        <div align="center"  style="text-align:center !important ; color:#ffcd16 "> 
        
              <img src="images/seed_bank.png" width="70%" > 

        
 <hr>
 
        
       
      اهلا بك : 
      <?php echo  $_SESSION['full_name'] ; ?> 
      
      
       </div>
      <h1 class="menu-page-header">خيارات المستخدم   </h1>
      <ul class="main-menu" dir="rtl">
         
       
        
        
        <li class=" "><a href="profile.php">
          <div class="icon-w">
            <div class="os-icon os-icon-home"></div>
          </div>
الصفحة الرئيسية            </a>
           
        </li>
        
        
        
        
                
        <li class=" ">
        
        <a href="admin_new_student.php">
        <!-- <a href="admin_std_1.php"> --> 
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
الطلاب             </a>
           
        </li>
        
        
         
                           
        <li class=" "><a href="admin_new_teatcher.php">
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
المدرسين                </a>
           
        </li>
             
                
        <li class=" "><a href="admin_traning_cource.php">
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
الشعب الدراسية والتسجيل             </a>
           
        </li>
        
        
         
                
                
        <li class=" "><a href="calender.php">
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
التقويم           </a>
           
        </li>
        
        
         
                            
     <!--           
        <li class=" "><a href="Admin_Catch_Receipt.php">
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
 سندات قبض            </a>
           
        </li>
         -->
            
                
 
        
         
        
        
                 <li class=" "><a href="settings.php">
          <div class="icon-w">
            <div class="os-icon os-icon-package"></div>
          </div>
 اعدادت             </a>
           
        </li>
        
                            
         
        
        
       
       
       
       
       
       
       
      


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


        
   
      </ul>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
    </div>
    <div class="content-w">
      <div class="top-bar color-scheme-bright">
         
        <!--<div class="top-menu-controls">
          <div class="element-search autosuggest-search-activator">
            <input placeholder="Start typing to search...">
          </div>
          <div class="messages-notifications os-dropdown-trigger os-dropdown-position-left"><i class="os-icon os-icon-mail-14"></i>
            <div class="new-messages-count">12</div>
            <div class="os-dropdown light message-list">
               
            </div>
          </div>
          <div class="top-icon top-settings os-dropdown-trigger os-dropdown-position-left"><i class="os-icon os-icon-ui-46"></i>
            <div class="os-dropdown">
              <div class="icon-w"><i class="os-icon os-icon-ui-46"></i></div>
               
            </div>
          </div>
          <div class="logged-user-w">
            <div class="logged-user-i">
              <div class="avatar-w"></div>
              <div class="logged-user-menu color-style-bright">
                 
                <div class="bg-icon"><i class="os-icon os-icon-wallet-loaded"></i></div>
                <ul>
                  <li><a href="apps_email.html"><i class="os-icon os-icon-mail-01"></i>  Profile</a></li>
    
    
                </ul>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      
      
      
      
      
      
      <div class="content-i" dir="rtl">
        <div class="content-box" dir="rtl">
          <div class="control-header">
            
          
          </div>


