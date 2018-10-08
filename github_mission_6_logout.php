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

		$token = $_GET[id];
		$ans = $_GET[ans];
		
		if($ans=='yes'){
			$sql = "update member3 set login = 0 where token = '$token'";
			$pdo->query($sql);
			
			$sql="update member3 set token='' where token='$token'";
			$pdo->query($sql);
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
		<title>ログアウト</title>
		<style>
		body{
			background-color: #AAFFFF;
		}
		div{
			background-color: #FFFFFF;
			text-align: center;
			font-size: 25px;
			margin: 300px auto;
		}

		a{
			display: inline-block;
			padding: 0.5em 1em;
			text-decoration: none;
			background: #668ad8;/*ボタン色*/
			color: #FFF;
			border-bottom: solid 4px #627295;
			border-radius: 3px;
		}
		a:hover{
			display: inline-block;
			padding: 0.5em 1em;
			text-decoration: none;
			background: #668ad8;/*ボタン色*/
			color: #FFFF33;
			border-bottom: solid 4px #627295;
			border-radius: 3px;
		}
		a:active {/*ボタンを押したとき*/
			-ms-transform: translateY(4px);
			-webkit-transform: translateY(4px);
			transform: translateY(4px);/*下に動く*/
			border-bottom: none;/*線を消す*/
		}
		</style>
	</head>
	<body>
	<div>
		<?php
			echo "<h1>ログアウト</h1>";
			if($ans == 'yes'){
				echo "ログアウトしました。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				echo "ログアウトします。<br>";
				echo "<a href = 'github_mission_6_logout.php?id=";
				echo $token;
				echo "&ans=yes'>確認</a>";
				echo "　　　　　　　　";
				echo "<a href='";
				echo $_SERVER['HTTP_REFERER'];
				echo "'>前に戻る</a><br>";
			}
		?>
	</div>
	</body>
</html>

