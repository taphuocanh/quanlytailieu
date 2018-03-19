<?php


$file = file_get_contents("data.csv");
    
$array = explode("\n", $file); //Ngắt dòng bằng ký tự xuống hàng
foreach ($array as $key => $row) {
    $array[$key]= explode("|", $row); //Phân trường bằng ký tự xổ thẳng " | "
    if ($key >0 ) {
        $array[$key] = array_combine($array[0], $array[$key]);
    }
    if (!$array[$key]) {
        unset($array[$key]);
    }
}
$data = $array;

$config = [ 'host'     => 'localhost',
                'port'     => '3306',
                'username' => 'root',
                'password' => '123456',
                'database' => 'document' 
            ];


    $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    mysqli_set_charset($conn,"utf8");
    foreach ($data as $key => $row) {
        if ($key > 0 ) {
            if ($row['Tên sách đề nghị kiểm tra hoặc thay thế'] === 'undefined') {
                $row['Tên sách đề nghị kiểm tra hoặc thay thế'] = NULL;
            }
            if ($row['Nơi xb/ Nhà xb'] === 'undefined') {
                $row['Nơi xb/ Nhà xb'] = NULL;
            }
            if ($row['Mã môn học'] === 'undefined') {
                $row['Mã môn học'] = NULL;
            }
            if ($row['Giáo viên nhập'] === 'undefined') {
                $row['Giáo viên nhập'] = NULL;
            }
            if ($row['Tên sách'] === 'undefined') {
                $row['Tên sách'] = NULL;
            }
            if ($row['Tác giả'] === 'undefined') {
                $row['Tác giả'] = NULL;
            }
            if ($row['Năm xb'] == NULL) {
                $row['Năm xb'] = 0;
                //echo $row['Năm xb'] . ' - ' . $row['Tên sách'] . '<hr>';
            }
            if ($row['Ghi chú tài liệu'] === 'undefined') {
                $row['Ghi chú tài liệu'] = NULL;
            }
            if ($row['Tình trạng'] === 'undefined') {
                $row['Tình trạng'] = NULL;
            }
            if ($row['Nơi lưu trữ'] === 'undefined') {
                $row['Nơi lưu trữ'] = NULL;
            }
    
    
            $sql = "INSERT INTO document (id, subj_code, subj_name, typed, bookname, author, publishdate, publisher, document_note, `status`, storageat, note)
            VALUES (" . $row['Stt'] . ", '" . $row['Mã môn học'] . "', '" . $row['Tên môn học'] . "', '" . $row['Giáo viên nhập'] . "', '" . $row['Tên sách'] . "', '" . $row['Tác giả'] . "', " . intval($row['Năm xb']) . ", '" . $row['Nơi xb/ Nhà xb'] . "', '" . $row['Ghi chú tài liệu'] . "', '" . $row['Tình trạng'] . "', '" . $row['Nơi lưu trữ'] . "', '" . $row['Tên sách đề nghị kiểm tra hoặc thay thế'] . "')";
    
            if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully". "<hr>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "<hr>";
            }
        }
        
    }