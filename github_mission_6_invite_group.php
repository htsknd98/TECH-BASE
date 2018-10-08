<?php 
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';

try{ //PDO設定
 	$pdo = new PDO($dsn,$user,$password);
 	$err=0;
 	$now = date("Y/m/d H:i:s",time());
 	if($pdo == null){
  		echo "cannot connected.<br>"; //connection確認
 	}
 	if(!$_POST['post']){
 		$err++;
 	}
 	
 	$sql = "CREATE TABLE groupinvite3"
	."("
	."id TEXT,"
	."frn TEXT,"
	."gname TEXT,"
	."mem TEXT,"
	."gid INT"
	.");";
	$stmt = $pdo->query($sql);
 	
	if($_POST['token']!=""){
	$token=$_POST['token'];
	}else{
	$token=$_GET[id];
	}
	$sql = "SELECT * FROM member3 where token = '$token'";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$prpfl = $stmt->fetchAll();
	}
	$login=$prpfl[0][6];
	$myname=$prpfl[0][1];
	$ID=$prpfl[0][0];
	if($_POST['gname']!=""){
		$gID = $_POST['gname'];
	}else{
		$gID = $_GET[gname];
	}
	
	$sql="SELECT * FROM group3 where gid = $gID";
	$stmt=$pdo->query($sql);
	if($stmt!=""){
		$result = $stmt->fetchAll();
	}
	
	$gname = $result[0][1];
	$member = $_POST['member'];
	
	
	
	$sql="SELECT * FROM groupinvite3 where gid=$gID";
	$stmt=$pdo->query($sql);
	if($stmt!=""){
		$giv=$stmt->fetchAll();
	}
	if($gname==""){
		$err++;
		$error['meta']="ＵＲＬが間違っています。";
	}
	if(empty($member)){
		$err++;
		$error['member']="メンバーのＩＤかアドレスを入力してください。";
	}
	
	if($err==0){
		$N=count($member);
		for($i=0;$i<$N;$i++){
			$MM =0;
			for($j=($i+1);$j<$N;$j++){
				if($member[$i]==$member[$j]){
					$MM++;
				}
			}
			if($MM==0){
				$memb[]=$member[$i];
			}
		}
		foreach($memb as $value){
			$nnn=0;
			$sql = "SELECT * FROM member3 where id = '$value'";
			$stmt = $pdo->query($sql);
			if($stmt!=""and $value!=""){
				$idname=$stmt->fetchAll();
				if(!empty($idname) and !empty($result)){
					foreach($result as $vale){
						if($vale[3] == $idname[0][0] and $vale[0] == $gID){
							$nnn++;
						}
					}
				}
				if(!empty($idname)and !empty($giv)){
					foreach($giv as $vale){
						if($vale[3] == $idname[0][1] and $vale[4] == $gID){
							$nnn++;
						}
					}
				}
				if(empty($idname)){
					$nnn++;
				}
				$nn[]=$nnn;
				if($nnn==0){
					$sql=$pdo->prepare("INSERT INTO groupinvite3(id,frn,gname,mem,gid) VALUES(:id,:from,:gname,:member,:gid)");
					$sql->bindParam(':id',$ID,PDO::PARAM_STR);
					$sql->bindParam(':from',$from,PDO::PARAM_STR);
					$sql->bindParam(':gname',$gnam,PDO::PARAM_STR);
					$sql->bindParam(':member',$mee,PDO::PARAM_STR);
					$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
					$ID = $idname[0][0];
					$from = $myname;
					$gnam = $gname;
					$mee = $idname[0][1];
					$sql->execute();
					if($from2!=""){
					$from2 = $from2.",".$mee;
					}else{
					$from2 = $mee;
					}
				}
				
			}
		}
		
		$sql = "SELECT * FROM groupinvite3";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$pgiv = $stmt->fetchAll();
		}
		foreach($memb as $value){
			$nnnn = 0;
			$sql = "SELECT * FROM member3 where address = '$value'";
			$stmt = $pdo->query($sql);
			if($stmt!=""and $value!=""){
				$idname1 = $stmt->fetchAll();
				if(!empty($idname1) and !empty($result)){
					foreach($result as $vale){
						if($vale[3] == $idname1[0][0] and $vale[0] == $gID){
							$nnnn++;
						}
					}
				}
				if(!empty($idname1)and !empty($pgiv)){
					foreach($pgiv as $vale){
						if($vale[3] == $idname1[0][1] and $vale[4] == $gID){
							$nnnn++;
						}
					}
				}
				if(empty($idname1)){
					$nnnn++;
				}
				$nnm[] = $nnnn;
				if($nnnn==0){
					$sql=$pdo->prepare("INSERT INTO groupinvite3(id,frn,gname,mem,gid) VALUES(:id,:from,:gname,:member,:gid)");
					$sql->bindParam(':id',$ID1,PDO::PARAM_STR);
					$sql->bindParam(':from',$from1,PDO::PARAM_STR);
					$sql->bindParam(':gname',$gnam1,PDO::PARAM_STR);
					$sql->bindParam(':member',$mee1,PDO::PARAM_STR);
					$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
					$ID1 = $idname1[0][0];
					$from1 = $myname;
					$gnam1 = $gname;
					$mee1 = $idname1[0][1];
					$sql->execute();
					if($froma1!=""){
					$froma1 = $froma1.",".$mee1;
					}elseif($from2!=""){
					$froma1 = $from2.",".$mee1;
					}else{
					$froma1 = $mee1;
					}
					
				}
			}
		}
		if($froma1!=""){
			$froma0 = $froma1;
		}elseif($from2!=""){
			$froma0 = $from2;
		}
		if($froma0!=""){
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
			$comment = $myname.'が'.$froma0.'を'.$gname.'に招待しました。';
			
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
	}
	$sql = "SELECT * FROM groupinvite3";
	$stmt1 = $pdo->query($sql);
	if($stmt1!=""){
		$result1 = $stmt1->fetchAll();
	}

}catch(PDOException $e){
 echo ('Connection failed:'.$e->getMessage());
 die(); //connection確認
}
header("Content-Type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>グループ招待</title>
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
				if($_POST['addn']){
					$num= $_POST['num'] + 1;
				}elseif(!$_POST['post']){
					$num = 1;
				}else{
					$num = $_POST['num'];
				}
				
				if($_POST['ID']!=""){
					$iid = $_POST['ID'];
				}else{
					$iid = $prpfl[0][0];
				}
				if(!empty($_POST['member'])){
					$tmem = $_POST['member'];
				}
				if($_POST['gname']!=""){
					$gn = $_POST['gname'];
				}else{
					$gn = $_GET[gname];
				}
				if($_POST['token']!=""){
					$tkn = $_POST['token'];
				}else{
					$tkn = $_GET[id];
				}
			}
		?>
		<?php
		if($login==1){
			if($err!=0){
				echo "<h1>メンバーの招待</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $token;
				echo "' class='black'>ログアウト</a></div><br>";
				echo "<div align = 'left'>".$gname."</div><br>";
				echo "<form action = 'github_mission_6_invite_group.php' method = 'post'>";
				echo "<input type = 'hidden' name = 'token' value = '";
				echo $tkn;
				echo "'>";
				echo "<input type = 'hidden' name = 'ID' value = '";
				echo $iid;
				echo "'>";
				echo "<input type = 'hidden' name = 'gname' value = '";
				echo $gn;
				echo "' placeholder = 'グループ名' ><br>";
				echo "<input type = 'hidden' name = 'num' value = '";
				echo $num;
				echo "'>";
					if($num!=0){
						for ($i = 1;$i<=$num;$i++){
							echo "<input type = 'text' name = 'member[]' value = '";
							echo $tmem[($i-1)];
							echo "' placeholder ='IDまたはアドレス";
							echo $i;
							echo "'>";
							echo "<br>";
						}
					}
				echo "<input type = 'submit' name = 'addn' value = 'メンバー追加' class='button'>";
				echo "<input type = 'submit' name = 'post' value = '招待'";
				echo "class='button'>";
				echo "</form><br>";
			}
		}
		?>
		<?php
			if($login==1){
				if($_POST['post']){
					if($err==0){
						echo "<h1>グループへ招待</h1>";
						echo "<div align = 'right'>";
						echo "<a href = 'github_mission_6_logout.php?id=";
						echo $token;
						echo "' class='black'>ログアウト</a></div><br>";
						echo "<div align='left'>";
						echo $gname."へ招待しました。<br>";
						echo "<a href='github_mission_6_invite_group.php?id=";
						echo $_POST['token'];
						echo "&gname=";
						echo $_POST['gname'];
						echo "'>さらに招待</a><br><br></div>";
					}else{
						foreach($error as $va){
							echo $va;
						}
					}
				}
				echo "<div align='left'>";
				echo "<a href='github_mission_6_group.php?name=";
				echo $gn;
				echo "&id=";
				echo $tkn;
				echo "'>グループページへ</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";
				echo $tkn;
				echo "'>マイページに戻る</a><br></div>";
			}
		?>
		</div>
	</body>
</html>

