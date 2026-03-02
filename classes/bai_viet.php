
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../lib/session.php');


?>

<?php
class post
{
  private $db;
  private $fm;
  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }

  //thêm danh mục 
  public function insert_post($data, $files)
  {
    // Lấy dữ liệu từ biểu mẫu và bảo vệ chống SQL injection
    $tieu_de = mysqli_real_escape_string($this->db->link, $data['tieu_de']);
    $id_benh = mysqli_real_escape_string($this->db->link, $data['id_benh']);
    $id_khoa = mysqli_real_escape_string($this->db->link, $data['id_khoa']);
    $content = mysqli_real_escape_string($this->db->link, $data['content']);
    $title = mysqli_real_escape_string($this->db->link, $data['title']);
    $keyword = mysqli_real_escape_string($this->db->link, $data['keyword']);
    $description = mysqli_real_escape_string($this->db->link, $data['description']);
    $slug = mysqli_real_escape_string($this->db->link, $data['slug']);
    $selectedImage = mysqli_real_escape_string($this->db->link, $data['selectedImage']);
    $created_at = $this->fm->created_at();

    // Xử lý hình ảnh nếu có
    $img = $selectedImage; // Mặc định là hình ảnh đã chọn trước đó
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
      $file_name = $_FILES['image']['name'];
      $file_temp = $_FILES['image']['tmp_name'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;
      move_uploaded_file($file_temp, $uploaded_image);
      $img = $unique_image; // Cập nhật với hình ảnh mới
    }

    // Lấy ID bài viết mới nhất và tạo slug
    $latest_id_query = "SELECT id FROM `admin_baiviet` ORDER BY id DESC LIMIT 1";
    $latest_id_result = $this->db->select($latest_id_query);
    $latest_id = ($latest_id_result && $latest_id_result->num_rows > 0)
      ? $latest_id_result->fetch_assoc()['id']
      : 0;
    $slug .= '-' . ($latest_id);

    // Thực hiện truy vấn nếu các trường không rỗng
    if ($tieu_de && $id_benh && $content) {
      $query = "INSERT INTO admin_baiviet (title, slug, content, id_benh, id_khoa, created_at, tieu_de, keyword, descriptions, user_id, img)
                  VALUES ('$title', '$slug', '$content', '$id_benh', '$id_khoa', '$created_at', '$tieu_de', '$keyword', '$description', '" . Session::get('id') . "', '$img')";
      $result = $this->db->insert($query);

      return $result
        ? ['status' => 'success', 'message' => 'Thêm bài viết thành công!']
        : ['status' => 'error', 'message' => 'Thêm bài viết thất bại!'];
    }

    return ['status' => 'error', 'message' => 'Các trường tiêu đề, chọn bênh, nội dung không được bổ trống!'];
  }



  public function getAll()
  {
    $query = "SELECT baiviet.*, user.user_name, user.email , user.full_name, benh.name AS ten_benh
        FROM admin_baiviet baiviet 
        JOIN admin_user user ON baiviet.user_id = user.id
        JOIN admin_benh benh ON baiviet.id_benh = benh.id
        ORDER BY baiviet.created_at DESC";
    $result = $this->db->select($query);
    return $result;
  }

  public function delete_baiviet($id)
  {
    $query = "DELETE FROM admin_baiviet WHERE id = $id ";
    $result = $this->db->delete($query);
    if ($result) {
      return array('status' => 'success', 'message' => 'Xóa bài viết thành công!');
    } else {
      return array('status' => 'error', 'message' => 'Xóa bài viết thất bại!');
    }
  }

  public function getById_baiviet($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM admin_baiviet WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result->fetch_assoc();
  }

  public function getById_benh($id, $limit, $offset)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM admin_benh WHERE slug = '$id' LIMIT 1 ";
    $result = $this->db->select($query);

    if ($result) {
      $row = $result->fetch_assoc();
      $benh_id = $row['id'];

      $query_baiviet = "SELECT * FROM admin_baiviet WHERE id_benh = $benh_id ORDER BY id DESC LIMIT $limit OFFSET $offset";
      $result_baiviet = $this->db->select($query_baiviet);
      return $result_baiviet;
    } else {
      // Xử lý khi không tìm thấy bệnh với slug tương ứng
      echo "Không tìm thấy bệnh với slug '$id'";
      return false;
    }
  }

  public function getPagingBaiVietTheoBenh($id, $limit, $offset) {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM admin_benh WHERE slug = '$id' LIMIT 1 ";
    $result = $this->db->select($query);
    $data = [];
    if ($result) {
      while ($rowBenh = $result->fetch_assoc()) {
        $idBenh = $rowBenh['id'];
        $query_baiviet = "SELECT * FROM admin_baiviet WHERE id_benh = $idBenh ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $result_baiviet = $this->db->select($query_baiviet);
        $dataBaiViet = [];
        if ($result_baiviet) {
            while ($row = $result_baiviet->fetch_assoc()) {
                $dataBaiViet[] = $row;
            }
        }

        $rowBenh['danhSachBaiViet'] = $dataBaiViet;
                $data[] = $rowBenh;
      }
    } 
    return $data;
  }

  public function getTotalCount($tieuDe,$IdBenh)
  {
    $tieuDe = mysqli_real_escape_string($this->db->link, $tieuDe);
    if ($tieuDe !== '' || $IdBenh !== '') {
      $query = "SELECT COUNT(*) AS total FROM admin_baiviet WHERE tieu_de LIKE '%$tieuDe%' ";
      if (!empty($IdBenh)) {
        $query .= " AND id_benh = '$IdBenh'";
      }
    } else {
      $query = "SELECT COUNT(*) AS total FROM admin_baiviet ";
    }

    $result = $this->db->select($query);
    $row = $result->fetch_assoc();
    return $row['total'];
  }

  public function getTotalCountById($id)
  {
    $queryBenh = "SELECT * FROM admin_benh WHERE slug = '$id' LIMIT 1";
    $resultBenh = $this->db->select($queryBenh);
    $rowBenh = $resultBenh->fetch_assoc();
    $id_benh = $rowBenh['id'];
    if ($resultBenh) {
      $query = "SELECT COUNT(*) AS total FROM admin_baiviet WHERE id_benh = ' $id_benh' ";
      $result = $this->db->select($query);
      $row = $result->fetch_assoc();
      return $row['total'];
    }
  }

  public function update_baiviet($data, $files, $id)
  {
    $tieu_de = mysqli_real_escape_string($this->db->link, $data['tieu_de']);
    $id_benh = mysqli_real_escape_string($this->db->link, $data['id_benh']);
    $id_khoa = mysqli_real_escape_string($this->db->link, $data['id_khoa']);
    $content = mysqli_real_escape_string($this->db->link, $data['content']);
    $title = mysqli_real_escape_string($this->db->link, $data['title']);
    $keyword = mysqli_real_escape_string($this->db->link, $data['keyword']);
    $description = mysqli_real_escape_string($this->db->link, $data['description']);
    $slug = mysqli_real_escape_string($this->db->link, $data['slug']);
    $selectedImage = mysqli_real_escape_string($this->db->link, $data['selectedImage']);



    // Xử lý hình ảnh nếu có
    $img = $selectedImage; // Mặc định là hình ảnh đã chọn trước đó
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
      $file_name = $_FILES['image']['name'];
      $file_temp = $_FILES['image']['tmp_name'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;
      move_uploaded_file($file_temp, $uploaded_image);
      $img = $unique_image; // Cập nhật với hình ảnh mới
    }

    if ($tieu_de !== '' && $id_benh !== '' && $content !== '') {


      if (empty($img)) {
        $query = "UPDATE admin_baiviet SET 
             tieu_de = '$tieu_de' ,
             id_benh = '$id_benh' ,
             id_khoa = '$id_khoa' ,
             content = '$content' ,
             title = '$title' ,
             keyword = '$keyword' ,
             descriptions = '$description' 
           WHERE id = '$id'";
      } else {
        $query = "UPDATE admin_baiviet SET 
        tieu_de = '$tieu_de' ,
        id_benh = '$id_benh' ,
        id_khoa = '$id_khoa' ,
        content = '$content' ,
        title = '$title' ,
        keyword = '$keyword' ,
        descriptions = '$description' ,
         img = '$img'
      WHERE id = '$id'";
      }
      $result = $this->db->update($query);


      if ($result) {
        return array('status' => 'success', 'message' => 'Cập nhật bài viết thành công!');
      } else {
        return array('status' => 'error', 'message' => 'Cập nhật bài viết thất bại!');
      }
    } else {
      return array('status' => 'error', 'message' => 'Các trường tiêu đề, chọn bênh, nội dung không được bổ trống!');
    }
  }

  public function getBaiViet_bySlug($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM admin_baiviet WHERE slug = '$id' LIMIT 1";
    $result = $this->db->select($query);
    if ($result) {
      return $result->fetch_assoc();
    } else {
      return null;
    }
  }

  public function getDanhSachBaiVietNew()
  {
    $query = "SELECT baiviet.*, 
    benh.slug AS slug_benh, 
    benh.id AS id_benh, 
    benh.id_khoa AS id_benh_khoa, 
    khoa.slug AS slug_khoa 
    FROM admin_baiviet baiviet 
    JOIN admin_benh benh ON baiviet.id_benh = benh.id
    JOIN admin_khoa khoa ON benh.id_khoa = khoa.id
    ORDER BY baiviet.id DESC 
    LIMIT 5";

    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $baivietArr = [];
      while ($row = $result->fetch_assoc()) {
        // Lấy id_khoa từ bảng admin_benh
        $id_benh_khoa = $row['id_benh_khoa'];


        $query_khoa = "SELECT * FROM admin_khoa WHERE id = $id_benh_khoa";
        $result_khoa = $this->db->select($query_khoa);

        if ($result_khoa && $result_khoa->num_rows > 0) {
          $khoa_info = $result_khoa->fetch_assoc();
          // Thêm thông tin từ bảng admin_khoa vào mảng baivietArr
          $row['name'] = $khoa_info['name'];
          $row['slug_khoa'] = $khoa_info['slug'];
        }

        // Thêm bài viết vào mảng kết quả
        $baivietArr[] = $row;
      }

      return $baivietArr;
    } else {
      return [];
    }
  }

  public function getPaginationTinTuc($limit, $offset, $tieuDe, $IdBenh)
  {
    $tieuDe = mysqli_real_escape_string($this->db->link, $tieuDe);
    $IdBenh = mysqli_real_escape_string($this->db->link, $IdBenh);
    if ($tieuDe !== '' || $IdBenh !== '') {
      $query = "SELECT baiviet.*, user.user_name, user.email , 
      user.full_name,
      benh.name AS ten_benh,
        benh.id_khoa AS id_benh_khoa, 
        khoa.slug AS slug_khoa 
        FROM admin_baiviet baiviet 
        JOIN admin_user user ON baiviet.user_id = user.id
        JOIN admin_benh benh ON baiviet.id_benh = benh.id
       JOIN admin_khoa khoa ON benh.id_khoa = khoa.id
       WHERE baiviet.tieu_de LIKE '%$tieuDe%'";

      if (!empty($IdBenh)) {
        $query .= " AND benh.id = '$IdBenh'";
      }
      $query .= " ORDER BY baiviet.id DESC LIMIT $limit OFFSET $offset";
    } else {
      $query = "SELECT baiviet.*, user.user_name, user.email , 
      user.full_name,
      benh.name AS ten_benh,
        benh.id_khoa AS id_benh_khoa, 
        khoa.slug AS slug_khoa 
        FROM admin_baiviet baiviet 
        JOIN admin_user user ON baiviet.user_id = user.id
        JOIN admin_benh benh ON baiviet.id_benh = benh.id
       JOIN admin_khoa khoa ON benh.id_khoa = khoa.id
        ORDER BY baiviet.id DESC LIMIT $limit OFFSET $offset";
    }

    $result = $this->db->select($query);
    return $result;
  }
}

?>