<?php

    session_start();

    require 'configs.php';

    $conn = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn,"utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $result = $conn->query("SELECT * FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_user = '" . $_POST['username'] . "' AND nv_tu_dien_nhan_vien_pass = '" . md5(md5($_POST['password'])) . "'");

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['login_user'] = $row['nv_tu_dien_nhan_vien_user'];
        echo json_encode(['status' => 'logged', 'username' => $row['nv_tu_dien_nhan_vien_user'] ]);
    } else {
        echo json_encode($_POST);
    }
    
?>