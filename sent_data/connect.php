<?php
// กำหนด Content-Type เป็น JSON
header('Content-Type: application/json');

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "wyqk6x041tfxg39e.chr7pe7iynqr.eu-west-1.rds.amazonaws.com"; // MySQL Hostname
$username = "vsy2jkwcvsmlks80"; // MySQL Username
$password = "ha2ewmeethfq7wz1"; // MySQL Password
$dbname = "lyalxrwicbmvdtkj"; // ชื่อฐานข้อมูล
$port = 3306; // MySQL Port (ค่าเริ่มต้นคือ 3306)

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(["error" => "การเชื่อมต่อฐานข้อมูลล้มเหลว"]);
    exit();
}

// ตัวแปรสำหรับเก็บข้อมูล
$response = [];

// SQL สำหรับค่าเฉลี่ย TDS ของวันนี้ (TDS_TODAY)
$sql_today_avg = "SELECT AVG(TDS) AS avg_tds, DATE_FORMAT(CURDATE(), '%Y-%m-%d') AS day FROM save_drink WHERE DATE(`day`) = CURDATE()";
$result_today_avg = $conn->query($sql_today_avg);

if ($result_today_avg->num_rows > 0) {
    $row_today_avg = $result_today_avg->fetch_assoc();
    $response['TDS_TODAY'] = round($row_today_avg['avg_tds'], 2); // ปัดเศษให้เหลือ 2 ตำแหน่ง
    $response['Date_TODAY'] = $row_today_avg['day'];
} else {
    $response['TDS_TODAY'] = null;
    $response['Date_TODAY'] = null;
}

// SQL สำหรับค่าเฉลี่ย TDS ของเมื่อวาน (TDS_YESTERDAY)
$sql_yesterday_avg = "SELECT AVG(TDS) AS avg_tds, DATE_FORMAT(CURDATE() - INTERVAL 1 DAY, '%Y-%m-%d') AS day FROM save_drink WHERE DATE(`day`) = CURDATE() - INTERVAL 1 DAY";
$result_yesterday_avg = $conn->query($sql_yesterday_avg);

if ($result_yesterday_avg->num_rows > 0) {
    $row_yesterday_avg = $result_yesterday_avg->fetch_assoc();
    $response['TDS_YESTERDAY'] = round($row_yesterday_avg['avg_tds'], 2); // ปัดเศษให้เหลือ 2 ตำแหน่ง
    $response['Date_YESTERDAY'] = $row_yesterday_avg['day'];
} else {
    $response['TDS_YESTERDAY'] = null;
    $response['Date_YESTERDAY'] = null;
}

// ส่งข้อมูลในรูปแบบ JSON
echo json_encode($response);

// ปิดการเชื่อมต่อ
$conn->close();
?>
