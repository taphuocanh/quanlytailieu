<?php 
header ('Content-type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'configs.php';

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

if (isset($_POST['documentid']) || isset($_GET['documentid'])) {
    echo json_encode(get_comments());
}


    function get_comments($id = null) {
        global $conn;
        global $conn2;
        $data = [];
        $query = "SELECT * FROM comments WHERE ";
        if ($id == null) {
            $query .= "documentid = " . intval($_POST['documentid']) . " AND commentid IS NULL";
        } else {
            $query .= "commentid = " . $id; 
        }
        $query .= " ORDER BY createdate DESC";
        $comments = $conn->query($query);
        while ( $row = mysqli_fetch_assoc($comments) ) {
            $query2 = "SELECT nv_tu_dien_nhan_vien_ho_lot_vn, nv_tu_dien_nhan_vien_ten_vn FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_id = " . $row['userid'];
            $hoten = $conn2->query($query2);

            if ($hoten && $ten = mysqli_fetch_assoc($hoten) ) {
                $row['userid'] = $ten['nv_tu_dien_nhan_vien_ho_lot_vn'] . " " . $ten['nv_tu_dien_nhan_vien_ten_vn'];
            }
            $row['child'] = get_comments($row['id']);            
            $data[] = $row;               
        }
        
        return $data;
    }

?>