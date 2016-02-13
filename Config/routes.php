<?php
return [
    ['home',
        '', 'Home_Home:index'], // empty = /

    ['login',
        'auth/login', 'Auth_Login:index'],


    ['logout',
        'auth/logout', 'Auth_Login:logOut'],

    ['register',
        'auth/register', 'Auth_Register:index'],

    ['blog',
        'blog', 'Blog_Blog:index'],

    ['blog-item',
        'blog/item/<:id>/<#title>', 'Blog_Blog:item'],

    ['blog-item-add',
        'blog/item/<:id>/<#title>/add', 'Blog_Blog:additem'],

    ['blog-item-edit',
        'blog/item/<:id>/<#title>/edit', 'Blog_Blog:edititem'],

    ['blog-item-remove',
        'blog/item/<:id>/<#title>/remove', 'Blog_Blog:removeitem'],

    ['admin',
        'admin/dashboard', 'Admin_Dashboard:index'],

    ['admin-users',
        'admin/users', 'Admin_User:index'],

    ['admin-users-delete',
        'admin/users/delete/<:id>', 'Admin_User:delete'],

    ['admin-users-edit',
        'admin/users/edit/<:id>', 'Admin_User:edit'],

    ['admin-blog',
        'admin/blog', 'Admin_Blog:index'],

    ['admin-blog-delete',
        'admin/blog/delete/<:id>', 'Admin_Blog:delete'],

    ['admin-blog-edit',
        'admin/blog/edit/<:id>', 'Admin_Blog:edit']
];
