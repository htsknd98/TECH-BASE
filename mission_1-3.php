<?php 
$filename = 'mission_1-2_syadan.txt';
$fp = fopen($filename,'r');
$news = fread($fp,filesize($filename));
fclose($fp);
echo $news;
?>

