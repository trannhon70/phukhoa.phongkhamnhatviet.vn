
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../lib/session.php');
?>

<?php
function validatePhoneNumber($phoneNumber)
{
    $pattern = "/^[0-9]{10}$/";
    if (preg_match($pattern, $phoneNumber)) {
        return true;
    } else {
        return false;
    }
}

function validateDateOfBirth($dateOfBirth)
{
    $pattern = "/^([0-2][0-9]|(3)[0-1])\/(((0)[0-9])|((1)[0-2]))\/\d{4}$/";
    return preg_match($pattern, $dateOfBirth) ? true : false;
}

function formatTime($time)
{
    // Chuyển đổi thành số nguyên
    $time = intval($time);
    // Đảm bảo rằng giờ luôn có 2 chữ số
    if ($time < 10) {
        return "0" . $time;
    }
    return strval($time);
}

class KhachHang
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    //thêm danh mục 
    public function createKhachHang($data, $nguon)
    {
        $hoten = mysqli_real_escape_string($this->db->link, $data['hoten']);
        $ngaysinh = mysqli_real_escape_string($this->db->link, $data['ngaysinh']);
        $sdt = mysqli_real_escape_string($this->db->link, $data['sdt']);
        $trieuchung = mysqli_real_escape_string($this->db->link, $data['trieuchung']);
        $ngaykham = mysqli_real_escape_string($this->db->link, $data['ngaykham']);
        $giokham = mysqli_real_escape_string($this->db->link, $data['giokham']);
        $created_at = $this->fm->created_at();

        if ($hoten !== '' && $ngaysinh !== '' && $sdt !== '' && $trieuchung !== '' && $ngaykham !== '' && $giokham !== '') {

            if (validatePhoneNumber($sdt) === false) {
                return array('status' => 'error', 'message' => 'Số điện thoại không hợp lệ!');
            }
            $query = "INSERT INTO admin_khachhang (hoten,ngaysinh,sdt,trieuchung,ngaykham,giokham,status, note, ketqua, nguon,user_tuvan,created_at) VALUE('$hoten','$ngaysinh','$sdt','$trieuchung','$ngaykham','$giokham',0,'','',' $nguon','','$created_at') ";
            $result = $this->db->insert($query);
            if ($result) {
                return array('status' => 'success', 'message' => 'Cảm ơn quý khách đã để lại thông tin, chúng tôi sẽ liên hệ với khách hàng trong thời sớm nhất!');
            }
        } else {
            return array('status' => 'error', 'message' => 'Tất cả các nội dung không được bổ trống!');
        }
    }

    public function getPaginationLichKham($limit, $offset)
    {
        $query = "SELECT kh.*, user.user_name 
              FROM admin_khachhang kh
              LEFT JOIN admin_user user ON kh.user_tuvan = user.id
              ORDER BY kh.id DESC LIMIT $limit OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) AS total FROM admin_khachhang ";
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getPaginationLichKhamByNgayKham($limit, $offset, $startDate, $endDate, $sdt)
    {
        $sdt = mysqli_real_escape_string($this->db->link, $sdt);
        $sdt = ltrim($sdt, '0');
        $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDate = DateTime::createFromFormat('d/m/Y', $endDate);
        $format_startDate = $startDate->format('Y-m-d');
        $format_endDate = $endDate->format('Y-m-d');
        
        $query = "SELECT khachHang.*, user.user_name 
        FROM admin_khachhang khachHang
        LEFT JOIN admin_user user ON khachHang.user_tuvan = user.id
        WHERE khachHang.sdt LIKE '%$sdt%'
        AND DATE(khachHang.ngaykham) BETWEEN '$format_startDate' AND '$format_endDate'
        ORDER BY khachHang.id DESC 
        LIMIT $limit OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCountByNgayKham($startDate, $endDate, $sdt)

    {

        $sdt = mysqli_real_escape_string($this->db->link, $sdt);
        $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDate = DateTime::createFromFormat('d/m/Y', $endDate);
        $format_startDate = $startDate->format('Y-m-d');
        $format_endDate = $endDate->format('Y-m-d');
        $query = "SELECT COUNT(*) AS total 
              FROM admin_khachhang 
              WHERE 1 ";

        // Kiểm tra và thêm điều kiện ngày tháng
        if (!empty($format_startDate) && !empty($format_endDate)) {
            $query .= " AND DATE(ngaykham) BETWEEN '$format_startDate' AND '$format_endDate' ";
        } elseif (!empty($format_startDate)) {
            $query .= " AND DATE(ngaykham) >= '$startDate' ";
        } elseif (!empty($format_endDate)) {
            $query .= " AND DATE(ngaykham) <= '$format_endDate' ";
        }

        // Kiểm tra và thêm điều kiện số điện thoại
        if (!empty($sdt)) {
            $query .= " AND sdt LIKE '%$sdt%' ";
        }

        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getByIdLichKham($id)
    {
        $query = " SELECT * FROM admin_khachhang WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result->fetch_assoc();
    }

    public function getAllSelectKQ()
    {
        $query = " SELECT * FROM admin_select_kq ";
        $result = $this->db->select($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function UpdateKhachHang($data, $id)
    {
        $status = mysqli_real_escape_string($this->db->link, $data['status']);
        $note = mysqli_real_escape_string($this->db->link, $data['note']);
        $ketqua = mysqli_real_escape_string($this->db->link, $data['ketqua']);
        $mahen = mysqli_real_escape_string($this->db->link, $data['mahen']);
        $user_tuvan = Session::get('id');

        if ($id !== '') {
            $query = "UPDATE admin_khachhang SET 
             status = '$status' ,
             note = '$note' ,
             ketqua = '$ketqua',
             user_tuvan = '$user_tuvan',
             mahen = '$mahen'
             WHERE id = '$id'";
            $result = $this->db->update($query);
            if ($result) {
                return array('status' => 'success', 'message' => 'Cập nhật thành công!');
            } else {
                return array('status' => 'error', 'message' => 'Cập nhật không thất bại!');
            }
        }
    }

    public function delete_khachHang($id)
    {
        $query = "DELETE FROM `admin_khachhang` WHERE id = '$id' LIMIT 1";
        $result = $this->db->delete($query);

        if ($result) {
            return array('status' => 'success', 'message' => 'Thông tin khách hàng xóa thành công!');
        } else {
            return array('status' => 'error', 'message' => 'Thông tin khách hàng xóa không thất bại!');
        }
    }
}

?>