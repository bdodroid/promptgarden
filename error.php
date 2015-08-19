<?php
		if(isset($_POST['options']))
		{
			$page=$_POST['options'];
			echo "displaying errors for $page </br></br></br>";
			ini_set("display_errors","On");
			error_reporting(E_ALL);
			include_once($page);
		}
		?>
<html>
	<form action=<?=$_SERVER['PHP_SELF']?> method="POST">
	<?php
		$aryTables = array('database.php','testAndroidConnect.php','testAndroidApi.php','functions.php',
				'promptGardenAPI.php'
		);
		$html = '<select name="options">';
		foreach ($aryTables as $k => $v) {
			$html .="<option name = $v value=$v>$v</option>";
		}
		$html.="</select>";
		echo $html;
	?>
	<input type='submit' name='submit' value='submit' />
</html>