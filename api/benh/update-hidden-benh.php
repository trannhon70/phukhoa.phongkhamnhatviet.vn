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
    $ma_benh = isset($data['ma_benh']) ? htmlspecialchars(strip_tags($data['ma_benh'])) : '';
    $hidden = isset($data['hidden']) ? htmlspecialchars(strip_tags($data['hidden'])) : '';

    $sql = "UPDATE admin_benh SET hidden = '$hidden' WHERE ma_benh = '$ma_benh'";
    $result = $db->update($sql);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu dữ liệu']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
