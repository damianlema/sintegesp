<?php
$a = 376953.00 + 47.00;
$b = 376952.46;
$c = 47.54;

$ab = round($a-$b,2);
$abc = round($ab,2)-round($c,2);

echo $a."<br>";
echo $b."<br>";
echo $c."<br>";
echo $ab."<br>";
echo $abc."<br>";
?>