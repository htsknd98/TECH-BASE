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
	 	$member = $_POST['member'];
	 	if($_POST['gname']!=""){
	 	$gID = $_POST['gname'];
	 	}else{
	 	$gID = $_GET[gname];
	 	}
	 	if($_POST['name']!=""){
	 	$pID = $_POST['name'];
	 	}else{
	 	$pID = $_GET[pname];
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
	 	
	 	$sql = "SELECT * FROM group3 where gid = $gID";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$result0 = $stmt->fetchAll();
	 	}
	 	
	 	$sql = "SELECT * FROM project6 where pid = $pID and gid = $gID";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$presult = $stmt->fetchAll();
	 	}
	 	$gname = $presult[0][5];
	 	$pname = $presult[0][0];
	 	
	 	if(!empty($result0) and !empty($presult)){
	 		foreach($result0 as $val0){
	 			$rr=0;
	 			foreach($presult as $pval){
	 				if($val0[2]==$pval[6]){
	 					$rr++;
	 				}
	 			}
	 			
	 			if($rr==0){
	 				$result2[]=$val0[2];
	 			}
	 		}
	 	}
	 	if(empty($member)){
	 	$err++;
	 	$error['member']="メンバーをクリックして入力してください。";
	 	}
	 	
	 	$gname = $_POST['gname'];
	 	$pname = $_POST['name'];
	 	if(empty($_POST)){
	 	$err++;
	 	}
	 	
	 	$sql = "SELECT * FROM project6 where pid = $pID and gid = $gID";
	 	$stmt = $pdo->query($sql);
	 	if($stmt!=""){
	 		$ppresult = $stmt->fetchAll();
	 	}
	 	if($err==0){
			foreach($member as $value){
				$xx=$value;
				$sql = "SELECT * FROM group3 where member = '$xx' and gid = $gID";
				$stmt = $pdo->query($sql);
				if($stmt!=""){
					$result = $stmt->fetchAll();
				}
				
				$sql = $pdo->prepare("INSERT INTO project6(name, fdate,tdate,fmonth,tmonth,gname,mem,gid,pid) VALUES(:name,:fdate,:tdate,:fmonth,:tmonth, :gname, :mem,:gid,:pid)");
				$sql->bindParam(':name', $name, PDO::PARAM_STR); //nameをbind
				$sql->bindParam(':fdate',$from,PDO::PARAM_STR);
				$sql->bindParam(':tdate',$to,PDO::PARAM_STR);
				$sql->bindParam(':fmonth',$f1,PDO::PARAM_STR);
				$sql->bindParam(':tmonth',$t1,PDO::PARAM_STR);
				$sql->bindParam(':gname', $gname, PDO::PARAM_STR);
				$sql->bindParam(':mem',$mem,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gID,PDO::PARAM_STR);
				$sql->bindParam(':pid',$pID,PDO::PARAM_STR);
				$name = $_POST['name'];
				$from = $ppresult[0][1];
				$to = $ppresult[0][2];
				$f1 = $ppresult[0][3];
				$t1 = $ppresult[0][4];
				$mem=$value;
				$sql->execute();
				if($froma1!=""){
				$froma1=$froma1.",".$mem;
				}else{
				$froma1 = $mem;
				}
				
				$mID1 = 1;
				
				$sql = $pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:dat0,:gnam0,:pnam0,:mnam0,:memm,:gid,:pid,:mid)");
				$sql->bindParam(':dat0',$dat0,PDO::PARAM_STR);
				$sql->bindParam(':gnam0',$gnam0,PDO::PARAM_STR);
				$sql->bindParam(':pnam0',$pnam0,PDO::PARAM_STR);
				$sql->bindParam(':mnam0',$mnam0,PDO::PARAM_STR);
				$sql->bindParam(':memm',$memm,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gID,PDO::PARAM_STR);
				$sql->bindParam(':pid',$pID,PDO::PARAM_STR);
				$sql->bindParam(':mid',$mID1,PDO::PARAM_STR);
				$dat0 = $from;
				$gnam0 = $group;
				$pnam0 = $name;
				$mnam0 = "開始";
				$memm = $value;
				$sql->execute();
			
				$mID2 = 2;
				
				$sql = $pdo->prepare("INSERT INTO mission1(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:dat1,:gnam1,:pnam1,:mnam1,:mem1,:gid,:pid,:mid)");
				$sql->bindParam(':dat1',$dat1,PDO::PARAM_STR);
				$sql->bindParam(':gnam1',$gnam1,PDO::PARAM_STR);
				$sql->bindParam(':pnam1',$pnam1,PDO::PARAM_STR);
				$sql->bindParam(':mnam1',$mnam1,PDO::PARAM_STR);
				$sql->bindParam(':mem1',$meme,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gID,PDO::PARAM_STR);
				$sql->bindParam(':pid',$pID,PDO::PARAM_STR);
				$sql->bindParam(':mid',$mID2,PDO::PARAM_STR);
				$dat1 = $to;
				$gnam1 = $group;
				$pnam1 = $name;
				$mnam1 = "終了";
				$meme = $value;
				$sql->execute();
				
			}
			$sql = "SELECT * FROM project6 where pid = $pID and gid = $gID";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$proj = $stmt->fetchAll();
			}
			
			if(!empty($proj)){
				foreach($proj as $val){
					if($froma!=""){
					$froma = $froma.",".$val[6];
					}else{
					$froma = $val[6];
					}
				}
			}
			
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
		 	$comment = 'プロジェクト:'.$name.'の担当者にメンバー'.$froma1.'を追加しました。';
		 	
		 	if($froma1!=""){
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
		<title>プロジェクトメンバーの追加</title>
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
			text-align: center;
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
			input[type=checkbox] {
  				display: none;
			}
			
			.checkbox {
  				box-sizing: border-box;
  				-webkit-transition: background-color 0.2s linear;
  				transition: background-color 0.2s linear;
  				position: relative;
  				display: inline-block;
  				margin: 0 20px 8px 0;
  				padding: 12px 12px 12px 42px;
  				border-radius: 8px;
  				background-color: #f6f7f8;
  				vertical-align: middle;
  				cursor: pointer;
			}
			
			.checkbox:hover {
  				background-color: #e2edd7;
			}
			
			.checkbox:hover:after {
  				border-color: #53b300;
			}
			
			.checkbox:after {
  			-webkit-transition: border-color 0.2s linear;
  			transition: border-color 0.2s linear;
  			position: absolute;
  			top: 50%;
  			left: 15px;
  			display: block;
  			margin-top: -10px;
  			width: 16px;
  			height: 16px;
  			border: 2px solid #bbb;
  			border-radius: 6px;
  			content: '';
			}
			
			.checkbox:before {
  			-webkit-transition: opacity 0.2s linear;
  			transition: opacity 0.2s linear;
  			position: absolute;
  			top: 50%;
  			left: 21px;
  			display: block;
  			margin-top: -7px;
  			width: 5px;
  			height: 9px;
  			border-right: 3px solid #53b300;
  			border-bottom: 3px solid #53b300;
  			content: '';
  			opacity: 0;
  			-webkit-transform: rotate(45deg);
  			-ms-transform: rotate(45deg);
  			transform: rotate(45deg);
			}
			input[type=checkbox]:checked + .checkbox:before {
  			opacity: 1;
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
			$ppp = $_GET[pname];
			}
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				echo "<h1>メンバーの追加</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $ID;
				echo "' class='black'>ログアウト</a></div><br>";
			}

		?>
		<form action = "github_mission_6_edit_project_member.php" method = "post">
			<input type = "hidden" name = "gname" value = "<?php echo $ggg; ?>">
			<input type = "hidden" name = "ID" value = "<?php echo $iid; ?>">
			<input type = "hidden" name = "name" value = "<?php echo $ppp; ?>" placeholder = "プロジェクト名">
			<?php
				if($login==1){
					if(!empty($result2)){
						if($err!=0){
							$n=1;
							foreach($result2 as $val){
								echo "<input type = 'checkbox' name = 'member[]' value = '";
								echo $val;
								echo "' id='checkbox";
								echo $n;
								echo "'>";
								echo "<label for='checkbox";
								echo $n;
								echo "' class='checkbox'>";
								echo $val."</label><br>";
								$n++;
							}		
							echo "<input class='button' type='submit' name='post' value='追加'>";
						}
					}
				}
			?>
		</form>
		<?php
			if($login==1){
				if($_POST['ID']!=""){
				$ID = $_POST['ID'];
				}else{
				$ID = $_GET[id];
				}
				if($err!=0){
					if(!empty($result2)){
						foreach($error as $eval){
							echo $eval."<br>";
						}
					}else{
						echo "グループのメンバーは全員プロジェクトに入っています。<br>";
					}
					echo "<div align='left'>";
					echo "<a href = 'github_mission_6_create_group.php?add=";
					echo $ggg;
					echo "&id=";
					echo $ID;
					echo "'>グループメンバーを追加</a><br>";
					
					echo "<a href = 'github_mission_6_project.php?gname=";
					echo $ggg;
					echo "&pname=";
					echo $ppp;
					echo "&id=";
					echo $ID;
					echo "'>プロジェクトページに戻る</a></div>";
				}else{
					echo "<div align='left'>";
					echo "メンバーを追加しました。<br>";
					echo "<a href = 'github_mission_6_edit_project_member.php?gname=";	
					echo $gID;
					echo "&pname=";
					echo $pID;
					echo "&id=";
					echo $ID;
					echo "'>さらに追加</a><br>";
					echo "<a href = 'github_mission_6_project.php?gname=";	
					echo $gID;
					echo "&pname=";
					echo $pID;
					echo "&id=";
					echo $ID;
					echo "'>プロジェクトページへ</a></div>";
				}
				echo "<div align='left'>";
				echo "<a href = 'github_mission_6_group.php?name=";	
				echo $gID;
				echo "&id=";
				echo $ID;
				echo "'>グループページへ</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";	
				echo $ID;
				echo "'>マイページへ</a></div><br>";
				
			}
		?>
		</div>
	</body>
</html>

