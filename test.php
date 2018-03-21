<?php

    $config2 = [ 'host'     => '10.6.1.57',
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

    if ($result = $conn->query("SELECT * FROM ctdt_tu_dien_mon_hoc")) {
        $data = [];
        
        while ($myrow = $result->fetch_array(MYSQLI_ASSOC))
        {
            $data[] = $myrow;
        }

        echo "<pre>";
        print_r($data);
        $result->close();
    }
    

    $conn->close();

//var_dump($conn);

        