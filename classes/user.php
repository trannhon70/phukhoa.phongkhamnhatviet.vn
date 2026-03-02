
<?php
$filepath = realpath(dirname(__FILE__));

include_once($filepath . '/../lib/session.php');
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

?>
<?php
class users
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function login_user($user_name, $password)
    {
        $user_name = $this->fm->validation($user_name);
        $password = $this->fm->validation($password);

        $user_name = mysqli_real_escape_string($this->db->link, $user_name);
        $password = mysqli_real_escape_string($this->db->link, md5($password));

        if (empty($user_name) || empty($password)) {
            $alert = "Tài khoản và mật khẩu không được bỏ trống !";
            return $alert;
        } else {

            $query = "SELECT * FROM admin_user WHERE user_name = '$user_name' AND password = '$password' LiMIT 1";
            $result = $this->db->select($query);

            if ($result != false) {
                $value = $result->fetch_assoc();

                Session::set('admin_login', true);
                Session::set('id', $value['id']);
                Session::set('user_name', $value['user_name']);
                Session::set('full_name', $value['full_name']);
                Session::set('email', $value['email']);
                Session::set('role', $value['role_id']);
                Session::set('ma_user', $value['ma_user']);


                header('Location:index.php');
                exit();
            } else {
                $alert = "Tài khoản và mật khẩu không đúng !";
                return $alert;
            }
            // exit();
        }
    }


    function getPaginationUsers($limit, $offset,$hoTen)
    {
        if($hoTen !== ''){
            $query = "SELECT 
            admin_user.id AS user_id,
            admin_user.ma_user,
            admin_user.user_name,
            admin_user.full_name,
            admin_user.email,
            admin_user.role_id,
            admin_user.created_at,
            roles.name
        FROM `admin_user`
        JOIN admin_role roles ON admin_user.role_id = roles.id
        WHERE admin_user.role_id != 1 AND admin_user.full_name LIKE '%$hoTen%'
        ORDER BY admin_user.id DESC LIMIT $limit OFFSET $offset";
        }else {
            $query = "SELECT 
            admin_user.id AS user_id,
             admin_user.ma_user,
            admin_user.user_name,
            admin_user.full_name,
            admin_user.email,
            admin_user.role_id,
            admin_user.created_at,
            roles.name
        FROM `admin_user`
        JOIN admin_role roles ON admin_user.role_id = roles.id
        WHERE admin_user.role_id != 1
        ORDER BY admin_user.id DESC LIMIT $limit OFFSET $offset";
        }
       
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalCountUser($hoTen)
    {
        if($hoTen !== ''){
            $query = "SELECT COUNT(*) AS total FROM admin_user 
            WHERE admin_user.role_id != 1 AND admin_user.full_name LIKE '%$hoTen%'";
        }else {
            $query = "SELECT COUNT(*) AS total FROM admin_user WHERE admin_user.role_id != 1";
        }
       
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}

?>