<?php header ('Content-type: application/json; charset=utf-8'); ?>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'vendor/autoload.php';
    use Ozdemir\Datatables\Datatables;
    use Ozdemir\Datatables\DB\MySQL;


    $config = [ 'host'     => 'localhost',
                'port'     => '3306',
                'username' => 'root',
                'password' => '123456',
                'database' => 'document' 
            ];

    
    $dt = new Datatables( new MySQL($config) );

    $dt->query("Select id, subj_code, subj_name, bookname, author, publishdate, publisher, document_note, `status`, storageat, note, `path` from document");

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


    $config2 = [ 
        'host'     => '10.6.1.57',
        'port'     => '3306',
        'username' => 'root',
        'password' => 'password',
        'database' => 'qlgd' 
    ];

    $conn = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn,"utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


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