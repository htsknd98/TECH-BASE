<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
</head>
<body>
<?php
if(isset($_POST['edit'])){
 $filename = 'mission_2-1_kondo.txt';
 $editnum = $_POST['numb'];
 if(file_exists($filename)==FALSE){
  echo "file cannot found.";
 }elseif(filesize($filename)==0){
  echo "";
 }else{
  $array=file($filename);
  foreach($array as $value){
  $string = explode("<>",$value);
   if($string[0]==$editnum){
    $edname = $string[1];
    $edcomment = $string[2];
   }
  }
 }
}
?>
<form action="mission_2-4.php" method="post"  >
<input type = "hidden" name="ednum" value =<?php echo $editnum; ?>>
名前: <input type="text" name="name" placeholder = "名前" value =<?php echo $edname; ?> >
コメント: <input type="text" name="comment" placeholder = "コメント" value = <?php echo $edcomment; ?> >
<input type="submit" value="送信" name = "submit">
<br>
削除対象番号: <input type = "text" name = "number" placeholder = "削除したい番号">
<input type = "submit" value = "削除" name = "delete">
<br>
編集対象番号: <input type = "text" name = "numb" placeholder = "編集したい番号">
<input type = "submit" value = "編集" name = "edit" >
</form>

<?php
$filename = 'mission_2-1_kondo.txt';

if(isset($_POST['delete'])){
 $delnum = $_POST['number'];
 if(intval($delnum)>0){

  if(file_exists($filename)==FALSE){
   echo "file cannot found.";
  }elseif(filesize($filename)==0){
   echo "";
  }else{
   $array=file($filename);

   foreach($array as $value){
    $string = explode("<>",$value);

    if($string[0]==$delnum){
     $vcontent = "";
    }elseif(intval($string[0])<intval($delnum)){
     echo $string[0]." ";
     echo $string[1]." ";
     echo $string[2]." ";
     echo $string[3];
     echo "<br>";
     $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3];
    }else{
     $n = intval($string[0]) - 1;
     $string[0] = "$n";
     echo $string[0]." ";
     echo $string[1]." ";
     echo $string[2]." ";
     echo $string[3];
     echo "<br>";
     $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3];
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
 $ednum = $_POST['ednum'];
 $name = $_POST['name'];
 $comment = $_POST['comment'];
 date_default_timezone_set('Asia/Tokyo');
 $timestamp = time();
 $now = date("Y/m/d H:i:s",$timestamp);
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

     if($string[0]==$ednum){
      $vcontent = $ednum."<>".$name."<>".$comment."<>".$now."\r\n";
      echo $ednum." ".$name." ".$comment." ".$now."<br>";
     }else{
      echo $string[0]." ";
      echo $string[1]." ";
      echo $string[2]." ";
      echo $string[3];
      echo "<br>";
      $vcontent = $string[0]."<>".$string[1]."<>".$string[2]."<>".$string[3];
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
   $content = "$num"."<>".$name."<>".$comment."<>".$now."\r\n";
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
