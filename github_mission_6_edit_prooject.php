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
	 	if(!$_POST['post']){
	 	$err++;
	 	}
	 	if($_POST['ID']!=""){
	 		$ID = $_POST['ID'];
		}else{
			$ID = $_GET[id];
		}
		
		$sql = "SELECT * FROM member3 where token = '$ID'";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$prpfl = $stmt->fetchAll();
		}
		
		$login = $prpfl[0][6];
		$myname = $prpfl[0][1];
		
		if($_POST['gname']!=""){
			$ggname = $_POST['gname'];
		}else{
			$ggname = $_GET[gname];
		}
		if($_POST['enm']!=""){
			$enm = $_POST['enm'];
		}else{
			$enm = $_GET[pname];
		}
		
		$sql="SELECT * FROM group3 where gid = $ggname";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$result0 = $stmt->fetchAll();
		}
	 	
	 	$sql = "SELECT * FROM project6 where gid = $ggname and pid = $enm";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$presult=$stmt->fetchAll();
		}
		if(!empty($presult)){
			$group = $presult[0][5];
			$project = $presult[0][0];
		}
	 	
		if($_POST['name']==""){
			$error['name']="名前を入力してください。";
			$err++;
		}
		$gname = $_POST['gname'];
		if($err==0){
			$sql = "SELECT * FROM project6 where gid = $gname ";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$presult=$stmt->fetchAll();
			}
			if(!empty($presult)){
				$group = $presult[0][5];
				$project = $presult[0][0];
			}
		}
		if($err==0){
			$enm=$_POST['enm'];
			$name = $_POST['name'];
			$sql = "update project6 set name = '$name' where pid = $enm and gid = $gname";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$result1 = $stmt->fetchAll();
			}
			
			$sql = "update mission2 set pname = '$name' where pid = $enm and gid = $gname";
			$stmt = $pdo->query($sql);
			
			$sql = "SELECT * FROM project6 where pid = $enm and gid = $gname";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$proj = $stmt->fetchAll();
			}
			if(!empty($proj)){
 				foreach($proj as $mem){
	 				if($froma!=""){
						$froma = $froma.",".$mem[6];
					}else{
						$froma = $mem[6];
	 				}
 				}
 			}
 			if($froma!=""){
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
		 		$comment = $myname.'が「'.$project.'」を「'.$name.'」という名前に変更しました。';
		 		
		 		$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
				$sql->bindParam(':id',$num,PDO::PARAM_INT);
				$sql->bindParam(':name',$user,PDO::PARAM_STR);
				$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
				$sql->bindParam(':time',$now,PDO::PARAM_STR);
				$sql->bindParam(':gname',$group,PDO::PARAM_STR);
				$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gname,PDO::PARAM_STR);
				$sql->execute();
			}
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
		<title>プロジェクト名の変更</title>
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
			if($_POST['gname']!=""){
				$ggg = $_POST['gname'];
			}else{
				$ggg = $_GET[gname];
			}

			if($_POST['ID']!=""){
				$iid = $_POST['ID'];
			}else{
				$iid = $_GET[id];
			}
			if($_POST['name']!=""){
				$ppp=$_POST['name'];
			}else{
				$ppp=$_GET[pname];
			}
			if($_POST['enm']!=""){
				$enm = $_POST['enm'];
			}else{
				$enm = $_GET[pname];
			}
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";

			}else{
				echo "<h1>プロジェクト名の変更</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
			}
		?>
		<?php
		if($login==1){
			if($err!=0){
				echo "<form action = 'github_mission_6_edit_prooject.php' method = 'post'>";
				echo "<input type = 'hidden' name = 'gname' value = '";
				echo $ggg;
				echo "'>";
				echo "<input type = 'hidden' name = 'ID' value = '";
				echo $iid;
				echo "'>";
				echo "<input type = 'hidden' name = 'enm' value = '";
				echo $enm;
				echo "'>";
				echo "<input type = 'text' name = 'name' value = '";
				echo $project;
				echo "' placeholder = 'プロジェクト名'><br>";
				echo "<input class='button' type='submit' name='post' value='編集'>";
				echo "</form>";
			}
		}
		?>
		<?php
			if($login==1){
				$ID = $_POST['ID'];
				$name = $_POST['name'];
				echo "<div align='left'>";
				if($err!=0){
					if($_POST['post']){
					foreach($error as $eval){
						echo $eval."<br>";
					}
					}
				}else{
					echo "変更しました。<br>";
					echo "<a href = 'github_mission_6_edit_prooject.php?pname=";
					echo $enm;
					echo "&gname=";
					echo $gname;
					echo "&id=";
					echo $ID;
					echo "'>再度変更する</a><br>";
				}
				echo "<a href = 'github_mission_6_project.php?gname=";	
				echo $ggg;
				echo "&pname=";
				echo $enm;
				echo "&id=";
				echo $iid;
				echo "'>プロジェクトページへ</a><br>";
				echo "<a href = 'github_mission_6_group.php?name=";	
				echo $ggg;
				echo "&id=";
				echo $iid;
				echo "'>グループページへ</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";
				echo $iid;
				echo "'>マイページへ</a><br>";
				echo "</div>";
			}
		?>
		</div>
	</body>
</html>

