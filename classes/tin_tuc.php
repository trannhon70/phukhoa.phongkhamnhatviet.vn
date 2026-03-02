<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
include_once($filepath . '/../lib/session.php');


?>

<?php
class news
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    //thêm danh mục 
    public function insert_tintuc($data, $files)
    {
        $tieu_de = mysqli_real_escape_string($this->db->link, $data['tieu_de']);
        $content = mysqli_real_escape_string($this->db->link, $data['content']);
        $title = mysqli_real_escape_string($this->db->link, $data['title']);
        $keyword = mysqli_real_escape_string($this->db->link, $data['keyword']);
        $description = mysqli_real_escape_string($this->db->link, $data['description']);
        $slug = mysqli_real_escape_string($this->db->link, $data['slug']);
        $selectedImage = mysqli_real_escape_string($this->db->link, $data['selectedImage']);
        $created_at = $this->fm->created_at();
    
        // Initialize variables
        $img = $selectedImage; // Mặc định là hình ảnh đã chọn trước đó
        $uploaded_image = '';
        $file_temp = '';
    
        // Xử lý hình ảnh nếu có
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
        $latest_id_query = "SELECT id FROM `admin_tintuc` ORDER BY id DESC LIMIT 1";
        $latest_id_result = $this->db->select($latest_id_query);
        $latest_id = ($latest_id_result && $latest_id_result->num_rows > 0)
            ? $latest_id_result->fetch_assoc()['id']
            : 0;
        $slug .= '-' . ($latest_id);
    
        if ($tieu_de !== '' && $content !== '') {
            $query = "INSERT INTO admin_tintuc (title,slug,content,tieu_de,keyword,descriptions,user_id,img,created_at) VALUE('$title','$slug','$content','$tieu_de','$keyword','$description','" . Session::get('id') . "','$img','$created_at') ";
            $result = $this->db->insert($query);
    
            if ($result) {
                return array('status' => 'success', 'message' => 'Thêm tin tức thành công!');
            } else {
                return array('status' => 'error', 'message' => 'Thêm tin tức thất bại!');
            }
        } else {
            return array('status' => 'error', 'message' => 'Các trường tiêu đề, nội dung không được bổ trống!');
        }
    }


    public function update_tintuc($data, $files, $id)
    {
        $tieu_de = mysqli_real_escape_string($this->db->link, $data['tieu_de']);

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

        if ($tieu_de !== '' && $content !== '') {


            if (empty($img)) {
                $query = "UPDATE admin_tintuc SET 
                tieu_de = '$tieu_de' ,
            
                content = '$content' ,
                title = '$title' ,
                keyword = '$keyword' ,
                descriptions = '$description' 
                WHERE id = '$id'";
            } else {
              
                $query = "UPDATE admin_tintuc SET 
                tieu_de = '$tieu_de' ,
            
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

    public function getPaginationTinTuc($limit, $offset, $tieuDe)
    {
        $tieuDe = mysqli_real_escape_string($this->db->link, $tieuDe);
        if ($tieuDe !== '') {
            $query = "SELECT tintuc.*, user.user_name, user.email , user.full_name
            FROM admin_tintuc tintuc 
            JOIN admin_user user ON tintuc.user_id = user.id
            WHERE tintuc.tieu_de LIKE '%$tieuDe%'
            ORDER BY tintuc.id DESC LIMIT $limit OFFSET $offset";
        } else {
            $query = "SELECT tintuc.*, user.user_name, user.email , user.full_name
            FROM admin_tintuc tintuc 
            JOIN admin_user user ON tintuc.user_id = user.id
            
            ORDER BY tintuc.id DESC LIMIT $limit OFFSET $offset";
        }

        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCountTinTuc($tieuDe)
    {
        $tieuDe = mysqli_real_escape_string($this->db->link, $tieuDe);

        if ($tieuDe !== "") {
            $query = "SELECT COUNT(*) AS total FROM admin_tintuc WHERE tieu_de LIKE '%$tieuDe%' ";
        } else {
            $query = "SELECT COUNT(*) AS total FROM admin_tintuc";
        }

        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }


    public function delete_tituc($id)
    {
        $query = "DELETE FROM admin_tintuc WHERE id = $id ";
        $result = $this->db->delete($query);
        if ($result) {
            return array('status' => 'success', 'message' => 'Xóa bài viết thành công!');
        } else {
            return array('status' => 'error', 'message' => 'Xóa bài viết thất bại!');
        }
    }

    public function getById_tintuc($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM admin_tintuc WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result->fetch_assoc();
    }

    public function getAllLimitTinTuc()
    {
        $query = "SELECT * FROM `admin_tintuc` WHERE 1
          ORDER BY id DESC LIMIT 10";
        $result = $this->db->select($query);
        return $result;
    }

    public function getOneLimitTinTuc()
    {
        $query = "SELECT * FROM `admin_tintuc` WHERE 1
          ORDER BY id DESC LIMIT 1";
        $result = $this->db->select($query);
        return $result->fetch_assoc();
    }

    public function getByslug_tintuc($slug)
    {
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        $query = "SELECT * FROM admin_tintuc WHERE slug = '$slug' LIMIT 1";
        $result = $this->db->select($query);
        return $result->fetch_assoc();
    }
}

?>