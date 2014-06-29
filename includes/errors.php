<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))  {
	echo "error";
 echo "<div class=\"msg\">";
foreach ($err as $e) {
  echo "$e <br>";
  }
echo "</div>";	
 }
/******************************* END ********************************/	  
if(!empty($msg))  {
	echo "msg";
 echo "<div class=\"msg\">";
foreach ($msg as $m) {
  echo "$m <br>";
  }
echo "</div>";	
 }


?>