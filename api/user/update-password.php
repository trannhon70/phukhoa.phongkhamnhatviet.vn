<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include_once('../../lib/database.php');
include_once('../../helpers/format.php');

$fm = new Format();
$db = new Database();
header('Content-Type: application/json'); // Đảm bảo phản hồi dưới dạng JSON
$data = json_decode(file_get_contents("php://input"), true);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data)) {
    // Xử lý và chuẩn bị dữ liệu
    $ma_user = isset($data['ma_user']) ? htmlspecialchars(strip_tags($data['ma_user'])) : '';
    $password = isset($data['password']) ? htmlspecialchars(strip_tags($data['password'])) : '';

    $password = $fm->validation($password);
    $password = mysqli_real_escape_string($db->link, md5($password));
    $hash_password = md5($password);

    $sql = "UPDATE admin_user SET password = '$hash_password' WHERE ma_user = '$ma_user'";
    $result = $db->update($sql);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu dữ liệu']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
