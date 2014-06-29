<?php 
include '../dbc.php';


$sql_insert = "INSERT INTO `payments`(`student_id`,`pay_date`,`pay_amount`,`pay_month`,`id`) VALUES (4444,444,444,444,NULL)";

$db->exec($sql_insert);

$sql_select = "SELECT last_insert_rowid() as last_insert_cid";
 
$rrows = $db->query($sql_select) or die(showDBError());

$rows = $rrows->fetch();

var_dump($rows);

?>