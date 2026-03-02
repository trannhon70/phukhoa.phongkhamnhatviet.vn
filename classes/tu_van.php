
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php


class TuVan
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    //thêm danh mục 
    public function createKhachHangTuVan($data, $nguon)
    {
        $hoten = mysqli_real_escape_string($this->db->link, $data['hoten']);
        $ngaysinh = mysqli_real_escape_string($this->db->link, $data['ngaysinh']);
        $sdt = mysqli_real_escape_string($this->db->link, $data['sdt']);
        $trieuchung = mysqli_real_escape_string($this->db->link, $data['trieuchung']);
        $created_at = $this->fm->created_at();

        if ($hoten !== '' && $ngaysinh !== '' && $sdt !== '' && $trieuchung !== '') {

            if (validatePhoneNumber($sdt) === false) {
                return array('status' => 'error', 'message' => 'Số điện thoại không hợp lệ!');
            }
            $query = "INSERT INTO admin_tuvan (hoten,ngaysinh,sdt,trieuchung,status, note, ketqua, nguon,user_tuvan,created_at) VALUE('$hoten','$ngaysinh','$sdt','$trieuchung',0,'','',' $nguon','','$created_at') ";
            $result = $this->db->insert($query);
            if ($result) {
                return array('status' => 'success', 'message' => 'Cảm ơn quý khách đã để lại thông tin, chúng tôi sẽ liên hệ với khách hàng trong thời sớm nhất!');
            }
        } else {
            return array('status' => 'error', 'message' => 'Tất cả các nội dung không được bổ trống!');
        }
    }
    public function getPaginationTuVan($limit, $offset)
    {
        $query = "SELECT tuvan.*, user.user_name 
              FROM admin_tuvan tuvan
              LEFT JOIN admin_user user ON tuvan.user_tuvan = user.id
              ORDER BY tuvan.id DESC LIMIT $limit OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) AS total FROM admin_tuvan ";
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getPaginationTuVanByNgayKham($limit, $offset, $startDate, $endDate, $sdt)
    {
        $sdt = mysqli_real_escape_string($this->db->link, $sdt);
        $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDate = DateTime::createFromFormat('d/m/Y', $endDate);
        $format_startDate = $startDate->format('Y-m-d');
        $format_endDate = $endDate->format('Y-m-d');
        $sdt = ltrim($sdt, '0');
        $query = "SELECT tuvan.*, user.user_name 
            FROM admin_tuvan tuvan
            LEFT JOIN admin_user user ON tuvan.user_tuvan = user.id
            WHERE tuvan.sdt LIKE '%$sdt%'
            AND DATE(tuvan.created_at) BETWEEN '$format_startDate' AND '$format_endDate'
            ORDER BY tuvan.id DESC 
            LIMIT $limit OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCountByNgayKham($startDate, $endDate, $sdt)
    {
        $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDate = DateTime::createFromFormat('d/m/Y', $endDate);
        $format_startDate = $startDate->format('Y-m-d');
        $format_endDate = $endDate->format('Y-m-d');
        $sdt = mysqli_real_escape_string($this->db->link, $sdt);

        $query = "SELECT COUNT(*) AS total 
              FROM admin_tuvan 
              WHERE 1 ";

        // Kiểm tra và thêm điều kiện ngày tháng
        if (!empty($format_startDate) && !empty($format_endDate)) {
            $query .= " AND DATE(created_at) BETWEEN '$format_startDate' AND '$format_endDate' ";
        } elseif (!empty($format_startDate)) {
            $query .= " AND DATE(created_at) >= '$format_startDate' ";
        } elseif (!empty($format_endDate)) {
            $query .= " AND DATE(created_at) <= '$format_endDate' ";
        }

        // Kiểm tra và thêm điều kiện số điện thoại
        if (!empty($sdt)) {
            $query .= " AND sdt LIKE '%$sdt%' ";
        }

        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }


    public function getByIdLichTuVan($id)
    {
        $query = " SELECT * FROM admin_tuvan WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result->fetch_assoc();
    }

    public function UpdateKhachHangTuVan($data, $id)
    {
        $status = mysqli_real_escape_string($this->db->link, $data['status']);
        $note = mysqli_real_escape_string($this->db->link, $data['note']);
        $ketqua = mysqli_real_escape_string($this->db->link, $data['ketqua']);
        $mahen = mysqli_real_escape_string($this->db->link, $data['mahen']);

        $user_tuvan = Session::get('id');

        if ($id !== '') {
            $query = "UPDATE admin_tuvan SET 
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

    public function delete_tuVan($id)
    {
        $query = " DELETE FROM `admin_tuvan` WHERE  id = '$id' LIMIT 1";
        $result = $this->db->delete($query);
        if ($result) {
            return array('status' => 'success', 'message' => 'Khách đã được xóa thành công!');
        } else {
            return array('status' => 'error', 'message' => 'Khách đã được xóa không thất bại!');
        }
    }
}

?>