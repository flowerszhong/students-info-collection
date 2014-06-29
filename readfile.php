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
  	$tel = $rowdata[2];
  	$user_name = $rowdata[1];
  	$major_origin = $rowdata[3];
  	$calss = $rowdata[5];
  	$major_tmp = preg_split('/[0-9]+/',$major_origin);
  	$major = $major_tmp[0];

  	if(preg_match('/[0-9]+/', $major_origin,$matchs)){
  		$class = $matchs[0] . "班";
  	}

    $net_id = $rowdata[6];
    $net_pwd = $rowdata[7];

    $fee = $rowdata[8];

    $last_pay_date = $rowdata[9];
    $expire_date = $rowdata[10];

    $consumer_record = $fee . '<br>' . $last_pay_date . '<br>' . $expire_date;


  	$grade = 13;


  	$sql = "INSERT INTO students (user_name, tel,grade,major,class,net_id,net_pwd,fee,last_pay_date,expire_date,consumer_record) VALUES 
  	('$user_name','$tel','$grade','$major','$class','$net_id','$net_pwd','$fee','$last_pay_date','$expire_date','$consumer_record')";  
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