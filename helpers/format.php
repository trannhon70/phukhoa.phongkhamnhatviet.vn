<?php
/**
* Format Class
*/
class Format{
 public function formatDate($date){
    return date('F j, Y, g:i a', strtotime($date));
 }

 public function textShorten($text, $limit = 400){
    $text = $text. " ";
    $text = substr($text, 0, $limit);
    $text = substr($text, 0, strrpos($text, ' '));
    $text = $text."...";
    return $text;
 }

 public function validation($data){
   if(is_string($data)) {
      $data = trim($data);
      $data = stripcslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  } else {
      echo "Data is not a string!";
      return null; // hoặc trả về giá trị mặc định
  }
 }

 public function title(){
    $path = $_SERVER['SCRIPT_FILENAME'];
    $title = basename($path, '.php');
    //$title = str_replace('_', ' ', $title);
    if ($title == 'index') {
     $title = 'home';
    }elseif ($title == 'contact') {
     $title = 'contact';
    }
    return $title = ucfirst($title);
   }

   public function created_at(){
      date_default_timezone_set('Asia/Ho_Chi_Minh');
      $current_time = date('Y-m-d H:i:s');
      return $current_time;
   }
}



?>