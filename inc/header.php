<?php

/** @var array $__CONFIG */
/** @var string $__ROUTE */
/** @var string $pageTitle */

$site = $__CONFIG['site'] ?? [];
$assetsUrl = $site['assets_url'] ?? '/assets';
$siteName = $site['name'] ?? 'Site';
$charset = $site['charset'] ?? 'UTF-8';

// Load currencies from DB for header dropdown
$currencyData = getCurrencies();
$currencies = $currencyData['currencies'] ?? [];
$currentCurrencyCode = $currencyData['currentCurrencyCode'] ?? 'USD';
$currentCurrencyLabel = $currencyData['currentCurrencyLabel'] ?? 'USD';
$currentCurrencySymbol = $currencyData['currentCurrencySymbol'] ?? '$';

// end currencies logic

?>
<!DOCTYPE html>
<html lang="en">

<head>
      <?php
      $titleOverride = __DIR__ . '/title.inc.php';
      if (is_file($titleOverride)) {
            include $titleOverride;
      }
      ?>
</head>

<body class="<?php echo htmlspecialchars($__ROUTE, ENT_QUOTES); ?>">
      <header id="header" class="header position-relative">
            <!-- Top Bar -->
            <div class="top-bar py-2 d-none d-lg-block">
                  <div class="container-fluid container-xl">
                        <div class="row align-items-center">
                              <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                          <div class="top-bar-item me-4">
                                                <i class="bi bi-telephone-fill me-2"></i>
                                                <span>Customer Support: </span>
                                                <a href="tel:+1234567890">+1 (234) 567-890</a>
                                          </div>
                                          <div class="top-bar-item">
                                                <i class="bi bi-envelope-fill me-2"></i>
                                                <a
                                                      href="/cdn-cgi/l/email-protection#1e6d6b6e6e716c6a5e7b667f736e727b307d7173"><span
                                                            class="__cf_email__"
                                                            data-cfemail="1c6f696c6c736e685c79647d716c7079327f7371">[email&#160;protected]</span></a>
                                          </div>
                                    </div>
                              </div>

                              <div class="col-lg-6">
                                    <div class="d-flex justify-content-end">
                                          <div class="top-bar-item me-4">
                                                <a href="track-order.html">
                                                      <i class="bi bi-truck me-2"></i>Track Order
                                                </a>
                                          </div>
                                          <div class="top-bar-item dropdown me-4">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                      <i class="bi bi-translate me-2"></i>English
                                                </a>
                                                <ul class="dropdown-menu">
                                                      <li><a class="dropdown-item" href="#"><i
                                                                        class="bi bi-check2 me-2 selected-icon"></i>English</a>
                                                      </li>
                                                      <li><a class="dropdown-item" href="#">Español</a></li>
                                                      <li><a class="dropdown-item" href="#">Français</a></li>
                                                      <li><a class="dropdown-item" href="#">Deutsch</a></li>
                                                </ul>
                                          </div>
                                          <div class="top-bar-item dropdown">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                      <i class="me-2"><?php echo $currentCurrencySymbol; ?></i><?php echo htmlspecialchars($currentCurrencyLabel, ENT_QUOTES); ?>
                                                </a>
                                                <ul class="dropdown-menu">
                                                      <?php if (!empty($currencies)) { ?>
                                                            <?php foreach ($currencies as $cur) {
                                                                  $isCurrent = strtoupper($currentCurrencyCode) === strtoupper($cur['code']); ?>
                                                                  <li>
                                                                        <a class="dropdown-item" href="?set_currency=<?php echo urlencode($cur['code']); ?>">
                                                                              <?php if ($isCurrent) { ?><i class="bi bi-check2 me-2 selected-icon"></i><?php } ?>
                                                                              <?php echo htmlspecialchars($cur['symbol'], ENT_QUOTES) . ' - ' . htmlspecialchars($cur['code'], ENT_QUOTES); ?>
                                                                        </a>
                                                                  </li>
                                                            <?php } ?>
                                                      <?php } else { ?>
                                                            <li><span class="dropdown-item-text text-muted">No currencies</span></li>
                                                      <?php } ?>
                                                </ul>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>

            <!-- Main Header -->
            <div class="main-header">
                  <div class="container-fluid container-xl">
                        <div class="header-container d-flex py-3 align-items-center justify-content-between">
                              <!-- Logo -->
                              <a href="/" class="logo d-flex align-items-center">
                                    <img src="assets/img/logo.png" alt="">
                                    <!-- <h1 class="sitename">Ch-Fashion<span>Store</span></h1> -->
                              </a>

                              <!-- Search -->
                              <form class="search-form desktop-search-form">
                                    <div class="input-group">
                                          <input type="text" class="form-control" placeholder="Search for products...">
                                          <button class="btn search-btn" type="submit">
                                                <i class="bi bi-search"></i>
                                          </button>
                                    </div>
                              </form>

                              <!-- Actions -->
                              <div class="header-actions d-flex align-items-center justify-content-end">

                                    <!-- Mobile Search Toggle -->
                                    <button class="header-action-btn mobile-search-toggle d-xl-none" type="button"
                                          data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false"
                                          aria-controls="mobileSearch">
                                          <i class="bi bi-search"></i>
                                    </button>

                                    <!-- Account -->
                                    <div class="dropdown account-dropdown">
                                          <button class="header-action-btn show" data-bs-toggle="dropdown">
                                                <i class="bi bi-person"></i>
                                                <span class="action-text d-none d-md-inline-block">Account</span>
                                          </button>
                                          <div class="dropdown-menu">
                                                <div class="dropdown-header">
                                                      <h6>Welcome to <span class="sitename">FashionStore</span></h6>
                                                      <p class="mb-0">Access account &amp; manage orders</p>
                                                </div>
                                                <div class="dropdown-body">
                                                      <a class="dropdown-item d-flex align-items-center"
                                                            href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=account">
                                                            <i class="bi bi-person-circle me-2"></i>
                                                            <span>My Profile</span>
                                                      </a>
                                                      <a class="dropdown-item d-flex align-items-center"
                                                            href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=orders">
                                                            <i class="bi bi-bag-check me-2"></i>
                                                            <span>My Orders</span>
                                                      </a>
                                                      <a class="dropdown-item d-flex align-items-center"
                                                            href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=wishlist">
                                                            <i class="bi bi-heart me-2"></i>
                                                            <span>My Wishlist</span>
                                                      </a>
                                                      <a class="dropdown-item d-flex align-items-center"
                                                            href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=returns">
                                                            <i class="bi bi-arrow-return-left me-2"></i>
                                                            <span>Returns &amp; Refunds</span>
                                                      </a>
                                                      <a class="dropdown-item d-flex align-items-center"
                                                            href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=settings">
                                                            <i class="bi bi-gear me-2"></i>
                                                            <span>Settings</span>
                                                      </a>
                                                </div>
                                                <div class="dropdown-footer">
                                                      <?php if (!empty($_SESSION['user'])) { ?>
                                                            <div class="mb-2 text-center">
                                                                  <small>Signed in as
                                                                        <strong><?php echo htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email'] ?? 'User', ENT_QUOTES); ?></strong>
                                                                  </small>
                                                            </div>
                                                            <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=logout" class="btn btn-outline-primary w-100">Sign Out</a>
                                                      <?php } else { ?>
                                                            <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=login" class="btn btn-primary w-100 mb-2">Sign
                                                                  In</a>
                                                            <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=register"
                                                                  class="btn btn-outline-primary w-100">Register</a>
                                                      <?php } ?>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Wishlist -->
                                    <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=wishlist" class="header-action-btn d-none d-md-flex">
                                          <i class="bi bi-heart"></i>
                                          <span class="action-text d-none d-md-inline-block">Wishlist</span>
                                          <span class="badge">0</span>
                                    </a>

                                    <!-- Cart -->
                                    <div class="dropdown cart-dropdown">
                                          <button class="header-action-btn" data-bs-toggle="dropdown">
                                                <i class="bi bi-cart3"></i>
                                                <span class="action-text d-none d-md-inline-block">Cart</span>
                                                <span class="badge">3</span>
                                          </button>
                                          <div class="dropdown-menu cart-dropdown-menu">
                                                <div class="dropdown-header">
                                                      <h6>Shopping Cart (3)</h6>
                                                </div>
                                                <div class="dropdown-body">
                                                      <div class="cart-items">
                                                            <!-- Cart Item 1 -->
                                                            <div class="cart-item">
                                                                  <div class="cart-item-image">
                                                                        <img src="assets/img/product/product-1.webp"
                                                                              alt="Product" class="img-fluid">
                                                                  </div>
                                                                  <div class="cart-item-content">
                                                                        <h6 class="cart-item-title">Wireless Headphones
                                                                        </h6>
                                                                        <div class="cart-item-meta">1 × $89.99</div>
                                                                  </div>
                                                                  <button class="cart-item-remove">
                                                                        <i class="bi bi-x"></i>
                                                                  </button>
                                                            </div>

                                                            <!-- Cart Item 2 -->
                                                            <div class="cart-item">
                                                                  <div class="cart-item-image">
                                                                        <img src="assets/img/product/product-2.webp"
                                                                              alt="Product" class="img-fluid">
                                                                  </div>
                                                                  <div class="cart-item-content">
                                                                        <h6 class="cart-item-title">Smart Watch</h6>
                                                                        <div class="cart-item-meta">1 × $129.99</div>
                                                                  </div>
                                                                  <button class="cart-item-remove">
                                                                        <i class="bi bi-x"></i>
                                                                  </button>
                                                            </div>

                                                            <!-- Cart Item 3 -->
                                                            <div class="cart-item">
                                                                  <div class="cart-item-image">
                                                                        <img src="assets/img/product/product-3.webp"
                                                                              alt="Product" class="img-fluid">
                                                                  </div>
                                                                  <div class="cart-item-content">
                                                                        <h6 class="cart-item-title">Bluetooth Speaker
                                                                        </h6>
                                                                        <div class="cart-item-meta">1 × $59.99</div>
                                                                  </div>
                                                                  <button class="cart-item-remove">
                                                                        <i class="bi bi-x"></i>
                                                                  </button>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="dropdown-footer">
                                                      <div class="cart-total">
                                                            <span>Total:</span>
                                                            <span class="cart-total-price">$279.97</span>
                                                      </div>
                                                      <div class="cart-actions">
                                                            <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=cart" class="btn btn-outline-primary">View
                                                                  Cart</a>
                                                            <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=checkout" class="btn btn-primary">Checkout</a>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Mobile Navigation Toggle -->
                                    <i class="mobile-nav-toggle d-xl-none bi bi-list me-0"></i>

                              </div>
                        </div>
                  </div>
            </div>

            <!-- Navigation -->
            <div class="header-nav">
                  <div class="container-fluid container-xl position-relative">
                        <nav id="navmenu" class="navmenu">
                              <ul>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=home" class="<?php echo htmlspecialchars($__ROUTE == 'home' ? 'active' : '', ENT_QUOTES); ?>">Home</a></li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=category" class="<?php echo htmlspecialchars($__ROUTE == 'category' ? 'active' : '', ENT_QUOTES); ?>">Category</a></li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=product" class="<?php echo htmlspecialchars($__ROUTE == 'product' ? 'active' : '', ENT_QUOTES); ?>">Product Details</a></li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=cart" class="<?php echo htmlspecialchars($__ROUTE == 'cart' ? 'active' : '', ENT_QUOTES); ?>">Cart</a></li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=checkout" class="<?php echo htmlspecialchars($__ROUTE == 'checkout' ? 'active' : '', ENT_QUOTES); ?>">Checkout</a></li>

                                    <li class="products-megamenu-1"><a href="#"><span>Megamenu 1</span> <i
                                                      class="bi bi-chevron-down toggle-dropdown"></i></a>
                                          <ul class="mobile-megamenu">
                                                <li><a href="#">Featured Products</a></li>
                                                <li><a href="#">New Arrivals</a></li>
                                                <li><a href="#">Sale Items</a></li>
                                                <li class="dropdown"><a href="#"><span>Clothing</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Men's Wear</a></li>
                                                            <li><a href="#">Women's Wear</a></li>
                                                            <li><a href="#">Kids Collection</a></li>
                                                            <li><a href="#">Sportswear</a></li>
                                                            <li><a href="#">Accessories</a></li>
                                                      </ul>
                                                </li>

                                                <li class="dropdown"><a href="#"><span>Electronics</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Smartphones</a></li>
                                                            <li><a href="#">Laptops</a></li>
                                                            <li><a href="#">Audio Devices</a></li>
                                                            <li><a href="#">Smart Home</a></li>
                                                            <li><a href="#">Accessories</a></li>
                                                      </ul>
                                                </li>

                                                <li class="dropdown"><a href="#"><span>Home &amp; Living</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Furniture</a></li>
                                                            <li><a href="#">Decor</a></li>
                                                            <li><a href="#">Kitchen</a></li>
                                                            <li><a href="#">Bedding</a></li>
                                                            <li><a href="#">Lighting</a></li>
                                                      </ul>
                                                </li>

                                                <li class="dropdown"><a href="#"><span>Beauty</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Skincare</a></li>
                                                            <li><a href="#">Makeup</a></li>
                                                            <li><a href="#">Haircare</a></li>
                                                            <li><a href="#">Fragrances</a></li>
                                                            <li><a href="#">Personal Care</a></li>
                                                      </ul>
                                                </li>

                                          </ul>
                                          <div class="desktop-megamenu">
                                                <div class="megamenu-tabs">
                                                      <ul class="nav nav-tabs" id="productMegaMenuTabs" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link active" id="featured-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#featured-content-1862"
                                                                        type="button" aria-selected="true"
                                                                        role="tab">Featured</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link" id="new-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#new-content-1862" type="button"
                                                                        aria-selected="false" tabindex="-1"
                                                                        role="tab">New Arrivals</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link" id="sale-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#sale-content-1862"
                                                                        type="button" aria-selected="false"
                                                                        tabindex="-1" role="tab">Sale</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link" id="category-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#category-content-1862"
                                                                        type="button" aria-selected="false"
                                                                        tabindex="-1" role="tab">Categories</button>
                                                            </li>
                                                      </ul>
                                                </div>

                                                <!-- Tabs Content -->
                                                <div class="megamenu-content tab-content">

                                                      <!-- Featured Tab -->
                                                      <div class="tab-pane fade show active" id="featured-content-1862"
                                                            role="tabpanel" aria-labelledby="featured-tab">
                                                            <div class="product-grid">
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-1.webp"
                                                                                    alt="Featured Product"
                                                                                    loading="lazy">
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Premium Headphones</h5>
                                                                              <p class="price">$129.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-2.webp"
                                                                                    alt="Featured Product"
                                                                                    loading="lazy">
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Smart Watch</h5>
                                                                              <p class="price">$199.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-3.webp"
                                                                                    alt="Featured Product"
                                                                                    loading="lazy">
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Wireless Earbuds</h5>
                                                                              <p class="price">$89.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-4.webp"
                                                                                    alt="Featured Product"
                                                                                    loading="lazy">
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Bluetooth Speaker</h5>
                                                                              <p class="price">$79.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <!-- New Arrivals Tab -->
                                                      <div class="tab-pane fade" id="new-content-1862" role="tabpanel"
                                                            aria-labelledby="new-tab">
                                                            <div class="product-grid">
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-5.webp"
                                                                                    alt="New Arrival" loading="lazy">
                                                                              <span class="badge-new">New</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Fitness Tracker</h5>
                                                                              <p class="price">$69.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-6.webp"
                                                                                    alt="New Arrival" loading="lazy">
                                                                              <span class="badge-new">New</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Wireless Charger</h5>
                                                                              <p class="price">$39.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-7.webp"
                                                                                    alt="New Arrival" loading="lazy">
                                                                              <span class="badge-new">New</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Smart Bulb Set</h5>
                                                                              <p class="price">$49.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-8.webp"
                                                                                    alt="New Arrival" loading="lazy">
                                                                              <span class="badge-new">New</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Portable Power Bank</h5>
                                                                              <p class="price">$59.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <!-- Sale Tab -->
                                                      <div class="tab-pane fade" id="sale-content-1862" role="tabpanel"
                                                            aria-labelledby="sale-tab">
                                                            <div class="product-grid">
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-9.webp"
                                                                                    alt="Sale Product" loading="lazy">
                                                                              <span class="badge-sale">-30%</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Wireless Keyboard</h5>
                                                                              <p class="price"><span
                                                                                          class="original-price">$89.99</span>
                                                                                    $62.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-10.webp"
                                                                                    alt="Sale Product" loading="lazy">
                                                                              <span class="badge-sale">-25%</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Gaming Mouse</h5>
                                                                              <p class="price"><span
                                                                                          class="original-price">$59.99</span>
                                                                                    $44.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-11.webp"
                                                                                    alt="Sale Product" loading="lazy">
                                                                              <span class="badge-sale">-40%</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>Desk Lamp</h5>
                                                                              <p class="price"><span
                                                                                          class="original-price">$49.99</span>
                                                                                    $29.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-card">
                                                                        <div class="product-image">
                                                                              <img src="assets/img/product/product-12.webp"
                                                                                    alt="Sale Product" loading="lazy">
                                                                              <span class="badge-sale">-20%</span>
                                                                        </div>
                                                                        <div class="product-info">
                                                                              <h5>USB-C Hub</h5>
                                                                              <p class="price"><span
                                                                                          class="original-price">$39.99</span>
                                                                                    $31.99</p>
                                                                              <a href="#" class="btn-view">View
                                                                                    Product</a>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <!-- Categories Tab -->
                                                      <div class="tab-pane fade" id="category-content-1862"
                                                            role="tabpanel" aria-labelledby="category-tab">
                                                            <div class="category-grid">
                                                                  <div class="category-column">
                                                                        <h4>Clothing</h4>
                                                                        <ul>
                                                                              <li><a href="#">Men's Wear</a></li>
                                                                              <li><a href="#">Women's Wear</a></li>
                                                                              <li><a href="#">Kids Collection</a></li>
                                                                              <li><a href="#">Sportswear</a></li>
                                                                              <li><a href="#">Accessories</a></li>
                                                                        </ul>
                                                                  </div>
                                                                  <div class="category-column">
                                                                        <h4>Electronics</h4>
                                                                        <ul>
                                                                              <li><a href="#">Smartphones</a></li>
                                                                              <li><a href="#">Laptops</a></li>
                                                                              <li><a href="#">Audio Devices</a></li>
                                                                              <li><a href="#">Smart Home</a></li>
                                                                              <li><a href="#">Accessories</a></li>
                                                                        </ul>
                                                                  </div>
                                                                  <div class="category-column">
                                                                        <h4>Home &amp; Living</h4>
                                                                        <ul>
                                                                              <li><a href="#">Furniture</a></li>
                                                                              <li><a href="#">Decor</a></li>
                                                                              <li><a href="#">Kitchen</a></li>
                                                                              <li><a href="#">Bedding</a></li>
                                                                              <li><a href="#">Lighting</a></li>
                                                                        </ul>
                                                                  </div>
                                                                  <div class="category-column">
                                                                        <h4>Beauty</h4>
                                                                        <ul>
                                                                              <li><a href="#">Skincare</a></li>
                                                                              <li><a href="#">Makeup</a></li>
                                                                              <li><a href="#">Haircare</a></li>
                                                                              <li><a href="#">Fragrances</a></li>
                                                                              <li><a href="#">Personal Care</a></li>
                                                                        </ul>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                </div>

                                          </div>
                                    </li>
                                    <li class="products-megamenu-2"><a href="#"><span>Megamenu 2</span> <i
                                                      class="bi bi-chevron-down toggle-dropdown"></i></a>
                                          <ul class="mobile-megamenu">
                                                <li><a href="#">Women</a></li>
                                                <li><a href="#">Men</a></li>
                                                <li><a href="#">Kids'</a></li>
                                                <li class="dropdown"><a href="#"><span>Clothing</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Shirts &amp; Tops</a></li>
                                                            <li><a href="#">Coats &amp; Outerwear</a></li>
                                                            <li><a href="#">Underwear</a></li>
                                                            <li><a href="#">Sweatshirts</a></li>
                                                            <li><a href="#">Dresses</a></li>
                                                            <li><a href="#">Swimwear</a></li>
                                                      </ul>
                                                </li>
                                                <li class="dropdown"><a href="#"><span>Shoes</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Boots</a></li>
                                                            <li><a href="#">Sandals</a></li>
                                                            <li><a href="#">Heels</a></li>
                                                            <li><a href="#">Loafers</a></li>
                                                            <li><a href="#">Slippers</a></li>
                                                            <li><a href="#">Oxfords</a></li>
                                                      </ul>
                                                </li>

                                                <li class="dropdown"><a href="#"><span>Accessories</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Handbags</a></li>
                                                            <li><a href="#">Eyewear</a></li>
                                                            <li><a href="#">Hats</a></li>
                                                            <li><a href="#">Watches</a></li>
                                                            <li><a href="#">Jewelry</a></li>
                                                            <li><a href="#">Belts</a></li>
                                                      </ul>
                                                </li>

                                                <li class="dropdown"><a href="#"><span>Specialty Sizes</span> <i
                                                                  class="bi bi-chevron-down toggle-dropdown"></i></a>
                                                      <ul>
                                                            <li><a href="#">Plus Size</a></li>
                                                            <li><a href="#">Petite</a></li>
                                                            <li><a href="#">Wide Shoes</a></li>
                                                            <li><a href="#">Narrow Shoes</a></li>
                                                      </ul>
                                                </li>

                                          </ul>
                                          <div class="desktop-megamenu">

                                                <div class="megamenu-tabs">
                                                      <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link active" id="womens-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#womens-content-1883"
                                                                        type="button" aria-selected="true"
                                                                        role="tab">WOMEN</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link" id="mens-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#mens-content-1883"
                                                                        type="button" aria-selected="false"
                                                                        tabindex="-1" role="tab">MEN</button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                  <button class="nav-link" id="kids-tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="#kids-content-1883"
                                                                        type="button" aria-selected="false"
                                                                        tabindex="-1" role="tab">KIDS</button>
                                                            </li>
                                                      </ul>
                                                </div>
                                                <div class="megamenu-content tab-content">
                                                      <div class="tab-pane fade show active" id="womens-content-1883"
                                                            role="tabpanel" aria-labelledby="womens-tab">
                                                            <div class="category-layout">
                                                                  <div class="categories-section">
                                                                        <div class="category-headers">
                                                                              <h4>Clothing</h4>
                                                                              <h4>Shoes</h4>
                                                                              <h4>Accessories</h4>
                                                                              <h4>Specialty Sizes</h4>
                                                                        </div>

                                                                        <div class="category-links">
                                                                              <div class="link-row">
                                                                                    <a href="#">Shirts &amp; Tops</a>
                                                                                    <a href="#">Boots</a>
                                                                                    <a href="#">Handbags</a>
                                                                                    <a href="#">Plus Size</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Coats &amp;
                                                                                          Outerwear</a>
                                                                                    <a href="#">Sandals</a>
                                                                                    <a href="#">Eyewear</a>
                                                                                    <a href="#">Petite</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Underwear</a>
                                                                                    <a href="#">Heels</a>
                                                                                    <a href="#">Hats</a>
                                                                                    <a href="#">Wide Shoes</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Sweatshirts</a>
                                                                                    <a href="#">Loafers</a>
                                                                                    <a href="#">Watches</a>
                                                                                    <a href="#">Narrow Shoes</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Dresses</a>
                                                                                    <a href="#">Slippers</a>
                                                                                    <a href="#">Jewelry</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Swimwear</a>
                                                                                    <a href="#">Oxfords</a>
                                                                                    <a href="#">Belts</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="featured-section">
                                                                        <div class="featured-image">
                                                                              <img src="assets/img/product/product-f-1.webp"
                                                                                    alt="Women's Heels Collection">
                                                                              <div class="featured-content">
                                                                                    <h3>Women's<br>Bags<br>Collection
                                                                                    </h3>
                                                                                    <a href="#" class="btn-shop">Shop
                                                                                          now</a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="tab-pane fade" id="mens-content-1883" role="tabpanel"
                                                            aria-labelledby="mens-tab">
                                                            <div class="category-layout">
                                                                  <div class="categories-section">
                                                                        <div class="category-headers">
                                                                              <h4>Clothing</h4>
                                                                              <h4>Shoes</h4>
                                                                              <h4>Accessories</h4>
                                                                              <h4>Specialty Sizes</h4>
                                                                        </div>

                                                                        <div class="category-links">
                                                                              <div class="link-row">
                                                                                    <a href="#">Shirts &amp; Polos</a>
                                                                                    <a href="#">Sneakers</a>
                                                                                    <a href="#">Watches</a>
                                                                                    <a href="#">Big &amp; Tall</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Jackets &amp; Coats</a>
                                                                                    <a href="#">Boots</a>
                                                                                    <a href="#">Belts</a>
                                                                                    <a href="#">Slim Fit</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Underwear</a>
                                                                                    <a href="#">Loafers</a>
                                                                                    <a href="#">Ties</a>
                                                                                    <a href="#">Wide Shoes</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Hoodies</a>
                                                                                    <a href="#">Dress Shoes</a>
                                                                                    <a href="#">Wallets</a>
                                                                                    <a href="#">Extended Sizes</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Suits</a>
                                                                                    <a href="#">Sandals</a>
                                                                                    <a href="#">Sunglasses</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Activewear</a>
                                                                                    <a href="#">Slippers</a>
                                                                                    <a href="#">Hats</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="featured-section">
                                                                        <div class="featured-image">
                                                                              <img src="assets/img/product/product-m-4.webp"
                                                                                    alt="Men's Footwear Collection">
                                                                              <div class="featured-content">
                                                                                    <h3>Men's<br>Footwear<br>Collection
                                                                                    </h3>
                                                                                    <a href="#" class="btn-shop">Shop
                                                                                          now</a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <!-- Kids Tab -->
                                                      <div class="tab-pane fade" id="kids-content-1883" role="tabpanel"
                                                            aria-labelledby="kids-tab">
                                                            <div class="category-layout">
                                                                  <div class="categories-section">
                                                                        <div class="category-headers">
                                                                              <h4>Clothing</h4>
                                                                              <h4>Shoes</h4>
                                                                              <h4>Accessories</h4>
                                                                              <h4>By Age</h4>
                                                                        </div>

                                                                        <div class="category-links">
                                                                              <div class="link-row">
                                                                                    <a href="#">T-shirts &amp; Tops</a>
                                                                                    <a href="#">Sneakers</a>
                                                                                    <a href="#">Backpacks</a>
                                                                                    <a href="#">Babies (0-24 months)</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Outerwear</a>
                                                                                    <a href="#">Boots</a>
                                                                                    <a href="#">Hats &amp; Caps</a>
                                                                                    <a href="#">Toddlers (2-4 years)</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Pajamas</a>
                                                                                    <a href="#">Sandals</a>
                                                                                    <a href="#">Socks</a>
                                                                                    <a href="#">Kids (4-7 years)</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Sweatshirts</a>
                                                                                    <a href="#">Slippers</a>
                                                                                    <a href="#">Gloves</a>
                                                                                    <a href="#">Older Kids (8-14
                                                                                          years)</a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Dresses</a>
                                                                                    <a href="#">School Shoes</a>
                                                                                    <a href="#">Scarves</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">Swimwear</a>
                                                                                    <a href="#">Sports Shoes</a>
                                                                                    <a href="#">Hair Accessories</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                              <div class="link-row">
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#">View all</a>
                                                                                    <a href="#"></a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="featured-section">
                                                                        <div class="featured-image">
                                                                              <img src="assets/img/product/product-9.webp"
                                                                                    alt="Kids' New Arrivals">
                                                                              <div class="featured-content">
                                                                                    <h3>Kids<br>New<br>Arrivals</h3>
                                                                                    <a href="#" class="btn-shop">Shop
                                                                                          now</a>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                    </li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=about" class="<?php echo htmlspecialchars($__ROUTE == 'about' ? 'active' : '', ENT_QUOTES); ?>">About Us</a></li>
                                    <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=contact" class="<?php echo htmlspecialchars($__ROUTE == 'contact' ? 'active' : '', ENT_QUOTES); ?>">Contact Us</a></li>
                              </ul>
                        </nav>
                  </div>
            </div>

            <div class="announcement-bar py-2">
                  <div class="container-fluid container-xl">
                        <div class="announcement-slider swiper init-swiper">
                              <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
                              <script type="application/json" class="swiper-config">
                                    {
                                          "loop": true,
                                          "speed": 600,
                                          "autoplay": {
                                                "delay": 5000
                                          },
                                          "slidesPerView": 1,
                                          "effect": "slide",
                                          "direction": "vertical"
                                    }
                              </script>
                              <div class="swiper-wrapper">
                                    <div class="swiper-slide">🚚 Free shipping on orders over $50</div>
                                    <div class="swiper-slide">💰 30 days money back guarantee</div>
                                    <div class="swiper-slide">🎁 20% off on your first order - Use code: FIRST20</div>
                                    <div class="swiper-slide">⚡ Flash Sale! Up to 70% off on selected items</div>
                              </div>
                        </div>
                  </div>
            </div>
            <!-- Mobile Search Form -->
            <div class="collapse" id="mobileSearch">
                  <div class="container">
                        <form class="search-form">
                              <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for products...">
                                    <button class="btn search-btn" type="submit">
                                          <i class="bi bi-search"></i>
                                    </button>
                              </div>
                        </form>
                  </div>
            </div>
      </header>