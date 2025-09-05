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
            'product-detail' => 'product_detail.php',
            'products' => 'products.php',
            'cart' => 'cart.php',
            'checkout' => 'checkout.php',
            'login' => 'login.php',
            'register' => 'register.php',
            'logout' => 'logout.php',
            'account' => 'account.php',
            'contact' => 'contact.php',
            'about' => 'about.php',
            'track-order' => 'track_order.php',
      ],
];
