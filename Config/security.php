<?php

return [
    'roles' => [
        'Guest' => 0,
        'User' => 1,
        'Admin' => 2
    ],
    'securedroutes' => [
        'admin' => ['Admin'],
        'admin-users' => ['Admin'],
        'admin-users-delete' => ['Admin'],
        'admin-users-edit' => ['Admin'],
        'admin-blog' => ['Admin'],
        'admin-blog-delete' => ['Admin'],
        'admin-blog-edit' => ['Admin'],
    ],
    'loginroute' => 'login',
    'checklogin' => 'Auth_Login:checkLogin'
];
