<?php 
include 'dbc.php';



// $insert = "INSERT INTO `payments`(`student_id`,`pay_date`,`pay_amount`,`pay_month`,`id`) VALUES (333,'2014-06-28','300',10,NULL) ";
// $stmt = $db->exec($insert);


$sql_select = "SELECT pay_date FROM payments";

$rrows = $db->query($sql_select) or die(showDBError());

$rows = $rrows->fetch();

var_dump($rows);

?>