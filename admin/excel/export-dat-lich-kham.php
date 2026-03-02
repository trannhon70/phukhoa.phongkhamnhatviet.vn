<?php
include '../../lib/database.php';

$db = new Database();
$dateCondition = '';

// Kiểm tra và xử lý ngày tháng
if (
    isset($_GET['start-date']) && $_GET['start-date'] !== '' &&
    isset($_GET['end-date']) && $_GET['end-date'] !== ''
) {
    $startDate = $_GET['start-date'];
    $endDate = $_GET['end-date'];
    $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
    $endDate = DateTime::createFromFormat('d/m/Y', $endDate);
    $format_startDate = $startDate->format('Y-m-d');
    $format_endDate = $endDate->format('Y-m-d');

    $query = "SELECT khachHang.*, user.user_name, kq.name as kq_name 
    FROM admin_khachhang khachHang
    LEFT JOIN admin_user user ON khachHang.user_tuvan = user.id
    LEFT JOIN admin_select_kq kq ON khachHang.ketqua = kq.id
    WHERE DATE(khachHang.ngaykham) BETWEEN '$format_startDate' AND '$format_endDate'
    ORDER BY khachHang.id DESC";

    $result = $db->select($query);
} else {
    $query = "SELECT khachHang.*, user.user_name, kq.name as kq_name 
    FROM admin_khachhang khachHang
    LEFT JOIN admin_user user ON khachHang.user_tuvan = user.id
    LEFT JOIN admin_select_kq kq ON khachHang.ketqua = kq.id
    ORDER BY khachHang.id DESC";

    $result = $db->select($query);
}

if ($result) {
    // Đặt header để tải file Excel
    header('Content-Encoding: UTF-8');
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment;filename="Danh-sach-dat-lich-kham-' . date('Y-m-d') . '.csv"');
    header('Cache-Control: max-age=0');
    echo "\xEF\xBB\xBF"; // Thêm BOM cho UTF-8

    // Tạo tiêu đề cho các cột và escape các ký tự đặc biệt
    echo '"ID","HỌ TÊN","NĂM SINH","SỐ ĐIỆN THOẠI","TRIỆU CHỨNG","NGÀY KHÁM","GIỜ KHÁM","TÌNH TRẠNG","KẾT QUẢ","NGUỒN URL","NGƯỜI TƯ VẤN","MÃ HẸN"' . "\n";

    // Xuất dữ liệu
    while ($row = $result->fetch_assoc()) {
        echo '"' . $row['id'] . '","' . 
             addslashes($row['hoten']) . '","' . 
             addslashes($row['ngaysinh']) . '","' . 
             '0' . addslashes($row['sdt']) . '","' . 
             addslashes($row['trieuchung']) . '","' . 
             addslashes($row['ngaykham']) . '","' . 
             addslashes($row['giokham']) . '","' . 
             ($row['status'] === '0' ? 'Chưa được tư vấn' : 'Đã được tư vấn') . '","' . 
             addslashes($row['kq_name']) . '","' . 
             addslashes($row['nguon']) . '","' . 
             addslashes($row['user_name']) . '","' . 
             addslashes($row['mahen']) . '"' . "\n";
    }
    exit;
} else {
    echo "No records found...";
    exit;
}
?>
