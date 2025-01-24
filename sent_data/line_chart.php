<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// กำหนด Content-Type เป็น JSON
header('Content-Type: application/json');

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "wyqk6x041tfxg39e.chr7pe7iynqr.eu-west-1.rds.amazonaws.com";
$username = "vsy2jkwcvsmlks80";
$password = "ha2ewmeethfq7wz1";
$dbname = "lyalxrwicbmvdtkj";
$port = 3306;

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(["error" => "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error]);
    exit();
}

// คำสั่ง SQL สำหรับดึงข้อมูลภายใน 7 วันและแยกตามวัน
$sql_last_7_days = "
    SELECT 
        DATE(`day`) AS date,
        AVG(TDS) AS avg_TDS
    FROM 
        save_drink
    WHERE 
        `day` >= NOW() - INTERVAL 7 DAY
    GROUP BY 
        date
    ORDER BY 
        `day` DESC
";

// ดำเนินการคำสั่ง SQL
$result_last_7_days = $conn->query($sql_last_7_days);

// ตัวแปรสำหรับเก็บข้อมูล
$response = [];
$last_7_days_data = [];

// สร้าง array สำหรับช่วงเวลา 7 วัน
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime('-' . $i . ' days'));
}

// เก็บข้อมูลที่ดึงได้จากฐานข้อมูล
while ($row = $result_last_7_days->fetch_assoc()) {
    $date = $row['date'];
    $last_7_days_data[$date] = [
        'date' => $date,
        'TDS' => round($row['avg_TDS'], 2)
    ];
}

// เพิ่มค่า 0 สำหรับวันที่ไม่มีข้อมูล
foreach ($dates as $date) {
    if (!isset($last_7_days_data[$date])) {
        $last_7_days_data[$date] = [
            'date' => $date,
            'TDS' => 0
        ];
    }
}

// เรียงข้อมูลตามวันที่
ksort($last_7_days_data);

// เพิ่มข้อมูลใน response
$response['last_7_days'] = array_values($last_7_days_data);

// ส่งข้อมูล JSON
echo json_encode($response);

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
