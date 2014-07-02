<?php 
include 'dbc.php';

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

/******** EMAIL ACTIVATION LINK**********************/
if(isset($get['user']) && !empty($get['activ_code']) && !empty($get['user']) && is_numeric($get['activ_code']) ) {

$err = array();
$msg = array();

$user = sqlite_escape_string($get['user']);
$activ = sqlite_escape_string($get['activ_code']);

//check if activ code and user is valid
$rs_check = $db->query("select student_id from students where md5_id='$user' and activation_code='$activ'") or die (print_r($db->errorInfo())); 
$rs_check_row = $rs_check->fetch();
// var_dump($rs_check_row);

$hasRecord = $rs_check_row['student_id'];
  // Match row found with more than 1 results  - the user is authenticated. 
  if ( !$hasRecord) { 
   $err[] = "账号不存在或激活码失效";
	 // $err[] = "Sorry no such account exists or activation code invalid.";
	header("Location: activate.php?msg=$msg");
	exit();
	}

if(empty($err)) {
// set the approved field to 1 to activate the account
$rs_activ = $db->exec("update students set approved='1' WHERE 
						 md5_id='$user' AND activation_code = '$activ' ") or die(print_r($db->errorInfo()));
$msg[] = "Thank you. 您的账号已经激活";
header("Location: activate.php?done=1");						 
exit();
 }
}
?>


<?php 
include 'includes/head.php';
if($get['done']){
?>

<h4 class="title">您的账号已经激活</h4>
<p>你可点此登录<a href="login.php">点此</a></p>

<?php 
include 'includes/footer.php';
}?>




