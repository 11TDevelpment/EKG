<?php
$ar= array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['c1'])) {
		$ar['c1'] = $_POST['c1'];
		$c1=fopen("../data/c1.txt", "w") or die();
		fputs($c1, $ar['c1']);
		echo $ar['c1'];
		fclose($c1);
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['c2'])) {
		$ar['c2'] = $_POST['c2'];
		$c2=fopen("../data/c2.txt", "w") or die();
		fputs($c2, $ar['c2']);
		echo $ar['c2'];
		fclose($c2);
	}
}
?>