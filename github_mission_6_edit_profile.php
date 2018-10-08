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
 	if($_POST['id0']!=""){
 	$pID = $_POST['token'];
 	}else{
 	$pID = $_GET[id];
 	}
 	$err=0;
 	$sql = "SELECT * FROM member3";
 	$stmt = $pdo->query($sql);
 	if($stmt!=""){
 		$pfl = $stmt->fetchAll();
 	}
 	if($pID!=""){
 		$sql = "SELECT * FROM member3 where token = '$pID'";
 		$stmt = $pdo->query($sql);
 		if($stmt!=""){
 			$prpfl = $stmt->fetchAll();
 		}
 	}
 	$plogin = $prpfl[0][6];
 	$passw = $prpfl[0][4];
 	if($_POST['pas0']==""){
 		$err++;
 		$error['pass']="パスワードを入力してください。";
 	}elseif($_POST['pas0']!=$passw){
 		$err++;
 		$error['pass']="パスワードが一致していません。";
 	}else{
 		if($_POST['id']!=$_POST['id0']){
 			foreach($pfl as $val0){
 				if($val0[0]==$_POST['id']){
 					$err++;
 					$error['id']="すでに使われているIDです。";
 				}
 			}
 			if($_POST['id']==""){
 				$err++;
 				$error['id']="IDを入力してください。";
 			}
 		}
 		
 		if($_POST['nam']!=$_POST['nam0']){
 			foreach($pfl as $val0){
 				if($val0[1]==$_POST['nam']){
 					$err++;
 					$error['name']="すでに使われている名前です。";
 				}
 			}
 			if($_POST['nam']==""){
 				$err++;
 				$error['name']="名前を入力してください。";
 			}
 		}
 		
 		if($_POST['id']==$_POST['id0'] and $_POST['nam']==$_POST['nam0']){
 			if($_POST['ad']==$_POST['ad0'] and $_POST['pas']==$_POST['pas0']){
 				$err++;
 				$error['system']="何も変更されていません。";
 			}
 		}
 		
 		if($err==0){
 			$name = $_POST['nam'];
 			$name0 = $_POST['nam0'];
 			$ID = $_POST['id'];
 			$ID0 = $_POST['id0'];
 			$address = $_POST['ad'];
 			$npass = $_POST['pas'];
 			if($npass!=""){
 				$sql = "update member3 set name = '$name', id = '$ID', address = '$address', password = '$npass' where id = '$ID0'";
 				$stmt = $pdo->query($sql);
 			}else{
 				$sql = "update member3 set name = '$name', id = '$ID', address = '$address' where id = '$ID0'";
 				$stmt = $pdo->query($sql);
 			}
 			
 			$sql = "update group3 set id = '$ID',member = '$name' where member = '$name0' and id = '$ID0'";
 			$stmt = $pdo->query($sql);
 			
 			$sql = "update project6 set mem = '$name' where mem = '$name0'";
 			$stmt = $pdo->query($sql);
 			
 			$sql = "update mission2 set mem = '$name' where mem = '$name0'";
 			$stmt = $pdo->query($sql);
 			
 			$sql = "update groupinvite3 set id='$ID',mem='$name' where member = '$name0' and id='$ID0'";
 			$stmt=$pdo->query($sql);
 			
 			$sql = "update grlog3 set name = '$name' where name = '$name0'";
 			$stmt = $pdo->query($sql);
 			
 			$sql = "SELECT * FROM grlog3";
 			$stmt = $pdo->query($sql);
 			if($stmt!=""){
 				$grl = $stmt->fetchAll();
 			}
 			
 			if(!empty($grl)){
 				foreach($grl as $value){
 					$exp = explode(",",$value[5]);
 					foreach($exp as $val){
 						if($val == $name0){
 							if($froma!=""){
 								$froma = $froma.",".$name;
 							}else{
 								$froma = $name;
 							}
 						}else{
 							if($froma!=""){
 								$froma = $froma.",".$val;
 							}else{
 								$froma = $val;
 							}
 						}
 					}
 					$sql = "update grlog3 set mem = '$froma' where mem = '$value[5]'";
 					$stmt = $pdo->query($sql);
 					unset($froma);
 				}
 				
 				
 			}
 			
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
<title>プロフィール変更</title>
	<style>
		body{
			background-color: #AAFFFF;
			}
			
			div.all{
				background-color: #FFFFFF;
				text-align: center;
				font-size: 25px;
			}
			

			input[type="password"],input[type="text"],
			textarea {
			padding: 0.8em;
			outline: none;
			border: 1px solid #DDD;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			font-size: 25px;
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
	if($plogin==0){
		echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
		echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
	}else{
		echo "<h1>プロフィール変更</h1>";
		echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
		echo $pID;
		echo "' class='black'>ログアウト</a></div><br>";
		
		if($err!=0){
			echo "変更しないものはそのままにしておいてください。<br>";
		}
		
		if($_POST['id0']!=""){
			$ID0 = $_POST['id0'];
		}else{
			$ID0 = $prpfl[0][0];
		}
		
		if($_POST['id']!=""){
			$ID = $_POST['id'];
		}else{
			$ID = $prpfl[0][0];
		}
		
		if($_POST['ad0']!=""){
			$ad0 = $_POST['ad0'];
		}else{
			$ad0 = $prpfl[0][2];
		}
		
		if($_POST['ad']!=""){
			$ad = $_POST['ad'];
		}else{
			$ad = $prpfl[0][2];
		}
		
		if($_POST['nam0']!=""){
			$nam0 = $_POST['nam0'];
		}else{
			$nam0 = $prpfl[0][1];
		}
		
		if($_POST['nam']!=""){
			$nam = $_POST['nam'];
		}else{
			$nam = $prpfl[0][1];
		}
		
	}
?>
<?php
	if($plogin==1){
		if($err!=0){
		echo "<form action = 'github_mission_6_edit_profile.php' method = 'POST'>";
			
			echo "<input type = 'hidden' name = 'token' value = '";
			echo $pID."'>";
			echo "<input type = 'hidden' name = 'id0' value = '";
			echo $ID0."'>";
			echo "<h3>ID</h3>";
			echo "<input type = 'text' name = 'id' value = '";
			echo $ID;
			echo "' placeholder = 'IDを編集'><br>";
			echo "<input type = 'hidden' name = 'ad0' value = '";
			echo $ad0;
			echo "'>";
			echo "<h3>アドレス</h3>";
			echo "<input type = 'text' name = 'ad' value = '";
			echo $ad;
			echo "' placeholder = 'アドレスを編集'><br>";
			echo "<input type = 'hidden' name = 'nam0' value = '";
			echo $nam0;
			echo "'>";
			echo "<h3>名前</h3>";
			echo "<input type = 'text' name = 'nam' value = '";
			echo $nam;
			echo "' placeholder = '名前を編集'><br>";
			echo "<h3>旧パスワード</h3>";
			echo "<input type = 'password' name = 'pas0' placeholder = '旧パスワード'><br>";
			echo "<h3>新パスワード</h3>";
			echo "<input type = 'password' name = 'pas' placeholder = '新パスワード'><br>";
			echo "<input class='button 'type = 'submit' value = '変更' name='post'><br>";
		echo "</form>";
		}
	}
?>
<?php
if($plogin ==1){
	
	if($err==0){
		echo "<div align='left'>";
		echo "プロフィールを変更しました。"."<br>";
		echo "ID:".$ID."<br>";
		echo "name:".$nam."<br>";
		
		echo "<a href = 'github_mission_6_edit_profile.php?id=";
		echo $pID;
		echo "'>さらに変更する</a><br>";
		echo "</div>";
	}else{
		if($_POST['post']){
		foreach($error as $val){
			echo $val;
			echo "<br>";
		}
		}
	}
	echo "<div align='left'>";
	echo "<a href = 'github_mission_6_mypage.php?id=";
	echo $pID;
	echo "'>マイページへ</a><br>";

	echo "<a href = 'github_mission_6.php'>メンバー登録</a><br>";
	echo "</div>";
}
?>
</div>
</body>
</html>

