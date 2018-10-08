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
	 	if($_POST['ID']!=""){
	 		$token = $_POST['ID'];
	 	}else{
	 		$token = $_GET[id];
	 	}
	 	
	 	$sql = "SELECT * FROM member3 where token = '$token'";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$prpfl = $stmt->fetchAll();
	 	}
	 	$login = $prpfl[0][6];
	 	$myname = $prpfl[0][1];
	 	if($_POST['edn']!=""){
	 	$gID = $_POST['edn'];
	 	}else{
	 	$gID = $_GET[edit];
	 	}
	 	
	 	$sql = "SELECT * FROM group3 where gid = $gID";
	 	$pstmt = $pdo->query($sql);
	 	if($pstmt!=""){
	 		$presult = $pstmt->fetchAll();
		}
	 	
	 	if(!empty($presult)){
	 		$ename = $presult[0][1];
	 	}
	 	
		$gname = $_POST['gname'];
		if(!empty($presult)){
			$err=0;
			if($gname!=""){
				if($err==0){
					$sql = "update group3 set name = '$gname' where gid = $gID";
					$stmt = $pdo->query($sql);
					
					$sql = "update mission2 set gname = '$gname' where gid = $gID";
					$stmt = $pdo->query($sql);
					
					$sql = "update project6 set gname = '$gname' where gid = $gID";
					$stmt = $pdo->query($sql);
					
					$sql = "update groupinvite3 set gname = '$gname' where gid=$gID";
					$stmt = $pdo->query($sql);
					
					$sql = "update grlog3 set gname = '$gname' where gid=$gID";
					$stmt = $pdo->query($sql);
					
					$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
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
		 			$comment = $myname.'が「'.$ename.'」を「'.$gname.'」という名前に変更しました。';
		 			
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
			}else{
				$err++;
				$error['name']="名前を入力してください。";
			}
		}else{
			$err++;
			$error['ename']="edit値を確認してください。";
		}
		if(!$_POST['post']){
			$err++;
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
		<title>グループ名変更</title>
		<style>
			body{
			background-color: #AAFFFF;
			}
			
			div.all{
				background-color: #FFFFFF;
				text-align: center;
				font-size: 25px;
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
			
			input.button{
			font-size: 20px;
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
				if($_POST['ID']!=""){
					$iid = $_POST['ID'];
				}else{
					$iid = $_GET[id];
				}
				if($_POST['gname']!=""){
					$gn = $_POST['gname'];
				}else{
					$gn = $presult[0][1];
				}
				if($_POST['edn']!=""){
					$enum = $_POST['edn'];
				}else{
					$enum = $_GET[edit];
				}
				
				echo "<h1>グループ名の変更</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $iid;
				echo "' class='black'>ログアウト</a></div><br>";
			}
		?>
		<?php
			if($login==1){
				if($err!=0){
					echo "<form action = 'github_mission_6_edit_group.php' method = 'post'>";
					echo "<input type = 'hidden' name = 'ID' value = '";
					echo $iid;
					echo "'>";
					echo "<input type = 'text' name = 'gname' value = '";
					echo $gn;
					echo "' placeholder = 'グループ名'><br>";
					echo "<input type = 'hidden' name = 'edn' value = '";
					echo $enum;
					echo "'>";
					echo "<input type = 'submit' name = 'post' value = '変更' class='button'><br>";
					echo "</form><br><br>";
				}
			}
		?>
		<?php
			if($login==1){
				if($_POST['post']){
					echo "<div align='left'>";
					if($err==0){
						echo "グループ名を変更しました。<br>";
						echo "<a href='github_mission_6_create_group.php?id=";
						echo $_POST['ID'];
						echo "'>新しいグループの作成</a><br>";
					}else{
						foreach($error as $va){
							echo $va;
						}
					}
					echo "</div>";
				}
				
				echo "<div align='left'><a href='github_mission_6_group.php?name=";
				echo $enum;
				echo "&id=";
				echo $iid;
				echo "'>グループページへ</a><br>";
				echo "<a href='github_mission_6_mypage.php?id=";
				echo $iid;
				echo "'>マイページへ</a><br></div>";
			}
		?>
	</div>
	</body>
</html>


