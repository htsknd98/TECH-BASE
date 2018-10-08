<?php
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
date_default_timezone_set('Asia/Tokyo');
$err = 0;
$nam = $_POST['name'];
$idd = $_POST['ID'];
$add = $_POST['address'];
$pas = $_POST['pass'];

try{ //PDO設定
	$pdo = new PDO($dsn,$user,$password);
	if($pdo == null){
		echo "cannot connected.<br>"; //connection確認
	}
	$sql = "CREATE TABLE premember1" //prememberのテーブル作成
	."("
	."id TEXT,"
	."name char(32),"
	."address TEXT,"
	."date TEXT,"
	."pass TEXT,"
	."urltoken TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	$sql = "SELECT * FROM member3"; //prememberの値を取り出す
	$stmnt = $pdo->query($sql);
	if($stmnt!=""){
		$result = $stmnt->fetchAll();
	}
	$cnt = count($result);
	if($_POST['register']){
		if($nam == "" or $idd == "" or $add == "" or $pas == ""){
			$err++;
			$clear = 1;
			if($nam==""){
				$error['name']="名前を入力してください";
			}
			if($idd==""){
				$error['id']="IDを入力してください";
			}
			if($add==""){
				$error['address']="addressを入力してください";
			}
			if($pas==""){
				$error['password']="passwordを入力してください";
			}
		}
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $add)){
			$error['address']="アドレスが正しくありません。";
			$err++;
		}
		for($i = 0; $i < $cnt; $i++){
			if($result[$i][0] == $idd){
				$error['id']="すでに使われているIDです。";
				$err++;
			}
			if($result[$i][1]==$nam){
				$error['name']="すでに使われている名前です。";
				$err++;
			}
		}
	}else{
		$err++;
		$error['id']="好きなアルファベットを入力してください。";
		$error['address']="example@○○.ne.jp";
		$error['name']="Taro Tanaka: 半角スペースで";
		$error['password'] = "アルファベットと数字でPasswordをお願いします。";
	}

	if($err ==0){//入力が問題ない場合
	//フォームの値をprememberに入れる。
	$sql = $pdo->prepare("INSERT INTO premember1(id,name, address, date, pass, urltoken) VALUES(:id,:name, :address, :date, :pass, :urltoken)");
	$sql->bindParam(':id',$id, PDO::PARAM_STR);
	$sql->bindParam(':name', $name, PDO::PARAM_STR);//nameをbind
	$sql->bindParam(':address', $address, PDO::PARAM_STR);//commentをbind
	$sql->bindParam(':date', $now, PDO::PARAM_STR);//dateをbind
	$sql->bindParam(':pass', $pass, PDO::PARAM_STR);//passをbind
	$sql->bindParam(':urltoken', $token, PDO::PARAM_STR);//tokenをbind

	//各値の割り当て
	$id = $_POST['ID'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$timestamp = time();
	$now = date("Y/m/d H:i:s",$timestamp);
	$pass = $_POST['pass'];
	$token = $_POST['token'];
	$sql->execute();

	$sql = "SELECT * FROM premember1";//prememberから選ぶ
	$stmt1 = $pdo->query($sql);
	$result1 = $stmt1->fetchAll();
	}

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
<meta http-equiv="Content-Style-Type" content="text/css">
<title>新規登録</title>
<style>
body{
background-color: #AAFFFF;
}

div{
background-color: #FFFFFF;
text-align: center;
font-size: 20px;
}

form{
text-align: left;
}

input[type="text"],
textarea {
padding: 0.8em;
outline: none;
border: 1px solid #DDD;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
font-size: 20px;
}
textarea {
width: 300px;
}

input[type="password"],
textarea {
padding: 0.8em;
outline: none;
border: 1px solid #DDD;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
font-size: 20px;
}
textarea {
width: 300px;
}

input.sample{
background-color: #FFFFFF;
}

input.sample:placeholder-shown{
background-color: #CCCCCC;
background-image: -webkit-gradient(linear, 0 0, 100% 100%,color-stop(.25, #F9F9F9), color-stop(.25, transparent),color-stop(.5, transparent), color-stop(.5, #F9F9F9),color-stop(.75, #F9F9F9), color-stop(.75, transparent),to(transparent));
-webkit-background-size: 7px 7px;
}

input.button{
display: inline-block;
padding: 0.5em 1em;
border-radius: 20%;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFF;
border-bottom: solid 4px #627295;
}
input.button:hover{
display: inline-block;
padding: 0.5em 1em;
border-radius: 20%;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFFF33;
border-bottom: solid 4px #627295;
}
input.button:active {/*ボタンを押したとき*/
-ms-transform: translateY(4px);
-webkit-transform: translateY(4px);
transform: translateY(4px);/*下に動く*/
border-bottom: none;/*線を消す*/
border-radius: 20%;
}

</style>

</head>

<body>
<div>
<h1>新規登録</h1>
<?php 
$hash = hash('sha256',uniqid(rand(),1));
?>
<?php
	if($err!=0 ){
		echo "<form action = 'github_mission_6.php' method = 'post'>";
		echo "<input type = 'hidden' name = 'token' value = '";
		echo $hash; 
		echo "'>";
		echo "<h2><label>登録アドレス</label></h2> <input type='text' name='address' placeholder = 'address@〇〇.com' class='sample'>";
		echo " ";
		if($_POST['register']){
			echo "<span style = 'color: red'>".$error['address']."</span>";
		}else{
			echo $error['address'];
		}
		echo "<br>";
		echo "<h2><label>登録ID</label></h2> <input type = 'text' name = 'ID' placeholder='IDを入力' class='sample'>";
		echo " ";
		if($_POST['register']){
			echo "<span style = 'color: red'>".$error['id']."</span>";
		}else{
			echo $error['id'];
		}
		echo "<br>";
		echo "<h2><label>氏名</label></h2> <input type = 'text' name = 'name' placeholder='名前をを入力' class='sample'>";
		echo " ";
		if($_POST['register']){
			echo "<span style = 'color: red'>".$error['name']."</span>";
		}else{
			echo $error['name'];
		}
		echo "<br>";
		echo "<h2><label>パスワード</label></h2> <input type = 'password' name = 'pass' placeholder='password' class='sample'>";
		echo " ";
		if($_POST['register']){
			echo "<span style = 'color: red'>".$error['password']."</span>";
		}else{
			echo $error['password'];
		}
		echo "<br>";
		echo "<input type='submit' value='登録' name = 'register' class='button'>";
		echo "</form>";
	}
?>
<?php 
$to = $_POST['address'];
$url = "http://tt-158.99sv-coco.com/github_mission_6_register.php"."?urltoken=".$token;
if($err!=0){
if($_POST['register']){
echo "<BIG>エラー発生。それぞれの欄の確認をお願いします。</BIG>";
}
}elseif(mail($to,"TEST MAIL", $url, "From: from@address.com")){
echo "メールが送信されました。";
echo "<br>";
}else{
echo "ダメでした。";
}
echo "<br>";
?>
<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>
</div>
</body>
</html>
