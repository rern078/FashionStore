<?php

$contact = getContact();
$socialLinks = $contact['socialLinks'] ?? [];
$adminEmail = $contact['adminEmail'] ?? '';
$adminPhone = $contact['adminPhone'] ?? '';
$about = getAbout();
$aboutTitle = $about['title'] ?? '';
$aboutContent = $about['content'] ?? '';
$aboutImage = $about['image_url'] ?? '';
$defaultAddressInfo = getDefaultAddress();
$addressText = is_array($defaultAddressInfo) ? ($defaultAddressInfo[0] ?? '') : (string)$defaultAddressInfo;
$addressHours = is_array($defaultAddressInfo) ? (string)($defaultAddressInfo[1] ?? '') : '';

?>
<footer id="footer" class="footer light-background">
      <div class="footer-main">
            <div class="container">
                  <div class="row gy-4">
                        <div class="col-lg-4 col-md-6">
                              <div class="footer-widget footer-about">
                                    <a href="/" class="logo">
                                          <span class="sitename">FashionStore</span>
                                    </a>
                                    <p><?php echo htmlspecialchars((string)($aboutContent !== '' ? $aboutContent : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in nibh vehicula, facilisis magna ut, consectetur lorem. Proin eget tortor risus.'), ENT_QUOTES); ?></p>

                                    <div class="social-links mt-4">
                                          <h5>Connect With Us</h5>
                                          <div class="social-icons">
                                                <?php if (!empty($socialLinks)) { ?>
                                                      <?php foreach ($socialLinks as $s) {
                                                            $label = (string)($s['label'] !== '' ? $s['label'] : ucfirst((string)$s['platform']));
                                                            $iconClass = (string)(!empty($s['icon']) ? $s['icon'] : social_default_icon((string)$s['platform']));
                                                      ?>
                                                            <a href="<?php echo htmlspecialchars((string)$s['url'], ENT_QUOTES); ?>" target="_blank" rel="noopener" aria-label="<?php echo htmlspecialchars($label, ENT_QUOTES); ?>" title="<?php echo htmlspecialchars($label, ENT_QUOTES); ?>">
                                                                  <?php if ($iconClass !== '') { ?>
                                                                        <i class="<?php echo htmlspecialchars($iconClass, ENT_QUOTES); ?>"></i>
                                                                  <?php } else { ?>
                                                                        <span><?php echo htmlspecialchars($label, ENT_QUOTES); ?></span>
                                                                  <?php } ?>
                                                            </a>
                                                      <?php } ?>
                                                <?php } ?>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-6">
                              <div class="footer-widget">
                                    <h4>Shop</h4>
                                    <ul class="footer-links">
                                          <li><a href="category.html">New Arrivals</a></li>
                                          <li><a href="category.html">Bestsellers</a></li>
                                          <li><a href="category.html">Women's Clothing</a></li>
                                          <li><a href="category.html">Men's Clothing</a></li>
                                          <li><a href="category.html">Accessories</a></li>
                                          <li><a href="category.html">Sale</a></li>
                                    </ul>
                              </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-6">
                              <div class="footer-widget">
                                    <h4>Support</h4>
                                    <ul class="footer-links">
                                          <li><a href="support.html">Help Center</a></li>
                                          <li><a href="account.html">Order Status</a></li>
                                          <li><a href="shiping-info.html">Shipping Info</a></li>
                                          <li><a href="return-policy.html">Returns &amp; Exchanges</a></li>
                                          <li><a href="#">Size Guide</a></li>
                                          <li><a href="contact.html">Contact Us</a></li>
                                    </ul>
                              </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                              <div class="footer-widget">
                                    <h4>Contact Information</h4>
                                    <div class="footer-contact">
                                          <div class="contact-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span><?php echo htmlspecialchars($addressText, ENT_QUOTES); ?></span>
                                          </div>
                                          <div class="contact-item">
                                                <i class="bi bi-telephone"></i>
                                                <span><?php echo htmlspecialchars($adminPhone !== '' ? $adminPhone : '+855 967 797 762', ENT_QUOTES); ?></span>
                                          </div>
                                          <div class="contact-item">
                                                <i class="bi bi-envelope"></i>
                                                <span><?php if ($adminEmail !== '') { ?><a href="mailto:<?php echo htmlspecialchars($adminEmail, ENT_QUOTES); ?>"><?php echo htmlspecialchars($adminEmail, ENT_QUOTES); ?></a><?php } else { ?><a href="mailto:tiengchamrern2@gmail.com" class="__cf_email__">tiengchamrern2@gmail.com</a><?php } ?></span>
                                          </div>
                                          <div class="contact-item">
                                                <i class="bi bi-clock"></i>
                                                <span><?php echo nl2br(htmlspecialchars(($addressHours !== '' ? $addressHours : "Monday-Friday: 9am-6pm\nSaturday: 10am-4pm\nSunday: Closed"), ENT_QUOTES)); ?></span>
                                          </div>
                                    </div>

                                    <div class="app-buttons mt-4">
                                          <a href="#" class="app-btn">
                                                <i class="bi bi-apple"></i>
                                                <span>App Store</span>
                                          </a>
                                          <a href="#" class="app-btn">
                                                <i class="bi bi-google-play"></i>
                                                <span>Google Play</span>
                                          </a>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="footer-bottom">
            <div class="container">
                  <div class="row gy-3 align-items-center">
                        <div class="col-lg-6 col-md-12">
                              <div class="copyright">
                                    <p>© <span>Copyright</span> <strong class="sitename">MyWebsite</strong>. All
                                          Rights Reserved.</p>
                              </div>
                              <div class="credits mt-1">
                                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                              </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                              <div
                                    class="d-flex flex-wrap justify-content-lg-end justify-content-center align-items-center gap-4">
                                    <div class="payment-methods">
                                          <div class="payment-icons">
                                                <i class="bi bi-credit-card" aria-label="Credit Card"></i>
                                                <i class="bi bi-paypal" aria-label="PayPal"></i>
                                                <i class="bi bi-apple" aria-label="Apple Pay"></i>
                                                <i class="bi bi-google" aria-label="Google Pay"></i>
                                                <i class="bi bi-shop" aria-label="Shop Pay"></i>
                                                <i class="bi bi-cash" aria-label="Cash on Delivery"></i>
                                          </div>
                                    </div>

                                    <div class="legal-links">
                                          <a href="tos.html">Terms</a>
                                          <a href="privacy.html">Privacy</a>
                                          <a href="tos.html">Cookies</a>
                                    </div>
                              </div>
                        </div>
                  </div>

            </div>
      </div>
</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script src="<?php echo htmlspecialchars($assetsUrl, ENT_QUOTES); ?>/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
      (function() {
            document.addEventListener('click', function(e) {
                  var swatch = e.target.closest('.color-option');
                  if (!swatch) return;
                  var group = swatch.closest('.option-group');
                  if (!group) return;
                  var selected = group.querySelector('.selected-option');
                  var all = group.querySelectorAll('.color-option');
                  for (var i = 0; i < all.length; i++) {
                        all[i].classList.remove('active');
                  }
                  swatch.classList.add('active');
                  if (selected) {
                        var name = swatch.getAttribute('data-color') || swatch.textContent || '';
                        selected.textContent = name;
                  }
            });

            document.addEventListener('click', function(e) {
                  var sz = e.target.closest('.size-option');
                  if (!sz) return;
                  var group = sz.closest('.option-group');
                  if (!group) return;
                  var selected = group.querySelector('.selected-option');
                  var all = group.querySelectorAll('.size-option');
                  for (var i = 0; i < all.length; i++) {
                        all[i].classList.remove('active');
                  }
                  sz.classList.add('active');
                  if (selected) {
                        var name = sz.getAttribute('data-size') || sz.textContent || '';
                        selected.textContent = name;
                  }
            });

            // Add to cart submission
            document.addEventListener('click', function(e) {
                  var btn = e.target.closest('.add-to-cart-btn');
                  if (!btn) return;
                  e.preventDefault();
                  var root = btn.closest('.product-info-wrapper');
                  if (!root) root = document;
                  var colorActive = root.querySelector('.color-option.active');
                  var sizeActive = root.querySelector('.size-option.active');
                  var qtyInput = root.querySelector('.quantity-input');
                  var color = colorActive ? (colorActive.getAttribute('data-color') || '') : '';
                  var size = sizeActive ? (sizeActive.getAttribute('data-size') || '') : '';
                  var qty = qtyInput ? parseInt(qtyInput.value || '1', 10) : 1;
                  qty = isNaN(qty) || qty < 1 ? 1 : qty;
                  var productId = parseInt(btn.getAttribute('data-product-id') || '0', 10);

                  var form = document.createElement('form');
                  form.method = 'post';
                  form.action = '<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=cart', ENT_QUOTES); ?>';

                  var f = function(n, v) {
                        var i = document.createElement('input');
                        i.type = 'hidden';
                        i.name = n;
                        i.value = v;
                        form.appendChild(i);
                  };
                  f('form', 'add_to_cart');
                  f('product_id', String(productId));
                  f('color', color);
                  f('size', size);
                  f('qty', String(qty));

                  document.body.appendChild(form);
                  form.submit();
            });
      })();
</script>
</body>

</html>