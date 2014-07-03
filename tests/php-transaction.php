<?php
// create a PDO object
$dbh = new PDO('sqlite:mydb.sdb');

// Start transaction
$dbh->beginTransaction();
$lines = file('/csv/file.txt'); // import lines as array
foreach ($lines as $line) {
    $line_array = (','$line); // create an array of comma-separated values in each line
    $values = '';
    foreach ($line_array as $l) {
        $values .= "'$l', ";
    }
    substr($values,-2,0); // get rid of the last comma and whitespace
    $query = "insert into sqlite_table values ($values)"; // plug the value into a query statement
    $dbh->query($query); // run the query
}
// commit transaction
$dbh->commit();

?>