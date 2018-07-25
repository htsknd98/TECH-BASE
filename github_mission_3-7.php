<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
$id = 1;
$nm = "車";
$kome = "日産";
$sql = "update tbtest set name = '$nm', comment='$kome' where id = $id";
$results = $pdo->query($sql);
}catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
?>

