
<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>

<?php 
  class Role 
  {
      private $db;
      private $fm;
      public function __construct()
      {
          $this->db = new Database();
          $this->fm = new Format();
      }

      //thêm danh mục 
    
      
      public function getAllRole(){
        $query = "SELECT * FROM admin_role WHERE 1";
        $result = $this->db->select($query);
        return $result;
        
      }
  }
  
?>