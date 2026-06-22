<?php
$conn=mysqli_connect("localhost" , "root" , "" , "Jewellery_db");
if(!$conn){

	echo("connection failed".mysqli_connect_error());
}

else{
//	echo("connected successfuly");
}
mysqli_set_charset($conn , "utf8");
?>
