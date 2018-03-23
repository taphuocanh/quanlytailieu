<?php 
session_start();

header ('Content-type: application/json; charset=utf-8'); ?>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'vendor/autoload.php';
    require 'configs.php';
    use Ozdemir\Datatables\Datatables;
    use Ozdemir\Datatables\DB\MySQL;
    

    $conn = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn,"utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $dt = new Datatables( new MySQL($config) );

    $query = "Select id, subj_code, subj_name, bookname, author, publishdate, publisher, document_note, `status`, storageat, note, `path` from document";

    if (array_key_exists("login_user",$_SESSION) && $_SESSION['login_user'] != '') {
        //Xem user thuộc bộ môn nào
        $result = $conn->query("SELECT nv_tu_dien_bo_mon_id FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_user = '" . $_SESSION['login_user'] . "'");
        //var_dump($result);
        $row = mysqli_fetch_assoc($result);
        $bomonid = $row['nv_tu_dien_bo_mon_id'];

        //Lấy danh sách môn học thuộc bộ môn
        $result = $conn->query("SELECT ctdt_tu_dien_mon_hoc_ma FROM ctdt_tu_dien_mon_hoc WHERE nv_tu_dien_bo_mon_id = " . $bomonid . "");
        
        while ($row = mysqli_fetch_assoc($result)) {
            $listmamonhoc[] = $row['ctdt_tu_dien_mon_hoc_ma'];
        }
        $query .= " WHERE subj_code IN ('" .  implode("','", $listmamonhoc) . "')";
        
    }
    
    

    $dt->query($query);

    $dt->edit('publishdate', function($data){
        // check if user has authorized to see that
        if ($data['publishdate'] == 0 ) {
            return 'Chưa xác định';
        } else {
            return $data['publishdate'];
        }
    });

    $dt->edit('note', function($data){
        // check if user has authorized to see that
        if ($data['note'] != '' ) {
            return "Tên sách đề nghị kiểm tra: " . $data['note'];
        } else {
            return "";
        }
        
    });

    $dt->add('control', function($data){
        //return an edit link in new column action
        return NULL;
    });



    $dt->add('mabomon', function($data){
        //return an edit link in new column action
        global $conn;
        $row = NULL;
        $result = $conn->query("SELECT nv_tu_dien_bo_mon_id FROM ctdt_tu_dien_mon_hoc WHERE ctdt_tu_dien_mon_hoc_ma LIKE '" . $data['subj_code'] . "'");
        //var_dump($result);
        $row = mysqli_fetch_assoc($result);
        if ($row["nv_tu_dien_bo_mon_id"] != '') {
            $result1 = $conn->query("SELECT nv_tu_dien_bo_mon_ma FROM nv_tu_dien_bo_mon WHERE nv_tu_dien_bo_mon_id = " . $row["nv_tu_dien_bo_mon_id"] . "");
            $row1 = mysqli_fetch_assoc($result1);
            return $row1["nv_tu_dien_bo_mon_ma"];
        } else {
            return NULL;
        }
    });
    $dt->add('tenbomon', function($data){
        //return an edit link in new column action
        global $conn;
        $row = NULL;
        if ($data['mabomon'] != NULL) {
            $result = $conn->query("SELECT nv_tu_dien_bo_mon_ten_vn FROM nv_tu_dien_bo_mon WHERE nv_tu_dien_bo_mon_ma = " . $data['mabomon'] . "");
            //var_dump($result);
            $row = mysqli_fetch_assoc($result);
            //var_dump($row);
            return $row["nv_tu_dien_bo_mon_ten_vn"];
        } else {
            return NULL;
        }
    });
    $dt->add('tendonvi', function($data){
        //return an edit link in new column action
        global $conn;
        $row = NULL;
        if ($data['mabomon'] != NULL) {
            $donviid = $conn->query("SELECT nv_tu_dien_don_vi_id FROM nv_tu_dien_bo_mon WHERE nv_tu_dien_bo_mon_ma = " . $data['mabomon'] . "");
            $donvi = mysqli_fetch_assoc($donviid);
            $result = $conn->query("SELECT nv_tu_dien_don_vi_ten_vn FROM nv_tu_dien_don_vi WHERE nv_tu_dien_don_vi_id = " . $donvi['nv_tu_dien_don_vi_id'] . "");
            //var_dump($result);
            $row = mysqli_fetch_assoc($result);
            //var_dump($row);
            return $row["nv_tu_dien_don_vi_ten_vn"];
        } else {
            return NULL;
        }
    });



    //$conn->close();



    //var_dump(json_decode($dt->generate(), true)['data']);

    echo $dt->generate();