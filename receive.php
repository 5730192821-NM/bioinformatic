<?php
	session_start();
	$myfile = fopen("graph.txt", "r") or die("Unable to open file!");
	$listHPO = [];
	$find = array();
	$graph = array();
	
	function findPath($name , $bank)
	{
		if ($name == "HP:0000001")
			return ["HP:0000001"];
		$path =array();
		array_push($path,$name);
		foreach ($bank[$name] as $par)
			$path = array_merge($path,findPath($par,$bank));
		return $path;
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
	$fp = fopen($_FILES['file']['tmp_name'], 'rb');
	
	// Store disease in $ds
	$di = -1;
	$ds = array();
	$HPOinD = array();
    while ( ($line = fgets($fp)) !== false)	
	{
	  $line = trim($line);	
      if ( substr($line,0,8) == "Disease:" )
	  {
		  array_push($ds,substr($line,9));
		  $temp = array();
		  array_push($HPOinD,$temp);
		  $di++;
	  }
	  else if ( substr($line,0,2) == "HP" )
	  {
		  $fi = findPath($line,$find);
		  $HPOinD[$di] = array_unique(array_merge($HPOinD[$di],$fi));
		  //print_r($temp);
	  }
    }
	$co = 0;
	//print_r($HPOinD);
	foreach($HPOinD as $obj)
	{
		echo $ds[$co]."<br>";
		$co++;
		foreach ( $obj as $hp )
			echo $hp .", ";
		echo "<br>";
	}
	// $M is similarity matrix
	$M = array();
	foreach ( $HPOinD as $dis )
	{
		$temp = array();
		foreach ( $HPOinD as $cmpDis )
		{
			$na = count($dis);
			$nb = count($cmpDis);
			$naub = count(array_unique(array_merge($dis,$cmpDis)));
			array_push($temp,($na+$nb-$naub)/$naub);
		}
		array_push($M,$temp);
	}
	for ( $i=0 ; $i<count($ds) ; $i++ )
	{
		for ( $j=0 ; $j<count($ds) ; $j++ )
		{
			echo $M[$i][$j]."&nbsp;";
		}
		echo "<br>";
	}
	$_SESSION['matrix'] = $M;
?>

<html>
	<body>
		<a href="fileCreator.php" target="download_frame">Download a matrix here </a>
		<iframe id="download_frame" style="display:none;"></iframe>
	</body>

</html>