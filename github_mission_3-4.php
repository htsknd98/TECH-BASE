<?php 
$dsn = 'mysql:dbname=�f�[�^�x�[�X��;host=localhost;charset=utf8';
$user = '���[�U�[��';
$password = '�p�X���[�h';
try{ //PDO�ݒ�
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection�m�F
 }
}catch(PDOException $e){
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection�m�F
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

