<?php 
$dsn = 'mysql:dbname=�f�[�^�x�[�X��;host=localhost;charset=utf8';
$user = '���[�U�[��';
$password = '�p�X���[�h';
try{ //PDO�ݒ�
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection�m�F
 }
 $sql = $pdo->prepare("INSERT INTO tbtest(id,name, comment) VALUES ('1',:name, :comment)");//table�ɒl������
 $sql->bindParam(':name', $name, PDO::PARAM_STR);//name�̌^�̐ݒ�
 $sql->bindParam(':comment', $comment, PDO::PARAM_STR);//comment�̌^�̐ݒ�
 $name = 'honda';//name�ɑ��
 $comment = 'demio';//comment�ɑ��
 $sql->execute();//���s
}catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection�m�F
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
