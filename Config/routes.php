<?php
return [
    [404,
        'error/404', 'Error_PageError:notFound'],
    [500,
        'error/500', 'Error_ServerError:serverError'],

    ['home',
        '', 'Home_Home:index'], // empty = /

    ['pygments',
        'pygments/<#style>/highlight.css', 'Home_Home:loadPygStyle'],


    ['login',
        'auth/login', 'Auth_Login:index'],

    ['logout',
        'auth/logout', 'Auth_Login:logOut'],

    ['register',
        'auth/register', 'Auth_Register:index'],

    ['contact',
        'contact', 'Contact_Contact:index'],

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

    ['portfolio',
        'portfolio', 'Portfolio_Portfolio:index'],

    ['portfoliocategory',
        'portfolio/<#category>', 'Portfolio_Portfolio:getItemsByCategory'],

    ['portfolioitem',
        'portfolio/<#category>/<:id>/<#title>', 'Portfolio_Portfolio:getItemsBySlug'],

    ['admin',
        'admin', 'Admin_Dashboard:index'],

    ['admin-users',
        'admin/users', 'Admin_User:index'],

    ['admin-users-delete',
        'admin/users/delete/<:id>', 'Admin_User:delete'],

    ['admin-users-edit',
        'admin/users/edit/<:id>', 'Admin_User:edit'],

    ['admin-menu',
        'admin/menu', 'Admin_Menu:index'],

    ['admin-menu-delete',
        'admin/menu/delete/<:id>', 'Admin_Menu:delete'],

    ['admin-menu-edit',
        'admin/menu/edit/<:id>', 'Admin_Menu:edit'],

    ['admin-blog',
        'admin/blog', 'Admin_Blog:index'],

    ['admin-blog-delete',
        'admin/blog/delete/<:id>', 'Admin_Blog:delete'],

    ['admin-blog-edit',
        'admin/blog/edit/<:id>', 'Admin_Blog:edit'],

    ['admin-portfolio',
        'admin/portfolio', 'Admin_Portfolio:index'],

    ['admin-portfolio-delete',
        'admin/portfolio/delete/<:id>', 'Admin_Portfolio:delete'],

    ['admin-portfolio-edit',
        'admin/portfolio/edit/<:id>', 'Admin_Portfolio:edit'],

    ['admin-pf-categories',
        'admin/portfolio/categories', 'Admin_Portfolio:manageCategories'],

    ['admin-pf-categories-delete',
        'admin/portfolio/categories/delete/<:id>', 'Admin_Portfolio:deleteCategory'],

    ['admin-pf-categories-edit',
        'admin/portfolio/categories/edit/<:id>', 'Admin_Portfolio:editCategory'],
];
