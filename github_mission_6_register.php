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
 	$sql = "CREATE TABLE member3"
 	."("
	."id TEXT,"
 	."name char(32),"
 	."address TEXT,"
	."date TEXT,"
	."password TEXT,"
	."token TEXT,"
	."login INT"
 	.");";
 	$stmt = $pdo->query($sql);
	$token = $_GET[urltoken];

	if($token!=""){
		$sql = "SELECT * FROM premember1 where urltoken = '$token'";
		$stmmt = $pdo->query($sql);
		
		$sql = "SELECT * FROM member3";
		$stmt = $pdo->query($sql);
		
		if($stmmt!=""){
			$result = $stmmt->fetchAll();
			$err=0;
			if($stmt!=""){
				$presult = $stmt->fetchAll();
				foreach($presult as $value){
					if($value[0] == $result[0][0]){
						$err++;
						$error['id']="すでにIDが登録されています。";
					}
					if($value[0] == $result[0][1]){
						$err++;
						$error['name']="すでに名前が登録されています。";
					}
				}
				if($err==0){
					$sql = $pdo->prepare("INSERT INTO member3(id, name, address, date, password,token,login) VALUES(:id, :name, :address, :date, :password,:token,:login)");
					$sql->bindParam(':id',$id, PDO::PARAM_STR);
					$sql->bindParam(':name', $name, PDO::PARAM_STR);//nameをbind
					$sql->bindParam(':address', $address, PDO::PARAM_STR);//commentをbind
					$sql->bindParam(':date',$now,PDO::PARAM_STR);
					$sql->bindParam(':password',$pass,PDO::PARAM_STR);
					$sql->bindParam(':token',$TOKEN,PDO::PARAM_STR);
					$sql->bindParam(':login',$login,PDO::PARAM_INT);
					$id = $result[0][0];
					$name = $result[0][1];
					$address = $result[0][2];
					$timestamp = time();
					$now = date("Y/m/d H:i:s",$timestamp);
					$pass = $result[0][4];
					$TOKEN = hash('sha256',uniqid(rand(),1));
					$login = 0;
					
					$sql->execute();

					$sql="SELECT * FROM member3";
					$sttmnt = $pdo->query($sql);
					$result1 = $sttmnt->fetchAll();
				}
			}
		}
	}else{
		$err++;
		$error['token']="登録のし直しをお願いします。";
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
<title>メンバー登録</title>
<style>
body{
background-color: #AAFFFF;
}

div{
background-color: #FFFFFF;
text-align: center;
font-size: 20px;
}

a.button{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFF;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.button:hover{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFFF33;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.button:active {/*ボタンを押したとき*/
-ms-transform: translateY(4px);
-webkit-transform: translateY(4px);
transform: translateY(4px);/*下に動く*/
border-bottom: none;/*線を消す*/
}

</style>
</head>
<body>
	<?php
		echo "<div>";
		echo "<h1>メンバー登録</h1>";
		if($err==0){
			echo "登録が完了しました。"."<br>";
			echo "ID:".$result[0][0]."<br>";
			echo "name:".$result[0][1]."<br>";
			echo "<a href = 'github_mission_6_loginform.php' class='button'>ログイン画面へ</a><br>";
		}else{
			foreach($error as $val){
				echo $val;
				echo "<br>";
			}
		}
		echo "<a href = 'github_mission_6.php'>メンバー登録</a>";
		echo "</div>";
	?>

</body>
</html>

