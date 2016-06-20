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
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => '\Auth\Entity\User',
                'identity_property' => 'login',
                'credential_property' => 'password',
            ),
        ),

    ),
    'acl' => array(
        'roles' => array(
            'UNAUTHENTICATED' => null,
            'EDITOR' => 'UNAUTHENTICATED',
            'ADMIN' => 'EDITOR'
        ),
        'resources' => array(
            'Admin\Controller\Marcas.index',
            'Admin\Controller\Marcas.save',
            'Admin\Controller\Marcas.delete',

            'Admin\Controller\SubCategorias.index',
            'Admin\Controller\SubCategorias.save',
            'Admin\Controller\SubCategorias.delete',

            'Admin\Controller\Categorias.index',
            'Admin\Controller\Categorias.save',
            'Admin\Controller\Categorias.delete',

            'Admin\Controller\TipoEnderecos.index',
            'Admin\Controller\TipoEnderecos.save',
            'Admin\Controller\TipoEnderecos.delete',

            'Admin\Controller\Enderecos.index',
            'Admin\Controller\Enderecos.save',
            'Admin\Controller\Enderecos.delete',

            'Admin\Controller\Produtos.index',
            'Admin\Controller\Produtos.save',
            'Admin\Controller\Produtos.delete',

            'Auth\Controller\Users.index',
            'Auth\Controller\Users.save',

            'Application\Controller\Auth.login',
            'Application\Controller\Auth.logout',
            'Application\Controller\Index.index',
            'Application\Controller\Index.logado',
            'Application\Controller\Auth.noauthorize',
        ),
        'privilege' => array(
            'UNAUTHENTICATED' => array(
                'allow' => array(
                    'Application\Controller\Auth.login',
                    'Application\Controller\Auth.logout',
                    'Admin\Controller\Enderecos.index',
                    'Admin\Controller\Enderecos.save',
                    'Admin\Controller\Enderecos.delete',
                    'Application\Controller\Index.index',
                    'Application\Controller\Auth.noauthorize',
                ),
            ),
            'EDITOR' => array(
                'allow' => array(
                    'Admin\Controller\Marcas.index',
                    'Admin\Controller\Marcas.save',
                    'Admin\Controller\Marcas.delete',

                    'Admin\Controller\SubCategorias.index',
                    'Admin\Controller\SubCategorias.save',
                    'Admin\Controller\SubCategorias.delete',

                    'Admin\Controller\Categorias.index',
                    'Admin\Controller\Categorias.save',
                    'Admin\Controller\Categorias.delete',

                    'Admin\Controller\TipoEnderecos.index',
                    'Admin\Controller\TipoEnderecos.save',
                    'Admin\Controller\TipoEnderecos.delete',

                    'Admin\Controller\Enderecos.index',
                    'Admin\Controller\Enderecos.save',
                    'Admin\Controller\Enderecos.delete',

                    'Admin\Controller\Produtos.index',
                    'Admin\Controller\Produtos.save',
                    'Admin\Controller\Produtos.delete',

                    'Auth\Controller\Users.index',
                    'Auth\Controller\Users.save',

                    'Application\Controller\Auth.login',
                    'Application\Controller\Auth.logout',
                    'Application\Controller\Index.logado',
                    'Application\Controller\Index.index',
                )
            ),
            'ADMIN' => array(
                'allow' => array(
                
                )
            ),
        )
    )//acl
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
