<!DOCTYPE html>
<html>
<?php
$dsn = 'sqlite:new.db';
try
{
    $dbh = new PDO($dsn);
 
    echo 'PDO Connection Ok<BR>';
 
    $dbh->exec("CREATE TABLE PKU(id integer,name varchar(255))");
 
    print("Create Table ok<BR>\n");
 
    $dbh->exec("INSERT INTO PKU values(1,'jarjin')");
 
    echo 'Insert Data ok<BR>';
 
    $dbh->beginTransaction();
 
    $sth = $dbh->prepare('SELECT * FROM PKU');
 
    $sth->execute();
 
       //获取结果
    $result = $sth->fetchAll();
 
    print_r($result);
 
    // $dsn=null;
}
catch (PDOException $e)
{
   echo 'Connection failed: ' . $e->getMessage();
 
   $dsn = null;
}
?>
</html>
 
