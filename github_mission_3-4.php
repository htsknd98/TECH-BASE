<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
}catch(PDOException $e){
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
?>

<?php
$sql = 'SHOW CREATE TABLE tbtest';//
$result = $pdo->query($sql);

foreach ($result as $row){
 print_r($row);
}
echo"<hr>";

?>

