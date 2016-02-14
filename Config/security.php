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

        'admin-portfolio' => ['Admin'],
        'admin-portfolio-delete' => ['Admin'],
        'admin-portfolio-edit' => ['Admin'],

        'admin-pf-categories' => ['Admin'],
        'admin-pf-categories-delete' => ['Admin'],
        'admin-pf-categories-edit' => ['Admin'],
    ],
    'loginroute' => 'login',
    'checklogin' => 'Auth_Login:checkLogin',
    'notauthroute' => 'home',
    'csrfsecret' => 'Y#^&FI*RF4!@*&gD7(hDj%Dasrt'
];
