<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="uft-8" />
</head>
<body>

<?php 
include 'dbc.php';
include 'char.php';



function rand_pick_string($arr,$l)
{
	$rand_keys = array_rand($arr,$l);
	print_r($rand_keys);
	if($l == 1){
		return $arr[$rand_keys];
	}else{
		$ret_string = '';
		for ($i=0; $i < $l ; $i++) { 
			$ret_string .=$arr[$rand_keys[$i]];
		}

		return $ret_string;
	}
}


echo rand_pick_string(array("赵","李","孙","钟","钱","冯","吴"),1);
echo "<br>";
$arr = array();
// for ($i=0; $i < 100; $i++) { 
// 	$arr[] = array(
// 			'student_id' => rand(2014000000,2014000000),
// 			 'md5_id'=> md5(rand()),
// 			 'user_name'=> rand_pick_string(array("赵","李","孙","钟","钱","冯","吴"),1) . rand_pick_string($r_char_array,2)
// 			);
// }

// var_dump($arr);




?>


</body>
</html>