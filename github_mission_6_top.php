<?php
$dsn = 'mysql:dbname=データベース名;host=localhost;charset=utf8';
$user = 'ユーザー名';
$password = 'パスワード';
date_default_timezone_set('Asia/Tokyo');
?>
<!DOCTYPE html>
<html>

<head>
<meta http-equiv = "Content-Type" content ="text/html; charset =UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>トップページ</title>
<style>
body{
background-color: #AAFFFF;
}
div{
font-size: 50px;
text-align: center;
margin: 300px auto;
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
<div>
<h1>ようこそ、GWSへ！</h1>
<?php
echo "<a href = 'github_mission_6_loginform.php'>ログイン</a>";
echo "　　　　　";
echo "<a href = 'github_mission_6.php'>新規登録</a><br>";
?>
</div>
</body>
</html>
