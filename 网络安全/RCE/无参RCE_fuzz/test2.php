<?php
$test1 =  gzcompress("abcdef");
echo $test1 .PHP_EOL;

$test2 =  gzuncompress($test1);
echo $test2;
?>