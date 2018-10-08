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
 	$gID = $_GET[gname];
 	$pID = $_GET[pname];
 	$mID = $_GET[mname];
 	//$group = $_GET[gname];
 	//$project=$_GET[pname];
 	//$mission = $_GET[mname];
 	$date=$_GET[date];
 	
 	$sql = "SELECT * FROM member3 where token = '$ID'";
 	$stmt = $pdo->query($sql);
 	if($stmt!=""){
 		$prpfl=$stmt->fetchAll();
 	}
 	
 	$login = $prpfl[0][6];
 	$name=$prpfl[0][1];
 	
 	$sql="SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date' and mid = $mID";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$mnamedate0 = $stmt->fetchAll();
	}
	$gname = $mnamedate0[0][1];
	$pname = $mnamedate0[0][2];
	$mname = $mnamedate0[0][3];
 	
 	if($_GET[yes]==1){
		$sql = "SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date' and mid = $mID and mem ='$name'";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$comename = $stmt->fetchAll();
		}
		
		$sql="SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date' and mid = $mID";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$mnamedate = $stmt->fetchAll();
		}
		$gname = $mnamedate[0][1];
		$pname = $mnamedate[0][2];
		$mname = $mnamedate[0][3];
	}
	if((!empty($mnamedate) and !empty($prpfl)) and empty($comename)){
		$sql = $pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:date,:gname,:pname,:mname,:mem,:gid,:pid,:mid)");
		$sql->bindParam(':date',$dat,PDO::PARAM_STR);
		$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
		$sql->bindParam(':pname',$pname,PDO::PARAM_STR);
		$sql->bindParam(':mname',$mname,PDO::PARAM_STR);
		$sql->bindParam(':mem',$member,PDO::PARAM_STR);
		$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
		$sql->bindParam(':pid',$pID,PDO::PARAM_INT);
		$sql->bindParam(':mid',$mID,PDO::PARAM_INT);
		$dat = $date;
		$member = $name;
		$sql->execute();
		
		$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$grl = $stmt->fetchAll();
		}
		if(!empty($grl)){
			$num = count($grl) + 1;
		}else{
			$num = 1;
		}
		$sql="SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date' and mid = $mID";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$mnamedat = $stmt->fetchAll();
		}
		if(!empty($mnamedat)){
			foreach($mnamedat as $mrm){
				if($froma!=""){
				$froma = $froma.','.$mrm[4];
				}else{
				$froma = $mrm[4];
				}
			}
		}
		$user = '連絡';
		$pass = 'a';
		$comment = $member.'が'.$pname.'の'.$mname.'に参加しました。';
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
		<title>担当者に自分を追加</title>
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
			$ID = $_GET[id];
			$date = $_GET[date];
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				echo "<h1>担当者に自分を追加</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
				if($_GET[yes]==1){
					echo $mname."の担当者に自分を追加しました。<br>";
					echo "<a href = 'github_mission_6_mission.php?gname=";	
					echo $gID;
					echo "&pname=";
					echo $pID;
					echo "&id=";
					echo $ID;
					echo "&mname=";
					echo $mID;
					echo "&date=";
					echo $date;
					echo "'>ミッションページに戻る</a><br>";
				}else{
					echo $mname."の担当者に自分を追加しますか？<br>";
					echo "<a href = 'github_mission_6_come_mission.php?gname=";	
					echo $gID;
					echo "&pname=";
					echo $pID;
					echo "&id=";
					echo $ID;
					echo "&mname=";
					echo $mID;
					echo "&date=";
					echo $date;
					echo "&yes=1";
					echo "'>はい</a>";
					
					echo "　　　　　";
					echo "<a href = '";	
					echo $_SERVER['HTTP_REFERER'];
					echo "'>いいえ</a><br>";
				}
				echo "<a href = 'github_mission_6_group.php?name=";	
				echo $gID;
				echo "&id=";
				echo $ID;
				echo "'>グループページへ</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";	
				echo $ID;
				echo "'>マイページへ</a>";
				
			}
		?>
	</div>
	</body>
</html>

