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
    $user_name = isset($data['user_name']) ? htmlspecialchars(strip_tags($data['user_name'])) : '';
    $password = isset($data['password']) ? htmlspecialchars(strip_tags($data['password'])) : '';
    $full_name = isset($data['full_name']) ? htmlspecialchars(strip_tags($data['full_name'])) : '';
    $email = isset($data['email']) ? htmlspecialchars(strip_tags($data['email'])) : '';
    $role_id = isset($data['role_id']) ? htmlspecialchars(strip_tags($data['role_id'])) : '';
    $ma_user = isset($data['ma_user']) ? htmlspecialchars(strip_tags($data['ma_user'])) : '';
    $created_at = $fm->created_at();
    $formatted_date = date('Y-m-d', strtotime($created_at));

    $password = $fm->validation($password);
    $password = mysqli_real_escape_string($db->link, md5($password));
    $hash_password = md5($password);

    // Kiểm tra tất cả các trường có được điền hay không
    if (empty($user_name) || empty($password) || empty($full_name) || empty($email) || empty($role_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Tất cả các trường không được bỏ trống!']);
        exit();
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
        exit();
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại trong cơ sở dữ liệu chưa
    $check_created = "SELECT * FROM admin_user WHERE user_name = '$user_name'";
    $check_result = $db->select($check_created);

    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại, vui lòng nhập tên khác!']);
        exit();
    } else {


        $sql = "INSERT INTO admin_user (user_name, password, full_name, email, role_id, ma_user, created_at) 
                VALUES ('$user_name', '$hash_password', '$full_name', '$email', '$role_id', '$ma_user', '$created_at')";
        $result = $db->insert($sql);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Tài khoản đã được tạo thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu dữ liệu']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
