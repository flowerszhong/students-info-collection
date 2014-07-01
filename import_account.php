<html>
	<head>
		<meta charset="utf-8" />
	</head>
</html>

<?php
include 'dbc.php';

ini_set('max_execution_time', 1000);



// 1,何寿鸿,18144773115,物理污染监测技术,13级,1班,fsVPDNhb001636@fsnhedu.v.gd,77654579,300,20131001,20140930


$file=fopen("data-ok.csv","r") or exit("Unable to open file!");
while (!feof($file)) 
  { 

  	$rowdata = explode(",", fgets($file));
    $net_id = $rowdata[6];
    $net_pwd = $rowdata[7];


  	$sql = "INSERT INTO accounts (account_id,account_pwd,used) VALUES 
  	('$net_id','$net_pwd','1')";  
  	echo $sql;
  	$db->exec($sql) or die(showDBError());
  }
fclose($file);


// for (int i = 0; i < values.size(); i++) {  
//     stmt.bindString(1, $rowdata[i]);  
//     stmt.bindString(2, $rowdata[i]);  
//     stmt.execute();  
//     stmt.clearBindings();  
// }  
   
// db.setTransactionSuccessful();  
// db.endTransaction();  

// $file=fopen("data-ok.csv","r") or exit("Unable to open file!");
// while (!feof($file)) 
//   { 

//   	$rowdata = explode(",", fgets($file));

//   	var_dump($rowdata);
//   }
// fclose($file);
?>