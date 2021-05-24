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

if($queries["action"] =='insertCoursesForStudent'){

  function throw_ex($er){  
    throw new Exception($er);  
    }  


  $data = json_decode(stripslashes($_POST['courses']));
  foreach($data as $d){
    $insertSql = sprintf("INSERT INTO cource_students (c_id, stu_id, cost, insert_date, reg_end_date) VALUES (%s, %s, %s, %s, %s) ",
    GetSQLValueString($d->courseId, "int"),
    GetSQLValueString($_POST['studentId'], "int"),
    GetSQLValueString($d->courseCost, "double"),
    GetSQLValueString($d->courseStartDate, "date"),
    GetSQLValueString($d->courseEndtDate, "date")
   );


try {  
mysql_select_db($database_coon, $coon);
$q = mysql_query($insertSql) or throw_ex(mysql_error());  
}  
catch(exception $e) {
echo "ex: ".$e; 
}

 }

   

}
else if ($queries["action"] =='delete'){
    

    $deleteStatment = sprintf("DELETE FROM `catch_receipt`  WHERE `catch_receipt`.`id` = %s;",
                             GetSQLValueString($_POST['paymentId'], "int")
                            );

        function throw_ex($er){  
            throw new Exception($er);  
          }  
          try {  
            mysql_select_db($database_coon, $coon);
          $q = mysql_query($deleteStatment) or throw_ex(mysql_error());  
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
else{
  
    header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => 'action is missing', 'code' => 500)));
}



