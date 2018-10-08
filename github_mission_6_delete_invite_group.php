<?php
	$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
	$user = 'ユーザー名';
	$password = 'パスワード';
	date_default_timezone_set('Asia/Tokyo');
	try{ //PDO設定
		$pdo = new PDO($dsn,$user,$password);
		$err = 0;
		$now = date("Y/m/d H:i:s",time());
	 	if($pdo == null){
	  		echo "cannot connected.<br>"; //connection確認
	 	}
	 	$ID = $_GET[id];
 		$gname=$_GET[gname];
 		$sql = "SELECT * FROM member3 where token = '$ID'";
 		$stmt = $pdo->query($sql);
 		if($stmt!=""){
 			$prpfl=$stmt->fetchAll();
 		}
 		$login = $prpfl[0][6];
 		$member=$prpfl[0][1];
 		
 		$sql = "SELECT * FROM group3 where gid = $gname";
 		$stmt = $pdo->query($sql);
 		if($stmt!=""){
 			$gro = $stmt->fetchAll();
 		}
 		$group = $gro[0][1];
 		
 		if($_GET[yes]==1){
 			if($_GET[name]!=""){
 				$member = $_GET[name];
 			}
 			echo $member;
 			
 			$sql = "delete from groupinvite3 where mem = '$member' and gid = $gname";
 			$stmt = $pdo->query($sql);
 			
 			$sql = "SELECT * FROM grlog3 where gid = $gname order by id asc";
 			$stmt=$pdo->query($sql);
 			if($stmt!=""){
 				$grl = $stmt->fetchAll();
 			}
 			if(!empty($grl)){
 				foreach($grl as $XX){}
 				$num = count($grl) +1;
 			}else{
 				$num = 1;
 			}
 			$user = '連絡';
 			$froma = 'all members';
 			$comment = $member.'の'.$group.'への招待を取り消しました。';
 			
 			$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
			$sql->bindParam(':id',$num,PDO::PARAM_INT);
			$sql->bindParam(':name',$user,PDO::PARAM_STR);
			$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
			$sql->bindParam(':time',$now,PDO::PARAM_STR);
			$sql->bindParam(':gname',$group,PDO::PARAM_STR);
			$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
			$sql->bindParam(':gid',$gname,PDO::PARAM_INT);
			$sql->execute();
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
		<title>グループ招待の取消</title>
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
		<div class ="all">
		<?php
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				$gname = $_GET[gname];
				echo "<h1>";
				if($_GET[name]!=""){
					echo $_GET[name].'の';
				} 
				echo $group."への招待取消</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
				if($_GET[yes]==1){
					if($_GET[name]!=""){
						echo $_GET[name].'の';
					} 
					echo $group."への招待を取り消しました。<br>";
					echo "<a href = 'github_mission_6_mypage.php?id=";
					echo $ID;
					echo "'>マイページへ</a><br>";
					if($_GET[name]!=""){
						echo "<a href = 'github_mission_6_group.php?id=";
						echo $ID;
						echo "&name=";
						echo $gname;
						echo "'>グループページへ</a>";
					}
					
				}else{
				if($_GET[name]!=""){
					echo $_GET[name].'の';
				} 
				echo $group."への招待を取り消しますか？<br>";
				echo "<a href = 'github_mission_6_delete_invite_group.php?gname=";
				echo $gname;
				echo "&id=";
				echo $ID;
				if($_GET[name]!=""){
					echo "&name=";
					echo $_GET[name];
				}
				echo "&yes=1'>はい</a>　　　　　　　　　　";
				echo "<a href = '";	
				echo $_SERVER['HTTP_REFERER'];
				echo "'>いいえ</a>";
				}
			}
		?>
		</div>
	</body>
</html>
