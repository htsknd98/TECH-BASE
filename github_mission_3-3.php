<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
 $sql = 'SHOW TABLES'; 
 $result = $pdo->query($sql); 
}catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php

foreach ($result as $row){ //テーブルから配列に何とかして入れて表示
 echo $row[0];
 echo '<br>';
}
echo"<hr>";

?>
</body>
</html>

