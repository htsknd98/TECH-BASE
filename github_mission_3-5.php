<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
 $sql = $pdo->prepare("INSERT INTO tbtest(id,name, comment) VALUES ('1',:name, :comment)");//tableに値を入れる
 $sql->bindParam(':name', $name, PDO::PARAM_STR);//nameの型の設定
 $sql->bindParam(':comment', $comment, PDO::PARAM_STR);//commentの型の設定
 $name = 'honda';//nameに代入
 $comment = 'demio';//commentに代入
 $sql->execute();//実行
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

echo mb_detect_encoding($name);
echo mb_detect_encoding($comment);

?>
</body>
</html>
