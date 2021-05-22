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
  $insertSQL = sprintf("INSERT INTO evaluations (awarded__id , supplier_id, tender_id, sector_id, summary, period_start, period_end, evaluator1, evaluator2, evaluator3, evaluator4, insert_date, insert_by, evaluation_date) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), %s, %s)",
                       GetSQLValueString($_POST['awarded__id'], "int"), GetSQLValueString($_POST['supplier_id'], "int"),
                       GetSQLValueString($_POST['tender_id'], "int"),
                       GetSQLValueString($_POST['sector_id'], "int"),
                       GetSQLValueString($_POST['summary'], "text"),
                       GetSQLValueString($_POST['period_start'], "date"),
                       GetSQLValueString($_POST['period_end'], "date"),
                       GetSQLValueString($_POST['evaluator1'], "int"),
                       GetSQLValueString($_POST['evaluator2'], "int"),
                       GetSQLValueString($_POST['evaluator3'], "int"),
                       GetSQLValueString($_POST['evaluator4'], "int"),
         
                       GetSQLValueString($_SESSION['user_id'], "int"),
                       GetSQLValueString($_POST['evaluation_date'], "date"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
  
  
  // get added evalution id 
  
mysql_select_db($database_conn, $conn);
$query_last_evaluation = "SELECT eval_id FROM evaluations ORDER BY eval_id DESC limit 1 ";
$last_evaluation = mysql_query($query_last_evaluation, $conn) or die(mysql_error());
$row_last_evaluation = mysql_fetch_assoc($last_evaluation);
$totalRows_last_evaluation = mysql_num_rows($last_evaluation);

$eval_id = $row_last_evaluation['eval_id'] ;  



  foreach($_POST['indicator_id'] as $indicator){
  
 
	
	
  $insertSQL = sprintf("INSERT INTO evaluation_indicators (evaluation_id, indicator_id) VALUES (%s, %s)",
                       GetSQLValueString($eval_id, "int"),
                       GetSQLValueString($indicator, "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  
     }
  
  
  header("location: tender_awarde_evaluation.php?awarde_id=".$_GET['awarde_id']);
}

mysql_select_db($database_conn, $conn);
$query_all_users = "SELECT user_id, user_name FROM users ORDER BY user_name ASC";
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
$totalRows_all_users = mysql_num_rows($all_users);

$colname_awwarde_tender_info = "-1";
if (isset($_GET['awarde_id'])) {
  $colname_awwarde_tender_info = $_GET['awarde_id'];
}
mysql_select_db($database_conn, $conn);
$query_awwarde_tender_info = sprintf("SELECT awarded_tenders.id, awarded_tenders.tender_id, awarded_tenders.awarded_supplier, tenders.tender_number, t_suppliers.supplier_name, tenders.tender_cat, tenders.tender_name FROM awarded_tenders, tenders, t_suppliers WHERE awarded_tenders.id = %s AND awarded_tenders.awarded_supplier =t_suppliers.id AND awarded_tenders.tender_id = tenders.id", GetSQLValueString($colname_awwarde_tender_info, "int"));
$awwarde_tender_info = mysql_query($query_awwarde_tender_info, $conn) or die(mysql_error());
$row_awwarde_tender_info = mysql_fetch_assoc($awwarde_tender_info);
$totalRows_awwarde_tender_info = mysql_num_rows($awwarde_tender_info);

mysql_select_db($database_conn, $conn);
$query_all_evaluation_indecators = "SELECT * FROM indicators_list";
$all_evaluation_indecators = mysql_query($query_all_evaluation_indecators, $conn) or die(mysql_error());
$row_all_evaluation_indecators = mysql_fetch_assoc($all_evaluation_indecators);
$totalRows_all_evaluation_indecators = mysql_num_rows($all_evaluation_indecators);

$colname_awward_evaluations = "-1";
if (isset($_GET['awarde_id'])) {
  $colname_awward_evaluations = $_GET['awarde_id'];
}
mysql_select_db($database_conn, $conn);
$query_awward_evaluations = sprintf("SELECT evaluations.eval_id, work_sector_item.item_name, evaluations.summary, evaluations.period_start, evaluations.period_end, evaluations.evaluator1, evaluations.evaluator2, evaluations.evaluator3, evaluations.evaluator4, evaluations.insert_date, users.user_name, evaluations.evaluation_date FROM evaluations, work_sector_item, users WHERE awarded__id = %s AND evaluations.sector_id = work_sector_item.item_id AND evaluations.insert_by = users.user_id", GetSQLValueString($colname_awward_evaluations, "int"));
$awward_evaluations = mysql_query($query_awward_evaluations, $conn) or die(mysql_error());
$row_awward_evaluations = mysql_fetch_assoc($awward_evaluations);
$totalRows_awward_evaluations = mysql_num_rows($awward_evaluations);


function get_user_name($user_id ,$database_conn ,$conn){
$colname_user_info_by_id = "-1";
 
mysql_select_db($database_conn, $conn);
$query_user_info_by_id = sprintf("SELECT user_id, user_name FROM users WHERE user_id = %s", GetSQLValueString($user_id, "int"));
$user_info_by_id = mysql_query($query_user_info_by_id, $conn) or die(mysql_error());
$row_user_info_by_id = mysql_fetch_assoc($user_info_by_id);
$totalRows_user_info_by_id = mysql_num_rows($user_info_by_id);
 
return ($row_user_info_by_id['user_name']) ;
}
 
//


function get_eval_result ($eval_id , $indicatorss,$evaluator ,$database_conn, $conn ){
	
		 
	  
  $colname_is_found_evaluation = $indicatorss;
  
  $evalid_is_found_evaluation = $eval_id ;
 
 
  
  $evaluater_is_found_evaluation = $evaluator ; 
 
 
mysql_select_db($database_conn, $conn);
$query_is_found_evaluation = sprintf("SELECT evaluation_indicator_value.id, evaluation_indicator_value.indicator_id, evaluation_indicator_value.evaluation_id, evaluation_indicator_value.evaluater_id, evaluation_indicator_value.important_rate, evaluation_indicator_value.evaluation_value FROM evaluation_indicator_value WHERE indicator_id = %s AND evaluation_indicator_value.evaluation_id =%s AND evaluation_indicator_value.evaluater_id=%s", GetSQLValueString($colname_is_found_evaluation, "int"),GetSQLValueString($evalid_is_found_evaluation, "int"),GetSQLValueString($evaluater_is_found_evaluation, "int"));
$is_found_evaluation = mysql_query($query_is_found_evaluation, $conn) or die(mysql_error());
$row_is_found_evaluation = mysql_fetch_assoc($is_found_evaluation);
$totalRows_is_found_evaluation = mysql_num_rows($is_found_evaluation); 

	 
	 return ($row_is_found_evaluation['evaluation_value'] )  ; 
	
	
	}


$page['title'] = 'اسم الصفحة ';
$page['desc'] = 'وصف محتوى الصفحة ';
 
 include('templeat_header.php');
  ?> 
  
  
   




<div class="row" > 
         
         
            
   <div class="col-md-2" > 
   
  
  
  <a  data-toggle="modal" data-target="#myModal" >
            
            
              <div class="btn blue col-md-12 text-white  m-1    "> <i class="fa fa-plus fa-2x"></i> <br>
   <h5>  <span class="label bottom" style="font-family:kofi;   text-align:center">        تقييم جديد         </span> 
   
   </h5> </div>
            
                                                        </a>
                                                        
                                                        
                                                        
   
  </div> 
   
   
         
         <div class="col-md-10 " > 
         
         
          
         
         
  <div class="card  p-4 text-dark" > 
  
 
 
 <h5 align="center" >  
  تقييم المورد ( <a href="supplier_profile.php?id=<?php echo $row_awwarde_tender_info['awarded_supplier']; ?>" style="color:#FF0000;"  >  <?php echo $row_awwarde_tender_info['supplier_name']; ?></a> ) <br />



<a href="tender_profile.php?id=<?php echo $row_awwarde_tender_info['tender_id']; ?>" > 

    <?php echo $row_awwarde_tender_info['tender_name']; ?> - رقم <?php echo $row_awwarde_tender_info['tender_number']; ?> 


</a> 



</h5>

<hr />
<?php if ($totalRows_awward_evaluations > 0) { // Show if recordset not empty ?>
  <div > 
    
    
    
    <?php do { ?>
      
      
      <div class="card border-info mb-3" >
        <div class="card-header bg-primary text-white">
          
          
          <div class="row col-md-12  "  align="center" style="font-size:14px; "> 
            
            <div class="col-md-4" > 
              <b style="color:#FF0;" > <?php echo $row_awward_evaluations['period_start']; ?></b> حتى 
              <b style="color:#FF0;" >
                <?php echo $row_awward_evaluations['period_end']; ?> 
                </b>
              
              </div>
            <div class="col-md-3" > 
              
              بواسطة : <?php echo $row_awward_evaluations['user_name']; ?> 
              
              </div>
            
            <div class="col-md-5" >
              
              المجال : <?php echo $row_awward_evaluations['item_name']; ?>
              
              </div>
            
            
            
            </div>
          
          
          </div>
        <div class="card-body text-info" dir="rtl" align="right"  >
       <b>    <h4 class="card-title    animated fadeIn delay-4s" align="right">  <?php echo $row_awward_evaluations['summary']; ?> </h4>
       
       
       
       
       
  </b>
  
  
   
<ul class="nav nav-tabs text-dark">
  <li class="nav-item">
    <a href="tender_awarde_evaluation.php?awarde_id=<?php echo $_GET['awarde_id'];?>" class="nav-link active"   >نتائج التقييم </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#tab2_<?php echo $row_awward_evaluations['eval_id'] ; ?>"> المقيم الاول <b style="color:#FF0000" > <?php echo get_user_name($row_awward_evaluations['evaluator1'],$database_conn ,$conn); ?></b> </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#tab3_<?php echo $row_awward_evaluations['eval_id'] ; ?>">  المقيم الثاني  <b style="color:#FF0000" > <?php echo get_user_name($row_awward_evaluations['evaluator2'],$database_conn ,$conn); ?></b></a>
  </li>
  
  
  
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#tab4_<?php echo $row_awward_evaluations['eval_id'] ; ?>">  المقيم الثالث  <b style="color:#FF0000" > <?php echo get_user_name($row_awward_evaluations['evaluator3'],$database_conn ,$conn); ?></b></a>
  </li>
  
  
  
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane container active" id="tab1_<?php echo $row_awward_evaluations['eval_id'] ; ?>">
  
  
  
  <?php 
  $colname_evaluation_indicatorss = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $colname_evaluation_indicatorss = $row_awward_evaluations['eval_id'];
}
mysql_select_db($database_conn, $conn);
$query_evaluation_indicatorss = sprintf("SELECT evaluation_indicators.id, indicators_list.Indicator_name FROM evaluation_indicators, indicators_list WHERE evaluation_id = %s AND evaluation_indicators.indicator_id = indicators_list.id", GetSQLValueString($colname_evaluation_indicatorss, "int"));
$evaluation_indicatorss = mysql_query($query_evaluation_indicatorss, $conn) or die(mysql_error());
$row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss);
$totalRows_evaluation_indicatorss = mysql_num_rows($evaluation_indicatorss);


?>
    <table border="1" class="table table-hover">
      <tr class="bg-primary text-white">
        <td>مؤشر القياس </td>
        <td> <?php echo get_user_name($row_awward_evaluations['evaluator1'],$database_conn ,$conn); ?>  </td>
        <td> <?php echo get_user_name($row_awward_evaluations['evaluator2'],$database_conn ,$conn); ?>    </td>
        <td> <?php echo get_user_name($row_awward_evaluations['evaluator3'],$database_conn ,$conn); ?>    </td>
        <td>   المعدل  </td>
        
        
      </tr>
      <?php do {
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		   ?>
        <tr>
          <td class="bg-success text-white "><?php echo $row_evaluation_indicatorss['Indicator_name']; ?></td>
          
        <td><?php 
		
		 $eval_result = ''; 
		$sum_eval  = 0;
		
		$eval_result =  get_eval_result ($row_awward_evaluations['eval_id'] , $row_evaluation_indicatorss['id'],$row_awward_evaluations['evaluator1'] ,$database_conn, $conn );
		
		
		echo  $eval_result  ; 
		
		if ($eval_result != '' ) {
			
			$evaluater_count = 1 ; 
			} 
		
		$sum_eval +=  $eval_result  ; 
		
		
		
		?> 
        
        
 
 
        
        </td>
        <td> 
		<?php 
		
		 $eval_result = ''; 
		
		$eval_result =  get_eval_result ($row_awward_evaluations['eval_id'] , $row_evaluation_indicatorss['id'],$row_awward_evaluations['evaluator2'] ,$database_conn, $conn );
		
		
		echo  $eval_result  ; 
		
		$sum_eval +=  $eval_result  ; 
		
		if ($eval_result != '' ) {
			
			$evaluater_count++ ; 
			} 
		
		
		?> 
          </td>
        
        
        <td> 
        <?php 
		
		 $eval_result = ''; 
		 
		
		$eval_result =  get_eval_result ($row_awward_evaluations['eval_id'] , $row_evaluation_indicatorss['id'],$row_awward_evaluations['evaluator3'] ,$database_conn, $conn );
		
		
		echo  $eval_result  ; 
		
		$sum_eval +=  $eval_result  ; 
		
			if ($eval_result != '' ) {
			
			$evaluater_count++ ; 
			} 
		
		
		?> 
        
         </td>
        <td class="bg-success text-white " align="center">    
        
        
        <?php $total =  number_format(($sum_eval  /$evaluater_count),1);
		
		
		echo $total ; 
		$final_total += $total ; 
		
		  ?> 
        
           </td>
        
        
        
        </tr>
        <?php } while ($row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss)); ?>
        
        
        <tr class="bg-dark text-white">
        
        
        <td colspan="4"> المعدل </td>
        <td align="center">
        
        <?php echo ($final_total /$totalRows_evaluation_indicatorss)*10 ;
		
		
		
		 $total  = 0 ; 
		$final_total = 0 ; 
		
		
		 ?>  % 
        
        
        
        
        
        
        
        
        </td>
        
        </tr>
        
        
        
    </table>
  </div>
  <div class="tab-pane container fade" id="tab2_<?php echo $row_awward_evaluations['eval_id'] ; ?>">
  
  
  
  
  
  
    
  <?php 
  $colname_evaluation_indicatorss = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $colname_evaluation_indicatorss = $row_awward_evaluations['eval_id'];
}
mysql_select_db($database_conn, $conn);
$query_evaluation_indicatorss = sprintf("SELECT evaluation_indicators.id, indicators_list.Indicator_name FROM evaluation_indicators, indicators_list WHERE evaluation_id = %s AND evaluation_indicators.indicator_id = indicators_list.id", GetSQLValueString($colname_evaluation_indicatorss, "int"));
$evaluation_indicatorss = mysql_query($query_evaluation_indicatorss, $conn) or die(mysql_error());
$row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss);
$totalRows_evaluation_indicatorss = mysql_num_rows($evaluation_indicatorss);






?>
    <table border="1" class="table table-hover">
      <tr class="bg-primary text-white">
        <td>مؤشر القياس </td>
      	<td width="40">1</td>
        <td width="40">2</td>
        <td width="40">3</td>
        <td width="40">4</td>
        <td width="40">5</td>
        <td width="40">6</td>
        <td width="40">7</td>
        <td width="40">8</td>
        <td width="40">9</td>
        <td width="40">10</td>
        
        <td></td>
        <td></td>
        
        
      </tr>
      <?php do { ?>
      
      <form >
        <tr>
          <td class="bg-success text-white "><?php echo $row_evaluation_indicatorss['Indicator_name']; ?></td>
     
     
     <?php
	 
	 
	 
	 
	 
	 
	 
if (isset($row_evaluation_indicatorss['id'])) {
  $colname_is_found_evaluation =$row_evaluation_indicatorss['id'];
}
$evalid_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $evalid_is_found_evaluation = $row_awward_evaluations['eval_id'];
}
$evaluater_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['evaluator1'])) {
  $evaluater_is_found_evaluation = $row_awward_evaluations['evaluator1'];
}
 
 
mysql_select_db($database_conn, $conn);
$query_is_found_evaluation = sprintf("SELECT evaluation_indicator_value.id, evaluation_indicator_value.indicator_id, evaluation_indicator_value.evaluation_id, evaluation_indicator_value.evaluater_id, evaluation_indicator_value.important_rate, evaluation_indicator_value.evaluation_value FROM evaluation_indicator_value WHERE indicator_id = %s AND evaluation_indicator_value.evaluation_id =%s AND evaluation_indicator_value.evaluater_id=%s", GetSQLValueString($colname_is_found_evaluation, "int"),GetSQLValueString($evalid_is_found_evaluation, "int"),GetSQLValueString($evaluater_is_found_evaluation, "int"));
$is_found_evaluation = mysql_query($query_is_found_evaluation, $conn) or die(mysql_error());
$row_is_found_evaluation = mysql_fetch_assoc($is_found_evaluation);
$totalRows_is_found_evaluation = mysql_num_rows($is_found_evaluation); 

	 
	 
 
	 
	 
	  for($i = 1 ; $i <= 10 ;  $i++ ) {
		  
		  
		   ?>      
   
   
   
   
   
      	<td><input <?php if ($row_is_found_evaluation['evaluation_value'] == $i) {echo 'checked="checked"' ;} ?>  onclick="add_evaluation_value( <?php echo $row_awward_evaluations['eval_id']; ?>, <?php echo $row_evaluation_indicatorss['id']; ?> , <?php echo $row_awward_evaluations['evaluator1'] ; ?> , this.value , '1' )" class="form-control" required="required"  type="radio" name="<?php echo $row_evaluation_indicatorss['id']; ?>" value="<?php echo $i ; ?>"  /></td>
    
    <?php } ?> 
        
      	 
  
  
  
      	<td id="result_<?php echo $row_awward_evaluations['eval_id']; ?>_<?php echo $row_evaluation_indicatorss['id']; ?>_<?php echo $row_awward_evaluations['evaluator1'] ; ?>">
        
        
          <?php if (isset($row_is_found_evaluation['evaluation_value'])){?>
        
        <a class="btn btn-primary text-white" ><?php echo $row_is_found_evaluation['evaluation_value'] ; ?></a>
        
        <?php }?> 
        
        
        
        
         </td>
    
        
        
        
        
      	<td>
        
        
     
        
         </td>
    
        
        
        
        
        
        
        </tr>
        
        </form>
        <?php } while ($row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss)); ?>
    </table>
  
  



</div>
  <div class="tab-pane container fade" id="tab3_<?php echo $row_awward_evaluations['eval_id'] ; ?>">
  
  
  
  
  
  
    
  <?php 
  $colname_evaluation_indicatorss = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $colname_evaluation_indicatorss = $row_awward_evaluations['eval_id'];
}
mysql_select_db($database_conn, $conn);
$query_evaluation_indicatorss = sprintf("SELECT evaluation_indicators.id, indicators_list.Indicator_name FROM evaluation_indicators, indicators_list WHERE evaluation_id = %s AND evaluation_indicators.indicator_id = indicators_list.id", GetSQLValueString($colname_evaluation_indicatorss, "int"));
$evaluation_indicatorss = mysql_query($query_evaluation_indicatorss, $conn) or die(mysql_error());
$row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss);
$totalRows_evaluation_indicatorss = mysql_num_rows($evaluation_indicatorss);






?>
    <table border="1" class="table table-hover">
      <tr class="bg-primary text-white">
        <td>مؤشر القياس </td>
      	<td width="40">1</td>
        <td width="40">2</td>
        <td width="40">3</td>
        <td width="40">4</td>
        <td width="40">5</td>
        <td width="40">6</td>
        <td width="40">7</td>
        <td width="40">8</td>
        <td width="40">9</td>
        <td width="40">10</td>
        
        <td></td>
        <td></td>
        
        
      </tr>
      <?php do { ?>
      
      <form >
        <tr>
          <td class="bg-success text-white "><?php echo $row_evaluation_indicatorss['Indicator_name']; ?></td>
     
     
     <?php
	 
	 
	 
	 
	 
	 
	 
if (isset($row_evaluation_indicatorss['id'])) {
  $colname_is_found_evaluation =$row_evaluation_indicatorss['id'];
}
$evalid_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $evalid_is_found_evaluation = $row_awward_evaluations['eval_id'];
}
$evaluater_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['evaluator2'])) {
  $evaluater_is_found_evaluation = $row_awward_evaluations['evaluator2'];
}
 
 
mysql_select_db($database_conn, $conn);
$query_is_found_evaluation = sprintf("SELECT evaluation_indicator_value.id, evaluation_indicator_value.indicator_id, evaluation_indicator_value.evaluation_id, evaluation_indicator_value.evaluater_id, evaluation_indicator_value.important_rate, evaluation_indicator_value.evaluation_value FROM evaluation_indicator_value WHERE indicator_id = %s AND evaluation_indicator_value.evaluation_id =%s AND evaluation_indicator_value.evaluater_id=%s", GetSQLValueString($colname_is_found_evaluation, "int"),GetSQLValueString($evalid_is_found_evaluation, "int"),GetSQLValueString($evaluater_is_found_evaluation, "int"));
$is_found_evaluation = mysql_query($query_is_found_evaluation, $conn) or die(mysql_error());
$row_is_found_evaluation = mysql_fetch_assoc($is_found_evaluation);
$totalRows_is_found_evaluation = mysql_num_rows($is_found_evaluation); 

	 
	 
	 
	 
	 
	  for($i = 1 ; $i <= 10 ;  $i++ ) {
		  
		  
		   ?>      
   
   
   
   
   
      	<td><input <?php if ($row_is_found_evaluation['evaluation_value'] == $i) {echo 'checked="checked"' ;} ?>  onclick="add_evaluation_value( <?php echo $row_awward_evaluations['eval_id']; ?>, <?php echo $row_evaluation_indicatorss['id']; ?> , <?php echo $row_awward_evaluations['evaluator2'] ; ?> , this.value , '1' )" class="form-control" required="required"  type="radio" name="<?php echo $row_evaluation_indicatorss['id']; ?>" value="<?php echo $i ; ?>"  /></td>
    
    <?php } ?> 
        
      	 
  
  
  
      	<td id="result_<?php echo $row_awward_evaluations['eval_id']; ?>_<?php echo $row_evaluation_indicatorss['id']; ?>_<?php echo $row_awward_evaluations['evaluator2'] ; ?>">
        
        
          <?php if (isset($row_is_found_evaluation['evaluation_value'])){?>
        
        <a class="btn btn-primary text-white" ><?php echo $row_is_found_evaluation['evaluation_value'] ; ?></a>
        
        <?php }?> 
        
        
        
        
         </td>
    
        
        
        
        
      	<td>
        
        
     
        
         </td>
    
        
        
        
        
        
        
        </tr>
        
        </form>
        <?php } while ($row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss)); ?>
    </table>
  
  


  
  
  
  
  
  </div>
  
  
  
   <div class="tab-pane container fade" id="tab4_<?php echo $row_awward_evaluations['eval_id'] ; ?>">
   
   
   
   
   
   
   
   
   
   
   
   
   
   
  
    
  <?php 
  $colname_evaluation_indicatorss = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $colname_evaluation_indicatorss = $row_awward_evaluations['eval_id'];
}
mysql_select_db($database_conn, $conn);
$query_evaluation_indicatorss = sprintf("SELECT evaluation_indicators.id, indicators_list.Indicator_name FROM evaluation_indicators, indicators_list WHERE evaluation_id = %s AND evaluation_indicators.indicator_id = indicators_list.id", GetSQLValueString($colname_evaluation_indicatorss, "int"));
$evaluation_indicatorss = mysql_query($query_evaluation_indicatorss, $conn) or die(mysql_error());
$row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss);
$totalRows_evaluation_indicatorss = mysql_num_rows($evaluation_indicatorss);






?>
    <table border="1" class="table table-hover">
      <tr class="bg-primary text-white">
        <td>مؤشر القياس </td>
      	<td width="40">1</td>
        <td width="40">2</td>
        <td width="40">3</td>
        <td width="40">4</td>
        <td width="40">5</td>
        <td width="40">6</td>
        <td width="40">7</td>
        <td width="40">8</td>
        <td width="40">9</td>
        <td width="40">10</td>
        
        <td></td>
        <td></td>
        
        
      </tr>
      <?php do { ?>
      
      <form >
        <tr>
          <td class="bg-success text-white "><?php echo $row_evaluation_indicatorss['Indicator_name']; ?></td>
     
     
     <?php
	 
	 
	 
	 
	 
	 
	 
if (isset($row_evaluation_indicatorss['id'])) {
  $colname_is_found_evaluation =$row_evaluation_indicatorss['id'];
}
$evalid_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['eval_id'])) {
  $evalid_is_found_evaluation = $row_awward_evaluations['eval_id'];
}
$evaluater_is_found_evaluation = "-1";
if (isset($row_awward_evaluations['evaluator3'])) {
  $evaluater_is_found_evaluation = $row_awward_evaluations['evaluator3'];
}
 
 
mysql_select_db($database_conn, $conn);
$query_is_found_evaluation = sprintf("SELECT evaluation_indicator_value.id, evaluation_indicator_value.indicator_id, evaluation_indicator_value.evaluation_id, evaluation_indicator_value.evaluater_id, evaluation_indicator_value.important_rate, evaluation_indicator_value.evaluation_value FROM evaluation_indicator_value WHERE indicator_id = %s AND evaluation_indicator_value.evaluation_id =%s AND evaluation_indicator_value.evaluater_id=%s", GetSQLValueString($colname_is_found_evaluation, "int"),GetSQLValueString($evalid_is_found_evaluation, "int"),GetSQLValueString($evaluater_is_found_evaluation, "int"));
$is_found_evaluation = mysql_query($query_is_found_evaluation, $conn) or die(mysql_error());
$row_is_found_evaluation = mysql_fetch_assoc($is_found_evaluation);
$totalRows_is_found_evaluation = mysql_num_rows($is_found_evaluation); 

	 
	  
	 
	  for($i = 1 ; $i <= 10 ;  $i++ ) {
		  
		  
		   ?>      
   
   
   
   
   
      	<td><input <?php if ($row_is_found_evaluation['evaluation_value'] == $i) {echo 'checked="checked"' ;} ?>  onclick="add_evaluation_value( <?php echo $row_awward_evaluations['eval_id']; ?>, <?php echo $row_evaluation_indicatorss['id']; ?> , <?php echo $row_awward_evaluations['evaluator3'] ; ?> , this.value , '1' )" class="form-control" required="required"  type="radio" name="<?php echo $row_evaluation_indicatorss['id']; ?>" value="<?php echo $i ; ?>"  /></td>
    
    <?php } ?> 
        
      	 
  
  
  
      	<td id="result_<?php echo $row_awward_evaluations['eval_id']; ?>_<?php echo $row_evaluation_indicatorss['id']; ?>_<?php echo $row_awward_evaluations['evaluator3'] ; ?>">
        
        
          <?php if (isset($row_is_found_evaluation['evaluation_value'])){?>
        
        <a class="btn btn-primary text-white" ><?php echo $row_is_found_evaluation['evaluation_value'] ; ?></a>
        
        <?php }?> 
        
        
        
        
         </td>
    
        
        
        
        
      	<td>
        
        
     
        
         </td>
    
        
        
        
        
        
        
        </tr>
        
        </form>
        <?php } while ($row_evaluation_indicatorss = mysql_fetch_assoc($evaluation_indicatorss)); ?>
    </table>
  
  















</div>
  
  
  
  
  
</div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          </div>
      </div>
      
      
      <?php } while ($row_awward_evaluations = mysql_fetch_assoc($awward_evaluations)); ?>
    
    
    
    
    
  </div>
  <?php } // Show if recordset not empty ?>
  </div></div></div>














<!-- Modal -->
<div id="myModal" class="modal text-dark fade" role="dialog">
  <div class="modal-dialog modal-xl ">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>
      <div class="modal-body p-3 text-dark" dir="rtl" >
      
      
      
      
      <div class="row" > 
      
      
      
      <div class="col-md-4" > 
      
      
      <h1 align="center" style="font-size:70px;" > 
      
      <i class="fa fa-check" aria-hidden="true"></i>



</h1>



      
      <h3  align="center"> اضافة تقييم جديد   </h3>
      
      <hr />
      
     <h6 align="center" > 
<b style="text-align:center" >

يمكنك من خلال النموذج التالي اضافة تقييم جديد لمورد خلال فترة محددة 
<br />

يرجى تعبئة البيانات ثم الضغط على زر الاضافة اسفل النموذج 

 </b>
 
 </h6>
      
      </div>
      <div class="col-md-8" > 
      
      
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" dir="rtl"  style="text-align:right ; ">
         
         
         <b>
         
         ملخص نشاطات المورد خلال فترة التقييم 
            
            </b>  
         
         <textarea name="summary" cols="32" rows="5" class="form-control"></textarea>
         
         
         
         <br />

         
    <div class="row" > 
       <div class="col-md-4" > 
 

بداية فترة التقييم
              
<input  type="date" class="form-control" required="required" name="period_start" value="" size="32" />


</div>

        <div class="col-md-4" > 
 
              

     نهاية فترة التقييم
              
              
              
      <input type="date" class="form-control" required="required"  name="period_end" value="" size="32" />
      
  </div>
  
  
  <div class="col-md-4" > 
              
           
               تاريخ عمل التقييم: 
               
               <input type="date" class="form-control"  name="evaluation_date" value="" size="32" />
               
               </div>
  
  
  
  </div>
  
            
       
<br />


       
       <div class="row" > 
       <div class="col-md-3" > 
 
 
 مقيم 1:  
 
 
 
 <select name="evaluator1" required class="form-control" >
              <option></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_all_users['user_id']?>" ><?php echo $row_all_users['user_name']?></option>
                <?php
} while ($row_all_users = mysql_fetch_assoc($all_users));
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);


?>
              </select>
              
              
    </div>
    
    
    <div class="col-md-3" >
    
              
              
              
              
              مقيم 2:
              
              
              
              
              <select name="evaluator2" required  class="form-control" >
              
              <option></option>
              
              
                <?php 
do {  
?>
                <option value="<?php echo $row_all_users['user_id']?>" ><?php echo $row_all_users['user_name']?></option>
                <?php
} while ($row_all_users = mysql_fetch_assoc($all_users));
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
?>
              </select>
              
              
              
              
              
              
              
              
              
  </div>
  
  
  
  
   <div class="col-md-3" >
    
            
               
               
               مقيم 3: 
               
               <select name="evaluator3" required class="form-control" >
               
               <option></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_all_users['user_id']?>" ><?php echo $row_all_users['user_name']?></option>
                
           
                
                
                <?php
} while ($row_all_users = mysql_fetch_assoc($all_users));
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);

?>
              </select>
     
     
     </div>
     
     
      <div class="col-md-3" >
    
    
     
 
 مقيم 4:
 
 
 <select name="evaluator4" class="form-control" >
              
              <option></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_all_users['user_id']?>" ><?php echo $row_all_users['user_name']?></option>
                <?php
} while ($row_all_users = mysql_fetch_assoc($all_users));
?>
              </select>
     
     
     </div>
    
    </div>
                 
              
              
             
          
               
          <input type="hidden" name="supplier_id" value="<?php echo $row_awwarde_tender_info['awarded_supplier']; ?>" />
          <input type="hidden" name="tender_id" value="<?php echo $row_awwarde_tender_info['tender_id']; ?>" />
          <input type="hidden" name="sector_id" value="<?php echo $row_awwarde_tender_info['tender_cat']; ?>" />
          
          
          
                 <input type="hidden" name="awarded__id" value="<?php echo $row_awwarde_tender_info['id']; ?>" />
          
          
          
          
         
          <input type="hidden" name="MM_insert" value="form1" />
       
       
       <br />
<b >
       يرجى اختيار المؤشرات التي سيتم تقييم المورد بناء عليها 
       
   </b>    
       
        <table border="1" class="table table-hover">
          <tr class="bg-primary text-white">
            <td>
       
            
            </td>
            <td>مؤشر القياس</td>
           </tr>
          <?php do { ?>
            <tr>
              <td class="bg-primary text-white "><input type="checkbox" checked="checked" value="<?php echo $row_all_evaluation_indecators['id']; ?>"  name="indicator_id[]"  class="form-control"  /> </td>
              <td> 
			  
			  
			   <?php echo $row_all_evaluation_indecators['Indicator_name']; ?></td>
               
               
               
               
             </tr>
            <?php } while ($row_all_evaluation_indecators = mysql_fetch_assoc($all_evaluation_indecators)); ?>
        </table> 
        
        
        
             
              <input class="btn btn-primary " type="submit" value=" اضافة تقييم جديد " />
               
               
               
               
        
        
         </form>
      </div></div>
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>





<script type="text/javascript" > 

function add_evaluation_value(eval_id , indicator_id , evaluater_id , evaluat_value , important_rate ){
	
	
	
		
	 $.post("ajax/add_evaluation_value.php",
  {
    evaluation_id: eval_id,
	 indicator_id: indicator_id , 
	 evaluater_id : evaluater_id ,
	 evaluat_value : evaluat_value , 
	 important_rate : important_rate
	 
	 
  },
  function(data, status){

document.getElementById('result_'+eval_id+'_'+indicator_id+'_'+evaluater_id ).innerHTML = data ; 

  });
  
  
  
	
	}




</script>







<?php 
//
  include('templeat_footer.php'); 
 
mysql_free_result($all_users);

mysql_free_result($awwarde_tender_info);



mysql_free_result($all_evaluation_indecators);

mysql_free_result($awward_evaluations);

 
mysql_free_result($evaluation_indicatorss);




 ?>
