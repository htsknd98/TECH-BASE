<head>
<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
</head>
<form action="mission_1-5.php" method="post"  >
<input type="text" name="name" value="" placeholder = "コメント">
<input type="submit" value="送信">
</form>

<?php
header("Content-Type: text/html; charset = UTF-8");
date_default_timezone_set('Asia/Tokyo');
$timestamp = time();
$now = date("Y/m/d H:i:s",$timestamp);
$name = $_POST['name'];

$filename = 'mission_1-5_kondo.txt';
if(filesize($filename)!=0){
$ffp = fopen($filename,'r');
$fcontent = fread($ffp,filesize($filename));
}else{
$fcontent = "";
}
$fp = fopen($filename,'w');
if($name==""){
$content = $fcontent;
}elseif($name=="完成！"){
$content="おめでとう！";
}else{
$content=$name;
}


fwrite($fp,$content);
fclose($fp);

echo $content;

?>
