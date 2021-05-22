<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "localhost";
$database_conn = "aldarb";
$username_conn = "root";
$password_conn = "uawcpal2010";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 








function get_day_name_from_date(){
	
	
 		if(date('D')=='Sat'){
			
			return 'السبت';
			} 
		
		
			if(date('D')=='Sun'){
			
			return 'الاحد';
			} 
		
		
			if(date('D')=='Mon'){
			
			return 'الاثنين';
			} 
		
		
			if(date('D')=='Tue'){
			
			return 'الثلاثاء';
			} 
		
		
			if(date('D')=='Wed'){
			
			return 'الاربعاء';
			} 
		
		
			if(date('D')=='Thu'){
			
			return 'الخميس';
			} 
		
		
			if(date('D')=='Fri'){
			
			return 'الجمعة';
			} 
		
		 
  
	
	} 



function get_coming_date_from_day_arabic($myday){
	
	
 
		
	
	if ($myday == 'السبت'){
		if(date('D')=='Sat'){
			
			return date('Y-m-d');
			}else{
		
		return date('Y-m-d', strtotime('next saturday'));
			}
		}
		
	
	if ($myday == 'الاحد'){
		if(date('D')=='Sun'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next sunday'));
		
		}}
		
	
	if ($myday == 'الاثنين'){
		if(date('D')=='Mon'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next monday'));
		
		}}
		
	
	if ($myday == 'الثلاثاء'){
		if(date('D')=='Tue'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next tuesday'));
		
		}}
		
	
	if ($myday == 'الاربعاء'){
		if(date('D')=='Wed'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next wednesday'));
		
		}}
		
 
		
	if ($myday == 'الخميس'){
		if(date('D')=='Thu'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next thursday'));
		
		}}
		
	
	if ($myday == 'الجمعة'){
		if(date('D')=='Fri'){
			
			return date('Y-m-d');
			}else{
		return date('Y-m-d', strtotime('next friday'));
		
		}}
		

	
	} 





error_reporting(0);
?>