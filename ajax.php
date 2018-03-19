<?php header ('Content-type: application/json; charset=utf-8'); ?>
<?php

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

    $dt->add('action', function($data){
        //return an edit link in new column action
        return "<a href='user.php?id=" . $data['uid'] . "'>edit</a>";
    });

    $dt->add('method', function($data){
        //return an edit link in new column action
        return "<a href='methode.php?id=" . $data['uid'] . "'>method</a>";
    });

    $dt->add('control', function($data){
        //return an edit link in new column action
        return NULL;
    });

    //var_dump(json_decode($dt->generate(), true)['data']);

    echo $dt->generate();