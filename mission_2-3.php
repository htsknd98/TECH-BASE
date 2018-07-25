<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
</head>
<body>
<form action="mission_2-3.php" method="post"  >
名前: <input type="text" name="name" placeholder = "名前">
コメント: <input type="text" name="comment" placeholder = "コメント">
<input type="submit" value="送信" name = "submit">
<br>
削除対象番号: <input type = "text" name = "number" placeholder = "削除したい番号">
<input type = "submit" value = "削除" name = "delete">
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
}else{
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	date_default_timezone_set('Asia/Tokyo');
	$timestamp = time();
	$now = date("Y/m/d H:i:s",$timestamp);

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
		$exp = "";
	}else{
		$content = "$num"."<>".$name."<>".$comment."<>".$now."\r\n";
		$exp = "$num $name $comment $now <br>";
	}

	$fp = fopen($filename,'w');
	fwrite($fp,$fcontent.$content);
	fclose($fp);

	if(file_exists($filename)==FALSE){
		echo "file cannot found.";
	}elseif(filesize($filename)==0){
		echo $exp;
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

?>
</body>
</html>
