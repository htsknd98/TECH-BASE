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

		$ID = $_GET[ID];
		$pass = $_GET[pass];

	 	$sql = "SELECT * FROM member3 where id = '$ID'";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
			$result = $stmt->fetchAll();
		}
		if(!empty($result)){
			$password = $result[0][4];
		}
	if($pass!=$password){
		$url="github_mission_6_loginform.php?error=set";
		
	}elseif($pass==""){
		$url = "github_mission_6_loginform.php?error=set";
	}else{
		$sql = "update member3 set login = 1 where id = '$ID'";
		$stmt = $pdo->query($sql);
		
		$TOKEN = hash('sha256',uniqid(rand(),1));
		$sql = "update member3 set token='$TOKEN' where id='$ID'";
		$pdo->query($sql);
		
		$url = "github_mission_6_mypage.php?id=".$TOKEN;
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
	</head>
	<body>
		<?php
			if($pass!=$password){
				echo "ＥＲＲＯＲ！<br>";
		
			}elseif($pass==""){
				echo "ＥＲＲＯＲ！<br>";
			}else{
				echo "ログインします。<br>";
			}
			
			echo "<a href = '";
			
			echo $url."'>ここをクリック</a><br>";

		?>
	</body>
</html>



	