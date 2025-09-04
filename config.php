<?php
// Basic site configuration
return [
      'site' => [
            'name' => 'FashionStore',
            'base_url' => '/',
            'assets_url' => '/assets',
            'charset' => 'UTF-8',
            'timezone' => 'UTC',
      ],
      // Map of route => module file inside mod/
      'routes' => [
            '' => 'index.php',
            'home' => 'index.php',
            'category' => 'category.php',
            'product' => 'product_detail.php',
            'cart' => 'cart.php',
            'checkout' => 'checkout.php',
            'login' => 'login.php',
            'register' => 'register.php',
            'logout' => 'logout.php',
            'account' => 'account.php',
            'contact' => 'contact.php',
      ],
];
