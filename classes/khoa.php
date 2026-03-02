
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php 
  class khoa 
  {
      private $db;
      private $fm;
      public function __construct()
      {
          $this->db = new Database();
          $this->fm = new Format();
      }

      //thêm danh mục 
      public function getAllKhoa(){

        $query = "SELECT * FROM `admin_khoa` WHERE 1";
        $result = $this->db->select($query);
        return $result;
        
      }
      
      public function getAllChiTietKhoaAndBenh() {
        // Step 1: Get all departments (khoa)
        $queryKhoa = "SELECT * FROM `admin_khoa` WHERE 1";
        $resultKhoa = $this->db->select($queryKhoa);
    
        $data = [];
        if ($resultKhoa) {
            while ($rowKhoa = $resultKhoa->fetch_assoc()) {
                // Step 2: For each department, get the list of diseases (benh)
                $idKhoa = $rowKhoa['id'];
                $danhSachBenh = $this->getDanhSachBenhByIdKhoa($idKhoa);
                
                // Step 3: Add the department and its diseases to the data array
                $rowKhoa['danhSachBenh'] = $danhSachBenh;
                $data[] = $rowKhoa;
            }
        }
        
        return $data;
    }
    
    public function getDanhSachBenhByIdKhoa($idKhoa) {
        // Sanitize the input to prevent SQL injection
        $idKhoa = intval($idKhoa);
    
        $queryBenh = "SELECT * FROM `admin_benh` WHERE id_khoa = '$idKhoa' AND hidden = '0'";
        $resultBenh = $this->db->select($queryBenh);
    
        $data = [];
        if ($resultBenh) {
            while ($row = $resultBenh->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }

    public function getDanhMucBenhByKhoa ($slug) {
        $queryKhoa = "SELECT * FROM `admin_khoa` WHERE slug = '$slug' LIMIT 1";
        $resultKhoa = $this->db->select($queryKhoa);
    
        $data = [];
        if ($resultKhoa) {
            while ($rowKhoa = $resultKhoa->fetch_assoc()) {
                // Step 2: For each department, get the list of diseases (benh)
                $idKhoa = $rowKhoa['id'];
                $danhSachBenh = $this->getDanhMucBenhByslugKhoa($idKhoa);
                
                // Step 3: Add the department and its diseases to the data array
                $rowKhoa['danhSachBenh'] = $danhSachBenh;
                $data[] = $rowKhoa;
            }
        }
        
        return $data;
    }

    public function getDanhMucBenhByslugKhoa($idKhoa) {
        // Sanitize the input to prevent SQL injection
        $idKhoa = intval($idKhoa);
    
        $queryBenh = "SELECT * FROM `admin_benh` WHERE id_khoa = '$idKhoa' AND hidden = '0'";
        $resultBenh = $this->db->select($queryBenh);
    
        $data = [];
        if ($resultBenh) {
            while ($row = $resultBenh->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }

    public function getDanhMucBenhByBaiViet($slug){
        // Xử lý input để tránh lỗi SQL Injection
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        // Truy vấn để lấy id_khoa từ bảng admin_baiviet dựa trên slug
        $queryBaiViet = "SELECT id_khoa FROM `admin_baiviet` WHERE slug = '$slug' LIMIT 1";
        $resultBaiViet = $this->db->select($queryBaiViet);
        
        $data = []; // Mảng lưu trữ kết quả cuối cùng
    
        if($resultBaiViet){
            // Duyệt qua từng bản ghi của bảng admin_baiviet (nếu có nhiều bản ghi)
            while($rowBaiViet = $resultBaiViet->fetch_assoc()){
                $id = $rowBaiViet['id_khoa'];
                // Truy vấn lấy thông tin khoa từ bảng admin_khoa dựa trên id_khoa
                $queryKhoa = "SELECT * FROM `admin_khoa` WHERE id = '$id' LIMIT 1";
                $resultKhoa = $this->db->select($queryKhoa);
                
                if($resultKhoa){
                    while($rowKhoa = $resultKhoa->fetch_assoc()){
                        $idKhoa = $rowKhoa['id'];
                        // Truy vấn lấy danh sách bệnh từ bảng admin_benh dựa trên id_khoa
                        $danhSachBenh = "SELECT * FROM `admin_benh` WHERE id_khoa = '$idKhoa'";
                        $resultDSBenh = $this->db->select($danhSachBenh);
                        
                        // Lưu danh sách bệnh vào mảng
                        $danhSachBenhArr = [];
                        if($resultDSBenh){
                            while($rowBenh = $resultDSBenh->fetch_assoc()){
                                $danhSachBenhArr[] = $rowBenh;
                            }
                        }
                        
                        // Gán danh sách bệnh vào mảng kết quả khoa
                        $rowKhoa['danhSachBenh'] = $danhSachBenhArr;
                        $data[] = $rowKhoa;
                    }
                }
            }   
        }
    
        // Trả về mảng kết quả cuối cùng
        return $data;
    }
    public function getTTBenhAndKhoa($khoa_slug, $benh_slug){
        // Sử dụng mysqli_real_escape_string để tránh SQL injection
        $khoa_slug = mysqli_real_escape_string($this->db->link, $khoa_slug);
        $benh_slug = mysqli_real_escape_string($this->db->link, $benh_slug);
    
        $queryKhoa = "SELECT name FROM `admin_khoa` WHERE slug = '$khoa_slug' LIMIT 1";
        $resultKhoa = $this->db->select($queryKhoa);
        $data = []; 
        if ($resultKhoa) {
            $khoa = $resultKhoa->fetch_assoc();  
            $queryBenh = "SELECT name FROM `admin_benh` WHERE slug = '$benh_slug' LIMIT 1";
            $resultBenh = $this->db->select($queryBenh);
            if ($resultBenh) {
                $benh = $resultBenh->fetch_assoc(); 
                $data = [
                    'khoa' => $khoa['name'],  
                    'benh' => $benh['name']  
                ];
            }
        }
    
        return $data;  
    }
    
    
  }

  
  
?>