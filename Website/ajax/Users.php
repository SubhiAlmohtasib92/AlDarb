<?php require_once('../Connections/conn.php'); ?>
 <?php
// if (!isset($_SESSION)) {
//   session_start();
// }
// $MM_authorizedUsers = "";
// $MM_donotCheckaccess = "true";

// // *** Restrict Access To Page: Grant or deny access to this page
// function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
//   // For security, start by assuming the visitor is NOT authorized. 
//   $isValid = False; 

//   // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
//   // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
//   if (!empty($UserName)) { 
//     // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
//     // Parse the strings into arrays. 
//     $arrUsers = Explode(",", $strUsers); 
//     $arrGroups = Explode(",", $strGroups); 
//     if (in_array($UserName, $arrUsers)) { 
//       $isValid = true; 
//     } 
//     // Or, you may restrict access to only certain users based on their username. 
//     if (in_array($UserGroup, $arrGroups)) { 
//       $isValid = true; 
//     } 
//     if (($strUsers == "") && true) { 
//       $isValid = true; 
//     } 
//   } 
//   return $isValid; 
// }

// $MM_restrictGoTo = "login.php";
// if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
//   $MM_qsChar = "?";
//   $MM_referrer = $_SERVER['PHP_SELF'];
//   if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
//   if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
//   $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
//   $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
//   header("Location: ". $MM_restrictGoTo); 
//   exit;
// }
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


$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

if(isset($queries["action"])){

if($queries["action"] =='updateStudent'){

  function throw_ex($er){  
    throw new Exception($er);  
    }  


    $insertSql = sprintf("UPDATE `users` SET `user_name`=%s, `address`=%s, `reg_date`=%s, `gender`=%s, `stu_class`=%s ,`mobile`=%s , `additional_info`=%s ,account_status = %s   WHERE `user_id`=%s",
    GetSQLValueString($_POST['user_name'], "text"),
    GetSQLValueString($_POST['address'], "text"),
    GetSQLValueString($_POST['regdate'], "date"),
    GetSQLValueString($_POST['gender'], "text"),
    GetSQLValueString($_POST['stu_class'], "int"),
    GetSQLValueString($_POST['mobile'], "int"),
    GetSQLValueString($_POST['additional_info'], "text"),
    GetSQLValueString($_POST['studentStatus'], "int"),
    GetSQLValueString($_POST['userid'], "int")
   );


try {  
mysql_select_db($database_coon, $coon);
$q = mysql_query($insertSql) or throw_ex(mysql_error());  
}  
catch(exception $e) {
echo "ex: ".$e; 
}

 }


else if ($queries["action"] =='updateTeacher'){
    

  $updateSQL = sprintf("UPDATE `users` SET `user_name`=%s, `address`=%s, `mobile`=%s   WHERE `user_id`=%s",
  GetSQLValueString($_POST['teacherName'], "text"),
  GetSQLValueString($_POST['teacherAddress'], "text"),
  GetSQLValueString($_POST['teacherMobile'], "int"),
  GetSQLValueString($_POST['inputTeacherId'], "int")
 );

 


        function throw_ex($er){  
            throw new Exception($er);  
          }  
          try {  
            mysql_select_db($database_coon, $coon);
          $q = mysql_query($updateSQL) or throw_ex(mysql_error());  
          }  
          catch(exception $e) {
            echo "ex: ".$e; 
          }


}
else{

    header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'unknown operation', 'code' => 500)));


}

}



