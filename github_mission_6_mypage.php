<?php
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
date_default_timezone_set('Asia/Tokyo');
try{ //PDO設定
	$pdo = new PDO($dsn,$user,$password);
	$today = date("Y"."m"."d",time());
	$err = 0;
 	if($pdo == null){
  		echo "cannot connected.<br>"; //connection確認
 	}
	$ID = $_GET[id];
	if($ID == ""){
		
	}else{
		
		$sql="SELECT * FROM member3 where token = '$ID'";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
		$nresult = $stmt->fetchAll();
		}
		
		$tID = $nresult[0][0];
		$login = $nresult[0][6];
		
 		$sql = "SELECT * FROM group3 where id = '$tID' order by name";
		$stmt = $pdo->query($sql);
		if($stmt!=""){
		$result = $stmt->fetchAll();
		}
		
		$sql="SELECT * FROM groupinvite3 where id = '$tID'";
		$stmt=$pdo->query($sql);
		if($stmt!=""){
			$invited=$stmt->fetchAll();
		}
	
		if(!empty($result) and !empty($nresult)){
			$gmax = count($result);
			$myname = $nresult[0][1];
			for($i=0;$i<$gmax;$i++){
				$gID = $result[$i][0];
				$sql = "SELECT * FROM project6 where gid = $gID and mem = '$myname' order by tdate";
				$stmt= $pdo->query($sql);
				if($stmt!=""){
					$presult[$i] = $stmt->fetchAll();
				}
				if(!empty($presult[$i])){
					foreach($presult[$i] as $key=>$value){
						$pID = $value[8];
						$sql = "SELECT * FROM mission2 where gid = $gID and pid = $pID and mem = '$myname' order by date";
						$stmt = $pdo->query($sql);
						if($stmt!=""){
							$mresult[$i][$key]=$stmt->fetchAll();
						}
						if(!empty($mresult)){
							$mmax[$i] = count($mresult);
						}
					}
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
<title>マイページ</title>
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

a.green{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #006400;/*ボタン色*/
color: #FFF;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.green:hover{
display: inline-block;
padding: 0.5em 1em;
text-decoration: none;
background: #006400;/*ボタン色*/
color: #FFFF33;
border-bottom: solid 4px #627295;
border-radius: 3px;
}
a.green:active {/*ボタンを押したとき*/
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
</style>
</head>
<body>
<?php
echo "<div class='all'>";
if($login==0){
	echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
	echo "<a href = 'github_mission_6_loginform.php' >ログインページへ</a><br>";
}else{
	echo "<h1>"."WELCOME!<br>".$tID."さん！</h1>";
	echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
	echo $ID;
	echo "' class='black'>ログアウト</a></div><br>";
	if(!empty($result)){
		echo "<table border = '1'>";
		for($i=0;$i<$gmax;$i++){
			echo "<tr>";
			echo "<td>";
			echo "<a href ='github_mission_6_group.php?name=";
			echo $result[$i][0];
			echo "&id=";
			echo $ID;
			echo "' class='green'>";
			echo $result[$i][1];
			echo "</a></td>";
			echo "<td>";
			if(!empty($presult[$i])){
				$MM = 0;
				foreach($presult[$i] as $key=>$value){
					if(intval($value[2])>=intval($today)){
						$MM++;
						echo "<table border = '1'>";
						echo "<tr>";
						echo "<td>";
						echo "<a href ='github_mission_6_project.php?pname=";
						echo $value[8];
						echo "&id=";
						echo $ID;
						echo "&gname=";
						echo $result[$i][0];
						echo "' class='pink'>";
						echo $value[0];
						echo "</a></td>";
						echo "<td>";
						if(!empty($mresult[$i][$key])){
							echo "<table border = '0'>";
							$TRR = 0;
							foreach($mresult[$i][$key] as $val){
								if($TRR<3){
									if(intval($val[0])==intval($today)){
									echo "<tr>";
									echo "<td>";
									echo "<span style='color: red'>";
									echo $val[3]."</span></td><td><span style='color: red'>:".$val[0];
									echo "</span></td>";
									echo "</td>";
									echo "</tr>";
									$TRR++;
									}elseif(intval($val[0])>intval($today)){
									echo "<tr>";
									echo "<td>";
									echo "<span>".$val[3]."</span></td><td><span>:".$val[0]."</span></td>";
									$TRR++;
									echo "</td>";
									echo "</tr>";
									}
									
								}
							}
							if(intval($value[2])<intval($today)){
								echo "プロジェクトは終了しました。";
							}
							echo "</table>";
						}else{
							echo "予定はありません。";
						}
					
					echo "</td>";
					echo "</tr>";
					echo "</table>";
					}
				}
				if($MM==0){
					echo "予定はありません。";
					echo "</td>";
				}
			}else{
				echo "プロジェクトに入っていません。";
				echo "</td>";
			}
			echo "<td>";
			echo "<a href = 'github_mission_6_delete_group.php?id=";
			echo $ID;
			echo "&gname=";
			echo $result[$i][0];
			echo "' class = 'button'>グループの削除</a>";
			echo "</td>";
			echo "<td>";
			echo "<a href = 'github_mission_6_escape_group.php?id=";
			echo $ID;
			echo "&gname=";
			echo $result[$i][0];
			echo "' class = 'button'>脱退</a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}else{
		echo "グループに所属していません。"."<br>";
	}
	echo "<br>";
	if(!empty($invited)){
		echo "<table border = '1'>";
		foreach($invited as $val){
			echo "<tr>";
			echo "<td>";
			echo "<B>".$val[2]."</B></td>";
			echo "<td>".$val[1]."から招待が来ています。</td>";
			echo "<td>　<a href='github_mission_6_come_group.php?id=";
			echo $ID;
			echo "&gname=";
			echo $val[4];
			echo "'class = 'button'>参加</a></td>";
			echo "<td>　<a href='github_mission_6_delete_invite_group.php?id=";
			echo $ID;
			echo "&gname=";
			echo $val[4];
			echo "'class = 'button'>入らない</a></td></tr>";
		}
		echo "</table><br>";
	}
	
	echo "<div align='left'><a href = 'github_mission_6_create_group.php?id=";
	echo $ID;
	echo "' class = 'button'>";
	echo  "グループの作成";
	echo "</a><br>";

	echo "<a href = 'github_mission_6_edit_profile.php?id=";
	echo $ID;
	echo "' class = 'button'>";
	echo  "プロフィールの変更";
	echo "</a><br></div>";
}
echo "</div>";
?>

</body>
</html>

