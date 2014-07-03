<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title></title>
</head>
<body>

</body>
</html>

<?php 
include 'dbc.php';
session_start();
if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$ret = $_SERVER['HTTP_REFERER'];

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}


if($get['cmd'] == 'associate'){
	$relid = $get['id'];
	$relids = explode(",", $relid);

	$relids_length = count($relids);

	$db->beginTransaction();
	$update_ok = associateNetIDs($relids);
	$db->commit();

	echo $update_ok;


	// if($relids_length > 1){
	// 	echo $relids_length;
	// 	$db->beginTransaction();
	// 	for ($i=0; $i < $relids_length ; $i++) { 
	// 		associateNetID($relids[$i]);
	// 	}
	// 	$db->commit();
	// 	associateNetID($relid);
	// }

}

function associateNetIDs($ids)
{
	$ids_length = count($ids);
	global $db;

	$sql_select = "select * from accounts order by id desc limit $ids_length";
	$select_rows = $db->query($sql_select);
	$rows = $select_rows->fetchAll();

	echo $insert_stmt;


	$cursor = 0;

	foreach ($rows as $record) {
		$net_id = $record['account_id'];
		$net_pwd = $record['account_pwd'];
		$id = $ids[$cursor];
		$record_id = $record['id'];

		echo $net_id;
		echo $net_pwd;
		echo $id;

		echo "<br>";

		$sql_udpate_students = "update students set net_id='$net_id',net_pwd='$net_pwd' where id=$id";
		$db->exec($sql_udpate_students) or die(showDBError());

		$sql_udpate_account = "update accounts set used=1 where id=$record_id";
		$db->exec($sql_udpate_account) or die(showDBError());

		$cursor++;
	}

	return "ok";


}

function associateNetID($userid)
{
	global $db;

	// echo $userid;
	$sql_select = "select * from accounts order by id desc limit 1";
	$select_row = $db->query($sql_select);
	$row = $select_row->fetch();

	if(!$row){
		echo "未关联成功，无可用账号；";
		return "未关联成功，无可用账号；";
	}

	// var_dump($row);

	$row_id = $row['id'];
	// echo $row_id;
	$net_id = $row['account_id'];
	$net_pwd = $row['account_pwd'];



	$sql_select1 = "select user_email from students where id=$userid";
	$select_row1 = $db->query($sql_select1);
	$row1 = $select_row1->fetch();
	$user_email = $row1['user_email'];

	// echo $user_email;


	$update_students = "update students set net_id=$net_id,net_pwd=$net_pwd where id=$userid";
	$db->exec($update_students);

	$subject = "你的上网账号已关联";
	$message = getAssociateMessage($net_id,$net_pwd);
	$smtpDone = sendEmail($subject,$message,$user_email);
	// echo $message;

	// echo $smtpDone;
	$update_account = "update accounts set used ='1',user_id='$userid' where id='$row_id'";
	$db->exec($update_account) or die(showDBError());

	echo $userid;

	// if($smtpDone){
	// 	echo "update accounts";
	// 	echo "<br>";
	// 	echo $userid;
	// 	$update_account = "update accounts set used ='1',user_id='$userid' where id='$row_id'";

	// 	// echo $update_account;
	// 	$db->exec($update_account) or die(showDBError());
	// }

}

function getAssociateMessage($net_id,$net_pwd)
{

	$message = "<p>hi $user_name,你的上网账号已经被分配</p>
    <ul>
        <li>账号: $net_id </li>
        <li>密码: $net_pwd</li>
    </ul>

    <p>Thank You</p>

    <p>______________________________________________________</p>
    该邮件为系统自动发现，请不要回复。<br/>
    THIS IS AN AUTOMATED RESPONSE. <br/>
    ***DO NOT RESPOND TO THIS EMAIL****<br/>
    ";

    return $message;
}



if($get['cmd'] == 'approve')
{
mysql_query("update users set approved='1' where id='$get[id]'") or die(mysql_error());
$rs_email = mysql_query("select user_email from users where id='$get[id]'") or die(mysql_error());
list($to_email) = mysql_fetch_row($rs_email);

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

$message = 
"Thank you for registering with us. Your account has been activated...

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

	@mail($to_email, "User Activation", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion());


 echo "Active";


}

if($get['cmd'] == 'ban')
{
mysql_query("update users set banned='1' where id='$get[id]' and `user_name` <> 'admin'");

//header("Location: $ret");  
echo "yes";
exit();

}
/* Editing users*/

if($get['cmd'] == 'edit')
{
/* Duplicate user name check */
$rs_usr_duplicate = mysql_query("select count(*) as total from `users` where `user_name`='$get[user_name]' and `id` != '$get[id]'") or die(mysql_error());
list($usr_total) = mysql_fetch_row($rs_usr_duplicate);
	if ($usr_total > 0)
	{
	echo "Sorry! user name already registered.";
	exit;
	} 
/* Duplicate email check */	
$rs_eml_duplicate = mysql_query("select count(*) as total from `users` where `user_email`='$get[user_email]' and `id` != '$get[id]'") or die(mysql_error());
list($eml_total) = mysql_fetch_row($rs_eml_duplicate);
	if ($eml_total > 0)
	{
	echo "Sorry! user email already registered.";
	exit;
	}
/* Now update user data*/	
mysql_query("
update users set  
`user_name`='$get[user_name]', 
`user_email`='$get[user_email]',
`user_level`='$get[user_level]'
where `id`='$get[id]'") or die(mysql_error());
//header("Location: $ret"); 

if(!empty($get['pass'])) {
$hash = PwdHash($get['pass']);
mysql_query("update users set `pwd` = '$hash' where `id`='$get[id]'") or die(mysql_error());
}

echo "changes done";
exit();
}

if($get['cmd'] == 'unban')
{
mysql_query("update users set banned='0' where id='$get[id]'");
echo "no";

//header("Location: $ret");  
// exit();

}


?>