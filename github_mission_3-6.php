<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
$sql = 'SELECT * FROM tbtest';
$results = $pdo->query($sql);
}catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
header("Content-Type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
foreach($results as $row){
 echo $row['id'].',';
 echo $row['name'].',';
 echo $row['comment'].'<br>';
}
?>
</body>
</html>
