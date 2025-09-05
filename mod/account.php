<main class="main">
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Account</li>
                        </ol>
                  </nav>
                  <h1>Account</h1>
            </div>
      </div>
      <section id="account" class="account section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="sidebar-toggle d-lg-none mb-3">
                        <button class="btn btn-toggle" type="button" data-bs-toggle="collapse"
                              data-bs-target="#profileSidebar" aria-expanded="false"
                              aria-controls="profileSidebar">
                              <i class="bi bi-list me-2"></i> Profile Menu
                        </button>
                  </div>

                  <div class="row">
                        <div class="col-lg-3 profile-sidebar collapse d-lg-block" id="profileSidebar"
                              data-aos="fade-right" data-aos-delay="200">
                              <div class="profile-header">
                                    <div class="profile-avatar">
                                          <span>S</span>
                                    </div>
                                    <div class="profile-info">
                                          <h4>Sarah Anderson</h4>
                                          <div class="profile-bonus">
                                                <i class="bi bi-gift"></i>
                                                <span>100 bonuses available</span>
                                          </div>
                                    </div>
                              </div>

                              <div class="profile-nav">
                                    <ul class="nav flex-column" id="profileTabs" role="tablist">
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="orders-tab"
                                                      data-bs-toggle="tab" data-bs-target="#orders" type="button"
                                                      role="tab" aria-controls="orders" aria-selected="true">
                                                      <i class="bi bi-box-seam"></i>
                                                      <span>Orders</span>
                                                      <span class="badge">1</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab"
                                                      data-bs-target="#wishlist" type="button" role="tab"
                                                      aria-controls="wishlist" aria-selected="false">
                                                      <i class="bi bi-heart"></i>
                                                      <span>Wishlist</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab"
                                                      data-bs-target="#payment" type="button" role="tab"
                                                      aria-controls="payment" aria-selected="false">
                                                      <i class="bi bi-credit-card"></i>
                                                      <span>Payment methods</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                                      data-bs-target="#reviews" type="button" role="tab"
                                                      aria-controls="reviews" aria-selected="false">
                                                      <i class="bi bi-star"></i>
                                                      <span>My reviews</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="personal-tab" data-bs-toggle="tab"
                                                      data-bs-target="#personal" type="button" role="tab"
                                                      aria-controls="personal" aria-selected="false">
                                                      <i class="bi bi-person"></i>
                                                      <span>Personal info</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="addresses-tab" data-bs-toggle="tab"
                                                      data-bs-target="#addresses" type="button" role="tab"
                                                      aria-controls="addresses" aria-selected="false">
                                                      <i class="bi bi-geo-alt"></i>
                                                      <span>Addresses</span>
                                                </button>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="notifications-tab"
                                                      data-bs-toggle="tab" data-bs-target="#notifications"
                                                      type="button" role="tab" aria-controls="notifications"
                                                      aria-selected="false">
                                                      <i class="bi bi-bell"></i>
                                                      <span>Notifications</span>
                                                </button>
                                          </li>
                                    </ul>

                                    <h6 class="nav-section-title">Customer service</h6>
                                    <ul class="nav flex-column">
                                          <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                      <i class="bi bi-question-circle"></i>
                                                      <span>Help center</span>
                                                </a>
                                          </li>
                                          <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                      <i class="bi bi-file-text"></i>
                                                      <span>Terms and conditions</span>
                                                </a>
                                          </li>
                                          <li class="nav-item">
                                                <a href="#" class="nav-link logout">
                                                      <i class="bi bi-box-arrow-right"></i>
                                                      <span>Log out</span>
                                                </a>
                                          </li>
                                    </ul>
                              </div>
                        </div>

                        <div class="col-lg-9 profile-content" data-aos="fade-left" data-aos-delay="300">
                              <div class="tab-content" id="profileTabsContent">
                                    <!-- Orders Tab -->
                                    <div class="tab-pane fade show active" id="orders" role="tabpanel"
                                          aria-labelledby="orders-tab">
                                          <div class="tab-header">
                                                <h2>Orders</h2>
                                                <div class="tab-filters">
                                                      <div class="row">
                                                            <div class="col-md-6 mb-3 mb-md-0">
                                                                  <div class="dropdown">
                                                                        <button class="btn dropdown-toggle"
                                                                              type="button" id="statusFilter"
                                                                              data-bs-toggle="dropdown"
                                                                              aria-expanded="false">
                                                                              <span>Select status</span>
                                                                              <i class="bi bi-chevron-down"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu"
                                                                              aria-labelledby="statusFilter">
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">All
                                                                                          statuses</a></li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">In progress</a>
                                                                              </li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">Delivered</a>
                                                                              </li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">Canceled</a>
                                                                              </li>
                                                                        </ul>
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                  <div class="dropdown">
                                                                        <button class="btn dropdown-toggle"
                                                                              type="button" id="timeFilter"
                                                                              data-bs-toggle="dropdown"
                                                                              aria-expanded="false">
                                                                              <span>For all time</span>
                                                                              <i class="bi bi-chevron-down"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu"
                                                                              aria-labelledby="timeFilter">
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">For all
                                                                                          time</a></li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">Last 30
                                                                                          days</a></li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">Last 6
                                                                                          months</a></li>
                                                                              <li><a class="dropdown-item"
                                                                                          href="#">Last year</a>
                                                                              </li>
                                                                        </ul>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="orders-table">
                                                <div class="table-header">
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="sort-header">
                                                                        Order #
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                  <div class="sort-header">
                                                                        Order date
                                                                        <i class="bi bi-arrow-down-up"></i>
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                  <div class="sort-header">
                                                                        Status
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                  <div class="sort-header">
                                                                        Total
                                                                        <i class="bi bi-arrow-down-up"></i>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="order-items">
                                                      <!-- Order Item 1 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">78A6431D409</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">02/15/2025</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status in-progress">
                                                                              <span class="status-dot"></span>
                                                                              <span>In progress</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$2,105.90</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-1.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-2.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-3.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails1"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails1">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails1">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">02/15/2025</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                4589)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-1.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Lorem ipsum dolor
                                                                                                sit amet</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-001</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $899.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-2.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Consectetur
                                                                                                adipiscing elit
                                                                                          </h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-002</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      2</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $599.95</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-3.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Sed do eiusmod
                                                                                                tempor</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-003</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $129.99</div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$1,929.93</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$15.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$159.98</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$2,105.90</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>123 Main Street<br>Apt
                                                                                          4B<br>New York, NY
                                                                                          10001<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Express Delivery (2-3
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->

                                                      <!-- Order Item 2 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">47H76G09F33</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">12/10/2024</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status delivered">
                                                                              <span class="status-dot"></span>
                                                                              <span>Delivered</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$360.75</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-4.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails2"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails2">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails2">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">12/10/2024</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                7821)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-4.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Ut enim ad minim
                                                                                                veniam</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-004</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $329.99</div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$329.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$9.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$20.77</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$360.75</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>123 Main Street<br>Apt
                                                                                          4B<br>New York, NY
                                                                                          10001<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Standard Shipping (5-7
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->

                                                      <!-- Order Item 3 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">502TR872W2</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">11/05/2024</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status delivered">
                                                                              <span class="status-dot"></span>
                                                                              <span>Delivered</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$4,268.00</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-5.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-6.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-7.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <span class="more-products">+3</span>
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails3"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails3">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails3">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">11/05/2024</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                4589)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-5.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Quis nostrud
                                                                                                exercitation</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-005</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      2</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $1,299.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-6.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Ullamco laboris nisi
                                                                                          </h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-006</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $799.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-7.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Aliquip ex ea
                                                                                                commodo</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-007</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      3</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $449.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-8.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Duis aute irure
                                                                                                dolor</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-008</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $249.99</div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$3,899.94</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$29.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$338.07</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$4,268.00</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>456 Business Ave<br>Suite
                                                                                          200<br>San Francisco, CA
                                                                                          94107<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Premium Delivery (1-2
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->

                                                      <!-- Order Item 4 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">34VB5540K83</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">09/22/2024</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status canceled">
                                                                              <span class="status-dot"></span>
                                                                              <span>Canceled</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$987.50</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-8.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-9.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails4"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails4">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails4">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">09/22/2024</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                7821)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-8.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>In reprehenderit in
                                                                                                voluptate</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-008</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $499.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-9.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Velit esse cillum
                                                                                                dolore</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-009</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $399.99</div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$899.98</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$12.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$74.53</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$987.50</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>123 Main Street<br>Apt
                                                                                          4B<br>New York, NY
                                                                                          10001<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Standard Shipping (5-7
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->

                                                      <!-- Order Item 5 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">112P45A90V2</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">05/18/2024</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status delivered">
                                                                              <span class="status-dot"></span>
                                                                              <span>Delivered</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$53.00</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-10.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails5"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails5">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails5">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">05/18/2024</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                4589)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-10.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Eu fugiat nulla
                                                                                                pariatur</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-010</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">$49.99
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$49.99</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$0.00</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$3.01</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$53.00</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>123 Main Street<br>Apt
                                                                                          4B<br>New York, NY
                                                                                          10001<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Free Shipping (7-10
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->

                                                      <!-- Order Item 6 -->
                                                      <div class="order-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-md-3">
                                                                        <div class="order-id">28BA67U0981</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-date">04/03/2024</div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-status canceled">
                                                                              <span class="status-dot"></span>
                                                                              <span>Canceled</span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <div class="order-total">$1,029.50</div>
                                                                  </div>
                                                            </div>
                                                            <div class="order-products">
                                                                  <div class="product-thumbnails">
                                                                        <img src="assets/img/product/product-11.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                        <img src="assets/img/product/product-1-variant.webp"
                                                                              alt="Product" class="product-thumb"
                                                                              loading="lazy">
                                                                  </div>
                                                                  <button type="button" class="order-details-link"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderDetails6"
                                                                        aria-expanded="false"
                                                                        aria-controls="orderDetails6">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                  </button>
                                                            </div>
                                                            <div class="collapse order-details"
                                                                  id="orderDetails6">
                                                                  <div class="order-details-content">
                                                                        <div class="order-details-header">
                                                                              <h5>Order Details</h5>
                                                                              <div class="order-info">
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Order
                                                                                                Date:</span>
                                                                                          <span
                                                                                                class="info-value">04/03/2024</span>
                                                                                    </div>
                                                                                    <div class="info-item">
                                                                                          <span
                                                                                                class="info-label">Payment
                                                                                                Method:</span>
                                                                                          <span
                                                                                                class="info-value">Credit
                                                                                                Card (****
                                                                                                7821)</span>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-items-list">
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-11.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Excepteur sint
                                                                                                occaecat</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-011</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $599.99</div>
                                                                              </div>
                                                                              <div class="order-item-detail">
                                                                                    <div class="item-image">
                                                                                          <img src="assets/img/product/product-1-variant.webp"
                                                                                                alt="Product"
                                                                                                loading="lazy">
                                                                                    </div>
                                                                                    <div class="item-info">
                                                                                          <h6>Cupidatat non
                                                                                                proident</h6>
                                                                                          <div class="item-meta">
                                                                                                <span
                                                                                                      class="item-sku">SKU:
                                                                                                      PRD-001-V</span>
                                                                                                <span
                                                                                                      class="item-qty">Qty:
                                                                                                      1</span>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="item-price">
                                                                                          $349.99</div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="order-summary">
                                                                              <div class="summary-row">
                                                                                    <span>Subtotal:</span>
                                                                                    <span>$949.98</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Shipping:</span>
                                                                                    <span>$0.00</span>
                                                                              </div>
                                                                              <div class="summary-row">
                                                                                    <span>Tax:</span>
                                                                                    <span>$79.52</span>
                                                                              </div>
                                                                              <div class="summary-row total">
                                                                                    <span>Total:</span>
                                                                                    <span>$1,029.50</span>
                                                                              </div>
                                                                        </div>
                                                                        <div class="shipping-info">
                                                                              <div class="shipping-address">
                                                                                    <h6>Shipping Address</h6>
                                                                                    <p>456 Business Ave<br>Suite
                                                                                          200<br>San Francisco, CA
                                                                                          94107<br>United States
                                                                                    </p>
                                                                              </div>
                                                                              <div class="shipping-method">
                                                                                    <h6>Shipping Method</h6>
                                                                                    <p>Free Express Shipping (1-2
                                                                                          business days)</p>
                                                                              </div>
                                                                        </div>
                                                                        <div class="cancellation-info mt-3">
                                                                              <h6>Cancellation Reason</h6>
                                                                              <p>Order was canceled at customer's
                                                                                    request. Items were not in
                                                                                    stock at the time of
                                                                                    processing.</p>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Order Item -->
                                                </div>

                                                <div class="pagination-container">
                                                      <nav aria-label="Orders pagination">
                                                            <ul class="pagination">
                                                                  <li class="page-item active"><a
                                                                              class="page-link" href="#">1</a>
                                                                  </li>
                                                                  <li class="page-item"><a class="page-link"
                                                                              href="#">2</a></li>
                                                                  <li class="page-item"><a class="page-link"
                                                                              href="#">3</a></li>
                                                                  <li class="page-item"><a class="page-link"
                                                                              href="#">4</a></li>
                                                            </ul>
                                                      </nav>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Wishlist Tab -->
                                    <div class="tab-pane fade" id="wishlist" role="tabpanel"
                                          aria-labelledby="wishlist-tab">
                                          <div class="tab-header">
                                                <h2>Wishlist</h2>
                                          </div>
                                          <div class="wishlist-items">
                                                <div class="row">
                                                      <!-- Wishlist Item 1 -->
                                                      <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up"
                                                            data-aos-delay="100">
                                                            <div class="wishlist-item">
                                                                  <div class="wishlist-image">
                                                                        <img src="assets/img/product/product-1.webp"
                                                                              alt="Product" loading="lazy">
                                                                        <button class="remove-wishlist"
                                                                              type="button">
                                                                              <i class="bi bi-x-lg"></i>
                                                                        </button>
                                                                  </div>
                                                                  <div class="wishlist-content">
                                                                        <h5>Lorem ipsum dolor sit amet</h5>
                                                                        <div class="product-price">$129.99</div>
                                                                        <button class="btn btn-add-cart">Add to
                                                                              cart</button>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Wishlist Item -->

                                                      <!-- Wishlist Item 2 -->
                                                      <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up"
                                                            data-aos-delay="200">
                                                            <div class="wishlist-item">
                                                                  <div class="wishlist-image">
                                                                        <img src="assets/img/product/product-2.webp"
                                                                              alt="Product" loading="lazy">
                                                                        <button class="remove-wishlist"
                                                                              type="button">
                                                                              <i class="bi bi-x-lg"></i>
                                                                        </button>
                                                                  </div>
                                                                  <div class="wishlist-content">
                                                                        <h5>Consectetur adipiscing elit</h5>
                                                                        <div class="product-price">$89.50</div>
                                                                        <button class="btn btn-add-cart">Add to
                                                                              cart</button>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Wishlist Item -->

                                                      <!-- Wishlist Item 3 -->
                                                      <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up"
                                                            data-aos-delay="300">
                                                            <div class="wishlist-item">
                                                                  <div class="wishlist-image">
                                                                        <img src="assets/img/product/product-3.webp"
                                                                              alt="Product" loading="lazy">
                                                                        <button class="remove-wishlist"
                                                                              type="button">
                                                                              <i class="bi bi-x-lg"></i>
                                                                        </button>
                                                                  </div>
                                                                  <div class="wishlist-content">
                                                                        <h5>Sed do eiusmod tempor</h5>
                                                                        <div class="product-price">$199.99</div>
                                                                        <button class="btn btn-add-cart">Add to
                                                                              cart</button>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Wishlist Item -->
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Payment Methods Tab -->
                                    <div class="tab-pane fade" id="payment" role="tabpanel"
                                          aria-labelledby="payment-tab">
                                          <div class="tab-header">
                                                <h2>Payment Methods</h2>
                                                <button class="btn btn-add-payment" type="button">
                                                      <i class="bi bi-plus-lg"></i> Add payment method
                                                </button>
                                          </div>
                                          <div class="payment-methods">
                                                <!-- Payment Method 1 -->
                                                <div class="payment-method-item" data-aos="fade-up"
                                                      data-aos-delay="100">
                                                      <div class="payment-card">
                                                            <div class="card-type">
                                                                  <i class="bi bi-credit-card"></i>
                                                            </div>
                                                            <div class="card-info">
                                                                  <div class="card-number">**** **** **** 4589
                                                                  </div>
                                                                  <div class="card-expiry">Expires 09/2026</div>
                                                            </div>
                                                            <div class="card-actions">
                                                                  <button class="btn-edit-card" type="button">
                                                                        <i class="bi bi-pencil"></i>
                                                                  </button>
                                                                  <button class="btn-delete-card" type="button">
                                                                        <i class="bi bi-trash"></i>
                                                                  </button>
                                                            </div>
                                                      </div>
                                                      <div class="default-badge">Default</div>
                                                </div><!-- End Payment Method -->

                                                <!-- Payment Method 2 -->
                                                <div class="payment-method-item" data-aos="fade-up"
                                                      data-aos-delay="200">
                                                      <div class="payment-card">
                                                            <div class="card-type">
                                                                  <i class="bi bi-credit-card"></i>
                                                            </div>
                                                            <div class="card-info">
                                                                  <div class="card-number">**** **** **** 7821
                                                                  </div>
                                                                  <div class="card-expiry">Expires 05/2025</div>
                                                            </div>
                                                            <div class="card-actions">
                                                                  <button class="btn-edit-card" type="button">
                                                                        <i class="bi bi-pencil"></i>
                                                                  </button>
                                                                  <button class="btn-delete-card" type="button">
                                                                        <i class="bi bi-trash"></i>
                                                                  </button>
                                                            </div>
                                                      </div>
                                                      <button class="btn btn-sm btn-make-default"
                                                            type="button">Make default</button>
                                                </div><!-- End Payment Method -->
                                          </div>
                                    </div>

                                    <!-- Reviews Tab -->
                                    <div class="tab-pane fade" id="reviews" role="tabpanel"
                                          aria-labelledby="reviews-tab">
                                          <div class="tab-header">
                                                <h2>My Reviews</h2>
                                          </div>
                                          <div class="reviews-list">
                                                <!-- Review Item 1 -->
                                                <div class="review-item" data-aos="fade-up" data-aos-delay="100">
                                                      <div class="review-header">
                                                            <div class="review-product">
                                                                  <img src="assets/img/product/product-4.webp"
                                                                        alt="Product" class="product-image"
                                                                        loading="lazy">
                                                                  <div class="product-info">
                                                                        <h5>Lorem ipsum dolor sit amet</h5>
                                                                        <div class="review-date">Reviewed on
                                                                              01/15/2025</div>
                                                                  </div>
                                                            </div>
                                                            <div class="review-rating">
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star"></i>
                                                            </div>
                                                      </div>
                                                      <div class="review-content">
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing
                                                                  elit. Nullam auctor, nisl eget ultricies
                                                                  tincidunt, nisl nisl aliquam nisl, eget
                                                                  ultricies nisl nisl eget nisl.</p>
                                                      </div>
                                                      <div class="review-actions">
                                                            <button class="btn btn-sm btn-edit-review"
                                                                  type="button">Edit</button>
                                                            <button class="btn btn-sm btn-delete-review"
                                                                  type="button">Delete</button>
                                                      </div>
                                                </div><!-- End Review Item -->

                                                <!-- Review Item 2 -->
                                                <div class="review-item" data-aos="fade-up" data-aos-delay="200">
                                                      <div class="review-header">
                                                            <div class="review-product">
                                                                  <img src="assets/img/product/product-5.webp"
                                                                        alt="Product" class="product-image"
                                                                        loading="lazy">
                                                                  <div class="product-info">
                                                                        <h5>Consectetur adipiscing elit</h5>
                                                                        <div class="review-date">Reviewed on
                                                                              12/03/2024</div>
                                                                  </div>
                                                            </div>
                                                            <div class="review-rating">
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                                  <i class="bi bi-star-fill"></i>
                                                            </div>
                                                      </div>
                                                      <div class="review-content">
                                                            <p>Sed do eiusmod tempor incididunt ut labore et
                                                                  dolore magna aliqua. Ut enim ad minim veniam,
                                                                  quis nostrud exercitation ullamco laboris nisi
                                                                  ut aliquip ex ea commodo consequat.</p>
                                                      </div>
                                                      <div class="review-actions">
                                                            <button class="btn btn-sm btn-edit-review"
                                                                  type="button">Edit</button>
                                                            <button class="btn btn-sm btn-delete-review"
                                                                  type="button">Delete</button>
                                                      </div>
                                                </div><!-- End Review Item -->
                                          </div>
                                    </div>

                                    <!-- Personal Info Tab -->
                                    <div class="tab-pane fade" id="personal" role="tabpanel"
                                          aria-labelledby="personal-tab">
                                          <div class="tab-header">
                                                <h2>Personal Information</h2>
                                          </div>
                                          <div class="personal-info-form" data-aos="fade-up" data-aos-delay="100">
                                                <form class="php-email-form">
                                                      <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                  <label for="firstName" class="form-label">First
                                                                        Name</label>
                                                                  <input type="text" class="form-control"
                                                                        id="firstName" name="firstName"
                                                                        value="Lorem" required="">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                  <label for="lastName" class="form-label">Last
                                                                        Name</label>
                                                                  <input type="text" class="form-control"
                                                                        id="lastName" name="lastName"
                                                                        value="Ipsum" required="">
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                  <label for="email"
                                                                        class="form-label">Email</label>
                                                                  <input type="email" class="form-control"
                                                                        id="email" name="email"
                                                                        value="lorem@example.com" required="">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                  <label for="phone"
                                                                        class="form-label">Phone</label>
                                                                  <input type="tel" class="form-control"
                                                                        id="phone" name="phone"
                                                                        value="+1 (555) 123-4567">
                                                            </div>
                                                      </div>
                                                      <div class="mb-3">
                                                            <label for="birthdate" class="form-label">Date of
                                                                  Birth</label>
                                                            <input type="date" class="form-control" id="birthdate"
                                                                  name="birthdate" value="1990-01-01">
                                                      </div>
                                                      <div class="mb-3">
                                                            <label class="form-label d-block">Gender</label>
                                                            <div class="form-check form-check-inline">
                                                                  <input class="form-check-input" type="radio"
                                                                        name="gender" id="genderMale" value="male"
                                                                        checked="">
                                                                  <label class="form-check-label"
                                                                        for="genderMale">Male</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                  <input class="form-check-input" type="radio"
                                                                        name="gender" id="genderFemale"
                                                                        value="female">
                                                                  <label class="form-check-label"
                                                                        for="genderFemale">Female</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                  <input class="form-check-input" type="radio"
                                                                        name="gender" id="genderOther"
                                                                        value="other">
                                                                  <label class="form-check-label"
                                                                        for="genderOther">Other</label>
                                                            </div>
                                                      </div>
                                                      <div class="loading">Loading</div>
                                                      <div class="error-message"></div>
                                                      <div class="sent-message">Your information has been updated.
                                                            Thank you!</div>
                                                      <div class="text-end">
                                                            <button type="submit" class="btn btn-save">Save
                                                                  Changes</button>
                                                      </div>
                                                </form>
                                          </div>
                                    </div>

                                    <!-- Addresses Tab -->
                                    <div class="tab-pane fade" id="addresses" role="tabpanel"
                                          aria-labelledby="addresses-tab">
                                          <div class="tab-header">
                                                <h2>My Addresses</h2>
                                                <button class="btn btn-add-address" type="button">
                                                      <i class="bi bi-plus-lg"></i> Add new address
                                                </button>
                                          </div>
                                          <div class="addresses-list">
                                                <div class="row">
                                                      <div class="col-lg-6 mb-4" data-aos="fade-up"
                                                            data-aos-delay="100">
                                                            <div class="address-item">
                                                                  <div class="address-header">
                                                                        <h5>Home Address</h5>
                                                                        <div class="address-actions">
                                                                              <button class="btn-edit-address"
                                                                                    type="button">
                                                                                    <i class="bi bi-pencil"></i>
                                                                              </button>
                                                                              <button class="btn-delete-address"
                                                                                    type="button">
                                                                                    <i class="bi bi-trash"></i>
                                                                              </button>
                                                                        </div>
                                                                  </div>
                                                                  <div class="address-content">
                                                                        <p>123 Main Street<br>Apt 4B<br>New York,
                                                                              NY 10001<br>United States</p>
                                                                  </div>
                                                                  <div class="default-badge">Default</div>
                                                            </div>
                                                      </div>
                                                      <div class="col-lg-6 mb-4" data-aos="fade-up"
                                                            data-aos-delay="200">
                                                            <div class="address-item">
                                                                  <div class="address-header">
                                                                        <h5>Work Address</h5>
                                                                        <div class="address-actions">
                                                                              <button class="btn-edit-address"
                                                                                    type="button">
                                                                                    <i class="bi bi-pencil"></i>
                                                                              </button>
                                                                              <button class="btn-delete-address"
                                                                                    type="button">
                                                                                    <i class="bi bi-trash"></i>
                                                                              </button>
                                                                        </div>
                                                                  </div>
                                                                  <div class="address-content">
                                                                        <p>456 Business Ave<br>Suite 200<br>San
                                                                              Francisco, CA 94107<br>United States
                                                                        </p>
                                                                  </div>
                                                                  <button class="btn btn-sm btn-make-default"
                                                                        type="button">Make default</button>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>

                                    <!-- Notifications Tab -->
                                    <div class="tab-pane fade" id="notifications" role="tabpanel"
                                          aria-labelledby="notifications-tab">
                                          <div class="tab-header">
                                                <h2>Notification Settings</h2>
                                          </div>
                                          <div class="notifications-settings" data-aos="fade-up"
                                                data-aos-delay="100">
                                                <div class="notification-group">
                                                      <h5>Order Updates</h5>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Order status
                                                                        changes</div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications when your order status
                                                                        changes</div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="orderStatusNotif" checked="">
                                                                  <label class="form-check-label"
                                                                        for="orderStatusNotif"></label>
                                                            </div>
                                                      </div>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Shipping updates
                                                                  </div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications about shipping and delivery
                                                                  </div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="shippingNotif" checked="">
                                                                  <label class="form-check-label"
                                                                        for="shippingNotif"></label>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="notification-group">
                                                      <h5>Account Activity</h5>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Security alerts
                                                                  </div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications about security-related
                                                                        activity</div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="securityNotif" checked="">
                                                                  <label class="form-check-label"
                                                                        for="securityNotif"></label>
                                                            </div>
                                                      </div>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Password changes
                                                                  </div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications when your password is
                                                                        changed</div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="passwordNotif" checked="">
                                                                  <label class="form-check-label"
                                                                        for="passwordNotif"></label>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="notification-group">
                                                      <h5>Marketing</h5>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Promotions and
                                                                        deals</div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications about special offers and
                                                                        discounts</div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="promoNotif">
                                                                  <label class="form-check-label"
                                                                        for="promoNotif"></label>
                                                            </div>
                                                      </div>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">New product
                                                                        arrivals</div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications when new products are added
                                                                  </div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="newProductNotif">
                                                                  <label class="form-check-label"
                                                                        for="newProductNotif"></label>
                                                            </div>
                                                      </div>
                                                      <div class="notification-item">
                                                            <div class="notification-info">
                                                                  <div class="notification-title">Personalized
                                                                        recommendations</div>
                                                                  <div class="notification-desc">Receive
                                                                        notifications with product recommendations
                                                                        based on your interests</div>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                  <input class="form-check-input" type="checkbox"
                                                                        id="recommendNotif" checked="">
                                                                  <label class="form-check-label"
                                                                        for="recommendNotif"></label>
                                                            </div>
                                                      </div>
                                                </div>

                                                <div class="notification-actions">
                                                      <button type="button" class="btn btn-save">Save
                                                            Preferences</button>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </section>
</main>