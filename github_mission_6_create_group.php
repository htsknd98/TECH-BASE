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
	 	$sql = "CREATE TABLE group3"
	 	."("
	 	."gid INT,"
	 	."name char(32),"
		."member TEXT,"
		."id TEXT"
	 	.");";
	 	$stmt = $pdo->query($sql);
	 	
	 	$sql = "CREATE TABLE groupinvite3"
		."("
		."id TEXT,"
		."frn TEXT,"
		."gname TEXT,"
		."mem TEXT,"
		."gid INT"
		.");";
		$stmt = $pdo->query($sql);
		
		$sql = "CREATE TABLE grlog3"
		."("
		."id INT,"
		."name TEXT,"
		."comment TEXT,"
		."time TEXT,"
		."gname TEXT,"
		."mem TEXT,"
		."gid INT"
		.");";
		$stmt=$pdo->query($sql);
		
	 	if($_POST['token']!=""){
	 		$token = $_POST['token'];
	 	}else{
	 		$token = $_GET[id];
	 	}
	 	$sql = "SELECT * FROM member3 where token = '$token'";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$prpfl = $stmt->fetchAll();
	 	}
	 	
	 	if(!empty($prpfl)){
	 		$ID = $prpfl[0][0];
	 		$myname = $prpfl[0][1];
	 		$login = $prpfl[0][6];
	 	}else{
	 		$login=0;
	 	}
	 	
	 	$sql = "SELECT * FROM group3 order by gid asc";
	 	$pstmt = $pdo->query($sql);
	 	if($pstmt!=""){
	 		$presult = $pstmt->fetchAll();
		}
		if(!empty($presult)){
			$a = count($presult);
			$gID=$presult[($a-1)][0] +1 ;
		}else{
			$gID = 1;
		};
		if(isset($_POST['post'])){
			$gname = $_POST['gname'];
			$arr = $_POST['member'];
			$err=0;
			if($ID!="" and $gname!=""){
				if($gname=="deleted"){
					$err++;
					$error['gname']="この名前は使えません。";
				}
				
				if($err==0){
					$num = 1;
					$user = "連絡";
					$comment = $myname."が".$gname."を作成しました。";
					$froma = "all members";
					$pass='a';
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
				
				if($err==0){	
					$rr=0;
					$sql = "SELECT * FROM member3 where id = '$ID'";
					$stmt = $pdo->query($sql);
					$result = $stmt->fetchAll();
					
					if(!empty($presult)){
						foreach($presult as $rval){
							if($rval[3] == $ID and $rval[0]==$gID){
								$rr++;
							}
						}
					}
					if($rr==0){
						$sql = $pdo->prepare("INSERT INTO group3(gid,name,member,id) VALUES(:gid,:name,:member,:id)");
						$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
						$sql->bindParam(':name',$name,PDO::PARAM_STR);
						$sql->bindParam(':member',$mem,PDO::PARAM_STR);
						$sql->bindParam(':id',$ID1,PDO::PARAM_STR);
						$name = $_POST['gname'];
						$mem = $result[0][1];
						$ID1 = $prpfl[0][0];
						$sql->execute();
					}
					$sql = "SELECT * FROM group3 where gid = $gID";
					$stmt = $pdo->query($sql);
					$result0 = $stmt->fetchAll();
					
					$sql= "SELECT * FROM groupinvite3 where gid=$gID";
					$stmt= $pdo->query($sql);
					if($stmt!=""){
						$giv=$stmt->fetchAll();
					}
					if(!empty($arr)){
						$N = count($arr);
						for($i=0;$i<$N;$i++){
							$MM =0;
							for($j=($i+1);$j<$N;$j++){
								if($arr[$i]==$arr[$j]){
									$MM++;
								}
							}
							if($MM==0){
								$arra[]=$arr[$i];
							}
						}
						foreach($arra as $value){
							$nnn = 0;
							$sql = "SELECT * FROM member3 where id = '$value'";
							$stmt = $pdo->query($sql);
							if($stmt!=""and $value!=""){
								$result1 = $stmt->fetchAll();
								if(!empty($result1) and !empty($result0)){
									foreach($result0 as $vale){
										if($vale[3] == $result1[0][0] and $vale[1] == $gname){
											$nnn++;
										}
									}
								}
								if(!empty($giv) and !empty($result1)){
									foreach($giv as $vale){
										if($vale[0]==$result1[0][0] and $vale[2] == $gname){
											$nnn++;
										}
									}
								}
								if(empty($result1)){
									$nnn++;
								}
								$nn[] = $nnn;
								if($nnn==0){
									$sql = $pdo->prepare("INSERT INTO groupinvite3(id,frn,gname,mem,gid) VALUES(:id,:frn, :gname, :mem,:gid)");
									$sql->bindParam(':id',$ID,PDO::PARAM_STR);
									$sql->bindParam(':frn',$frn,PDO::PARAM_STR);
									$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
									$sql->bindParam(':mem',$mem,PDO::PARAM_STR);
									$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
									$ID = $result1[0][0];
									$frn = $result[0][1];
									$gname = $_POST['gname'];
									$mem = $result1[0][1];
									$sql->execute();
									if($from2!=""){
									$from2 = $from2.",".$mem;
									}else{
									$from2 = $mem;
									}
								}
							}
						}
						$sql = "SELECT * FROM group3 where gid = $gID";
						$stmt=$pdo->query($sql);
						if($stmt!=""){
							$presult1 = $stmt->fetchAll();
						}
						$sql="SELECT * FROM groupinvite3 where gid=$gID";
						$stmt=$pdo->query($sql);
						if($stmt!=""){
							$giv1 = $stmt->fetchAll();
						}
						foreach($arra as $value){
							$nnn = 0;
							$sql = "SELECT * FROM member3 where address = '$value'";
							$stmt = $pdo->query($sql);
							if($stmt!=""and $value!=""){
								$result1 = $stmt->fetchAll();
								if(!empty($result1) and !empty($presult1)){
									foreach($presult1 as $vale){
										if($vale[3] == $result1[0][0] and $vale[0] == $gID){
											$nnn++;
										}
									}
								}
								if(!empty($giv1) and !empty($result1)){
									foreach($giv1 as $vale){
										if($vale[0]==$result1[0][0] and $vale[3] == $gID){
											$nnn++;
										}
									}
								}
								if(empty($result1)){
									$nnn++;
								}
								
								$nn[] = $nnn;
								if($nnn==0){
									$sql = $pdo->prepare("INSERT INTO groupinvite3(id,frn,gname,mem,gid) VALUES(:id1,:frn1, :gname1, :mem1,:gid1)");
									$sql->bindParam(':id1',$ID1,PDO::PARAM_STR);
									$sql->bindParam(':frn1',$frn,PDO::PARAM_STR);
									$sql->bindParam(':gname1',$gname,PDO::PARAM_STR);
									$sql->bindParam(':mem1',$mem1,PDO::PARAM_STR);
									$sql->bindParam(':gid1',$gID,PDO::PARAM_INT);
									$ID1 = $result1[0][0];
									$frn = $result[0][1];
									$gname = $_POST['gname'];
									$mem1 = $result1[0][1];
									$sql->execute();
									if($froma1!=""){
									$froma1 = $froma1.",".$mem1;
									}elseif($from2!=""){
									$froma1 = $from2.",".$mem1;
									}else{
									$froma1 = $mem1;
									}
								}
							}
						}
						if($froma1!=""){
							$froma0 = $froma1;
						}elseif($from2!=""){
							$froma0 = $from2;
						}
						$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
						$stmt=$pdo->query($sql);
						if($stmt!=""){
							$pgr = $stmt->fetchAll();
						}
						if(!empty($pgr)){
							foreach($pgr as $XX){}
							$num = count($pgr) + 1;
						}else{
							$num=1;
						}
						if($froma0!=""){
							$fromn = 'all members';
							$pass='a';
							$user='連絡';
							$comment = $myname."が".$froma0."を".$gname."に招待しました。";
							$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
							$sql->bindParam(':id',$num,PDO::PARAM_INT);
							$sql->bindParam(':name',$user,PDO::PARAM_STR);
							$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
							$sql->bindParam(':time',$now,PDO::PARAM_STR);
							$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
							$sql->bindParam(':mem',$fromn,PDO::PARAM_STR);
							$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
							$sql->execute();
						}
					}
				}
			}else{
				$err++;
				$error['name']="グループ名を入力してください。";
			}
			
		}else{
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
		<title>グループ作成</title>
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
					$num = 0;
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
					$gn = $_GET[add];
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
				echo "<h1>新しいグループの作成</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $token;
				echo "' class='black'>ログアウト</a></div><br>";
				echo "<form action = 'github_mission_6_create_group.php' method = 'post'>";
				echo "<input type = 'hidden' name = 'token' value = '";
				echo $tkn;
				echo "'>";
				echo "<input type = 'hidden' name = 'ID' value = '";
				echo $iid;
				echo "'>";
				echo "<input type = 'text' name = 'gname' value = '";
				echo $gn;
				echo "' placeholder = 'グループ名' ><br>";
				echo "<input type = 'hidden' name = 'num' value = '";
				echo $num;
				echo "'>";
				if($num!=0){
					for ($i = 1;$i<=$num;$i++){
						echo "<input type = 'text' name = 'member[]' value = '";
						echo $tmem[($i-1)];
						echo "' placeholder ='メンバーのIDまたはアドレス";
						echo $i;
						echo "'>";
						echo "<br>";
					}
				}
				echo "<input type = 'submit' name = 'addn' value = 'メンバー追加' class='button'>";
				echo "<input type = 'submit' name = 'post' value = '作成' class='button'>";
				echo "</form><br>";
			}
		}
		?>
		<?php
			if($login==1){
				if($_POST['post']){
					if($err==0){
						echo "<h1>".$_POST['gname']."</h1>";
						echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
						echo $token;
						echo "' class='black'>ログアウト</a></div><br>";
						echo "<div align='left'><a href='github_mission_6_invite_group.php?id=";
						echo $_POST['token'];
						echo "&gname=";
						echo $gID;
						echo "'>グループのメンバーの追加</a><br><br>";
						echo "<a href='github_mission_6_create_group.php?id=";
						echo $_POST['token'];
						echo "'>新しいグループの作成</a><br><br>";
						echo "<a href='github_mission_6_group.php?name=";
						echo $gID;
						echo "&id=";
						echo $_POST['token'];
						echo "'>グループページへ</a><br></div><br>";
					}else{
						foreach($error as $va){
							echo $va;
						}
					}
				}
				
				echo "<div align='left'><a href = 'github_mission_6_mypage.php?id=";
				echo $tkn;
				echo "'>マイページに戻る</a><br></div>";
			}
		?>
		</div>
	</body>
</html>

