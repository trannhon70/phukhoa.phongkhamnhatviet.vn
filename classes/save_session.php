<?php
session_start();
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once ($filepath . '/../lib/session.php');
include_once($filepath . '/../helpers/format.php');
$fm = new Format();
$db = new Database();





if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if (isset($_POST['khoa']) && isset($_POST['benh'])) {
        $khoa = $_POST['khoa'];
        $benh = $_POST['benh'];
        Session::set('khoa',$khoa);
        Session::set('benh',$benh);
        Session::set('page',1);
    } else {
        echo "Both khoa and benh must be provided.";
    }
}

?> 