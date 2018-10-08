<?php
	$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
	$user = 'ユーザー名';
	$password = 'パスワード';
	date_default_timezone_set('Asia/Tokyo');
	try{ //PDO設定
		$pdo = new PDO($dsn,$user,$password);
		$err = 0;
		$now = date("Y/m/d H:i:s",time());
		$week = array('日','月','火','水','木','金','土');
	 	if($pdo == null){
	  		echo "cannot connected.<br>"; //connection確認
	 	}
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
			$pp = $_POST['pname'];
		}else{
			$pp = $_GET[pname];
		}
		if($_POST['gname']!=""){
			$gg = $_POST['gname'];
		}else{
			$gg = $_GET[gname];
		}
		if($_POST['W']!=""){
			$WW = $_POST['W'];
		}
		$aweek = $week[$WW];
		
		$sql = "SELECT * FROM project6 where pid = '$pp' and gid = '$gg'";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$result = $stmt->fetchAll();
		}
		
		$member=$_POST['member'];
		$group = $_POST['gname'];
		$project = $_POST['pname'];
		$mission = $_POST['mname'];
		/*$Y = $_POST{'Y'};
		$m = $_POST{'m'};
		$d = $_POST{'d'};*/
		$err = 0;
		if($mission==""){
			$err++;
			$error['mname'] = "名前を入力してください。";
		}
		if($mission=="deleted"){
			$err++;
			$error['mname'] = "無効な名前です。";
		}
		if(empty($member)){
			$err++;
			$error['member'] = "誰か担当者を入れてください。";
		}
		$sql = "SELECT * FROM project6 where gid = $group and pid = $project";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$result0 = $stmt->fetchAll();
			$fdate = $result0[0][1];
			$tdate = $result0[0][2];
			$gname = $result0[0][5];
			$pname = $result0[0][0];
		}

		if($err==0){
			$sql = "SELECT * FROM mission2 where gid = $group and pid = $project order by mid";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$presult = $stmt->fetchAll();
				if(!empty($presult)){
					$AA = count($presult);
					$mID = $presult[($AA-1)][7] + 1;
				}else{
					$mID = 1;
				}
			}
		}
		if($err==0){
			if($fdate!="" and $tdate!=""){
				$fyear = date('Y',strtotime($fdate));
				$fmonth = date('m',strtotime($fdate));
				$fday = date('d',strtotime($fdate));
				$fweek = date('w',strtotime($fdate));
				
				$tyear = date('Y',strtotime($tdate));
				$tmonth = date('m',strtotime($tdate));
				$tday = date('Y',strtotime($tdate));
				$tweek = date('w',strtotime($tdate));
				
				if(intval($tm)>=intval($fm)){
					$yc = intval($tyear) - intval($fyear);
					$mc = intval($tmonth) - intval($fmonth);
					$cnt = 12 * $yc + $mc ;
				}else{
					$yc = intval($tyear) - intval($fyear) - 1; 
					$mc = intval($tmonth) +12 - intval($fmonth);
					$cnt = 12 * $yc +$mc;
				}
				for($n = 0;$n<=$cnt;$n++){
					$month[$n]=(intval($fmonth) + $n)%12;
					if($n==0){
						$year[$n]=$fyear;
					}else{
						if($month[$n-1]==0){
							$year[$n]=$year[$n-1] + 1;
						}else{
							$year[$n]=$year[$n-1];
						}
					}
					if($month[$n]==0){
						$start[$n]=$year[$n]."1201";
					}elseif($month[$n]<10){
						$start[$n]=$year[$n]."0".$month[$n]."01";
					}else{
						$start[$n]=$year[$n].$month[$n]."01";
					}
					$wee[$n]=date('w',strtotime($start[$n]));
					if($year[$n]%4==0 and $month[$n]==2){
							$max[$n]=5;
							$dmax[$n]=29;
					}elseif($year[$n]%4!=0 and $month[$n]==2){
						if($wee[$n]==0){
							$max[$n]=4;
						}else{
							$max[$n]=5;
						}
						$dmax[$n]=28;

					}elseif($month[$n]==4 or $month[$n]==6 or $month[$n]==9 or $month[$n]==11){
						if($wee[$n]==6){
							$max[$n]=6;
						}else{
							$max[$n]=5;
						}
						$dmax[$n]=30;

					}else{
						if($wee[$n]>=5){
							$max[$n]=6;
						}else{
							$max[$n]=5;
						}
						$dmax[$n]=31;
					}
					for($j=1;$j<=$dmax[$n];$j++){
						if($month[$n]==0){
							$mm=12;
						}elseif($month[$n]<10){
							$mm="0".$month[$n];
						}else{
							$mm=$month[$n];
						}
						if($j<10){
							$dd="0".$j;
						}else{
							$dd=$j;
						}
						$dayy=$year[$n].$mm.$dd;
						$ddd[$n][($j-1)]=$dayy;
						if(intval($dayy)<=intval($tdate) and intval($dayy)>=intval($fdate)){
							$aw = date('w',strtotime($dayy));
							if($aw==$WW){
								if(!empty($member)){
									foreach($member as $value){
										$sql=$pdo->prepare("INSERT INTO mission2(date,gname,pname,mname,mem,gid,pid,mid) VALUES(:date,:gname,:pname,:mname,:mem,:gid,:pid,:mid)");
										$sql->bindParam(':date',$dayy,PDO::PARAM_STR);
										$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
										$sql->bindParam(':pname',$pname,PDO::PARAM_STR);
										$sql->bindParam(':mname',$mname,PDO::PARAM_STR);
										$sql->bindParam(':mem',$value,PDO::PARAM_STR);
										$sql->bindParam(':gid',$gg,PDO::PARAM_STR);
										$sql->bindParam(':pid',$pp,PDO::PARAM_STR);
										$sql->bindParam(':mid',$mID,PDO::PARAM_STR);
										$mname = $aweek.':'.$mission;
										$sql->execute();
									}
								}
								$mID = $mID + 1;
							}
						}
						
					}
				}
				if(!empty($member)){
					foreach($member as $value){
						if($froma!=""){
							$froma = $froma.','.$value;
						}else{
							$froma = $value;
						}
					}
				}
				$sql = "SELECT * FROM grlog3 where gid = $group order by id asc";
				$stmt=$pdo->query($sql);
				if($stmt!=""){
					$grl = $stmt->fetchAll();
				}
				if(!empty($grl)){
					$num = count($grl)+1;
				}else{
					$num = 1;
				}
				$user = '連絡';
				$comment = $myname.'が'.$pname.'に毎週'.$aweek.'曜日の予定'.$mission.'を作成しました。';
				$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
				$sql->bindParam(':id',$num,PDO::PARAM_INT);
				$sql->bindParam(':name',$user,PDO::PARAM_STR);
				$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
				$sql->bindParam(':time',$now,PDO::PARAM_STR);
				$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
				$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
				$sql->bindParam(':gid',$gg,PDO::PARAM_INT);
				$sql->execute();
			}
		}
		$sql="SELECT * FROM mission2";
		$stmt = $pdo->query($sql);
		$result1 = $stmt->fetchAll();
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
			if($_POST['W']!=""){
				$WWW = $_POST['W'];
			}else{
				$WWW = date('w',strtotime($date));
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
					echo "<form action = 'github_mission_6_create_weekly_mission.php' method = 'POST'>";
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
					echo "毎週<SELECT name = 'W'>";
					for ($y=0;$y<7;$y++) {
						echo "<OPTION value=$y>$week[$y]</OPTION>";
					}
					echo "</SELECT></div>曜日<br>";
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
				echo "'>グループページへ</a><br>";
				echo "<a href = 'github_mission_6_mypage.php?id=";
				echo $iid;
				echo "'>マイページへ</a><br>";
				
				echo "<a href = 'github_mission_6_create_mission.php?gname=";
				echo $ggg;
				echo "&pname=";
				echo $ppp;
				echo "&id=";
				echo $iid;
				echo "'>一日のスケジュール作成</a><br>";
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

