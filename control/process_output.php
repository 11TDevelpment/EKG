<?php
$ar= array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['c1'])) {
		$c1=fopen("../data/c1.txt","r") or exit();
		while (!feof($c1)){echo fgetc($c1);}
		fclose($c1);
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['c2'])) {
		$c2=fopen("../data/c2.txt","r") or exit();
		while (!feof($c2)){echo fgetc($c2);}
		fclose($c2);
	}
}
?>