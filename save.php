
<?php
	$number = $_GET['number'];
	$posX = $_GET['posX'];
	$posZ = $_GET['posZ'];
	$direction = $_GET['direction'];
	echo "$number";

	$db = new PDO("sqlite:furniture.sqlite");
	for( $i=0; $i<$number; $i++ ){
				$updateX = $db->query("update furniture set posX = ".$posX[$i]." WHERE furniture_id = ".$i );
				$updateZ = $db->query("update furniture set posZ = ".$posZ[$i]." WHERE furniture_id = ".$i );
				$updateDirection = $db->query("update furniture set direction = ".$direction[$i]." WHERE furniture_id = ".$i );
			}
?>
