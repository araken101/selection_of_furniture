
<?php
$num = isset($_POST['hidden_input']) ? $_POST['hidden_input'] : null;

//print "num: " . $num;
$data=split(",",$num);
date_default_timezone_set("Asia/Tokyo");
$db=new PDO("sqlite:furniture.sqlite");
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
$db->query("insert into furniture values(null,'$data[0]','$data[1]','$data[3]','$data[2]',0,0,0)");
header("location: room1.php");
exit();
?>
