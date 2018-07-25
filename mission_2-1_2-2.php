<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
</head>
<body>
<form action="mission_2-1_2-2.php" method="post"  >
名前: <input type="text" name="name" placeholder = "名前">
コメント: <input type="text" name="comment" placeholder = "コメント">
<input type="submit" value="送信">
</form>
<?php
$filename = 'mission_2-1_kondo.txt';
$name = $_POST['name'];
$comment = $_POST['comment'];
date_default_timezone_set('Asia/Tokyo');
$timestamp = time();
$now = date("Y/m/d H:i:s",$timestamp);

if(file_exists($filename)==FALSE){
$num = 1;
$fcontent = "";
}elseif(filesize($filename)==0){
$num = 1;
$fcontent = "";
}else{
$ffp = fopen($filename,'r');
$fcontent = fread($ffp,filesize($filename));
$num = mb_substr_count($fcontent,"\r\n") + 1;
fclose($ffp);
}

if($comment == ""){
$content = "";
$exp = "";
}else{
$content = "$num"."<>".$name."<>".$comment."<>".$now."\r\n";
$exp = "$num $name $comment $now <br>";
}

$fp = fopen($filename,'w');
fwrite($fp,$fcontent.$content);
fclose($fp);

if(file_exists($filename)==FALSE){
echo "file cannot found.";
}elseif(filesize($filename)==0){
echo $exp;
}else{
$array=file($filename);
foreach($array as $value){
$string= explode("<>",$value);
echo $string[0]." ";
echo $string[1]." ";
echo $string[2]." ";
echo $string[3]." ";
echo "<br>";
}

}

?>
</body>
</html>
