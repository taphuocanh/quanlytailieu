<?php
header ('Content-type: application/json; charset=utf-8');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'configs.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


if (array_key_exists("login_user",$_SESSION) && $_SESSION['login_user'] != '') {
    $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    mysqli_set_charset($conn,"utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    
    $conn2 = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn2,"utf8");
    if ($conn2->connect_error) {
        die("Connection failed: " . $conn2->connect_error);
    } 
    
    $result = $conn2->query("SELECT nv_tu_dien_nhan_vien_id,nv_tu_dien_nhan_vien_ho_lot_vn, nv_tu_dien_nhan_vien_ten_vn FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_user = '" . $_SESSION['login_user'] . "'");
    $user = mysqli_fetch_assoc($result);




    
    if ( isset($_POST['documentid']) && isset($_POST['content']) && isset($_POST['commentid']) ) {
        if (isset($_POST['id']) && $_POST['id'] != NULL ) {
            $query = "UPDATE comments SET comment = '" . $_POST['content'] . "' WHERE id = " . $_POST['id'];
            $result = $conn->query($query);
            $query = "SELECT * FROM comments WHERE id = " . $_POST['id'];
        } else {
            $query = "INSERT INTO comments (id, userid, commentid, comment, documentid, createdate, updatedate)  VALUES (null, " . $user['nv_tu_dien_nhan_vien_id'] . ", " . ($_POST['commentid'] ? $_POST['commentid'] : 'null') . ", '" . $_POST['content'] . "', " . $_POST['documentid'] . ", " . time() . ", null)";
        
            $result = $conn->query($query);

            /** Send mail announcent */

            $last_id = $conn->insert_id;
            $query = "SELECT * FROM comments WHERE id = " . $last_id;
            
            
        }
        $result = $conn->query($query);
        $comment = mysqli_fetch_assoc($result);
        $comment['userid'] = $user['nv_tu_dien_nhan_vien_ho_lot_vn'] . " " . $user['nv_tu_dien_nhan_vien_ten_vn'];

        echo json_encode( $comment );
    } else {
        if (isset($_POST['id']) && $_POST['id'] != NULL ) {
            $query = "DELETE FROM comments WHERE id = " . $_POST['id'];
            $conn->query($query);
            $query = "UPDATE comments SET commentid = null WHERE commentid = " . $_POST['id'];
            $conn->query($query);
            echo '{}';
        }
    }
}




?>