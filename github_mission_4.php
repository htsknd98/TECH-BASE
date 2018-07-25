<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
date_default_timezone_set('Asia/Tokyo');
try{ //PDO設定
 $pdo = new PDO($dsn,$user,$password);
 if($pdo == null){
  echo "cannot connected.<br>"; //connection確認
 }
 $sql = "CREATE TABLE kondo1"
 ."("
 ."id INT,"
 ."name char(32),"
 ."comment TEXT,"
 ."date TEXT,"
 ."password TEXT"
 .");";
 $stmt = $pdo->query($sql);
 
 
 $sql = "SELECT * FROM kondo1 ORDER BY id";
 $stm = $pdo->query($sql);
 $presult = $stm->fetchAll();
 if(empty($presult)){
  $num = 1;
 }else{
  $num = count($presult) + 1;
 }
 
 if(isset($_POST['delete'])){
  $did = $_POST['number'] - 1;
  $id = $_POST['number'];
  if($presult[$did][4]=="" or $_POST['dpass']==$presult[$did][4]){
   if($id != 0){
    $sql = "delete from kondo1 where id = $id";
    $stmt = $pdo->query($sql);
   
    for($i = $id; $i <= $num; $i++){
     $id1 = $i;
     $id2 = $i - 1;
     $sql = "update kondo1 set id = '$id2' where id = $id1";
     $stamt = $pdo->query($sql);
    }
   }
  }
 }elseif(isset($_POST['edit'])){
  $eid = $_POST['numb'] - 1;
  $id = $_POST['numb'];
  if($presult[$eid][4]=="" or $_POST['epass']==$presult[$eid][4]){
   if($id != 0){
    $sql = "SELECT * FROM kondo1 where id = $id";
    $estmt = $pdo->query($sql);
    $eresult = $estmt->fetchAll();
   }
  }
 }elseif(isset($_POST['reset'])){
  for($j = 1; $j < $num; $j++ ){
   $id = $j;
   $sql = "delete from kondo1 where id = $id";
   $stmt5 = $pdo->query($sql);
  }
 }elseif(isset($_POST['submit'])){
  if($_POST['ednum']!=""){
   $id = $_POST['ednum'];
   $name = $_POST['name'];
   $comment = $_POST['comment'];
   $password = $_POST['pass'];
   $timestamp = time();
   $now = date("Y/m/d H:i:s",$timestamp);
   $sql = "update kondo1 set name = '$name', comment = '$comment', date = '$now', password = '$password' where id = $id";
   $stmt4 = $pdo->query($sql);
  }elseif($_POST['comment']!=""){
   $sql = $pdo->prepare("INSERT INTO kondo1(id,name, comment, date, password) VALUES(:id, :name, :comment, :date, :password)");
   $sql->bindParam(':id',$n, PDO::PARAM_INT);
   $sql->bindParam(':name', $name, PDO::PARAM_STR);//nameをbind
   $sql->bindParam(':comment', $comment, PDO::PARAM_STR);//commentをbind
   $sql->bindParam(':date',$now,PDO::PARAM_STR);
   $sql->bindParam(':password',$password,PDO::PARAM_STR);
   $n = $num; //変数設定
   $name = $_POST['name'];
   $comment = $_POST['comment'];
   $password = $_POST['pass'];
   $timestamp = time();
   $now = date("Y/m/d H:i:s",$timestamp);
   $sql->execute();
  }
 }

 $sql = "SELECT * FROM kondo1 ORDER BY id";
 $stmt2 = $pdo->query($sql);
 $results = $stmt2->fetchAll();
 //データの挿入、削除
}catch(PDOException $e){
 header("Content-Type: text/plain; charset=utf-8",true,500);
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection失敗
}
header("Content-Type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
</head>
<body>

<?php 
$editnum = $eresult[0][0];
$edname = $eresult[0][1];
$edcomment = $eresult[0][2];
$editpass = $eresult[0][4];
?>

<form action="github_mission_4.php" method="post"  >
<input type = "hidden" name="ednum" value =<?php echo $editnum; ?>>
名前: <input type="text" name="name" placeholder = "名前" value =<?php echo $edname; ?>>
コメント: <input type="text" name="comment" placeholder = "コメント" value = <?php echo $edcomment; ?>>
pass: <input type = "text" name="pass" placeholder = "パスワードを入力してください" value = <?php echo $editpass; ?>>
<input type="submit" value="送信" name = "submit">
<br><br>
削除対象番号: <input type = "text" name = "number" placeholder = "削除したい番号">
pass: <input type = "text" name = "dpass" placeholder = "Password">
<input type = "submit" value = "削除" name = "delete">

<br>
編集対象番号: <input type = "text" name = "numb" placeholder = "編集したい番号">
pass: <input type = "text" name = "epass" placeholder = "Password">
<input type = "submit" value = "編集" name = "edit">
<br>
<input type = "submit" value = "リセット" name = "reset">
</form>

<?php
echo "<hr>";
foreach($results as $row){
 echo $row['id'].' ';
 echo $row['name'].' ';
 echo $row['comment'].' ';
 echo $row['date'].'<br>';
}

?>
</body>
</html>
