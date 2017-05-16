<!DOCTYPE html>
<?php
	$myfile = fopen("graph.txt", "r") or die("Unable to open file!");
	$listHPO = [];
	$find = array();
	$graph = array();
    $a =0;
    for($val = 0 ;$val<=10 ; $val++){
      $a += $val;
    }
	$no = 0;
	while(!feof($myfile)) {
		$line = fgets($myfile);
		$pre = substr($line,0,2);
		if ($pre == "id")
		{
			$id = trim(substr($line,5));
			//echo $id . "<br>";
			array_push($listHPO,$id);
			$temp = [];
			$find[$id] = $temp;
		}
		else if ($pre == "pa")
		{
			$name = trim(substr($line,5));
			array_push($find[$id],$name);
		}
	}
	/*foreach ( $find as $str => $ord )
	{
		echo $str . " -> ";
		foreach ( $ord as $sma)
			echo $sma.", ";
		echo "<br>";
	}*/
?>
<html>
	<body>

		<form action="receive.php" method="post" enctype="multipart/form-data">
			Select image to upload:
			<input type="file" name="file" id="file">
			<input type="submit" value="Submit" name="submit">
		</form>

	</body>
</html>
kuykuykuy sum = 
<?php
    echo $a;
?>