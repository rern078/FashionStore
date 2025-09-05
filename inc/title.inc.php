<?php
// Override $pageTitle per route if desired
if (!isset($pageTitle) || !$pageTitle) {
      $map = [
            '' => ($__CONFIG['site']['name'] ?? 'Home'),
            'home' => ($__CONFIG['site']['name'] ?? 'Home'),
            'category' => 'Shop by Category',
            'product' => 'Product Details',
            'cart' => 'Your Cart',
            'checkout' => 'Checkout',
            'login' => 'Login',
            'register' => 'Register',
            'account' => 'Your Account',
            'contact' => 'Contact Us',
      ];
      if (isset($map[$__ROUTE])) {
            $pageTitle = $map[$__ROUTE];
      }
}

?>
<meta charset="<?php echo htmlspecialchars($charset, ENT_QUOTES); ?>">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title><?php echo htmlspecialchars($pageTitle ?: $siteName, ENT_QUOTES); ?></title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="robots" content="noindex, nofollow">
<!-- Favicons -->
<link href="assets/img/favicon.png" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl, ENT_QUOTES); ?>/css/main.css">