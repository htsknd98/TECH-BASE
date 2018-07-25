<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
</head>
<body>
<?php
$filename = 'mission_2-5_kondo.txt';
$arr = file($filename);
if(isset($_POST['edit'])){
 $editnum = $_POST['numb'];
 $nn = intval($editnum) - 1;
 if($nn > -1){
  $val = $arr[$nn];
  $ep = explode("<>",$val);
  if($_POST['epass']==$ep[4]){
   $editpass = $_POST['epass'];
   if(file_exists($filename)==FALSE){
    echo "file cannot found.";
   }elseif(filesize($filename)==0){
    echo "";
   }else{
    foreach($arr as $value){
     $string = explode("<>",$value);
     if($string[0]==$editnum){
      $edname = $string[1];
      $edcomment = $string[2];
     }
    }
   }
  }else{
   echo "編集権限がありません";
  }
 }
}elseif(isset($_POST['delete'])){
 $nn=intval($_POST['number']) - 1;
 if($nn > -1){
  $val = $arr[$nn];
  $dp = explode("<>",$val);
  if($_POST['dpass']!=$dp[4]){
   $dedit = "cannot";
   echo "編集権限がありません";
  }else{
   $dedit = "can";
  }
 }
}
?>
<form action="mission_2-5.php" method="post"  >
<input type = "hidden" name="ednum" value =<?php echo $editnum; ?>>
<input type = "hidden" name="edpass" value = <?php echo $editpass; ?>>

名前: <input type="text" name="name" placeholder = "名前" value =<?php echo $edname; ?>>
コメント: <input type="text" name="comment" placeholder = "コメント" value = <?php echo $edcomment; ?>>
pass: <input type = "text" name="pass" placeholder = "パスワードを入力してください" value = <?php echo $editpass; ?>>
<input type="submit" value="送信" name = "submit">
<br>
削除対象番号: <input type = "text" name = "number" placeholder = "削除したい番号">
pass: <input type = "text" name = "dpass" placeholder = "Password">
<input type = "submit" value = "削除" name = "delete">

<br>
編集対象番号: <input type = "text" name = "numb" placeholder = "編集したい番号">
pass: <input type = "text" name = "epass" placeholder = "Password">
<input type = "submit" value = "編集" name = "edit">
<br>
<input type = "submit" value = "Clear" name = "clear">
</form>

<?php
$filename = 'mission_2-5_kondo.txt';

if(isset($_POST['clear'])){
$fp0 = fopen($filename,'r+');
ftruncate($fp0,0);
}elseif(isset($_POST['delete'])){
 $delnum = $_POST['number'];
 $dpass = $_POST['dpass'];
 if(intval($delnum)>0){
  if(file_exists($filename)==FALSE){
   echo "file cannot found.";
  }elseif(filesize($filename)==0){
   echo "";
  }else{
   $array=file($filename);
   foreach($array as $value){
    $string = explode("<>",$value);
    if($dedit=="can"){
     if($string[0]==$delnum){
      $vcontent = "";
     }elseif(intval($string[0])<intval($delnum)){//$delnumよりも下の数字
      echo $string[0]." ";
      echo $string[1]." ";
      echo $string[2]." ";
      echo $string[3];
      echo "<br>";
      $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3]."<>".$string[4]."<>"."\r\n";
     }else{//$delnumよりも上の数字
      $n = intval($string[0]) - 1;
      $string[0] = "$n";
      echo $string[0]." ";
      echo $string[1]." ";
      echo $string[2]." ";
      echo $string[3];
      echo "<br>";
      $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3]."<>".$string[4]."<>"."\r\n";
     }
    }else{//$dpassが一致しなかった場合
     echo $string[0]." ";
     echo $string[1]." ";
     echo $string[2]." ";
     echo $string[3];
     echo "<br>";
     $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3]."<>".$string[4]."<>"."\r\n";
    }
    $content = $content.$vcontent;
   }
   $fp = fopen($filename,'w');
   fwrite($fp,$content);
   fclose($fp);
  }
 }else{
  if(file_exists($filename)==FALSE){
   echo "file cannot found.";
  }elseif(filesize($filename)==0){
   echo "";
  }else{
   $array=file($filename);
   foreach($array as $value){
    $string = explode("<>",$value);
    echo $string[0]." ";
    echo $string[1]." ";
    echo $string[2]." ";
    echo $string[3];
    echo "<br>";
   }
  }
 }
}else{
 $edpass = $_POST['edpass'];
 $ednum = $_POST['ednum'];
 $name = $_POST['name'];
 $comment = $_POST['comment'];
 date_default_timezone_set('Asia/Tokyo');
 $timestamp = time();
 $now = date("Y/m/d H:i:s",$timestamp);
 $password = $_POST['pass'];
 if($ednum != ""){
  if(intval($ednum)>0){
   if(file_exists($filename)==FALSE){
    echo "file not found.";
   }elseif(filesize($filename)==0){
    echo "";
   }else{
    $array=file($filename);

    foreach($array as $value){
     $string = explode("<>",$value);
     if($string[4]==$edpass){
      if($string[0]==$ednum){
       $vcontent = $ednum."<>".$name."<>".$comment."<>".$now."<>".$password."<>"."\r\n";
       echo $ednum." ".$name." ".$comment." ".$now."<br>";
      }else{
       echo $string[0]." ";
       echo $string[1]." ";
       echo $string[2]." ";
       echo $string[3];
       echo "<br>";
       $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3]."<>".$string[4]."<>"."\r\n";
      }
     }else{
      echo $string[0]." ";
      echo $string[1]." ";
      echo $string[2]." ";
      echo $string[3];
      echo "<br>";
      $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3]."<>".$string[4]."<>"."\r\n";
     }
     $content = $content.$vcontent;
     $fp = fopen($filename,'w');
     fwrite($fp,$content);
     fclose($fp);
    }

   }

  }
 }else{
  if(file_exists($filename)==FALSE){
   $num = 1;
   $fcontent = "";
  }elseif(filesize($filename)==0){
   $num = 1;
   $fcontent = "";
  }else{
   $ffp = fopen($filename,'r');
   $fcontent = fread($ffp,filesize($filename));
   $num = mb_substr_count($fcontent,"\r\n") + 1;
   fclose($ffp);
  }

  if($comment == ""){
   $content = "";
   $disp = "";
  }else{
   $content = "$num"."<>".$name."<>".$comment."<>".$now."<>".$password."<>"."\r\n";
   $disp = "$num"." ".$name." ".$comment." ".$now."<br>";
  }

  $fp = fopen($filename,'w');
  fwrite($fp,$fcontent.$content);
  fclose($fp);

  if(file_exists($filename)==FALSE){
   echo "file cannot found.";
  }elseif(filesize($filename)==0){
   echo $disp;
  }else{	
   $array=file($filename);

   foreach($array as $value){
    $string= explode("<>",$value);

    if($string[0]!="\r\n"){
     echo $string[0]." ";
     echo $string[1]." ";
     echo $string[2]." ";
     echo $string[3]." ";
     echo "<br>";
    }else{
     echo "<br>";
    }

   }

  }

 }
}
?>
</body>
</html>

