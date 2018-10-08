<?php
	$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
	$user = 'ユーザー名';
	$password = 'パスワード';
	date_default_timezone_set('Asia/Tokyo');
	try{ //PDO設定
		$pdo = new PDO($dsn,$user,$password);
		$err = 0;
	 	if($pdo == null){
	  		echo "cannot connected.<br>"; //connection確認
	 	}
	 	
	 	$token = $_GET[id];
	 	$gname=$_GET[gname];
	  	$sql = "SELECT * FROM member3 where token = '$token'";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$prpfl = $stmt->fetchAll();
	 	}
	 	
	 	$login = $prpfl[0][6];
	 	$ID=$prpfl[0][0];
	 	
	 	$sql = "SELECT * FROM group3 where gid = $gname";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$gro = $stmt->fetchAll();
	 	}
	 	$group = $gro[0][1];
	 	
	 	
	 	if($_GET[yes]==1){
	 		$sql="update group3 set name = 'deleted', member = '', id = '', name = '' where gid=$gname";
	 		$stmt=$pdo->query($sql);
	 		
	 		$sql="delete from project6 where gid=$gname";
	 		$stmt=$pdo->query($sql);
	 		
	 		$sql="delete from mission2 where gid=$gname";
	 		$stmt=$pdo->query($sql);
	 		
	 		$sql = "delete from grlog3 where gid = $gname";
	 		$stmt=$pdo->query($sql);
	 		
	 		$sql="delete from groupinvite3 where gid=$gname";
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
		<title>グループの削除</title>
		<style>
			body{
				background-color: #AAFFFF;
			}
			div.all{
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
			
			a.black{
			display: inline-block;
			padding: 0.5em 1em;
			text-decoration: none;
			background: #000000;/*ボタン色*/
			color: #FFF;
			border-bottom: solid 4px #627295;
			border-radius: 3px;
			}
			a.black:hover{
			display: inline-block;
			padding: 0.5em 1em;
			text-decoration: none;
			background: #000000;/*ボタン色*/
			color: #FFFF33;
			border-bottom: solid 4px #627295;
			border-radius: 3px;
			}
			a.black:active {/*ボタンを押したとき*/
			-ms-transform: translateY(4px);
			-webkit-transform: translateY(4px);
			transform: translateY(4px);/*下に動く*/
			border-bottom: none;/*線を消す*/
			}
		</style>
	</head>
	<body>
		<div class='all'>
		<?php
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				$ID = $_GET[id];
				echo "<h1>".$group."の削除</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
				if($_GET[yes]==1){
					echo $group."を削除しました。<br>";
					echo "<a href = 'github_mission_6_mypage.php?id=";
					echo $ID;
					echo "'>マイページへ</a><br>";
				}else{
					echo $group."を削除しますか？<br>";
					echo "<a href = 'github_mission_6_delete_group.php?id=";
					echo $ID;
					echo "&gname=";
					echo $gname;
					echo "&yes=1'>はい</a>　　　　　　　　　　";
					echo "<a href='";
					echo $_SERVER['HTTP_REFERER'];
					echo "'>いいえ</a><br><br>";
					echo "<a href = 'github_mission_6_mypage.php?id=";
					echo $ID;
					echo "'>マイページへ</a><br>";
				}
			}
		?>
		</div>
	</body>
</html>
