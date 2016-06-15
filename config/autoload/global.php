<?php

return array(
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '5432',
                    'user'     => 'postgres',
                    'password' => 'root',
                    'dbname'   => 'ecommerce',
                )
            )
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            ),
        ),
    ),
);



//PDO Mysql
// return array(   
//     'doctrine' => array(
//         'connection' => array(
//             'orm_default' => array(
//                 'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
//                 'params' => array(
//                     'driver'   => 'pdo_mysql', 
//                     'host'     => 'localhost',
//                     'port'     => '3306',
//                     'user'     => 'root',
//                     'password' => 'password',
//                     'dbname'   => 'blog', 
//                 )
//             )
//        )
//     )
// );
