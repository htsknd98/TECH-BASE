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
	$date = $_GET[date];
	$ID = $_GET[id];
	$come = $_GET[come];
	$del = $_GET[del];
	$esc = $_GET[esc];
	
	
	$sql = "SELECT * FROM member3 where token = '$ID'";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
		$idname = $stmt->fetchAll();
	}
	$name = $idname[0][1];
	$login = $idname[0][6];
	
	$sql="SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date'";
	$stmt = $pdo->query($sql);
	if($stmt!=""){
	$result = $stmt->fetchAll();
	}
	
	if(!empty($result)){
		$cnt = count($result);
		for($i=0;$i<$cnt;$i++){
			$rr=0;
			$name=$result[$i][3];
			for($j=($i+1);$j<$cnt;$j++){
				if($result[$j][3]==$name){
					$rr++;
				}
			}
			if($rr==0){
				$result1[]=$result[$i][7];
				$result11[] = $result[$i][3];
			}
		}
	}
	
	if(!empty($result1)){
		foreach($result1 as $key => $val){
			$nn=$val;
			$sql = "SELECT * FROM mission2 where gid = $gID and pid = $pID and date = '$date' and mid = $nn";
			$stmt = $pdo->query($sql);
			if($stmt!=""){
				$resu2 = $stmt->fetchAll();
				foreach($resu2 as $val2){
					$result2[$key][] = $val2[4];
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
<meta http-equiv="Content-Style-Type" content="text/css">
<title>
<?php
$str = $_GET[date];
$date = date('Y年m月d日',strtotime($str));
echo $date;
?>
</title>
<style>
body{
background-color: #AAFFFF;
}

div.all{
background-color: #FFFFFF;
text-align: center;
font-size: 20px;
}

table{
text-align: center;

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
</style>
</head>
<body>
<div class='all'>
<?php
$str = $_GET[date];
$date = date('Y年m月d日',strtotime($str));
if($login == 0 ){
	echo "更新ボタンを押して、ページが表示されない場合は、もう一度ログインし直してください。<br>";
	echo "<a href = 'github_mission_6_loginform.php'>ログインページへ</a><br>";
}else{
	echo "<h1>";
	echo $date;
	echo "</h1>";

	echo "<div align = 'right'><a href = 'github_mission_6_logout.php?id=";
	echo $ID;
	echo "' class='black'>ログアウト</a></div><br>";

	if(!empty($result1) and !empty($result2)){
		echo "<div align='center'>";
		echo "<table border = '1' >";
		foreach($result1 as $key=>$value){
			if($result11[$key]!="deleted"){
				echo "<tr>";
				echo "<td>";
				echo $result11[$key]." ";
				echo "</td><td>";
				echo "<B>担当: </B>";
				$nnnn=0;
				foreach($result2[$key] as $vall){
					echo $vall."/ ";
					if($vall==$idname[0][1]){
						$nnnn++;
					}
				}
				echo "</td>";
				if($nnnn!=0){
					echo "<td>";
					echo "　";
					if($result11[$key]!="開始" and $result11[$key]!="終了"){
						echo "<a href = 'github_mission_6_edit_mission.php?mname=";
						echo $value;
						echo "&pname=";
						echo $pID;
						echo "&gname=";
						echo $gID;
						echo "&id=";
						echo $_GET[id];
						echo "&date=";
						echo $_GET[date];
						echo "'>予定:";
						echo $result11[$key];
						echo " の編集</a></td>";
					}else{
						echo "編集不可</td>";
					}
					echo "<td>";
					echo "　";
					if($result11[$key]!='開始' and $result11[$key]!='終了'){
						echo "<a href = 'github_mission_6_delete_mission.php?mname=";
						echo $value;
						echo "&pname=";
						echo $pID;
						echo "&gname=";
						echo $gID;
						echo "&id=";
						echo $_GET[id];
						echo "&date=";
						echo $_GET[date];
						echo "'>削除</a></td>";
					}else{
						echo "削除不可</td>";
					}
					echo "<td>";
					echo "　";
					if($result11[$key]!='開始' and $result11[$key]!='終了'){
						echo "<a href = 'github_mission_6_escape_mission.php?mname=";
						echo $value;
						echo "&pname=";
						echo $pID;
						echo "&gname=";
						echo $gID;
						echo "&id=";
						echo $_GET[id];
						echo "&date=";
						echo $_GET[date];
						echo "'>担当を外れる</a></td>";
					}else{
						echo "脱退不可</td>";
					}
					
				}else{
					echo "<td>";
					echo "　";
					echo "<a href = 'github_mission_6_come_mission.php?mname=";
					echo $value;
					echo "&pname=";
					echo $pID;
					echo "&gname=";
					echo $gID;
					echo "&id=";
					echo $_GET[id];
					echo "&date=";
					echo $_GET[date];
					echo "'>担当者に自分を追加</a></td>";
				}
				echo "</tr>";
			}
			
		}
		echo "</table>";
		echo "</div>";
	}else{
		echo "<div align='center'>";
		echo "予定はありません。<br>";
		echo "</div>";
	}
	echo "<br>";
	echo "<div align='left'>";
	echo "<a href = 'github_mission_6_create_mission.php?pname=";
	echo $pID;
	echo "&gname=";
	echo $gID;
	echo "&id=";
	echo $_GET[id];
	echo "&date=";
	echo $_GET[date];
	echo "'> 予定の作成</a><br>";

	echo "<a href = 'github_mission_6_project.php?pname=";
	echo $pID;
	echo "&gname=";
	echo $gID;
	echo "&id=";
	echo $_GET[id];
	echo "'> プロジェクトページへ戻る</a><br>";

	echo "<a href = 'github_mission_6_group.php?name=";
	echo $gID;
	echo "&id=";
	echo $_GET[id];
	echo "'> グループページへ戻る</a><br>";
	
	echo "<a href = 'github_mission_6_mypage.php?id=";
	echo $_GET[id];
	echo "'> マイページへ戻る</a><br>";
	echo "</div>";
}
?>
</div>
</body>
</html>