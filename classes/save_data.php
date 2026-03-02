<?php
session_start();
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once ($filepath . '/../lib/session.php');
include_once($filepath . '/../helpers/format.php');
$fm = new Format();
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $name = $fm->validation($_POST['name']);
    $created_at = $fm->created_at();
    $sql = "INSERT INTO brand(brand_name, created_at) VALUES ('$name', '$created_at')";
    $result = $db->insert($sql);
    if ($result) {
        echo "Dữ liệu đã được lưu vào cơ sở dữ liệu thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $db->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['link'])) {
    $link = $_POST['link'];
    $link = $fm->validation($_POST['link']);
    $parts = explode('/', trim($link, '/'));
    Session::set('khoa',$parts[0]);
    Session::set('benh',$parts[1]);
    Session::set('page',1);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['benh'])) {
    $benh = $_POST['benh'];
    $benh = $fm->validation($_POST['benh']);
    Session::set('benh',$benh);
    Session::set('page',1);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['page'])) {
    $page = $_POST['page'];
    $page = $fm->validation($_POST['page']);
    Session::set('page',$page);
}

?> 