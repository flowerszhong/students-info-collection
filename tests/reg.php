
<html>
	<head>
		<meta charset="utf-8" />
	</head>
</html>
<?php 

$str = "";
    $words = preg_split("/[0-9]+/", $str)[0];
    var_dump($words);

if (preg_match('/[0-9]/',$str,$matchs)){
    echo 'true';
    echo $matchs[0];

}



$pos = strrpos($str, '/[0-9]/');
if ($pos === false) { // note: three equal signs
    echo "not found";
}else{
	echo $pos;
}

 ?>