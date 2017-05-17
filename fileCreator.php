<?php
	session_start();
	$M= $_SESSION['matrix'];
	header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="Matrix.txt"');
	
	foreach ( $M as $rows )
	{
		echo "[";
		foreach ( $rows as $a )
		{
			echo number_format($a, 4, '.', '').",";
		}
		echo "]".PHP_EOL;
	}
	//session_destroy(); 
?>