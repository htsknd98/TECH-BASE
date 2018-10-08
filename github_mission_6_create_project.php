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
	 		$ID = $_POST['ID'];
	 	}else{
	 		$ID = $_GET[id];
	 	}
	 	$sql = "SELECT * FROM member3 where token = '$ID'";
	 	$stmt=$pdo->query($sql);
	 	if($stmt!=""){
	 	$prpfl=$stmt->fetchAll();
	 	}
	 	$login=$prpfl[0][6];
	 	
	 	$sql = "CREATE TABLE project6"
	 	."("
	 	."name TEXT,"
	 	."fdate TEXT,"
		."tdate TEXT,"
		."fmonth TEXT,"
		."tmonth TEXT,"
		."gname TEXT,"
		."mem TEXT,"
		."gid INT,"
		."pid INT"
	 	.");";
	 	$stmt = $pdo->query($sql);
	 	
	 	$sql = "CREATE TABLE mission2"
	 	."("
	 	."date TEXT,"
		."gname TEXT,"
		."pname TEXT,"
		."mname TEXT,"
		."mem TEXT,"
		."gid INT,"
		."pid INT,"
		."mid INT"
	 	.");";
	 	$stmt = $pdo->query($sql);
		
		if($_POST['gname']!=""){
			$gID = $_POST['gname'];
		}else{
			$gID = $_GET[gname];
		}
		$sql="SELECT * FROM group3 where gid = $gID";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$result0 = $stmt->fetchAll();
		}
		if(!$_POST['post']){
			$err++;
		}
		$member = $_POST['member'];
		if(empty($member)){
			$err++;
			$error['member']="担当者を入れてください。";
		}

	 	if($_POST['fm']==4 or $_POST['fm']==6 or $_POST['fm']==9 or $_POST['fm']==11){
			if($_POST['fd']==31){
				$error['fdate']="存在しない日付です。";
				$err++;
			}
		}elseif($_POST['fm']==2){
			if($_POST['fY']%4==0){
				if($_POST['fd']>29){
					$error['fdate']="存在しない日付です。";
					$err++;
				}
			}else{
				if($_POST['fd']>28){
					$error['fdate']="存在しない日付です。";
					$err++;
				}
			}
		}
		if($_POST['tm']==4 or $_POST['tm']==6 or $_POST['tm']==9 or $_POST['tm']==11){
			if($_POST['td']==31){
				$error['tdate']="存在しない日付です。";
				$err++;
			}
		}elseif($_POST['tm']==2){
			if($_POST['tY']%4==0){
				if($_POST['td']>29){
					$error['tdate']="存在しない日付です。";
					$err++;
				}
			}else{
				if($_POST['td']>28){
					$error['tdate']="存在しない日付です。";
					$err++;
				}
			}
		}
		if($_POST['fY']>$_POST['tY']){
			$error['tdate']="終了日は開始日よりも後の日を入力してください。";
			$err++;
		}elseif($_POST['fY']==$_POST['tY']){
			if($_POST['fm']>$_POST['tm']){
				$error['tdate']="終了日は開始日よりも後の日を入力してください。";
				$err++;
			}elseif($_POST['fm']==$_POST['tm']){
				if($_POST['fd']>$_POST['td'] or $_POST['fd']==$_POST['td']){
					$error['tdate']="終了日は開始日よりも後の日を入力してください。";
					$err++;
				}
			}
		}
		if($_POST['name']==""){
			$error['name']="名前を入力してください。";
			$err++;
		}
		if($_POST['name']=="deleted"){
			$error['name']="その名前は使えません。";
			$err++;
		}
		$gname = $result0[0][1];
		$fY = $_POST['fY'];
		$fm = $_POST['fm'];
		$fd = $_POST['fd'];
		if($fm<10){
			$sfm = "0".$fm;
		}else{
			$sfm = $fm;
		}
		if($fd<10){
			$sfd = "0".$fd;
		}else{
			$sfd = $fd;
		}
		$tY = $_POST['tY'];
		$tm = $_POST['tm'];
		if($tm<10){
			$stm = "0".$tm;
		}else{
			$stm = $tm;
		}
		$td = $_POST['td'];
		if($td<10){
			$std = "0".$td;
		}else{
			$std = $td;
		}
		
		if($err==0){
			$sql = "SELECT * FROM project6 where gid=$gID order by pid";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$presult=$stmt->fetchAll();
			}
			if(!empty($presult)){
				$A = count($presult);
				$pID = $presult[($A-1)][8] + 1;
			}else{
				$pID = 1;
			}
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
				$sql->bindParam(':gname', $group, PDO::PARAM_STR);
				$sql->bindParam(':mem',$mem,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
				$sql->bindParam(':pid',$pID,PDO::PARAM_INT);
				$name = $_POST['name'];
				$from = $fY.$sfm.$sfd;
				$to = $tY.$stm.$std;
				$f1 = $fY.$sfm."01";
				$t1 = $tY.$stm."01";
				$group = $gname;
				$mem=$result[0][2];
				$sql->execute();
				if($froma!=""){
					$froma = $froma.",".$mem;
				}else{
					$froma = $mem;
				}
			}
			$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
		 	$stmt=$pdo->query($sql);
		 	if($stmt!=""){
		 		$grl = $stmt->fetchAll();
		 	}
		 	if(!empty($grl)){
		 		foreach($grl as $XX){}
		 		$num = count($grl)+1;
		 	}else{
		 		$num = 1;
		 	}
		 	$user = '連絡';
		 	$pass = 'a';
		 	$comment = $gname.'にプロジェクト:'.$name.'を追加しました。';
		 	
		 	$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
			$sql->bindParam(':id',$num,PDO::PARAM_INT);
			$sql->bindParam(':name',$user,PDO::PARAM_STR);
			$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
			$sql->bindParam(':time',$now,PDO::PARAM_STR);
			$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
			$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
			$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
			$sql->execute();
				
			$sql = "SELECT * FROM project6 where pid=$pID and gid = $gID";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$result1 = $stmt->fetchAll();
			}
			if(!empty($result1)){
				$mID = 1;
				foreach($result1 as $val0){
					$sql = $pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:date,:gname,:pname,:mname,:mem,:gid,:pid,:mid)");
					$sql->bindParam(':date',$date,PDO::PARAM_STR);
					$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
					$sql->bindParam(':pname',$pname,PDO::PARAM_STR);
					$sql->bindParam(':mname',$mname,PDO::PARAM_STR);
					$sql->bindParam(':mem',$mem,PDO::PARAM_STR);
					$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
					$sql->bindParam(':pid',$pID,PDO::PARAM_INT);
					$sql->bindParam(':mid',$mID,PDO::PARAM_INT);
					$date = $val0[1];
					$gname = $val0[5];
					$pname = $val0[0];
					$mname = "開始";
					$mem = $val0[6];
					$sql->execute();
				}
				$mID0=$mID+1;
				foreach($result1 as $val0){
					$sql = $pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:date,:gname,:pname,:mname,:mem,:gid,:pid,:mid)");
					$sql->bindParam(':date',$date,PDO::PARAM_STR);
					$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
					$sql->bindParam(':pname',$pname,PDO::PARAM_STR);
					$sql->bindParam(':mname',$mname,PDO::PARAM_STR);
					$sql->bindParam(':mem',$mem,PDO::PARAM_STR);
					$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
					$sql->bindParam(':pid',$pID,PDO::PARAM_INT);
					$sql->bindParam(':mid',$mID0,PDO::PARAM_INT);
					$date = $val0[2];
					$gname = $val0[5];
					$pname = $val0[0];
					$mname = "終了";
					$mem = $val0[6];
					$sql->execute();
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
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>プロジェクトの作成</title>
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

			select,input[type="text"],
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
			select{
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				padding: 0;
				margin: 0;
				height: 30px;
				background: transparent;
				position: relative;
				z-index: 1;
				padding: 0 40px 0 10px;
				border: 1px solid #ccc;
			}
			select::-ms-expand {
				display: none;
			}
			.selectWrap{
				position: relative;
				display: inline-block;
			}
			.selectWrap::before{
    				content: '';
    				position: absolute;
    				z-index: 0;
    				top: 0;
    				right: 0;
    				background: #ccc;
    				height: 100%;
    				width: 30px;
			}
			.selectWrap::after{
				content: '';
				position: absolute;
				z-index: 0;
				top: 0;
				bottom: 0;
				margin: auto 0;
				right: 9px;
				width: 0px;
				height: 0px;
				border-style: solid;
				border-width: 6px 6px 0 6px;
				border-color: #fff transparent transparent transparent; 
			}
			
		</style>
	</head>
	<body>
	<div class='all'>
		<?php
		
			$a = array(1, 2, 3, 4, 5, 6,7, 8, 9, 10, 11, 12);
			$c = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
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
			$ppp=$_POST['name'];
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				echo "<h1>プロジェクトの作成</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $iid;
				echo "' class='black'>ログアウト</a></div>";
			}

		?>
		<?php
			if($login==1){
				if($err!=0){
					echo "<form action = 'github_mission_6_create_project.php' method = 'post'>";
					echo "<input type = 'hidden' name = 'gname' value = '";
					echo $ggg;
					echo "'>";
					echo "<input type = 'hidden' name = 'ID' value = '";
					echo $iid;
					echo "'>";
					echo "<input type = 'text' name = 'name' value = '";
					echo $ppp;
					echo "' placeholder = 'プロジェクト名'><br>";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'fY'>";
					for ($y=date(Y);$y<date(Y)+11;$y++) {
						echo "<OPTION value=$y>$y</OPTION>";
					}
					echo "</SELECT>";
					echo "</div>年";
					
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'fm'>";
						foreach ($a as $key => $value) {
							$b = $key + 1;
							echo "<OPTION value=$b>$value</OPTION>";
						}
					echo "</SELECT></div>月";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'fd'>";
					foreach($c as $key1 => $value1){
						$d = $key1 + 1;
						echo "<OPTION value=$d>$value1</OPTION>";
					}
					echo "</SELECT></div>日～";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'tY'>";
					for ($y=date(Y);$y<date(Y)+31;$y++) {
						echo "<OPTION value=$y>$y</OPTION>";
					}
					echo "</SELECT></div>年";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'tm'>";
					foreach ($a as $key => $value) {
						$b = $key + 1;
						echo "<OPTION value=$b>$value</OPTION>";
					}
					echo "</SELECT></div>月";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'td'>";
					foreach($c as $key1 => $value1){
						$d = $key1 + 1;
						echo "<OPTION value=$d>$value1</OPTION>";
					}
					echo "</SELECT></div>日<br>";
					if(is_array($result0)){
						$n=1;
						foreach($result0 as $val){
							echo "<input type = 'checkbox' name = 'member[]' value = '";
							echo $val[2];
							echo "' id='checkbox";
							echo $n;
							echo "'>";
							echo "<label for='checkbox";
							echo $n;
							echo "' class='checkbox'>";
							echo $val[2]."</label><br>";
							$n++;
						}
					}
					echo "<input type='submit' name='post' value='作成' class='button'>";
					echo "</form>";
				}
			}
		?>
		<?php
			if($login==1){
				$ID = $_POST['ID'];
				echo "<div align='left'>";
				if($err!=0){
					if($_POST['post']){
						foreach($error as $eval){
							echo $eval."<br>";
						}
					}
				}else{
					echo "<a href = 'github_mission_6_project.php?gname=";	
					echo $ggg;
					echo "&pname=";
					echo $pID;
					echo "&id=";
					echo $ID;
					echo "'>カレンダー作成</a><br>";
				}
				echo "<a href ='github_mission_6_group.php?name=";
				echo $ggg;
				echo "&id=";
				echo $iid;
				echo "'>グループページに戻る</a><br>";
				echo "<a href ='github_mission_6_mypage.php?id=";
				echo $iid;
				echo "'>マイページへ</a><br></div>";
				
			}
		?>
	</div>
	</body>
</html>

