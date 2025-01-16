<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "wyqk6x041tfxg39e.chr7pe7iynqr.eu-west-1.rds.amazonaws.com"; // MySQL Hostname
$username = "vsy2jkwcvsmlks80"; // MySQL Username
$password = "ha2ewmeethfq7wz1"; // MySQL Password
$dbname = "lyalxrwicbmvdtkj"; // ชื่อฐานข้อมูล
$port = 3306; // MySQL Port (ค่าเริ่มต้นคือ 3306)

// ตั้งค่า timezone
date_default_timezone_set("Asia/Bangkok");

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่ามีค่าที่ส่งผ่าน URL หรือไม่
if (isset($_GET['TDS'])) {
    // รับค่าจาก URL
    $tds = $_GET['TDS'];
    $timestamp = date("Y-m-d H:i:s"); // เวลาปัจจุบัน

    // ใช้ prepared statement
    $stmt = $conn->prepare("INSERT INTO save_drink (TDS, Date) VALUES (?, ?)");
    $stmt->bind_param("is", $tds, $timestamp);

    // ดำเนินการเพิ่มข้อมูล
    if ($stmt->execute()) {
        echo "เพิ่มข้อมูลสำเร็จ! TDS: $tds, เวลา: $timestamp";
    } else {
        echo "ข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "กรุณาระบุข้อมูล TDS ผ่าน URL เช่น ?TDS=123";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
