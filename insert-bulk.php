<?php

$arr = array();
for($i=1;$i<=100;$i++){
	for($j=1;$j<=4;$j++){
		$arr[$i][] =  "'".$i."_".$j."'";
	}
}


$filename = dirname(__FILE__)."/data1.csv";

$handle = fopen($filename,'w');

if(!$handle){
	echo "system error";
	exit();
}else{
	foreach($arr as $k=>$v){
		fwrite($handle,json_encode($v)."\n");
	
	}
	fclose($handle);
}

$arr = file_get_contents($filename);
$array = explode("\n", $arr); 
$sql = "INSERT INTO user (name,age,sex,phone) VALUES ";
$tmp = array();
foreach($array as $k=>$v){
	//echo $k.'===='.'('.implode(',',(json_decode($v))).')'."\n";

	$tmp[] = '('.implode(',',(json_decode($v))).')';

}

echo $sum = count($tmp);
unset($tmp[$sum-1]);
print_r($tmp);
$sql .= implode(',',$tmp);

echo $sql;
?>

