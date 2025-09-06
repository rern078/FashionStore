<?php
$categories = getCategories();
$filterCat = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
if ($categoryId > 0 && $filterCat === '') {
      foreach ($categories['categories'] as $cat) {
            if ((int)($cat['id'] ?? 0) === $categoryId) {
                  $filterCat = (string)$cat['slug'];
                  break;
            }
      }
}
$baseUrl = (string)($__CONFIG['site']['base_url'] ?? '/');
?>
<main class="main">
      <section id="category-cards" class="category-cards section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                  <div class="category-tabs">
                        <ul class="nav justify-content-center" id="category-cards-tabs" role="tablist">
                              <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="category-cards-men-tab" data-bs-toggle="tab"
                                          data-bs-target="#category-cards-men-content" type="button" role="tab"
                                          aria-controls="category-cards-men-content" aria-selected="false">SHOP
                                          MEN</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="category-cards-women-tab"
                                          data-bs-toggle="tab" data-bs-target="#category-cards-women-content"
                                          type="button" role="tab" aria-controls="category-cards-women-content"
                                          aria-selected="true">SHOP WOMEN</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="category-cards-accesoires-tab"
                                          data-bs-toggle="tab" data-bs-target="#category-cards-accesoires-content"
                                          type="button" role="tab"
                                          aria-controls="category-cards-accesoires-content"
                                          aria-selected="false">SHOP ACCESSOIRCES</button>
                              </li>
                        </ul>
                  </div>

                  <div class="tab-content" id="category-cards-tabContent">
                        <!-- Men's Categories -->
                        <div class="tab-pane fade" id="category-cards-men-content" role="tabpanel"
                              aria-labelledby="category-cards-men-tab">
                              <div class="row g-4">
                                    <!-- Leather Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-m-11.webp"
                                                      alt="Men's Leather" class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      LEATHER <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Denim Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-m-12.webp" alt="Men's Denim"
                                                      class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      DENIM <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Swimwear Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-m-19.webp"
                                                      alt="Men's Swimwear" class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      SWIMWEAR <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <!-- Women's Categories -->
                        <div class="tab-pane fade show active" id="category-cards-women-content" role="tabpanel"
                              aria-labelledby="category-cards-women-tab">
                              <div class="row g-4">
                                    <!-- Dresses Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-f-11.webp"
                                                      alt="Women's Dresses" class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      DRESSES <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Tops Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-f-18.webp" alt="Women's Tops"
                                                      class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      TOPS <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Accessories Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-f-13.webp"
                                                      alt="Women's Accessories" class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      ACCESSORIES <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <!-- Kid's Categories -->
                        <div class="tab-pane fade" id="category-cards-accesoires-content" role="tabpanel"
                              aria-labelledby="category-cards-accesoires-tab">
                              <div class="row g-4">
                                    <!-- Boys Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-1.webp" alt="Boys Clothing"
                                                      class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      BOYS <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Girls Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-2.webp" alt="Girls Clothing"
                                                      class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      GIRLS <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>

                                    <!-- Toys Category -->
                                    <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="400">
                                          <div class="category-card">
                                                <img src="assets/img/product/product-3.webp" alt="Kids Toys"
                                                      class="img-fluid" loading="lazy">
                                                <a href="#" class="category-link">
                                                      TOYS <i class="bi bi-arrow-right"></i>
                                                </a>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>

            </div>

      </section>
      <section id="product-list" class="product-list section">
            <div class="container isotope-layout" data-aos="fade-up" data-aos-delay="100" data-default-filter="<?php echo $filterCat !== '' ? '.filter-' . htmlspecialchars($filterCat, ENT_QUOTES) : '*'; ?>"
                  data-layout="masonry" data-sort="original-order">
                  <div class="row">
                        <div class="col-12">
                              <div class="product-filters isotope-filters mb-5 d-flex justify-content-center"
                                    data-aos="fade-up">
                                    <ul class="d-flex flex-wrap gap-2 list-unstyled">
                                          <li class="<?php echo $filterCat === '' ? 'filter-active' : ''; ?>" data-filter="*"><a href="<?php echo htmlspecialchars($baseUrl . '?p=products', ENT_QUOTES); ?>">All</a></li>
                                          <?php foreach ($categories['categories'] as $category) { ?>
                                                <li class="<?php echo $filterCat === (string)$category['slug'] ? 'filter-active' : ''; ?>" data-filter=".filter-<?php echo htmlspecialchars($category['slug'], ENT_QUOTES); ?>"><a href="<?php echo htmlspecialchars($baseUrl . '?p=products&category_id=' . (int)$category['id'], ENT_QUOTES); ?>"><?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?></a></li>
                                          <?php } ?>
                                    </ul>
                              </div>
                        </div>
                  </div>

                  <div class="row product-container isotope-container" data-aos="fade-up" data-aos-delay="200">
                        <?php
                        $perPage = 8;
                        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                        // Use $filterCat computed at top (from slug or category_id)
                        $total = countActiveProducts($filterCat !== '' ? $filterCat : null);
                        $totalPages = max(1, (int)ceil($total / $perPage));
                        if ($page > $totalPages) {
                              $page = $totalPages;
                        }
                        $offset = ($page - 1) * $perPage;

                        $products = getActiveProductsPaginated($perPage, $offset, $filterCat !== '' ? $filterCat : null);

                        // Fallback: if category filter yields no products, show all
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
                        if (is_array($products) && !empty($products)) {
                              foreach ($products as $p) {
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
                                    <?php
                                    $catClasses = '';
                                    if (!empty($p['category_slugs'])) {
                                          $slugs = explode(',', (string)$p['category_slugs']);
                                          foreach ($slugs as $s) {
                                                $s = trim($s);
                                                if ($s !== '') {
                                                      $catClasses .= ' filter-' . htmlspecialchars($s, ENT_QUOTES);
                                                }
                                          }
                                    }
                                    ?>
                                    <div class="col-md-6 col-lg-3 product-item isotope-item<?php echo $catClasses; ?>">
                                          <div class="product-card">
                                                <div class="product-image">
                                                      <?php if ($isOnSale) { ?><span class="badge">Sale</span><?php } ?>
                                                      <img src="admin/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                                                            class="img-fluid main-img">
                                                      <img src="admin/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES); ?> Hover"
                                                            class="img-fluid hover-img">
                                                      <div class="product-overlay">
                                                            <a href="#" class="btn-cart"><i class="bi bi-cart-plus"></i> Add to Cart</a>
                                                            <div class="product-actions">
                                                                  <a href="#" class="action-btn"><i class="bi bi-heart"></i></a>
                                                                  <a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>" class="action-btn"><i class="bi bi-eye"></i></a>
                                                                  <a href="#" class="action-btn"><i class="bi bi-arrow-left-right"></i></a>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="product-info">
                                                      <h5 class="product-title"><a href="<?php echo htmlspecialchars($href, ENT_QUOTES); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES); ?></a></h5>
                                                      <div class="product-price">
                                                            <?php if ($isOnSale) { ?>
                                                                  <span class="current-price"><?php echo $currencySymbol . number_format($sale, 2); ?></span>
                                                                  <span class="old-price"><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                            <?php } else { ?>
                                                                  <span class="current-price"><?php echo $currencySymbol . number_format($price, 2); ?></span>
                                                            <?php } ?>
                                                      </div>
                                                      <div class="product-rating">
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-half"></i>
                                                            <span>(0)</span>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              <?php
                              }
                        } else {
                              ?>
                              <div class="col-12">
                                    <div class="alert alert-info text-center">No products found.</div>
                              </div>
                        <?php
                        }
                        ?>
                  </div>

                  <section id="product-pagination" class="category-pagination section">
                        <div class="container">
                              <nav class="d-flex justify-content-center" aria-label="Page navigation">
                                    <ul>
                                          <?php if ($page > 1) { ?>
                                                <li>
                                                      <a href="<?php echo htmlspecialchars($baseUrl . '?p=products' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . ($page - 1), ENT_QUOTES); ?>" aria-label="Previous page">
                                                            <i class="bi bi-arrow-left"></i>
                                                            <span class="d-none d-sm-inline">Previous</span>
                                                      </a>
                                                </li>
                                          <?php } ?>

                                          <?php
                                          // Render up to 7 page links centered around current page
                                          $window = 3;
                                          $start = max(1, $page - $window);
                                          $end = min($totalPages, $page + $window);
                                          if ($start > 1) {
                                                echo '<li><a href="' . htmlspecialchars($baseUrl . '?p=products' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=1', ENT_QUOTES) . '">1</a></li>';
                                                if ($start > 2) {
                                                      echo '<li class="ellipsis">...</li>';
                                                }
                                          }
                                          for ($i = $start; $i <= $end; $i++) {
                                                $active = $i === $page ? ' class="active"' : '';
                                                echo '<li><a' . $active . ' href="' . htmlspecialchars($baseUrl . '?p=products' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . $i, ENT_QUOTES) . '">' . $i . '</a></li>';
                                          }
                                          if ($end < $totalPages) {
                                                if ($end < $totalPages - 1) {
                                                      echo '<li class="ellipsis">...</li>';
                                                }
                                                echo '<li><a href="' . htmlspecialchars($baseUrl . '?p=products' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . $totalPages, ENT_QUOTES) . '">' . $totalPages . '</a></li>';
                                          }
                                          ?>

                                          <?php if ($page < $totalPages) { ?>
                                                <li>
                                                      <a href="<?php echo htmlspecialchars($baseUrl . '?p=products' . ($filterCat !== '' ? ('&category=' . urlencode($filterCat)) : '') . '&page=' . ($page + 1), ENT_QUOTES); ?>" aria-label="Next page">
                                                            <span class="d-none d-sm-inline">Next</span>
                                                            <i class="bi bi-arrow-right"></i>
                                                      </a>
                                                </li>
                                          <?php } ?>
                                    </ul>
                              </nav>
                        </div>
                  </section>
                  <div class="text-center mt-5" data-aos="fade-up">
                        <a href="category.html" class="view-all-btn">View All Products <i
                                    class="bi bi-arrow-right"></i></a>
                  </div>
            </div>
      </section>
</main>