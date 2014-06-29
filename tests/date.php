<?php
echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("Y.m.d") . "<br>";
echo "Today is " . date("Y-m-d") . "<br>";
echo "Today is " . date("Ymd") . "<br>";
echo "Today is " . date("l");


$date1 = strtotime('20140930');

echo date("Y/m/d",$date1);

$date2 = strtotime("20140502");
echo date("Y/m/d",$date2);

echo date('m',$date2)+1;

echo "<br>";


echo time() > $date1;

echo time() > $date2;
?>