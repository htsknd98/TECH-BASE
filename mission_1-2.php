<?php 
$filename = 'mission_1-2_syadan.txt';
$fp = fopen($filename,'w');
fwrite($fp,'test');
fclose($fp);
?>
