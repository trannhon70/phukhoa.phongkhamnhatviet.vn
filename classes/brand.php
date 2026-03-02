
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php 
  class brand 
  {
      private $db;
      private $fm;
      public function __construct()
      {
          $this->db = new Database();
          $this->fm = new Format();
      }

      //thêm danh mục 
      public function insert_brand($name){
        $brandName = $name['name'];
        $brandName = $this->fm->validation($brandName);
        $brandName = mysqli_real_escape_string($this->db->link, $brandName);
          if(empty($brandName)) {
              $alert = '<span style="color:red;">Thương hiệu không được bỏ trống !</span>';
              return $alert;
          }
          else{
              $query = "INSERT INTO brand(brand_name) VALUE('$brandName') ";
              $result = $this->db->insert($query);
              if ($result) {
                  $alert = "<span style='color:blue;'>Thêm thương hiệu sản phẩm thành công!!</span>";
                  return $alert;
              }else {
                  $alert = "<span style='color:red;'>Thêm danh mục sản phẩm thất bại!!</span>";
                  return $alert;
              }
          }
      }
      
      
  }
  
?>