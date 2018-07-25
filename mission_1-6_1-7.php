<head>
<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
</head>
<form action="mission_1-6_1-7.php" method="post"  >
<input type="text" name="name" value="" placeholder = "コメント">
<input type="submit" value="送信">
</form>

<?php
date_default_timezone_set('Asia/Tokyo');
$timestamp = time();
$now = date("Y/m/d H:i:s",$timestamp);
$name = $_POST['name'];

$filename = 'mission_1-6_kondo.txt';
if($name==""){
$content=$name;
}else{
$content=$name."\n";
}
if(file_exists($filename)==FALSE){
$fp = fopen($filename,'w');
fwrite($fp,$content);
fclose($fp);
}elseif(filesize($filename)==0){
$fp = fopen($filename,'w');
fwrite($fp,$content);
fclose($fp);
}else{
$ffp = fopen($filename,'r');
$fcontent = fread($ffp,filesize($filename));
$fp = fopen($filename,'w');
fwrite($fp,$fcontent.$content);
fclose($fp);
fclose($ffp);
}

if(file_exists($filename)==FALSE){
echo "file cannot found.";
}elseif(filesize($filename)==0){
echo "$name";
}else{
$fpp = fopen($filename,'r');
while(($buffer = fgets($fpp,4096))!=false){
$array[]=$buffer;
}
fclose($fpp);
foreach($array as $value){
echo $value;
echo "<br>";
}
}
?>
