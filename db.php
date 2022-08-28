<?php

   $hostname = 'localhost';

	$user = 'yomies';

	$password = 'Zxc123vbn!~';

    $database = "yomies_yomies";

    $prefix = "";

    $conn=mysqli_connect($hostname,$user,$password,$database);

 if ($conn) {
        echo "DB Connected";
    }

?>

