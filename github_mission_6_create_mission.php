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
	 	
		
		if($_POST['pname']!=""){
			$pID = $_POST['pname'];
		}else{
			$pID = $_GET[pname];
		}
		if($_POST['gname']!=""){
			$gID = $_POST['gname'];
		}else{
			$gID = $_GET[gname];
		}
		$sql = "SELECT * FROM project6 where pid = $pID and gid = $gID";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$result = $stmt->fetchAll();
		}
		
		$member=$_POST['member'];
		$group = $result[0][5];
		$project = $result[0][0];
		$mission = $_POST['mname'];
		$Y = $_POST{'Y'};
		$m = $_POST{'m'};
		$d = $_POST{'d'};
		$err = 0;
		if($mission==""){
			$err++;
			$error['mname'] = "名前を入力してください。";
		}
		if(empty($member)){
			$err++;
			$error['member'] = "誰か担当者を入れてください。";
		}
		if($m==4 or $m==6 or $m==9 or $m==11){
			if($d==31){
				$error['fdate']="存在しない日付です。";
				$err++;
			}
		}elseif($m==2){
			if($Y%4==0){
				if($d>29){
					$error['fdate']="存在しない日付です。";
					$err++;
				}
			}else{
				if($d>28){
					$error['fdate']="存在しない日付です。";
					$err++;
				}
			}
		}
		$sql = "SELECT * FROM project6 where gid = $gID and pid = $pID";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$result0 = $stmt->fetchAll();
			$fdate = $result0[0][1];
			$tdate = $result0[0][2];
		}

		if($m<10){
			$tm = "0".$m;
		}else{
			$tm = $m;
		}
		if($d < 10){
			$td = "0".$d;
		}else{
			$td = $d;
		}
		$date = $Y.$tm.$td;
		
		if($fdate!="" and $tdate!=""){
			if(intval($fdate)>intval($date) or intval($tdate)<intval($date)){
				$err++;
				$error['fdate']="期間に入っていません。";
			} 
		}
		if($err==0){
			$sql = "SELECT * FROM mission2 where gid = $gID and pid = $pID";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$presult = $stmt->fetchAll();
			}
			if(!empty($presult)){
				$A = count($presult);
				$mID = $presult[($A-1)][7];
			}else{
				$mID = 1;
			}
		}
		
		if($err==0){
			if(!empty($member)){
				foreach($member as $value){
					$sql = $pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:date, :gname, :pname, :mname,:mem,:gid,:pid,:mid)");
					$sql->bindParam(':date', $date, PDO::PARAM_STR);//nameをbind
					$sql->bindParam(':gname',$group,PDO::PARAM_STR);
					$sql->bindParam(':pname',$project,PDO::PARAM_STR);
					$sql->bindParam(':mname',$mission,PDO::PARAM_STR);
					$sql->bindParam(':mem',$xx,PDO::PARAM_STR);
					$sql->bindParam(':gid',$gID,PDO::PARAM_INT);
					$sql->bindParam(':pid',$pID,PDO::PARAM_INT);
					$sql->bindParam(':mid',$mID,PDO::PARAM_INT);
					$xx = $value;
					if($m<10){
						$tm = "0".$m;
					}else{
						$tm = $m;
					}
					if($d < 10){
						$td = "0".$d;
					}else{
						$td = $d;
					}
					$date = $Y.$tm.$td;
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
					if($froma!=""){
					$froma = $froma.','.$xx;
					}else{
					$froma = $xx;
					}
				}
				
				$user = '連絡';
				$gname = $group;
				$comment = $myname.'が'.$project.'に'.$date.'の予定'.$mission.'を作成しました。';
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
			$sql="SELECT * FROM mission2";
			$stmt = $pdo->query($sql);
			$result1 = $stmt->fetchAll();
			
			
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
		<title>スケジュールの作成</title>
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

			if($_POST['pname']!=""){
				$ppp = $_POST['pname'];
			}else{
				$ppp = $_GET[pname];
			}
			
			if($_POST['ID']!=""){
				$iid = $_POST['ID'];
			}else{
				$iid = $_GET[id];
			}

			if($_POST['mname']!=""){
				$mmm = $_POST['mname'];
			}else{
				$mmm = "";
			}
			
			$date = $_GET[date];
			if($_POST['Y']!=""){
				$YYY = $_POST['Y'];
			}else{
				$YYY = date('Y',strtotime($date));
			}
			
			if($_POST['m']!=""){
				$mm = $_POST['m'];
			}else{
				$mm = date('m',strtotime($date));
			}
			
			if($_POST['d']!=""){
				$ddd = $_POST['d'];
			}else{
				$ddd = date('d',strtotime($date));
			}
			
			if($login==0){
				echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
				echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
			}else{
				echo "<h1>スケジュールの作成</h1>";
				echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
				echo $iid;
				echo "' class='black'>ログアウト</a></div><br>";
			}
			
		?>
		<?php
			if($login==1){
				if($err!=0){
					echo "<form action = 'github_mission_6_create_mission.php' method = 'POST'>";
					echo "<input type = 'hidden' name = 'gname' value = '";
					echo $ggg;
					echo "'>";
					echo "<input type = 'hidden' name = 'pname' value = '";
					echo $ppp;
					echo "'>";
					echo "<input type = 'hidden' name = 'ID' value = '";
					echo $iid;
					echo "'>";
					echo "<input type = 'text' name = 'mname' value = '";
					echo $mmm;
					echo "' placeholder = '予定'><br>";
					if(!empty($result)){
						$n=1;
						foreach ($result as $val){
							$MA =0;
							if(!empty($member)){
								foreach($member as $mem){
									if($val[6]==$mem){
										$MA++;
									}
								}
							}
							echo "<input type = 'checkbox' name = 'member[]' value = '";
							echo $val[6];
							echo "' id='checkbox";
							echo $n;
							if($MA==0){
							echo "'>";
							}else{
							echo "' checked>";
							}
							echo "<label for='checkbox";
							echo $n;
							echo "' class='checkbox'>";
							echo $val[6];
							echo "</label><br>";
							$n++;
						}
					}
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'Y'>";
					for ($y=date(Y);$y<date(Y)+11;$y++) {
						if($y==$YYY){
							echo "<OPTION value=$y selected>$y</OPTION>";
						}else{
							echo "<OPTION value=$y>$y</OPTION>";
						}
					}
					echo "</SELECT></div>年";

					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'm'>";
					foreach ($a as $key => $value) {
						$b = $key + 1;
						if($b==$mm){
						echo "<OPTION value=$b selected>$value</OPTION>";
						}else{
						echo "<OPTION value=$b>$value</OPTION>";
						}
					}
					echo "</SELECT></div>月";
					echo "<div class='selectWrap'>";
					echo "<SELECT name = 'd'>";
					foreach($c as $key1 => $value1){
						$d = $key1 + 1;
						if($d==$ddd){
						echo "<OPTION value=$d selected>$value1</OPTION>";
						}else{
						echo "<OPTION value=$d>$value1</OPTION>";
						}
					}
					echo "</SELECT></div>日<br>";
					echo "<input class='button' type = 'submit' value = '作成' name='post'><br>";
					echo "</form><br>";
				}
			}
		?>
		<?php	
			if($login==1){
				echo "<div align='left'>";
				echo "<a href = 'github_mission_6_project.php?gname=";
				echo $ggg;
				echo "&pname=";
				echo $ppp;
				echo "&id=";
				echo $iid;
				echo "'>プロジェクトページへ戻る</a><br>";
				echo "<a href = 'github_mission_6_group.php?name=";
				echo $ggg;
				echo "&id=";
				echo $iid;
				echo "'>グループページへ戻る</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";
				echo $iid;
				echo "'>マイページへ戻る</a><br>";
				echo "<a href = 'github_mission_6_create_weekly_mission.php?gname=";
				echo $ggg;
				echo "&pname=";
				echo $ppp;
				echo "&id=";
				echo $iid;
				echo "'>週単位のスケジュール作成</a><br>";
				if($err==0){
					echo "スケジュールを作成しました。<br>";
					
				}else{
					if($_POST['post']){
						foreach($error as $value){
						echo $value."<br>";
						}
					}
				}
				echo "</div>";
			}
		?>
		</div>
	</body>
</html>

