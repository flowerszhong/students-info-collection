<?php 
include '../dbc.php';



var_dump($db);

$sql_select = "SELECT pay_date FROM payments";

$rrows = $db->query($sql_select) or die(showDBError());

$rows = $rrows->fetch();

var_dump($rows);

?>