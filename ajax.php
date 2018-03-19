<?php

    require_once 'vendor/autoload.php';
    use Ozdemir\Datatables\Datatables;
    use Ozdemir\Datatables\DB\MySQL;

    $config = [ 'host'     => 'localhost',
                'port'     => '3306',
                'username' => 'root',
                'password' => '123456',
                'database' => 'drupal' ];

    $dt = new Datatables( new MySQL($config) );

    $dt->query("Select uuid,uid from users");

    $dt->add('action', function($data){
        // return an edit link in new column action
        return "<a href='user.php?id=" . $data['uid'] . "'>edit</a>";
    });

    $dt->add('method', function($data){
        // return an edit link in new column action
        return "<a href='methode.php?id=" . $data['uid'] . "'>method</a>";
    });

    //var_dump(json_decode($dt->generate(), true)['data']);

    echo $dt->generate();