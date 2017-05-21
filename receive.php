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
	/*foreach($HPOinD as $obj)
	{
		echo $ds[$co]."<br>";
		$co++;
		foreach ( $obj as $hp )
			echo $hp .", ";
		echo "<br>";
	}*/
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
	/*for ( $i=0 ; $i<count($ds) ; $i++ )
	{
		for ( $j=0 ; $j<count($ds) ; $j++ )
		{
			echo $M[$i][$j]."&nbsp;";
		}
		echo "<br>";
	}*/
	$_SESSION['matrix'] = $M;
?>

<html>
	<head>

	<style>
	ul.topnav {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

ul.topnav li {float: left;}

ul.topnav li a {
		font-size: 30px;
    display: block;
    color: white;
    text-align: center;
    padding: 34px 36px;
    text-decoration: none;
}

ul.topnav li a:hover:not(.active) {background-color: #111;}

ul.topnav li a.active {background-color: #0D4FFF;}

ul.topnav li.right {float: right;}

@media screen and (max-width: 600px){
    ul.topnav li.right,
    ul.topnav li {float: none;}
}
	</style>
		<meta charset="utf-8">
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="css/pablerashow.css" , rel="stylesheet">
		<link href="css/stylish-portfolio.css" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
	</head>
	<body>

		<ul class="topnav">
  <li><a href="index.html">Home</a></li>
  <li><a class="active" href="#news">Tools</a></li>
  <li class="right"><a href="about.html">About</a></li>
</ul>
		<div>
			<h1 style="margin-top:50px;"><b>SIMILARITY MATRIX</b></h1>
			<br>
			<h3 style="text-align:center;
			margin-top:-10px;
			margin-bottom:30px;">base on hpo disease</h3>
		</div>

		<div id ="numbertable">
			<table class="container">
			<thead>
				<tr style="color:#FB667A;">
				<?php
					echo "<th style ='background-color:#2C3446;'></th>";
					foreach ($ds as $dis)
					{
						echo "<th style ='background-color:#2C3446;'>".$dis."</th>";
					}
				?>
				</tr>
			</thead>
			<tbody>
				<?php
					for( $i=0 ; $i<count($ds) ; $i++ )
					{
						echo "<tr>";
						echo "<th style ='color:#FB667A; background-color:#2C3446;'>".$ds[$i]."</th>";
						foreach ( $M[$i] as $number )
						{
							echo "<td style ='width: 70px;'>".number_format($number, 4, '.', '')."</td>";
						}
					}
				?>
			</tbody>
			</table>
		</div>
		<br>
		<br>
		<div id ="colortable">
			<table class="container" style ="border-width: 0px;">
			<thead>
				<tr style="color:#FB667A;  background-color:#2C3446;">
				<?php
					echo "<th style ='background-color:#2C3446;'></th>";
					foreach ($ds as $dis)
					{
						echo "<th style ='background-color:#2C3446;'>".$dis."</th>";
					}
				?>
				</tr>
			</thead>
			<tbody>
				<?php
					for( $i=0 ; $i<count($ds) ; $i++ )
					{
						echo "<tr>";
						echo "<th style ='color:#FB667A; background-color:#2C3446;'>".$ds[$i]."</td>";
						foreach ( $M[$i] as $number )
						{
							$color = (int)255 * $number;
							$red = dechex(255-$color);
							if (strlen($red) < 2 )	$red = '0'.$red;
							$green = dechex($color);
							if (strlen($green) < 2 )	$green = '0'.$red;
							$col = $red.$green."00";

							echo "<td style ='background-color:".$col."; width: 70px;'></td>";
						}
					}
				?>
			</tbody>
			</table>
		</div>
		<div style="text-align:center;
		margin: auto;
		width: 10%;
		padding-top: 100px;
		padding-bottom: 100px
		">
			<div class="buttons" style="position:center;">
				<a href="fileCreator.php" target="download_frame" style="width:300px;">Download a matrix here </a>
				<iframe id="download_frame" style="display:none;"></iframe>
  			<p class="top" style="width:280px">click to begin</p>
  			<p class="bottom" style="width:280px">Matrix.txt</p>
			</div>
		</div>
		<br>
	</body>

</html>
