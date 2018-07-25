<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
 $sql = "CREATE TABLE tbtest"
 ."("
 ."id INT,"
 ."name char(32),"
 ."comment TEXT"
 .");";
 $stmt = $pdo->query($sql);"
 }catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
?>

 