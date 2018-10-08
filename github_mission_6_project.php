<?php
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
date_default_timezone_set('Asia/Tokyo');
try{ //PDO設定
	$pdo = new PDO($dsn,$user,$password);
	$err = 0;
 	if($pdo == null){
  		echo "cannot connected.<br>"; //connection確認
 	}
	$gID = $_GET[gname];
	$pID = $_GET[pname];
	$ID = $_GET[id];
	$come = $_GET[come];
	$timestamp = time();
	$now = date("Y"."m"."d",$timestamp);

	$sql = "SELECT * FROM project6 where pid = $pID and gid = $gID";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$result = $stmt->fetchAll();
	}
	$pname = $result[0][0];
	$gname = $result[0][6];
	
	$sql = "SELECT * FROM member3 where token = '$ID'";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$nresult = $stmt->fetchAll();
	}
	$login = $nresult[0][6];
	
	$mem0 = $nresult[0][1];
	
	$sql = "SELECT * FROM mission2 where pid = $pID and gid = $gID and date = '$now'";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$todaysmission = $stmt->fetchAll();
	}
	
	if(!empty($todaysmission)){
		$x = count($todaysmission);
		for($i = 0; $i < $x ; $i++){
			$mcount = 0;
			$missionname=$todaysmission[$i][3];
			for($j = ($i+1); $j < $x; $j++){
				if($todaysmission[$j][3]==$missionname){
					$mcount++;
				}
			}
			if($mcount==0){
				$tms[]=$todaysmission[$i];
			}
		}
		
		if(!empty($tms)){
			foreach($tms as $key=>$value){
				foreach($todaysmission as $val0){
					if($val0[3]==$value[3]){
						$members[$key][]=$val0[4];
					}
				}
			}
		}
	}
	
	if(!empty($result)){
		foreach($result as $key=>$value){
			$meme = $value[6];
			$sql = "SELECT * FROM mission2 where pid = $pID and gid = $gID and mem = '$meme' ORDER BY date";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$result1 = $stmt->fetchAll();
				if(!empty($result1)){
					foreach($result1 as $val){
						$result2[$key][] = $val[3];
						$result3[$key][] = $val[0];
					}
				}
			}
		}
	}
	
	$f1 = $result[0][3];
	$fw = date('w',strtotime($f1));
	$fY = date('Y',strtotime($f1));
	$fm = date('m',strtotime($f1));
	$fd = date('d',strtotime($f1));

	$t1 = $result[0][4];
	$tw = date('w',strtotime($t1));
	$tY = date('Y',strtotime($t1));
	$tm = date('m',strtotime($t1));
	$td = date('d',strtotime($t1));
	
	if(intval($tm)>=intval($fm)){
		$yc = intval($tY) - intval($fY);
		$mc = intval($tm) - intval($fm);
		$cnt = 12 * $yc + $mc ;
	}else{
		$yc = intval($tY) - intval($fY) - 1; 
		$mc = intval($tm) +12 - intval($fm);
		$cnt = 12 * $yc +$mc;
	}
	for($n = 0;$n<=$cnt;$n++){
		$month[$n]=(intval($fm) + $n)%12;
		if($n==0){
			$year[$n]=$fY;
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
		$week[$n]=date('w',strtotime($start[$n]));
		if($year[$n]%4==0 and $month[$n]==2){
				$max[$n]=5;
				$dmax[$n]=29;
		}elseif($year[$n]%4!=0 and $month[$n]==2){
			if($week[$n]==0){
				$max[$n]=4;
			}else{
				$max[$n]=5;
			}
			$dmax[$n]=28;

		}elseif($month[$n]==4 or $month[$n]==6 or $month[$n]==9 or $month[$n]==11){
			if($week[$n]==6){
				$max[$n]=6;
			}else{
				$max[$n]=5;
			}
			$dmax[$n]=30;

		}else{
			if($week[$n]>=5){
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
			$sql = "SELECT * FROM mission2 where date = '$dayy' and pid = $pID and gid = $gID";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$result0[$n][($j-1)] = $stmt->fetchAll();
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
<style>

body{
	background-color: #AAFFFF;
}
			
div.all{
	background-color: #FFFFFF;
	text-align: center;
	font-size: 20px;
}

td a{
display: block;
width: 100%;
height: 100%;
}

td a:hover{
background-color: #FFFF66;
}

td.sample1{
background-color: #FFFFCC;
}
td.sample1 a{
display: block;
width: 100%;
height: 100%;
}

td.sample1 a:hover{
background-color: #FFFF66;
}

td.sample2{
background-color: #FFCCFF;
color: #FFFFFF;
}

td.sample2 a{
display: block;
width: 100%;
height: 100%;
}

td.sample2 a:hover{
background-color: #FF99FF;
}

			a.button{
				display: inline-block;
				padding: 0.5em 1em;
				text-decoration: none;
				background: #668ad8;/*ボタン色*/
				color: #FFF;
				border-bottom: solid 4px #627295;
				border-radius: 3px;
			}
			a.button:hover{
				display: inline-block;
				padding: 0.5em 1em;
				text-decoration: none;
				background: #668ad8;/*ボタン色*/
				color: #FFFF33;
				border-bottom: solid 4px #627295;
				border-radius: 3px;
			}
			a.button:active {/*ボタンを押したとき*/
				-ms-transform: translateY(4px);
				-webkit-transform: translateY(4px);
				transform: translateY(4px);/*下に動く*/
				border-bottom: none;/*線を消す*/
			}
			a.button1{
				display: inline-block;
				padding: 0.5em 1em;
				text-decoration: none;
				background: #668ad8;/*ボタン色*/
				color: #FFF;
				width: 150px;
				border-bottom: solid 4px #627295;
				border-radius: 3px;
			}
			a.button1:hover{
				display: inline-block;
				padding: 0.5em 1em;
				text-decoration: none;
				background: #668ad8;/*ボタン色*/
				color: #FFFF33;
				border-bottom: solid 4px #627295;
				border-radius: 3px;
			}
			a.button1:active {/*ボタンを押したとき*/
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
			
			table{
			background-color: #DDFFFF;/*ボタン色*/
			}
			table.white{
			background-color: #FFFFFF;/*ボタン色*/
			}

</style>

<title>
<?php
echo $pname;
?>
</title>
</head>
<body>
<div class='all'>
<?php
if($login==0){
	echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
	echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
}else{
	if($pname=="deleted"){
		echo "このプロジェクトは削除されました。";
		echo "<a href = 'github_mission_6_group.php?name=";
		echo $gID;
		echo "&id=";
		echo $_GET[id];
		echo "' class='button'>グループページに戻る</a><br>";
		echo "<a href = 'github_mission_6_mypage.php?id=";
		echo $_GET[id];
		echo "' class='button'>マイページに戻る</a><br>";
	}else{
		echo "<h1>";
		echo $pname;
		echo "</h1>";
		
		echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
		echo $ID;
		echo "' class='black'>ログアウト</a></div>";
		
		if(!empty($result) and !empty($nresult)){
			$p=0;
			foreach($result as $nval){
				if($nval[6] == $nresult[0][1]){
					$p++;
				}
			}
			if($p!=0){
				echo "<div align='left'>";
				echo "<a href = 'github_mission_6_edit_prooject.php?pname=";
				echo $pID;
				echo "&gname=";
				echo $gID;
				echo "&id=";
				echo $_GET[id];
				echo "' class='button'>プロジェクト名の変更</a><br>";
				echo "</div>";
			}
		}
		echo "<div align='center'>";
		echo "<table border = '5'>";
		echo "<tr>";
		echo "<td>";
		if($p!=0){
			echo "<a href='github_mission_6_create_mission.php?pname=";
			echo $pID;
			echo "&gname=";
			echo $gID;
			echo "&id=";
			echo $_GET[id];
			echo "' class='button1'>予定の追加</a><br>";
		}
		echo "<TABLE class='white' border = '0'>";
		for($n=0;$n<=$cnt;$n++){
			
			if($month[$n]==0){
				$mm="12";
			}elseif($month[$n]<10){
				$mm = "0".$month[$n];
			}else{
				$mm = $month[$n];
			}
			if($n%4==0){
				echo "<TR>";
			}
			echo "<TD>";
			echo "<table border = '1' class='white'>";
			echo "<tr>";
			echo"<th colspan='7'>";
			echo $year[$n]."年 ".$mm."月";
			echo "</th>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<th style='color:red'> 日</th>";
			echo "<th> 月</th>";
			echo "<th> 火</th>";
			echo "<th> 水</t>";
			echo "<th> 木</th>";
			echo "<th> 金</th>";
			echo "<th> 土</th>";
			echo "</tr>";
			echo "<tr>";
			$day = 1;
			for($i=0;$i<$week[$n];$i++){
				echo "<td> </td>";
			}
			for($i=$week[$n];$i<=6;$i++){
				if($day<10){
					$sday = "0".$day;
				}else{
					$sday = $day;
				}
				$sdate = $year[$n].$mm.$sday;
				if($sdate==$now){
					echo "<td class='sample2' >";
				}elseif(!empty($result0[$n][($day-1)])){
					$RRR = 0;
					foreach($result0[$n][($day-1)] as $dmis){
						if($dmis[3]!="deleted"){
							$RRR++;
						}
					}
					if($RRR!=0){
						echo "<td class='sample1'>";
					}else{
						echo "<td>";
					}
				}else{
					echo "<td>";
				}
				echo "<a href = 'github_mission_6_mission.php?gname=";
				echo $gID;
				echo "&pname=";
				echo $pID;
				echo "&date=";
				echo $sdate;
				echo "&id=";
				echo $_GET[id];
				echo "'>";
				echo $day;
				echo "</td>";
				$day++;
			}
			echo "</tr>";
			for($j=1; $j<$max[$n]-1; $j++){
				echo "<tr>";
				for($i=0;$i<7;$i++){
			 		if($day<10){
					$sday = "0".$day;
					}else{
						$sday = $day;
					}
					$sdate = $year[$n].$mm.$sday;
					if($sdate==$now){
						echo "<td class='sample2' >";
					}elseif(!empty($result0[$n][($day-1)])){
						$RRR = 0;
						foreach($result0[$n][($day-1)] as $dmis){
							if($dmis[3]!="deleted"){
								$RRR++;
							}
						}
						if($RRR!=0){
							echo "<td class='sample1'>";
						}else{
							echo "<td>";
						}
					}else{
						echo "<td>";
					}
					echo "<a href = 'github_mission_6_mission.php?gname=";
					echo $gID;
					echo "&pname=";
					echo $pID;
					echo "&date=";
					echo $sdate;
					echo "&id=";
					echo $_GET[id];
					echo "'>";
					echo $day;
					echo "</td>";
					$day++;
				}
				echo "</tr>";
			}
			echo "<tr>";
			for($i=$day;$i<=$dmax[$n];$i++){
				$sdate = $year[$n].$mm.$i;
				if($sdate==$now){
					echo "<td class='sample2' >";
				}elseif(!empty($result0[$n][($i-1)])){
					$RRR = 0;
					foreach($result0[$n][($i-1)] as $dmis){
						if($dmis[3]!="deleted"){
							$RRR++;
						}
					}
					if($RRR!=0){
						echo "<td class='sample1'>";
					}else{
						echo "<td>";
					}
				}else{
					echo "<td>";
				}
				echo "<a href = 'github_mission_6_mission.php?gname=";
				echo $gID;
				echo "&pname=";
				echo $pID;
				echo "&date=";
				echo $sdate;
				echo "&id=";
				echo $_GET[id];
				echo "'>";
				echo $i;
				echo "</td>";
			}
			
			echo "</tr>";
			$day = 1;
			
			echo "</table>";
			echo "</TD>";
			if($n%4==3){
				echo "</TR>";
			}elseif($n==$cnt){
				echo "</TR>";
			}
		}
		echo "</TABLE><br>";
		echo "</td>";

		echo "<td>";
		echo "<div class='all'>";
		echo "<h2>".$now."</h2>";
		echo "今日の予定:<br>";
		if(!empty($tms)){
			foreach($tms as $key=>$value){
				if($value[3]!="deleted"){
					echo $value[3]." 担当: ";
					foreach($members[$key] as $val){
						echo $val."/ ";
					}
					echo "<br>";
				}
			}
		}else{
			echo "なし<br>";
		}
		echo "<br>";
		foreach($result as $key=>$value){
			echo $value[6].":<br>";
			$R = 0;
			if(!empty($result2[$key])){
			foreach($result2[$key] as $n=>$vaa){
				if($R<=3){
					if($vaa!="deleted"){
						if(intval($result3[$key][$n])==intval($now)){
						echo "<span style = 'color:red'>";
						echo $vaa.":";
						echo $result3[$key][$n];
						echo "　";
						echo "</span>";
						$R++;
						}elseif(intval($result3[$key][$n])>intval($now)){
						echo "<span>";
						echo $vaa.":";
						echo $result3[$key][$n];
						echo "　";
						echo "</span>";
						$R++;
						}
					}
				}
			}
			}
			
			echo "<br>";
		}
		if($p!=0){
			echo "<a href ='github_mission_6_edit_project_member.php?gname=";
			echo $gID;
			echo "&id=";
			echo $_GET[id];
			echo "&pname=";
			echo $pID;
			echo "' class='button1'>メンバーの追加</a><br>";
		}
		echo "</div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		echo "<div align='left'>";
		echo "<a href = 'github_mission_6_group.php?name=";
		echo $gID;
		echo "&id=";
		echo $_GET[id];
		echo "' class='button'>グループページに戻る</a><br>";
		echo "<a href = 'github_mission_6_mypage.php?id=";
		echo $_GET[id];
		echo "' class='button'>マイページに戻る</a><br>";
		echo "</div>";

		echo "<div align='left'>";
		if($p!=0){
			echo "<a href = 'github_mission_6_escape_project.php?pname=";
			echo $pID;
			echo "&id=";
			echo $_GET[id];
			echo "&gname=";
			echo $gID;
			echo "' class='button'>脱退</a><br>";
		}else{
			echo "<a href = 'github_mission_6_come_project.php?pname=";
			echo $pID;
			echo "&id=";
			echo $_GET[id];
			echo "&gname=";
			echo $gID;
			echo "' class='button'>参加</a><br>";
		}
		echo "</div>";
		
		if($p!=0){
		echo "<div align='left'>";
		echo "<a href = 'github_mission_6_delete_project.php?pname=";
		echo $pID;
		echo "&id=";
		echo $_GET[id];
		echo "&gname=";
		echo $gID;
		echo "' class='button'>プロジェクトの削除</a></div>";
		}
	}
}
?>
</div>
</body>
</html>
