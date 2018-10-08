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
 		$pname=$_GET[pname];
 		$gname=$_GET[gname];
 		$sql = "SELECT * FROM member3 where token = '$ID'";
 		$stmt = $pdo->query($sql);
 		if($stmt!=""){
 			$prpfl=$stmt->fetchAll();
 		}
 		$login = $prpfl[0][6];
 		
 		$sql = "SELECT * FROM project6 where pid = $pname and gid = $gname";
 		$stmt=$pdo->query($sql);
 		if($stmt!=""){
 			$proj = $stmt->fetchAll();
 		}
 		$project = $proj[0][0];
 		$group = $proj[0][5];
 		
 		if($_GET[yes]==1){
 			$sql = "SELECT * FROM project6 where pid = $pname and gid = $gname";
 			$stmt=$pdo->query($sql);
 			if($stmt!=""){
 				$proj = $stmt->fetchAll();
 			}
 			
 			$sql="update project6 set name = 'deleted', fdate = '', tdate='',fmonth = '',tmonth='',mem = '',gname = ''  where pid=$pname and gid=$gname";
 			$pdo->query($sql);
 			
 			$sql="delete from mission2 where pid=$pname and gid=$gname";
 			$pdo->query($sql);
 			if(!empty($proj)){
 				foreach($proj as $mem){
	 				if($froma!=""){
						$froma = $froma.",".$mem[6];
					}else{
						$froma = $mem[6];
	 				}
 				}
 			}
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
		 	$pass = 'a';
		 	$comment = $group.'のプロジェクト:'.$project.'を削除しました。';
		 	
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
		<title>プロジェクトの削除</title>
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
				$pnam = $_GET[pname];
				$gname = $_GET[gname];
				$ID = $_GET[id];
				echo "<h1>".$project."の削除</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
				if($_GET[yes]==1){
				echo $project."を削除しました。<br>";
				echo "<a href = 'github_mission_6_group.php?name=";
				echo $gname;
				echo "&id=";
				echo $ID;
				echo "'>グループページへ</a><br>";
				}else{
				echo $project."を削除しますか？<br>";
				echo "<a href = 'github_mission_6_delete_project.php?gname=";
				echo $gname;
				echo "&id=";
				echo $ID;
				echo "&pname=";
				echo $pnam;
				echo "&yes=1'>はい</a>　　　　　　　　　　";
				echo "<a href = '";	
				echo $_SERVER['HTTP_REFERER'];
				echo "'>いいえ</a><br>";
				}
				echo "<a href = 'github_mission_6_mypage.php?id=";	
				echo $ID;
				echo "'>マイページへ</a>";
			}
		?>
		</div>
	</body>
</html>
