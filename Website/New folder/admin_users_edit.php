<?php require_once('Connections/conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
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

$MM_restrictGoTo = "login.php?info=access";
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


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	
		
	include 'uploud_function.php'; 
	
		
	if($_FILES['profile_pic']['name']) {
		$u_file = uploud_pic('profile_pic' , 'images/users/' , 91 )  ;
    }else {	$u_file = 'images/user.jpg'; } 
	
	 
	 
	 
	 
  $updateSQL = sprintf("UPDATE users SET profile_pic=%s WHERE user_id=%s",
                       GetSQLValueString('images/users/'.$u_file, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

 
 

$colname_users = "-1";
if (isset($_GET['user_id'])) {
  $colname_users = $_GET['user_id'];
}
mysql_select_db($database_conn, $conn);
$query_users = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_users, "int"));
$users = mysql_query($query_users, $conn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<?php //
include 'templeat_header.php'; ?>

<div class="row" >
<div class="col-lg-9 animated fadeInLeft">
  <div class="ibox float-e-margins">
    <div class="ibox-title">
      <div class="ibox-content">
        <div class="panel panel-primary sortable-widget-item" style="position: relative; top: 0px; left: 0px;">
          <div class="panel-body">
            <div class="tab-content">
              <div class="tab-pane fade in active" id="home5">
                 
                     
       
                
              </div>
            </div>
            <!--/tab-content-->
          </div>
          <!-- /panel-body -->
        </div>
      </div>
    </div>
  </div>
</div>






<div class="col-lg-3 animated fadeInLeft">
  <div class="ibox float-e-margins">
    <div class="ibox-content">
      <div class="row" >
        <div class="panel panel-primary sortable-widget-item   ">
        
                  <div class="panel-body">
                  
                  
                  
                  <div class="col-md-12" align="center"  > 
            
            <img class="img-circle " src="<?php echo htmlentities($row_users['profile_pic'], ENT_COMPAT, ''); ?>" width="70%"  /> <br />
                  <hr />
            </div>  
                  
                    <form enctype="multipart/form-data"  action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                    
                    <input required="required" accept="image/jpeg" type="file"  name="profile_pic" value="<?php echo htmlentities($row_users['profile_pic'], ENT_COMPAT, ''); ?>" size="32" />
                    <br />

                    
                    <input class="btn btn-primary" type="submit" value="تحديث الصورة الشخصية " />
                    
                      
                      <input type="hidden" name="MM_update" value="form1" />
                      <input type="hidden" name="user_id" value="<?php echo $row_users['user_id']; ?>" />
                    </form>
                   </div>
           
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'templeat_footer.php'; 
mysql_free_result($users);

 
?>
