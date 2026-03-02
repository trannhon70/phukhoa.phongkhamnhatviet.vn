<?php
session_start();
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once ($filepath . '/../lib/session.php');
include_once($filepath . '/../helpers/format.php');
$fm = new Format();
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sdt'])) {
    $sdt = $_POST['sdt'];
    $nguon = $_POST['url'];

    $created_at = $fm->created_at();
    $formatted_date = date('Y-m-d', strtotime($created_at));

    $check_created = "SELECT * FROM `admin_tuvan` WHERE sdt = '$sdt' AND DATE(created_at) = '$formatted_date'";
    $check_result = $db->select($check_created);
    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Số điện thoại này đã được đăng ký trong ngày hôm nay']);
    }
    else {
        // Thực hiện thêm mới dữ liệu
        $sql = "INSERT INTO admin_tuvan (hoten, ngaysinh, sdt, trieuchung, status, note, ketqua, nguon, user_tuvan, created_at) 
                VALUES ('', '', '$sdt', '', 0, '', 0, '$nguon', 0, '$formatted_date')";
        $result = $db->insert($sql);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu dữ liệu']);
        }
    }
   
}



?> 