<head>
<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
</head>
<form action="mission_1-4.php" method="post">
<input type="text" name="name" value="コメント">
<input type="submit" value="送信">
</form>

<?php
date_default_timezone_set('Asia/Tokyo');
$timestamp = time();
$now = date("Y/m/d H:i:s",$timestamp);
$name = $_POST['name'];
if($name != ""){
echo "「ご入力ありがとうございます。<br>";
echo " $now に $name を受け付けました。」";
}
?>
