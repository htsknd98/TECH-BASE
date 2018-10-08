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
 	$ID = $_GET[id];
 	$gID=$_GET[gname];
 	$now = date("Y/m/d H:i:s",time());
 	
 	
 	$sql = "SELECT * FROM member3 where token = '$ID'";
 	$stmt = $pdo->query($sql);
 	if($stmt!=""){
 		$prpfl=$stmt->fetchAll();
 	}
 	
 	$login = $prpfl[0][6];
 	$mem0 = $prpfl[0][1];
 	
 	$sql = "SELECT * FROM group3 where gid = $gID";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$result = $stmt->fetchAll();
	}
	$gname = $result[0][1];
 	if($_GET[yes]==1){
 		$sql = "SELECT * FROM group3 where member = '$mem0' and gid = $gID";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$cresult = $stmt->fetchAll();
		}
		
		$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$logg = $stmt->fetchAll();
		}
		if(!empty($logg)){
			$num = count($logg) + 1;
		}else{
			$num = 1;
		}
		$comment=$mem0."が".$gname.'に参加しました。';
		$user = "連絡";
		$froma='all members';
		$pass='a';
		
		if((!empty($result) and !empty($prpfl))and empty($cresult)){
			$sql = $pdo->prepare("INSERT INTO group3(gid,name,member,id) VALUES(:gid,:name,:member,:id)");
			$sql->bindParam(':name',$name,PDO::PARAM_STR);
			$sql->bindParam(':member',$mem,PDO::PARAM_STR);
			$sql->bindParam(':id',$ID1,PDO::PARAM_STR);
			$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
			$name = $gname;
			$mem = $mem0;
			$ID1 = $prpfl[0][0];
			$sql->execute();
			
			$sql = "delete from groupinvite3 where mem='$mem0' and gid=$gID";
			$stmt=$pdo->query($sql);
			
			
		}
		$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
		$sql->bindParam(':id',$num,PDO::PARAM_INT);
		$sql->bindParam(':name',$user,PDO::PARAM_STR);
		$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql->bindParam(':time',$now,PDO::PARAM_STR);
		$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
		$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
		$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
		$sql->execute();
 	}
 	
}catch(PDOException $e){
 	header("Content-Type: text/plain; charset=utf-8",true,500);
 	echo ('Connection failed:'.$e->getMessage());
 	die(); //connection失敗
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
		<title>グループの参加</title>
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
				echo "<h1>グループ参加</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
				if($_GET[yes]==1){
					echo $gname."に参加しました。<br>";
					echo "<a href = 'github_mission_6_group.php?name=";	
					echo $gID;
					echo "&id=";
					echo $ID;
					echo "'>グループページへ</a>";
				}else{
					echo $gname."に参加しますか？<br>";
					echo "<a href = 'github_mission_6_come_group.php?gname=";	
					echo $gID;
					echo "&id=";
					echo $ID;
					echo "&yes=1'>はい</a>";
					
					echo "　　　　　";
					echo "<a href = '";	
					echo $_SERVER['HTTP_REFERER'];
					echo "'>いいえ</a>";
				}
			}
		?>
		</div>
	</body>
</html>

