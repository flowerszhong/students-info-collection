<!doctype html>
<html>
	<head>
	    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	</head>
	<body>
		
<?php 
function filter($data) {
	// $data = trim($data);
	// $data = trim(htmlentities(strip_tags($data)));
	$data = trim(strip_tags($data));
	
	// if (get_magic_quotes_gpc())
	// 	$data = stripslashes($data);
	
	$data = mysql_real_escape_string($data);
	

	return $data;
}

echo filter("钟云辉");
 ?>
	</body>

</html>
