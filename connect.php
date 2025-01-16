<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "wyqk6x041tfxg39e.chr7pe7iynqr.eu-west-1.rds.amazonaws.com"; // MySQL Hostname
$username = "vsy2jkwcvsmlks80"; // MySQL Username
$password = "ha2ewmeethfq7wz1"; // MySQL Password
$dbname = "lyalxrwicbmvdtkj"; // ชื่อฐานข้อมูล
$port = 3306; // MySQL Port (ค่าเริ่มต้นคือ 3306)

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $dbname, $port);


?>
