<?php
$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
if ($slug !== '') {
      $product = getProductBySlug($slug);
}
if (!$product && $id > 0) {
      $product = getProductById($id);
}
if (!$product) {
      echo '<main class="main"><section class="section"><div class="container"><div class="alert alert-warning">Product not found.</div></div></section></main>';
      return;
}
$title = (string)($product['title'] ?? 'Product');
$price = (float)($product['price'] ?? 0);
$discountPercent = isset($product['discount_percent']) && $product['discount_percent'] !== null ? (float)$product['discount_percent'] : null;
$sale = ($discountPercent !== null && $discountPercent > 0) ? max(0.0, $price * (1 - ($discountPercent / 100))) : null;
$inStock = (int)($product['stock_qty'] ?? 0) > 0;
$stockQty = max(0, (int)($product['stock_qty'] ?? 0));
$featuredImage = (string)($product['featured_image'] ?? '');
$images = getProductImages((int)$product['id']);
if ($featuredImage !== '') {
      array_unshift($images, $featuredImage);
}
if (empty($images)) {
      $images = ['assets/img/product/product-1.webp'];
}
$mainImage = $images[0];
if (strpos($mainImage, 'admin/') !== 0 && strpos($mainImage, '/admin') !== 0 && strpos($mainImage, 'assets/') !== 0) {
      $mainImage = 'admin/' . $mainImage;
}
$currencySymbol = isset($currentCurrencySymbol) ? (string)$currentCurrencySymbol : '$';
// Load sizes from variants for this product
$variantSizes = getVariantSizes((int)$product['id']);

?>
<main class="main">
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'] ?? '/', ENT_QUOTES); ?>?p=home">Home</a></li>
                              <li class="current">Product Details</li>
                        </ol>
                  </nav>
                  <h1><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h1>
            </div>
      </div>

      <section id="product-details" class="product-details section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row g-5">
                        <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
                              <div class="product-gallery">
                                    <div class="thumbnails-vertical">
                                          <div class="thumbnails-container">
                                                <?php foreach ($images as $index => $img) {
                                                      $active = $index === 0 ? ' active' : '';
                                                      $src = (strpos($img, 'admin/') === 0 || strpos($img, '/admin') === 0 || strpos($img, 'assets/') === 0) ? $img : ('admin/' . $img); ?>
                                                      <div class="thumbnail-item<?php echo $active; ?>" data-image="<?php echo htmlspecialchars($src, ENT_QUOTES); ?>">
                                                            <img src="admin/<?php echo htmlspecialchars($src, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" class="img-fluid">
                                                      </div>
                                                <?php } ?>
                                          </div>
                                    </div>

                                    <!-- Main Image -->
                                    <div class="main-image-wrapper">
                                          <div class="image-zoom-container">
                                                <img src="admin/<?php echo htmlspecialchars($mainImage, ENT_QUOTES); ?>"
                                                      alt="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" class="img-fluid main-image drift-zoom"
                                                      id="main-product-image"
                                                      data-zoom="<?php echo htmlspecialchars($mainImage, ENT_QUOTES); ?>">
                                                <div class="zoom-overlay">
                                                      <i class="bi bi-zoom-in"></i>
                                                </div>
                                          </div>
                                          <div class="image-nav">
                                                <button class="image-nav-btn prev-image">
                                                      <i class="bi bi-chevron-left"></i>
                                                </button>
                                                <button class="image-nav-btn next-image">
                                                      <i class="bi bi-chevron-right"></i>
                                                </button>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <!-- Product Info Column -->
                        <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                              <div class="product-info-wrapper" id="product-info-sticky">
                                    <!-- Product Meta -->
                                    <div class="product-meta">
                                          <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="product-category"><?php echo htmlspecialchars(getProductCategory((int)($product['id'] ?? 0)) ?? '—', ENT_QUOTES); ?></span>
                                                <div class="product-share">
                                                      <button class="share-btn" aria-label="Share product">
                                                            <i class="bi bi-share"></i>
                                                      </button>
                                                      <div class="share-dropdown">
                                                            <a href="#" aria-label="Share on Facebook"><i
                                                                        class="bi bi-facebook"></i></a>
                                                            <a href="#" aria-label="Share on Twitter"><i
                                                                        class="bi bi-twitter-x"></i></a>
                                                            <a href="#" aria-label="Share on Pinterest"><i
                                                                        class="bi bi-pinterest"></i></a>
                                                            <a href="#" aria-label="Share via Email"><i
                                                                        class="bi bi-envelope"></i></a>
                                                      </div>
                                                </div>
                                          </div>

                                          <h1 class="product-title"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h1>
                                          <div class="product-rating">
                                                <div class="stars">
                                                      <i class="bi bi-star-fill"></i>
                                                      <i class="bi bi-star-fill"></i>
                                                      <i class="bi bi-star-fill"></i>
                                                      <i class="bi bi-star-fill"></i>
                                                      <i class="bi bi-star-half"></i>
                                                      <span class="rating-value">4.5</span>
                                                </div>
                                                <a href="#reviews" class="rating-count">42 Reviews</a>
                                          </div>
                                    </div>

                                    <!-- Product Price -->
                                    <div class="product-price-container">
                                          <div class="price-wrapper">
                                                <?php if ($sale !== null && $sale > 0 && $sale < $price) { ?>
                                                      <span class="current-price"><?php echo $currencySymbol . number_format($sale, 2); ?></span>
                                                      <span class="original-price"><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                <?php } else { ?>
                                                      <span class="current-price"><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                <?php } ?>
                                          </div>
                                          <?php if ($sale !== null && $sale > 0 && $sale < $price) {
                                                $savePct = round((($price - $sale) / max(0.01, $price)) * 100); ?>
                                                <span class="discount-badge">Save <?php echo (int)$savePct; ?>%</span>
                                          <?php } ?>
                                          <div class="stock-info">
                                                <?php if ($inStock) { ?>
                                                      <i class="bi bi-check-circle-fill"></i>
                                                      <span>In Stock</span>
                                                      <span class="stock-count">(<?php echo (int)$stockQty; ?> items left)</span>
                                                <?php } else { ?>
                                                      <i class="bi bi-x-circle-fill"></i>
                                                      <span>Out of Stock</span>
                                                <?php } ?>
                                          </div>
                                    </div>

                                    <!-- Product Description -->
                                    <div class="product-short-description">
                                          <p><?php echo nl2br(htmlspecialchars((string)($product['description'] ?? ''), ENT_QUOTES)); ?></p>
                                    </div>

                                    <!-- Product Options -->
                                    <div class="product-options">
                                          <!-- Color Options -->
                                          <div class="option-group">
                                                <div class="option-header">
                                                      <h6 class="option-title">Color</h6>
                                                      <?php $variantColors = getVariantColors((int)($product['id'] ?? 0));
                                                      $firstColor = $variantColors[0]['name'] ?? ''; ?>
                                                      <span class="selected-option"><?php echo htmlspecialchars($firstColor !== '' ? $firstColor : '—', ENT_QUOTES); ?></span>
                                                </div>
                                                <div class="color-options">
                                                      <?php if (!empty($variantColors)) {
                                                            foreach ($variantColors as $idx => $col) {
                                                                  $active = $idx === 0 ? ' active' : '';
                                                                  $name = (string)($col['name'] ?? '');
                                                                  $hex = (string)($col['hex'] ?? '');
                                                                  $styleHex = $hex !== '' ? $hex : '#222'; ?>
                                                                  <div class="color-option<?php echo $active; ?>" data-color="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                                                                        style="background-color: <?php echo htmlspecialchars($styleHex, ENT_QUOTES); ?>;">
                                                                        <i class="bi bi-check"></i>
                                                                  </div>
                                                            <?php }
                                                      } else { ?>
                                                            <div class="color-option active" data-color="Default" style="background-color: #222;">
                                                                  <i class="bi bi-check"></i>
                                                            </div>
                                                      <?php } ?>
                                                </div>
                                          </div>

                                          <!-- Size Options -->
                                          <div class="option-group">
                                                <div class="option-header">
                                                      <h6 class="option-title">Size</h6>
                                                      <span class="selected-option"><?php echo htmlspecialchars($variantSizes[0] ?? '', ENT_QUOTES); ?></span>
                                                </div>
                                                <div class="size-options">
                                                      <?php if (!empty($variantSizes)) {
                                                            foreach ($variantSizes as $idx => $sz) {
                                                                  $active = $idx === 0 ? ' active' : ''; ?>
                                                                  <div class="size-option<?php echo $active; ?>" data-size="<?php echo htmlspecialchars($sz, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sz, ENT_QUOTES); ?></div>
                                                            <?php }
                                                      } else { ?>
                                                            <div class="size-option" data-size="S">S</div>
                                                            <div class="size-option active" data-size="M">M</div>
                                                            <div class="size-option" data-size="L">L</div>
                                                      <?php } ?>
                                                </div>
                                          </div>

                                          <!-- Quantity Selector -->
                                          <div class="option-group">
                                                <h6 class="option-title">Quantity</h6>
                                                <div class="quantity-selector">
                                                      <button class="quantity-btn decrease">
                                                            <i class="bi bi-dash"></i>
                                                      </button>
                                                      <input type="number" class="quantity-input" value="1"
                                                            min="1" max="<?php echo (int)max(1, $stockQty); ?>">
                                                      <button class="quantity-btn increase">
                                                            <i class="bi bi-plus"></i>
                                                      </button>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="product-actions">
                                          <button class="btn btn-primary add-to-cart-btn" data-product-id="<?php echo (int)($product['id'] ?? 0); ?>" <?php echo $inStock ? '' : ' disabled'; ?>>
                                                <i class="bi bi-cart-plus"></i> Add to Cart
                                          </button>
                                          <a class="btn btn-outline-primary buy-now-btn" href="<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout', ENT_QUOTES); ?>" <?php echo $inStock ? '' : ' aria-disabled="true"'; ?>>
                                                <i class="bi bi-lightning-fill"></i> Buy Now
                                          </a>
                                          <button class="btn btn-outline-secondary wishlist-btn"
                                                aria-label="Add to wishlist">
                                                <i class="bi bi-heart"></i>
                                          </button>
                                    </div>

                                    <!-- Delivery Options -->
                                    <div class="delivery-options">
                                          <div class="delivery-option">
                                                <i class="bi bi-truck"></i>
                                                <div>
                                                      <h6>Free Shipping</h6>
                                                      <p>On orders over $50</p>
                                                </div>
                                          </div>
                                          <div class="delivery-option">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <div>
                                                      <h6>30-Day Returns</h6>
                                                      <p>Hassle-free returns</p>
                                                </div>
                                          </div>
                                          <div class="delivery-option">
                                                <i class="bi bi-shield-check"></i>
                                                <div>
                                                      <h6>2-Year Warranty</h6>
                                                      <p>Full coverage</p>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>

                  <!-- Sticky Add to Cart Bar (appears on scroll) -->
                  <div class="sticky-add-to-cart">
                        <div class="container">
                              <div class="sticky-content">
                                    <div class="product-preview">
                                          <img src="admin/<?php echo htmlspecialchars($mainImage, ENT_QUOTES); ?>" alt="Product"
                                                class="product-thumbnail">
                                          <div class="product-info">
                                                <h5 class="product-title"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h5>
                                                <div class="product-price"><?php echo $currencySymbol . number_format(($sale !== null && $sale > 0 && $sale < $price) ? $sale : $price, 2); ?></div>
                                          </div>
                                    </div>
                                    <div class="sticky-actions">
                                          <div class="quantity-selector">
                                                <button class="quantity-btn decrease">
                                                      <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="quantity-input" value="1" min="1"
                                                      max="<?php echo (int)max(1, $stockQty); ?>">
                                                <button class="quantity-btn increase">
                                                      <i class="bi bi-plus"></i>
                                                </button>
                                          </div>
                                          <button class="btn btn-primary add-to-cart-btn" data-product-id="<?php echo (int)($product['id'] ?? 0); ?>" <?php echo $inStock ? '' : ' disabled'; ?>>
                                                <i class="bi bi-cart-plus"></i> Add to Cart
                                          </button>
                                    </div>
                              </div>
                        </div>
                  </div>

                  <!-- Product Details Accordion -->
                  <div class="row mt-5" data-aos="fade-up">
                        <div class="col-12">
                              <div class="product-details-accordion">
                                    <!-- Description Accordion -->
                                    <div class="accordion-item">
                                          <h2 class="accordion-header">
                                                <button class="accordion-button" type="button"
                                                      data-bs-toggle="collapse" data-bs-target="#description"
                                                      aria-expanded="true" aria-controls="description">
                                                      Product Description
                                                </button>
                                          </h2>
                                          <div id="description" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                      <div class="product-description">
                                                            <h4>Product Overview</h4>
                                                            <p><?php echo nl2br(htmlspecialchars((string)($product['description'] ?? ''), ENT_QUOTES)); ?></p>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Specifications Accordion -->
                                    <div class="accordion-item">
                                          <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                      data-bs-toggle="collapse" data-bs-target="#specifications"
                                                      aria-expanded="false" aria-controls="specifications">
                                                      Technical Specifications
                                                </button>
                                          </h2>
                                          <div id="specifications" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                      <div class="product-specifications">
                                                            <div class="row">
                                                                  <div class="col-md-6">
                                                                        <div class="specs-group">
                                                                              <h4>Technical Specifications</h4>
                                                                              <div class="specs-table">
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Connectivity</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                Bluetooth 5.0,
                                                                                                3.5mm jack</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Battery Life</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                Up to 30 hours
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Charging Time
                                                                                          </div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                3 hours</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Driver Size</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                40mm</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Frequency Response
                                                                                          </div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                20Hz - 20kHz</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Impedance</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                32 Ohm</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Weight</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                250g</div>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                  </div>

                                                                  <div class="col-md-6">
                                                                        <div class="specs-group">
                                                                              <h4>Features</h4>
                                                                              <div class="specs-table">
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Noise Cancellation
                                                                                          </div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                Active Noise
                                                                                                Cancellation (ANC)
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Controls</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                Touch controls,
                                                                                                Voice assistant
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Microphone</div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                Dual beamforming
                                                                                                microphones</div>
                                                                                    </div>
                                                                                    <div class="specs-row">
                                                                                          <div
                                                                                                class="specs-label">
                                                                                                Water Resistance
                                                                                          </div>
                                                                                          <div
                                                                                                class="specs-value">
                                                                                                IPX4 (splash
                                                                                                resistant)</div>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Reviews Accordion -->
                                    <div class="accordion-item" id="reviews">
                                          <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                      data-bs-toggle="collapse" data-bs-target="#reviewsContent"
                                                      aria-expanded="false" aria-controls="reviewsContent">
                                                      Customer Reviews (42)
                                                </button>
                                          </h2>
                                          <div id="reviewsContent" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                      <div class="product-reviews">
                                                            <div class="reviews-summary">
                                                                  <div class="row">
                                                                        <div class="col-lg-4">
                                                                              <div class="overall-rating">
                                                                                    <div class="rating-number">4.5
                                                                                    </div>
                                                                                    <div class="rating-stars">
                                                                                          <i
                                                                                                class="bi bi-star-fill"></i>
                                                                                          <i
                                                                                                class="bi bi-star-fill"></i>
                                                                                          <i
                                                                                                class="bi bi-star-fill"></i>
                                                                                          <i
                                                                                                class="bi bi-star-fill"></i>
                                                                                          <i
                                                                                                class="bi bi-star-half"></i>
                                                                                    </div>
                                                                                    <div class="rating-count">
                                                                                          Based on 42 reviews
                                                                                    </div>
                                                                              </div>
                                                                        </div>

                                                                        <div class="col-lg-8">
                                                                              <div class="rating-breakdown">
                                                                                    <div class="rating-bar">
                                                                                          <div
                                                                                                class="rating-label">
                                                                                                5 stars</div>
                                                                                          <div class="progress">
                                                                                                <div class="progress-bar"
                                                                                                      role="progressbar"
                                                                                                      style="width: 65%;"
                                                                                                      aria-valuenow="65"
                                                                                                      aria-valuemin="0"
                                                                                                      aria-valuemax="100">
                                                                                                </div>
                                                                                          </div>
                                                                                          <div
                                                                                                class="rating-count">
                                                                                                27</div>
                                                                                    </div>
                                                                                    <div class="rating-bar">
                                                                                          <div
                                                                                                class="rating-label">
                                                                                                4 stars</div>
                                                                                          <div class="progress">
                                                                                                <div class="progress-bar"
                                                                                                      role="progressbar"
                                                                                                      style="width: 25%;"
                                                                                                      aria-valuenow="25"
                                                                                                      aria-valuemin="0"
                                                                                                      aria-valuemax="100">
                                                                                                </div>
                                                                                          </div>
                                                                                          <div
                                                                                                class="rating-count">
                                                                                                10</div>
                                                                                    </div>
                                                                                    <div class="rating-bar">
                                                                                          <div
                                                                                                class="rating-label">
                                                                                                3 stars</div>
                                                                                          <div class="progress">
                                                                                                <div class="progress-bar"
                                                                                                      role="progressbar"
                                                                                                      style="width: 8%;"
                                                                                                      aria-valuenow="8"
                                                                                                      aria-valuemin="0"
                                                                                                      aria-valuemax="100">
                                                                                                </div>
                                                                                          </div>
                                                                                          <div
                                                                                                class="rating-count">
                                                                                                3</div>
                                                                                    </div>
                                                                                    <div class="rating-bar">
                                                                                          <div
                                                                                                class="rating-label">
                                                                                                2 stars</div>
                                                                                          <div class="progress">
                                                                                                <div class="progress-bar"
                                                                                                      role="progressbar"
                                                                                                      style="width: 2%;"
                                                                                                      aria-valuenow="2"
                                                                                                      aria-valuemin="0"
                                                                                                      aria-valuemax="100">
                                                                                                </div>
                                                                                          </div>
                                                                                          <div
                                                                                                class="rating-count">
                                                                                                1</div>
                                                                                    </div>
                                                                                    <div class="rating-bar">
                                                                                          <div
                                                                                                class="rating-label">
                                                                                                1 star</div>
                                                                                          <div class="progress">
                                                                                                <div class="progress-bar"
                                                                                                      role="progressbar"
                                                                                                      style="width: 2%;"
                                                                                                      aria-valuenow="2"
                                                                                                      aria-valuemin="0"
                                                                                                      aria-valuemax="100">
                                                                                                </div>
                                                                                          </div>
                                                                                          <div
                                                                                                class="rating-count">
                                                                                                1</div>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>

                                                            <div class="reviews-list">
                                                                  <!-- Review Item -->
                                                                  <div class="review-item">
                                                                        <div class="review-header">
                                                                              <div class="reviewer-info">
                                                                                    <img src="assets/img/person/person-m-1.webp"
                                                                                          alt="Reviewer"
                                                                                          class="reviewer-avatar">
                                                                                    <div>
                                                                                          <h5
                                                                                                class="reviewer-name">
                                                                                                John Doe</h5>
                                                                                          <div
                                                                                                class="review-date">
                                                                                                03/15/2024</div>
                                                                                    </div>
                                                                              </div>
                                                                              <div class="review-rating">
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                              </div>
                                                                        </div>
                                                                        <h5 class="review-title">Exceptional sound
                                                                              quality and comfort</h5>
                                                                        <div class="review-content">
                                                                              <p>Lorem ipsum dolor sit amet,
                                                                                    consectetur adipiscing elit.
                                                                                    Vestibulum at lacus congue,
                                                                                    suscipit elit nec, tincidunt
                                                                                    orci. Phasellus egestas nisi
                                                                                    vitae lectus imperdiet
                                                                                    venenatis. Suspendisse
                                                                                    vulputate quam diam, et
                                                                                    consectetur augue condimentum
                                                                                    in.</p>
                                                                        </div>
                                                                  </div><!-- End Review Item -->

                                                                  <!-- Review Item -->
                                                                  <div class="review-item">
                                                                        <div class="review-header">
                                                                              <div class="reviewer-info">
                                                                                    <img src="assets/img/person/person-f-2.webp"
                                                                                          alt="Reviewer"
                                                                                          class="reviewer-avatar">
                                                                                    <div>
                                                                                          <h5
                                                                                                class="reviewer-name">
                                                                                                Jane Smith</h5>
                                                                                          <div
                                                                                                class="review-date">
                                                                                                02/28/2024</div>
                                                                                    </div>
                                                                              </div>
                                                                              <div class="review-rating">
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i class="bi bi-star"></i>
                                                                              </div>
                                                                        </div>
                                                                        <h5 class="review-title">Great headphones,
                                                                              battery could be better</h5>
                                                                        <div class="review-content">
                                                                              <p>Lorem ipsum dolor sit amet,
                                                                                    consectetur adipiscing elit.
                                                                                    Vestibulum at lacus congue,
                                                                                    suscipit elit nec, tincidunt
                                                                                    orci. Phasellus egestas nisi
                                                                                    vitae lectus imperdiet
                                                                                    venenatis.</p>
                                                                        </div>
                                                                  </div><!-- End Review Item -->

                                                                  <!-- Review Item -->
                                                                  <div class="review-item">
                                                                        <div class="review-header">
                                                                              <div class="reviewer-info">
                                                                                    <img src="assets/img/person/person-m-3.webp"
                                                                                          alt="Reviewer"
                                                                                          class="reviewer-avatar">
                                                                                    <div>
                                                                                          <h5
                                                                                                class="reviewer-name">
                                                                                                Michael Johnson
                                                                                          </h5>
                                                                                          <div
                                                                                                class="review-date">
                                                                                                02/15/2024</div>
                                                                                    </div>
                                                                              </div>
                                                                              <div class="review-rating">
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-fill"></i>
                                                                                    <i
                                                                                          class="bi bi-star-half"></i>
                                                                              </div>
                                                                        </div>
                                                                        <h5 class="review-title">Impressive noise
                                                                              cancellation</h5>
                                                                        <div class="review-content">
                                                                              <p>Lorem ipsum dolor sit amet,
                                                                                    consectetur adipiscing elit.
                                                                                    Vestibulum at lacus congue,
                                                                                    suscipit elit nec, tincidunt
                                                                                    orci. Phasellus egestas nisi
                                                                                    vitae lectus imperdiet
                                                                                    venenatis. Suspendisse
                                                                                    vulputate quam diam.</p>
                                                                        </div>
                                                                  </div><!-- End Review Item -->
                                                            </div>

                                                            <div class="review-form-container mt-5">
                                                                  <h4>Write a Review</h4>
                                                                  <form class="review-form">
                                                                        <div class="rating-select mb-4">
                                                                              <label class="form-label">Your
                                                                                    Rating</label>
                                                                              <div class="star-rating">
                                                                                    <input type="radio" id="star5"
                                                                                          name="rating"
                                                                                          value="5"><label
                                                                                          for="star5"
                                                                                          title="5 stars"><i
                                                                                                class="bi bi-star-fill"></i></label>
                                                                                    <input type="radio" id="star4"
                                                                                          name="rating"
                                                                                          value="4"><label
                                                                                          for="star4"
                                                                                          title="4 stars"><i
                                                                                                class="bi bi-star-fill"></i></label>
                                                                                    <input type="radio" id="star3"
                                                                                          name="rating"
                                                                                          value="3"><label
                                                                                          for="star3"
                                                                                          title="3 stars"><i
                                                                                                class="bi bi-star-fill"></i></label>
                                                                                    <input type="radio" id="star2"
                                                                                          name="rating"
                                                                                          value="2"><label
                                                                                          for="star2"
                                                                                          title="2 stars"><i
                                                                                                class="bi bi-star-fill"></i></label>
                                                                                    <input type="radio" id="star1"
                                                                                          name="rating"
                                                                                          value="1"><label
                                                                                          for="star1"
                                                                                          title="1 star"><i
                                                                                                class="bi bi-star-fill"></i></label>
                                                                              </div>
                                                                        </div>

                                                                        <div class="row g-3 mb-3">
                                                                              <div class="col-md-6">
                                                                                    <label for="review-name"
                                                                                          class="form-label">Your
                                                                                          Name</label>
                                                                                    <input type="text"
                                                                                          class="form-control"
                                                                                          id="review-name"
                                                                                          required="">
                                                                              </div>
                                                                              <div class="col-md-6">
                                                                                    <label for="review-email"
                                                                                          class="form-label">Your
                                                                                          Email</label>
                                                                                    <input type="email"
                                                                                          class="form-control"
                                                                                          id="review-email"
                                                                                          required="">
                                                                              </div>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                              <label for="review-title"
                                                                                    class="form-label">Review
                                                                                    Title</label>
                                                                              <input type="text"
                                                                                    class="form-control"
                                                                                    id="review-title" required="">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                              <label for="review-content"
                                                                                    class="form-label">Your
                                                                                    Review</label>
                                                                              <textarea class="form-control"
                                                                                    id="review-content" rows="4"
                                                                                    required=""></textarea>
                                                                              <div class="form-text">Tell others
                                                                                    what you think about this
                                                                                    product. Be honest and
                                                                                    helpful!</div>
                                                                        </div>

                                                                        <div class="loading">Loading</div>
                                                                        <div class="error-message"></div>
                                                                        <div class="sent-message">Your review has
                                                                              been submitted. Thank you!</div>

                                                                        <div class="text-end">
                                                                              <button type="submit"
                                                                                    class="btn btn-primary">Submit
                                                                                    Review</button>
                                                                        </div>
                                                                  </form>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </section><!-- /Product Details Section -->

</main>