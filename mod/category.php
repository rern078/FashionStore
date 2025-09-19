<?php
// Fetch categories and subcategories using global helper
$__catData = getCategories();
$__fe_categories = $__catData['categories'] ?? [];
$__fe_subcats_by_category = $__catData['subcatsByCategory'] ?? [];
$brands = getBrand();
$colors = getColors();

// Compute category filter (supports category slug via ?category=, or map subcategory via ?sub= to its parent category slug)
$filterCat = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
if ($filterCat === '' && isset($_GET['sub'])) {
      $subSlug = trim((string)$_GET['sub']);
      if ($subSlug !== '') {
            foreach ($__fe_subcats_by_category as $__fe_cat_id => $__fe_children) {
                  foreach ($__fe_children as $__fe_sc) {
                        if (strcasecmp((string)($__fe_sc['slug'] ?? ''), $subSlug) === 0) {
                              foreach ($__fe_categories as $__fe_cat) {
                                    if ((int)($__fe_cat['id'] ?? 0) === (int)$__fe_cat_id) {
                                          $filterCat = (string)($__fe_cat['slug'] ?? '');
                                          break;
                                    }
                              }
                              break 2;
                        }
                  }
            }
      }
}

// Pagination and product retrieval
$perPage = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$total = countActiveProducts($filterCat !== '' ? $filterCat : null);
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) {
      $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$products = getActiveProductsPaginated($perPage, $offset, $filterCat !== '' ? $filterCat : null);
if (empty($products) && $filterCat !== '') {
      $filterCat = '';
      $total = countActiveProducts(null);
      $totalPages = max(1, (int)ceil($total / $perPage));
      if ($page > $totalPages) {
            $page = $totalPages;
      }
      $offset = ($page - 1) * $perPage;
      $products = getActiveProductsPaginated($perPage, $offset, null);
}
$currencySymbol = isset($currentCurrencySymbol) ? (string)$currentCurrencySymbol : '$';
$baseUrl = (string)($__CONFIG['site']['base_url'] ?? '/');
?>
<main class="main">
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Category</li>
                        </ol>
                  </nav>
                  <h1>Category</h1>
            </div>
      </div>

      <div class="container">
            <div class="row">
                  <div class="col-lg-4 sidebar">
                        <div class="widgets-container">
                              <!-- Product Categories Widget -->
                              <div class="product-categories-widget widget-item">
                                    <h3 class="widget-title">Categories</h3>
                                    <ul class="category-tree list-unstyled mb-0">
                                          <?php foreach ($__fe_categories as $__fe_cat): ?>
                                                <?php
                                                $__fe_cat_id = (int)$__fe_cat['id'];
                                                $__fe_cat_name = htmlspecialchars($__fe_cat['name'], ENT_QUOTES);
                                                $__fe_cat_slug = htmlspecialchars($__fe_cat['slug'], ENT_QUOTES);
                                                $__fe_children = $__fe_subcats_by_category[$__fe_cat_id] ?? [];
                                                $__fe_has_children = !empty($__fe_children);
                                                $__fe_target_id = 'categories-' . $__fe_cat_id . '-subcategories';
                                                ?>
                                                <li class="category-item">
                                                      <div class="d-flex justify-content-between align-items-center category-header <?php echo $__fe_has_children ? 'collapsed' : ''; ?>" <?php if ($__fe_has_children): ?>
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#<?php echo $__fe_target_id; ?>"
                                                            aria-expanded="false"
                                                            aria-controls="<?php echo $__fe_target_id; ?>" <?php endif; ?>>
                                                            <a href="javascript:void(0)" class="category-link"><?php echo $__fe_cat_name; ?></a>
                                                            <?php if ($__fe_has_children): ?>
                                                                  <span class="category-toggle">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                        <i class="bi bi-chevron-up"></i>
                                                                  </span>
                                                            <?php endif; ?>
                                                      </div>
                                                      <?php if ($__fe_has_children): ?>
                                                            <ul id="<?php echo $__fe_target_id; ?>" class="subcategory-list list-unstyled collapse ps-3 mt-2">
                                                                  <?php foreach ($__fe_children as $__fe_sc): ?>
                                                                        <li><a href="?p=category&amp;sub=<?php echo htmlspecialchars($__fe_sc['slug'], ENT_QUOTES); ?>" class="subcategory-link"><?php echo htmlspecialchars($__fe_sc['name'], ENT_QUOTES); ?></a></li>
                                                                  <?php endforeach; ?>
                                                            </ul>
                                                      <?php endif; ?>
                                                </li>
                                          <?php endforeach; ?>
                                    </ul>

                              </div><!--/Product Categories Widget -->

                              <!-- Pricing Range Widget -->
                              <div class="pricing-range-widget widget-item">

                                    <h3 class="widget-title">Price Range</h3>

                                    <div class="price-range-container">
                                          <div class="current-range mb-3">
                                                <span class="min-price">$0</span>
                                                <span class="max-price float-end">$1000</span>
                                          </div>

                                          <div class="range-slider">
                                                <div class="slider-track"></div>
                                                <div class="slider-progress"></div>
                                                <input type="range" class="min-range" min="0" max="1000" value="0"
                                                      step="10">
                                                <input type="range" class="max-range" min="0" max="1000"
                                                      value="500" step="10">
                                          </div>

                                          <div class="price-inputs mt-3">
                                                <div class="row g-2">
                                                      <div class="col-6">
                                                            <div class="input-group input-group-sm">
                                                                  <span class="input-group-text">$</span>
                                                                  <input type="number"
                                                                        class="form-control min-price-input"
                                                                        placeholder="Min" min="0" max="1000"
                                                                        value="0" step="10">
                                                            </div>
                                                      </div>
                                                      <div class="col-6">
                                                            <div class="input-group input-group-sm">
                                                                  <span class="input-group-text">$</span>
                                                                  <input type="number"
                                                                        class="form-control max-price-input"
                                                                        placeholder="Max" min="0" max="1000"
                                                                        value="500" step="10">
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="filter-actions mt-3">
                                                <button type="button" class="btn btn-sm btn-primary w-100">Apply
                                                      Filter</button>
                                          </div>
                                    </div>

                              </div><!--/Pricing Range Widget -->

                              <!-- Brand Filter Widget -->
                              <h3 class="brand-filter-widget widget-item">Filter by Brand</h3>
                              <!--/Brand Filter Widget -->

                              <!-- Color Filter Widget -->
                              <div class="color-filter-widget widget-item">

                                    <h3 class="widget-title">Filter by Color</h3>

                                    <div class="color-filter-content">
                                          <div class="color-options">
                                                <?php if (!empty($colors)) { ?>
                                                      <?php foreach ($colors as $c) {
                                                            $cid = (int)$c['id'];
                                                            $cname = htmlspecialchars($c['name'] ?? '', ENT_QUOTES);
                                                            $chex = htmlspecialchars(strtoupper($c['hex'] ?? ''), ENT_QUOTES);
                                                      ?>
                                                            <div class="form-check color-option">
                                                                  <input class="form-check-input" type="checkbox" id="color-<?php echo $cid; ?>" name="color[]" value="<?php echo $cid; ?>">
                                                                  <label class="form-check-label" for="color-<?php echo $cid; ?>">
                                                                        <span class="color-swatch" style="background-color: <?php echo ($chex !== '' ? $chex : '#CCCCCC'); ?>;" title="<?php echo $cname; ?>"></span>
                                                                  </label>
                                                            </div>
                                                      <?php } ?>
                                                <?php } else { ?>
                                                      <div class="text-muted small">No colors found</div>
                                                <?php } ?>
                                          </div>

                                          <div class="filter-actions mt-3">
                                                <button type="button"
                                                      class="btn btn-sm btn-outline-secondary">Clear All</button>
                                                <button type="button" class="btn btn-sm btn-primary">Apply
                                                      Filter</button>
                                          </div>
                                    </div>

                              </div><!--/Color Filter Widget -->

                              <!-- Brand Filter Widget -->
                              <div class="brand-filter-widget widget-item">

                                    <h3 class="widget-title">Filter by Brand</h3>

                                    <div class="brand-filter-content">
                                          <div class="brand-search">
                                                <input type="text" class="form-control"
                                                      placeholder="Search brands...">
                                                <i class="bi bi-search"></i>
                                          </div>

                                          <div class="brand-list">
                                                <?php if (!empty($brands)) { ?>
                                                      <?php foreach ($brands as $b) {
                                                            $bid = (int)$b['id'];
                                                            $bname = htmlspecialchars($b['name'] ?? '', ENT_QUOTES);
                                                            $bslug = htmlspecialchars($b['slug'] ?? '', ENT_QUOTES);
                                                            $count = (int)($b['product_count'] ?? 0);
                                                      ?>
                                                            <div class="brand-item">
                                                                  <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="brand-<?php echo $bid; ?>" name="brand[]" value="<?php echo $bslug; ?>">
                                                                        <label class="form-check-label" for="brand-<?php echo $bid; ?>">
                                                                              <?php echo $bname; ?>
                                                                              <span class="brand-count"><?php echo $count > 0 ? '(' . $count . ')' : ''; ?></span>
                                                                        </label>
                                                                  </div>
                                                            </div>
                                                      <?php } ?>
                                                <?php } else { ?>
                                                      <div class="text-muted small">No brands found</div>
                                                <?php } ?>
                                          </div>

                                          <div class="brand-actions">
                                                <button class="btn btn-sm btn-outline-primary">Apply
                                                      Filter</button>
                                                <button class="btn btn-sm btn-link">Clear All</button>
                                          </div>
                                    </div>

                              </div><!--/Brand Filter Widget -->

                        </div>

                  </div>

                  <div class="col-lg-8">

                        <!-- Category Header Section -->
                        <section id="category-header" class="category-header section">

                              <div class="container" data-aos="fade-up">

                                    <!-- Filter and Sort Options -->
                                    <div class="filter-container mb-4" data-aos="fade-up" data-aos-delay="100">
                                          <div class="row g-3">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                      <div class="filter-item search-form">
                                                            <label for="productSearch" class="form-label">Search
                                                                  Products</label>
                                                            <div class="input-group">
                                                                  <input type="text" class="form-control"
                                                                        id="productSearch"
                                                                        placeholder="Search for products..."
                                                                        aria-label="Search for products">
                                                                  <button class="btn search-btn" type="button">
                                                                        <i class="bi bi-search"></i>
                                                                  </button>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-2">
                                                      <div class="filter-item">
                                                            <label for="priceRange" class="form-label">Price
                                                                  Range</label>
                                                            <select class="form-select" id="priceRange">
                                                                  <option selected="">All Prices</option>
                                                                  <option>Under $25</option>
                                                                  <option>$25 to $50</option>
                                                                  <option>$50 to $100</option>
                                                                  <option>$100 to $200</option>
                                                                  <option>$200 &amp; Above</option>
                                                            </select>
                                                      </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-2">
                                                      <div class="filter-item">
                                                            <label for="sortBy" class="form-label">Sort By</label>
                                                            <select class="form-select" id="sortBy">
                                                                  <option selected="">Featured</option>
                                                                  <option>Price: Low to High</option>
                                                                  <option>Price: High to Low</option>
                                                                  <option>Customer Rating</option>
                                                                  <option>Newest Arrivals</option>
                                                            </select>
                                                      </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                      <div class="filter-item">
                                                            <label class="form-label">View</label>
                                                            <div class="d-flex align-items-center">
                                                                  <div class="view-options me-3">
                                                                        <button type="button"
                                                                              class="btn view-btn active"
                                                                              data-view="grid"
                                                                              aria-label="Grid view">
                                                                              <i
                                                                                    class="bi bi-grid-3x3-gap-fill"></i>
                                                                        </button>
                                                                        <button type="button" class="btn view-btn"
                                                                              data-view="list"
                                                                              aria-label="List view">
                                                                              <i class="bi bi-list-ul"></i>
                                                                        </button>
                                                                  </div>
                                                                  <div class="items-per-page">
                                                                        <select class="form-select"
                                                                              id="itemsPerPage"
                                                                              aria-label="Items per page">
                                                                              <option value="12">12 per page
                                                                              </option>
                                                                              <option value="24">24 per page
                                                                              </option>
                                                                              <option value="48">48 per page
                                                                              </option>
                                                                              <option value="96">96 per page
                                                                              </option>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="row mt-3">
                                                <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                                                      <div class="active-filters">
                                                            <span class="active-filter-label">Active
                                                                  Filters:</span>
                                                            <div class="filter-tags">
                                                                  <span class="filter-tag">
                                                                        Electronics <button
                                                                              class="filter-remove"><i
                                                                                    class="bi bi-x"></i></button>
                                                                  </span>
                                                                  <span class="filter-tag">
                                                                        $50 to $100 <button
                                                                              class="filter-remove"><i
                                                                                    class="bi bi-x"></i></button>
                                                                  </span>
                                                                  <button class="clear-all-btn">Clear All</button>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>

                                    </div>

                              </div>

                        </section><!-- /Category Header Section -->

                        <!-- Category Product List Section -->
                        <section id="category-product-list" class="category-product-list section">
                              <div class="container" data-aos="fade-up" data-aos-delay="100">
                                    <div class="row gy-4">
                                          <?php if (!empty($products)) { ?>
                                                <?php foreach ($products as $p) {
                                                      $id = (int)($p['id'] ?? 0);
                                                      $name = (string)($p['title'] ?? 'Product');
                                                      $slug = (string)($p['slug'] ?? '');
                                                      $price = (float)($p['price'] ?? 0);
                                                      $discountPercent = isset($p['discount_percent']) && $p['discount_percent'] !== null ? (float)$p['discount_percent'] : null;
                                                      $sale = ($discountPercent !== null && $discountPercent > 0) ? max(0.0, $price * (1 - ($discountPercent / 100))) : null;
                                                      $image = (string)($p['featured_image'] ?? '');
                                                      $image = $image !== '' ? $image : 'assets/img/product/product-1.webp';
                                                      $isOnSale = $sale !== null && $sale > 0 && $sale < $price;
                                                      $href = $baseUrl . '?p=product-detail' . ($slug !== '' ? ('&slug=' . urlencode($slug)) : ($id > 0 ? ('&id=' . $id) : ''));
                                                ?>
                                                      <div class="col-lg-6">
                                                            <div class="product-box">
                                                                  <div class="product-thumb">
                                                                        <?php if ($isOnSale) { ?><span class="product-label product-label-sale">-<?php echo htmlspecialchars((string)$discountPercent, ENT_QUOTES); ?>%</span><?php } ?>
                                                                        <img src="admin/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" class="main-img" loading="lazy">
                                                                        <div class="product-overlay">
                                                                              <div class="product-quick-actions">
                                                                                    <button type="button" class="quick-action-btn"><i class="bi bi-heart"></i></button>
                                                                                    <button type="button" class="quick-action-btn"><i class="bi bi-arrow-repeat"></i></button>
                                                                                    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>" class="quick-action-btn" aria-label="View"><i class="bi bi-eye"></i></a>
                                                                              </div>
                                                                              <div class="add-to-cart-container">
                                                                                    <form method="post" action="<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=cart', ENT_QUOTES); ?>" style="display:inline;">
                                                                                          <input type="hidden" name="form" value="add_to_cart">
                                                                                          <input type="hidden" name="product_id" value="<?php echo (int)$id; ?>">
                                                                                          <input type="hidden" name="qty" value="1">
                                                                                          <button type="button" class="add-to-cart-btn" data-product-id="<?php echo (int)$id; ?>">Add to Cart</button>
                                                                                    </form>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-content">
                                                                        <div class="product-details">
                                                                              <h3 class="product-title"><a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES); ?></a></h3>
                                                                              <div class="product-price">
                                                                                    <?php if ($isOnSale) { ?>
                                                                                          <span class="original"><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                                                          <span class="sale"><?php echo $currencySymbol . number_format($sale, 2); ?></span>
                                                                                    <?php } else { ?>
                                                                                          <span><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                                                    <?php } ?>
                                                                              </div>
                                                                        </div>
                                                                        <div class="product-rating-container">
                                                                              <div class="rating-stars">
                                                                                    <i class="bi bi-star-fill"></i>
                                                                                    <i class="bi bi-star-fill"></i>
                                                                                    <i class="bi bi-star-fill"></i>
                                                                                    <i class="bi bi-star-fill"></i>
                                                                                    <i class="bi bi-star-half"></i>
                                                                              </div>
                                                                              <span class="rating-number">0.0</span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                <?php } ?>
                                          <?php } else { ?>
                                                <!-- Product 1 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <span class="product-label">New Season</span>
                                                                  <img src="assets/img/product/product-3.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="btn btn-primary">Add to
                                                                                    Cart</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Vestibulum
                                                                                    ante ipsum primis</a></h3>
                                                                        <div class="product-price">
                                                                              <span>$149.99</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star"></i>
                                                                        </div>
                                                                        <span class="rating-number">4.0</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option"
                                                                              style="background-color: #3b82f6;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #22c55e;"></span>
                                                                        <span class="color-option active"
                                                                              style="background-color: #f97316;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 1 -->

                                                <!-- Product 2 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <span
                                                                        class="product-label product-label-sale">-30%</span>
                                                                  <img src="assets/img/product/product-6.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="btn btn-primary">Add to
                                                                                    Cart</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Aliquam
                                                                                    tincidunt mauris eu risus</a></h3>
                                                                        <div class="product-price">
                                                                              <span class="original">$199.99</span>
                                                                              <span class="sale">$139.99</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-half"></i>
                                                                        </div>
                                                                        <span class="rating-number">4.5</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option"
                                                                              style="background-color: #0ea5e9;"></span>
                                                                        <span class="color-option active"
                                                                              style="background-color: #111827;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #a855f7;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 2 -->

                                                <!-- Product 3 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <img src="assets/img/product/product-9.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="btn btn-primary">Add to
                                                                                    Cart</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Cras
                                                                                    ornare tristique elit</a></h3>
                                                                        <div class="product-price">
                                                                              <span>$89.50</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star"></i>
                                                                              <i class="bi bi-star"></i>
                                                                        </div>
                                                                        <span class="rating-number">3.0</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option active"
                                                                              style="background-color: #ef4444;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #64748b;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #eab308;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 3 -->

                                                <!-- Product 4 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <img src="assets/img/product/product-11.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="btn btn-primary">Add to
                                                                                    Cart</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Integer
                                                                                    vitae libero ac risus</a></h3>
                                                                        <div class="product-price">
                                                                              <span>$119.00</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                        </div>
                                                                        <span class="rating-number">5.0</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option"
                                                                              style="background-color: #10b981;"></span>
                                                                        <span class="color-option active"
                                                                              style="background-color: #8b5cf6;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #ec4899;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 4 -->

                                                <!-- Product 5 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <span class="product-label product-label-sold">Sold
                                                                        Out</span>
                                                                  <img src="assets/img/product/product-2.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="add-to-cart-btn disabled">Sold
                                                                                    Out</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Donec eu
                                                                                    libero sit amet quam</a></h3>
                                                                        <div class="product-price">
                                                                              <span>$75.00</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-half"></i>
                                                                        </div>
                                                                        <span class="rating-number">4.7</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option active"
                                                                              style="background-color: #4b5563;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #e11d48;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #4f46e5;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 5 -->

                                                <!-- Product 6 -->
                                                <div class="col-lg-6">
                                                      <div class="product-box">
                                                            <div class="product-thumb">
                                                                  <span
                                                                        class="product-label product-label-hot">Hot</span>
                                                                  <img src="assets/img/product/product-12.webp"
                                                                        alt="Product Image" class="main-img"
                                                                        loading="lazy">
                                                                  <div class="product-overlay">
                                                                        <div class="product-quick-actions">
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-heart"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                              </button>
                                                                              <button type="button"
                                                                                    class="quick-action-btn">
                                                                                    <i class="bi bi-eye"></i>
                                                                              </button>
                                                                        </div>
                                                                        <div class="add-to-cart-container">
                                                                              <button type="button"
                                                                                    class="btn btn-primary">Add to
                                                                                    Cart</button>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="product-content">
                                                                  <div class="product-details">
                                                                        <h3 class="product-title"><a
                                                                                    href="product-details.html">Pellentesque
                                                                                    habitant morbi tristique</a></h3>
                                                                        <div class="product-price">
                                                                              <span>$64.95</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="product-rating-container">
                                                                        <div class="rating-stars">
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-fill"></i>
                                                                              <i class="bi bi-star-half"></i>
                                                                              <i class="bi bi-star"></i>
                                                                        </div>
                                                                        <span class="rating-number">3.6</span>
                                                                  </div>
                                                                  <div class="product-color-options">
                                                                        <span class="color-option"
                                                                              style="background-color: #eab308;"></span>
                                                                        <span class="color-option"
                                                                              style="background-color: #14b8a6;"></span>
                                                                        <span class="color-option active"
                                                                              style="background-color: #facc15;"></span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <!-- End Product 6 -->
                                          <?php } ?>
                                    </div>
                              </div>
                        </section><!-- /Category Product List Section -->

                        <!-- Category Pagination Section -->
                        <section id="category-pagination" class="category-pagination section">

                              <div class="container">
                                    <nav class="d-flex justify-content-center" aria-label="Page navigation">
                                          <ul>
                                                <?php if ($page > 1) { ?>
                                                      <li>
                                                            <a href="<?php echo htmlspecialchars($baseUrl . '?p=category' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . ($page - 1), ENT_QUOTES); ?>" aria-label="Previous page">
                                                                  <i class="bi bi-arrow-left"></i>
                                                                  <span class="d-none d-sm-inline">Previous</span>
                                                            </a>
                                                      </li>
                                                <?php } ?>

                                                <?php
                                                $window = 3;
                                                $start = max(1, $page - $window);
                                                $end = min($totalPages, $page + $window);
                                                if ($start > 1) {
                                                      echo '<li><a href="' . htmlspecialchars($baseUrl . '?p=category' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=1', ENT_QUOTES) . '">1</a></li>';
                                                      if ($start > 2) {
                                                            echo '<li class="ellipsis">...</li>';
                                                      }
                                                }
                                                for ($i = $start; $i <= $end; $i++) {
                                                      $active = $i === $page ? ' class="active"' : '';
                                                      echo '<li><a' . $active . ' href="' . htmlspecialchars($baseUrl . '?p=category' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . $i, ENT_QUOTES) . '">' . $i . '</a></li>';
                                                }
                                                if ($end < $totalPages) {
                                                      if ($end < $totalPages - 1) {
                                                            echo '<li class="ellipsis">...</li>';
                                                      }
                                                      echo '<li><a href="' . htmlspecialchars($baseUrl . '?p=category' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . $totalPages, ENT_QUOTES) . '">' . $totalPages . '</a></li>';
                                                }
                                                ?>

                                                <?php if ($page < $totalPages) { ?>
                                                      <li>
                                                            <a href="<?php echo htmlspecialchars($baseUrl . '?p=category' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . ($page + 1), ENT_QUOTES); ?>" aria-label="Next page">
                                                                  <span class="d-none d-sm-inline">Next</span>
                                                                  <i class="bi bi-arrow-right"></i>
                                                            </a>
                                                      </li>
                                                <?php } ?>
                                          </ul>
                                    </nav>
                              </div>
                        </section>
                  </div>
            </div>
      </div>
</main>