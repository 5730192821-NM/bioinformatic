<?php
	$fp = fopen($_FILES['file']['tmp_name'], 'rb');
    while ( ($line = fgets($fp)) !== false) {
      echo "$line<br>";
    }
	$str = "Hello";
?>

<html>
	

</html>