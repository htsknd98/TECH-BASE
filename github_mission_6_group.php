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
 	$token = $_GET[id];
 	
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
 	
 	$sql = "SELECT * FROM member3 where token = '$token'";
 	$stmt = $pdo->query($sql);
 	if($stmt!=""){
 		$prpfl = $stmt->fetchAll();
 	}
 	$ID = $prpfl[0][0];
 	$login = $prpfl[0][6];
	$gID = $_GET[name];
	$today = date("Y"."m"."d",time());
	$now = date("Y/m/d H:i:s",time());
	$myname = $prpfl[0][1];
	$delete = $_GET[del];
	$sql = "SELECT * FROM group3 where gid = $gID ORDER BY member";
		$stmt = $pdo->query($sql);
	if($stmt!=""){
		$result0 = $stmt->fetchAll();
	}
	
	$gname = $result0[0][1];
	
	if($ID == ""){
		$err++;
	}else{
		$sql = "SELECT * FROM member3 where id = '$ID'";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$nresult = $stmt->fetchAll();
		}
	}
	
	if($delete!=""){
		$sql = "update grlog3 set name='', comment = '削除しました。', mem='',time='$now' where id = '$delete' and gid='$gID'";
		$stmt=$pdo->query($sql);
	}
	if(isset($_GET[post])){
		$user=$_GET[user];
		$comment = $_GET[comment];
		$member = $_GET[member];
		$pass = $_GET[password];
		if(!empty($member)){
			foreach($member as $value){
				if($froma!=""){
				$froma = $froma.",".$value;
				}else{
				$froma = $value;
				}
			}
		}
		
		$sql = "SELECT * FROM grlog3 where gid = $gID order by id asc";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$logg = $stmt->fetchAll();
		}
		if(!empty($logg)){
			foreach($logg as $X){
			
			}
			$num = count($logg) + 1;
		}else{
		$num = 1;
		}
		
		if($user!="" and $comment !=""){
			$sql = $pdo->prepare("INSERT INTO grlog3(id,name,comment,time,gname,mem,gid) VALUES(:id,:name,:comment,:time,:gname,:mem,:gid)");
			$sql->bindParam(':id',$num,PDO::PARAM_INT);
			$sql->bindParam(':name',$user,PDO::PARAM_STR);
			$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
			$sql->bindParam(':time',$now,PDO::PARAM_STR);
			$sql->bindParam(':gname',$gname,PDO::PARAM_STR);
			$sql->bindParam(':mem',$froma,PDO::PARAM_STR);
			$sql->bindParam(':gid',$gID,PDO::PARAM_STR);
			$sql->execute();
		}
	}
	
	if($gname == ""){
		$err++;
	}else{
 		$sql = "SELECT * FROM project6 where gid = $gID ORDER BY mem";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
			$result = $stmt->fetchAll();
		}
		
		if(!empty($nresult) and !empty($result)){
			$name = $nresult[0][1];
			foreach($result as $val){
				if($val[6]==$name){
					$result1[]=$val;
				}
			}
			foreach($result as $va){
				$rr=0;
				if(!empty($result1)){
					foreach($result1 as $val1){
						if($va[0]==$val1[0]){
							$rr++;
						}
					}
				}
				if($rr==0){
					$result2[]=$va;
				}
			}
			
			$n = count($result2);
			if(!empty($result2)){
				$nn=0;
				for($i=0; $i<$n;$i++){
					$p = 0;
					$pname=$result2[$i][0];
					for($j=($i+1);$j<$n;$j++){
						if($result2[$j][0]==$pname){
							$p++;
						}
					}
					
					if($p==0){
						$resulk[$nn]=$result2[$i];
						$nn++;
					}
					$nnn[]=$p;
				}
			}
			
			if(!empty($result1)){
				foreach($result1 as $key=>$va1){
					foreach($result as $vall){
						if($vall[0]==$va1[0]){
							$resul[$key][] = $vall[6];
						}
					}
				}
			}
			if(!empty($resulk)){
				foreach($resulk as $key=>$valk){
					foreach($result as $valuu){
						if($valuu[0]==$valk[0]){
							$resuly[$key][] = $valuu[6];
						}
					}
				}
			}
		}
		if(!empty($result1)){
			foreach($result1 as $key=>$value){
				$pro = $value[8];
				$you = $value[6];
				$sql = "SELECT * FROM mission2 where pid = $pID and gid = $gID order by date";
				$stmt = $pdo->query($sql);
				if($stmt!=""){
				$mresult[$key] = $stmt->fetchAll();
				}
			}
			if(!empty($mresult)){
				$N = count($mresult);
				for($i=0;$i<$N;$i++){
					$missions = $mresult[$i];
					$M = count($missions);
					for($j = 0;$j<$M;$j++){
						$mname0 = $missions[$j][3];
						$mdate0 = $missions[$j][0];
						$mm=0;
						for($l = ($j+1);$l<$M;$l++){
							if($missions[$l][3]==$mname0 and $missions[$l][0]==$mdate0){
								$mm++;
							}
						}
						if($mm==0){
							$missionname[$i][$j][]=$missions[$j];
						}
					
					}
				}
			}
		}
		$sql="SELECT * FROM groupinvite3 where gid=$gID";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$giv=$stmt->fetchAll();
		}
		
		$sql="SELECT * FROM grlog3 where gid=$gID order by id desc";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$glog = $stmt->fetchAll();
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
<title><?php echo $gname; ?></title>
<style>
body{
background-color: #AAFFFF;
}

div.all{
background-color: #FFFFFF;
text-align: center;
font-size: 20px;
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

a.pink{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #FF00FF;/*ボタン色*/
color: #000000;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.pink:hover{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #FF99FF;/*ボタン色*/
color: #222222;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.pink:active {/*ボタンを押したとき*/
-ms-transform: translateY(4px);
-webkit-transform: translateY(4px);
transform: translateY(4px);/*下に動く*/
border-bottom: none;/*線を消す*/
}

a.button{
font-size 25px;
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

input.button{
font-size 20px;
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFF;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
input.button:hover{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFFF33;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
input.button:active {/*ボタンを押したとき*/
-ms-transform: translateY(4px);
-webkit-transform: translateY(4px);
transform: translateY(4px);/*下に動く*/
border-bottom: none;/*線を消す*/
}

span.button{
font-size 25px;
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFF;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
span.button:hover{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #668ad8;/*ボタン色*/
color: #FFFF33;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
span.button:active {/*ボタンを押したとき*/
-ms-transform: translateY(4px);
-webkit-transform: translateY(4px);
transform: translateY(4px);/*下に動く*/
border-bottom: none;/*線を消す*/
}

div.flex{
	padding: 0 0;
	display: flex;
	flex-direction: row; 
	justify-content: space-between;
}

div.flex > *{
	width: 560px ;
	height: 800px ;
	background: #fff;
	font-size: 20px; 
	overflow: auto;
}

div.flex,
div.flex > *{
	border: 0px solid #fff;
}

div.flex > div.black{
	text-align: left;
	width: 1800px ;
	border: 1px solid #000;
}

div.hidden{
	height: 0;
	width: 0;
	border: 1px solid #000;
	overflow: hidden
}

div.hidden:hover{
	height: 80%;
	width: 100%;
	text-align: left;
	border: 1px solid #000;
}

span:hover + div.hidden{
	height: 80%;
	width: 100%;
	text-align: left;
	border: 1px solid #000;
}

tr.warning{
	background-color: #FFDDFF;
}

			form{
				text-align: left;
			}

			input[type="text"],
			input[type="password"] {
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

</style>
</head>
<body>
<div class='all'>
<?php

if($login==0){
	echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
	echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
}else{
	$token = $_GET[id];
	if($gname=="deleted"){
		echo "このグループは削除されました。<br>";
		echo "<a href= 'github_mission_6_mypage.php?id=";
		echo $token;
		echo "' class='button'>マイページに戻る</a>";
	}else{
		echo "<h1>".$gname."</h1>";
		echo "<div align='left'><a href = 'github_mission_6_edit_group.php?id=";
		echo $token;
		echo "&edit=";
		echo $gID;
		echo "' class='button'>グループ名の変更</a></div>";

		echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
		echo $token;
		echo "' class='black'>ログアウト</a></div>";

		echo "<hr>";
		echo "<div class='flex'>";
		echo "<div align='left'><h4>メンバー</h4>";
		if(!empty($result0)){
			foreach($result0 as $val0){
				echo "\t".$val0[2]."<br>";
			}
			echo "<a href= 'github_mission_6_invite_group.php?gname=";
			echo $gID;
			echo "&id=";
			echo $token;
			echo "' class='button'>";
			echo "メンバーの追加</a>";
		}
		if(!empty($giv)){
			echo "<h4>招待中</h4>";
			echo "<table border='0'>";
			foreach($giv as $vaal){
				echo "<tr>";
				echo "<td>";
				echo $vaal[3];
				echo "</td><td>";
				echo "　<a href = 'github_mission_6_delete_invite_group.php?id=";
				echo $token;
				echo "&gname=";
				echo $gID;
				echo "&name=";
				echo $vaal[3];
				echo "' class='button'>取消</a></td></tr>";
			}
			echo "</table>";
		}
		
		echo "</div>";
		
		echo "<div>";
		echo "<span class='button'>掲示板に書き込み</span>";
		echo "<div class='hidden'>";
			echo "<form action='github_mission_6_group.php' method='get'>";
			echo "<input type = 'hidden' name='id' value=";
			echo $token;
			echo ">";
			echo "<input type = 'hidden' name='name' value='";
			echo $gID;
			echo "'>";
			
			if(is_array($result0)){
				echo "To:<br>";
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
			echo "名前　　　：<input type = 'text' name = 'user' value='";
			echo $prpfl[0][1];
			echo "'><br>";
			echo "コメント　：<input type = 'text' name = 'comment' ><br>";
			echo "<input type = 'submit' value = '送信' name = 'post' class='button'>";
			echo "</form>";
		echo "</div>";
		
		echo "</div>";
		
		echo "<div align='center' class='black'>";
		if(!empty($glog)){
			echo "<table border='0'>";
			foreach($glog as $gval){
				$toall = explode(',',$gval[5]);
				$TR=0;
				if(!empty($toall)){
					foreach($toall as $tyval){
						if($tyval==$myname){
							$TR++;
						}
					}
				}
				if($gval[5]=='all members'){
					echo "<tr class='warning'>";
				}elseif($TR!=0){
					echo "<tr class='warning'>";
				}else{
					echo "<tr>";
				}
				echo "<td>";
				echo $gval[1].":";
				echo "</td>";
				echo "<td>";
				
				if($gval[5]!=""){
					echo "<B>To:".$gval[5]."</B>";
				}
				echo "</td>";
				echo "<td>";
				echo $gval[2];
				echo "</td><td>";
				echo $gval[3];
				echo "</td>";
				if($gval[1]==$myname){
					echo "<td><a href ='github_mission_6_group.php?del=";
					echo $gval[0];
					echo "&name=";
					echo $gID;
					echo "&id=";
					echo $token;
					echo "'>削除</a>";
					echo "</td>";
				}
				echo "</div>";
				echo "</tr>";
			}
			echo "</table>";
		}
		echo "</div>";
		echo "</div>";
		echo "<hr>";
		echo"<div align='left'>";
		if(!empty($result1)){
			echo "<h4>参加中のプロジェクト</h4>";
			foreach($result1 as $key=>$value){
				if($value[0]!="deleted"){
					echo "<a href ='github_mission_6_project.php?gname=";
					echo $gID;
					echo "&";
					echo "pname=";
					echo $value[8];
					echo "&id=";
					echo $token;
					echo "' class='pink'>";
					echo $value[0];
					echo "</a>";
					echo " 担当者: ";
					foreach($resul[$key] as $vallu){
						echo $vallu." /";
					}
					echo "<a href = 'github_mission_6_delete_project.php?gname=";
					echo $gID;
					echo "&id=";
					echo $token;
					echo "&pname=";
					echo $value[8];
					echo "' class='button'>削除</a> /";
					echo "<a href = 'github_mission_6_escape_project.php?pname=";
					echo $value[8];
					echo "&id=";
					echo $token;
					echo "&gname=";
					echo $gID;
					echo "' class='button'>脱退</a>";
					
					echo "<br>";
					if(!empty($missionname)){
						$RR=0;
						foreach($missionname[$key] as $ry){
							
							foreach($ry as $valm){
								if($RR<3){
									if(intval($valm[0])>intval($today)){
										echo "<span >".$valm[3].":".$valm[0]."　　"."</span>";
										$RR++;
									}elseif(intval($valm[0])==intval($today)){
										echo "<span style='color:red'>".$valm[3].":".$valm[0]."　　"."</span>";
										$RR++;
									}
								}
							}
						}
						if(intval($valm[0])>intval($today)){
							echo "<span >".$valm[3].":".$valm[0]."　　"."</span>";
						}elseif(intval($valm[0])==intval($today)){
							echo "<span style='color:red'>".$valm[3].":".$valm[0]."　　"."</span>";
						}
						echo "<br>";
					}
				}
			}
		}else{
			echo "プロジェクトが存在しません。"."<br>";
		}
		echo "</div>";
			if(!empty($resulk)){
				echo "<hr>";
				echo "<div align='left'><h4>参加していないプロジェクト</h4>";
				foreach($resulk as $key=>$value2){
					if($value2[0]!="deleted"){
						echo "<a href ='github_mission_6_project.php?gname=";
						echo $gID;
						echo "&";
						echo "pname=";
						echo $value2[8];
						echo "&id=";
						echo $token;
						echo "' class='pink'>";
						echo $value2[0];
						echo "</a>";
						echo " 担当者: ";
						foreach($resuly[$key] as $vallu){
							echo $vallu." /";
						}
						echo "<a href ='github_mission_6_come_project.php?gname=";
						echo $gID;
						echo "&pname=";
						echo $value2[8];
						echo "&id=";
						echo $token;
						echo "' class='button'>参加</a>";
						echo "<br>";
					}
				}
				echo "</div>";
			}

		echo "<hr>";
		echo "<div align='left'><a href= 'github_mission_6_create_project.php?gname=";
		echo $gID;
		echo "&id=";
		echo $token;
		echo "' class='button'>プロジェクトの作成</a><br>";

		echo "<a href= 'github_mission_6_delete_group.php?gname=";
		echo $gID;
		echo "&id=";
		echo $token;
		echo "' class='button'>グループの削除</a><br>";
		
		echo "<a href= 'github_mission_6_escape_group.php?gname=";
		echo $gID;
		echo "&id=";
		echo $token;
		echo "' class='button'>グループから脱退</a><br>";

		echo "<a href= 'github_mission_6_mypage.php?id=";
		echo $token;
		echo "' class='button'>マイページに戻る</a></div>";
	}
}

?>
</div>
</body>
</html>


