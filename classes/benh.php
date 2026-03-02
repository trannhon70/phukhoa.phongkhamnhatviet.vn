
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php
class Benh
{
  private $db;
  private $fm;
  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }

  //thêm danh mục 
  public function getByIdKhoa()
  {
    $query = "SELECT * FROM admin_benh WHERE 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function getAllDanhSachBenh()
  {
    $query = "SELECT * FROM admin_benh WHERE 1";
    $result = $this->db->select($query);
    return $result;
  }

  function getPaginationBenhs($limit, $offset, $tenBenh, $IdKhoa)
  {
    if ($tenBenh !== '' || $IdKhoa !== '') {
      $query = "SELECT admin_benh.id AS benh_id, admin_benh.name, admin_benh.slug, admin_benh.hidden, admin_benh.ma_benh, admin_benh.created_at,
            khoa.name AS nameKhoa,
            user.full_name
            FROM `admin_benh`
            JOIN admin_khoa khoa ON admin_benh.id_khoa = khoa.id
            JOIN admin_user user ON admin_benh.ma_user = user.ma_user
            WHERE admin_benh.name LIKE '%$tenBenh%'";
      if (!empty($IdKhoa)) {
        $query .= " AND khoa.id = '$IdKhoa'";
      }

      $query .= "ORDER BY admin_benh.id DESC LIMIT $limit OFFSET $offset";
    } else {
      $query = "SELECT admin_benh.id AS benh_id, admin_benh.name, admin_benh.slug, admin_benh.hidden , admin_benh.ma_benh ,admin_benh.created_at,
            khoa.name AS nameKhoa,
            user.full_name
            FROM `admin_benh`
            JOIN admin_khoa khoa ON admin_benh.id_khoa = khoa.id
            JOIN admin_user user ON admin_benh.ma_user = user.ma_user
            ORDER BY admin_benh.id DESC LIMIT $limit OFFSET $offset";
    }

    $result = $this->db->select($query);
    return $result;
  }

  function getTotalCountBenhs($tenBenh, $IdKhoa)
  {
    if ($tenBenh !== '' || $IdKhoa !== '') {
      $query = "SELECT COUNT(*) AS total FROM admin_benh WHERE admin_benh.name LIKE '%$tenBenh%'";

      if (!empty($IdKhoa)) {
        $query .= " AND admin_benh.id_khoa = '$IdKhoa'";
      }
    } else {
      $query = "SELECT COUNT(*) AS total FROM admin_benh ";
    }

    $result = $this->db->select($query);
    $row = $result->fetch_assoc();
    return $row['total'];
  }

  function getDanhSachBenhByIdKhoa($idKhoa)
  {
    $query = "SELECT * FROM `admin_benh` WHERE id_khoa = '$idKhoa' AND hidden = '0' ";
    $result = $this->db->select($query);

    $data = [];
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }

  public function getActiveByBenh($path)
  {
    $path = mysqli_real_escape_string($this->db->link, $path);
    $queryBenh = "SELECT id_benh FROM `admin_baiviet` WHERE slug = '$path' LIMIT 1";
    $resultBenh = $this->db->select($queryBenh);
    if ($resultBenh && $resultBenh->num_rows > 0) {
      $row = $resultBenh->fetch_assoc();
      $idBenh = $row['id_benh'];
      $query = "SELECT slug FROM admin_benh WHERE id = '$idBenh' LIMIT 1";
      $result = $this->db->select($query);

      if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
      } else {
        return null;
      }
    }

    return null;
  }

  public function getMenuMobile() {
    $queryKhoa = "SELECT * FROM `admin_khoa` WHERE `id` BETWEEN 1 AND 4";
    $resultKhoa = $this->db->select($queryKhoa);
    $data = [];
    if ($resultKhoa) {
        while ($row = $resultKhoa->fetch_assoc()) {
            $idKhoa = $row['id'];
            
            $query = "SELECT * FROM `admin_benh` WHERE `id_khoa` = '$idKhoa'";
            $result = $this->db->select($query);

            $dsBenh = [];
            if ($result) {
                while ($benhRow = $result->fetch_assoc()) {
                    $dsBenh[] = $benhRow;
                }
            }

            $row['dsBenh'] = $dsBenh;
            $data[] = $row;
        }
    }

    return $data;
}
}

?>