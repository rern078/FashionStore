<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="assets/images/faces/face1.jpg" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">WELCOME TO </span>
          <span class="text-secondary text-small">CH.FASHION.STORE</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'dashboard' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=dashboard">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'category' || $__ROUTE === 'subcategories' || $__ROUTE === 'brands' ? 'active' : ''; ?>">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-category" aria-expanded="false" aria-controls="ui-category">
        <span class="menu-title">Category</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-crosshairs-gps menu-icon"></i>
      </a>
      <div class="collapse" id="ui-category">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item <?php echo $__ROUTE === 'category' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=category">Categories</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'subcategories' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=subcategories">Subcategories</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'brands' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=brands">Brands</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'products' || $__ROUTE === 'variants' || $__ROUTE === 'inventory' ? 'active' : ''; ?>">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-product" aria-expanded="false" aria-controls="ui-product">
        <span class="menu-title">Product</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-rename-box-outline menu-icon"></i>
      </a>
      <div class="collapse" id="ui-product">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item <?php echo $__ROUTE === 'products' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=products">Products</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'variants' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=variants">Variants</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'inventory' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=inventory">Inventory</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'colors' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=colors">
        <span class="menu-title">Colors</span>
        <i class="mdi mdi-palette menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'sizes' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=sizes">
        <span class="menu-title">Sizes</span>
        <i class="mdi mdi-ruler-square menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'carts' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=carts">
        <span class="menu-title">Carts</span>
        <i class="mdi mdi-cart menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'promotions' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=promotions">
        <span class="menu-title">Promotions</span>
        <i class="mdi mdi-sale menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'orders' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=orders">
        <span class="menu-title">Orders</span>
        <i class="mdi mdi-receipt menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'payments' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=payments">
        <span class="menu-title">Payments</span>
        <i class="mdi mdi-credit-card menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'shipments' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=shipments">
        <span class="menu-title">Shipments</span>
        <i class="mdi mdi-truck-delivery menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'returns' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=returns">
        <span class="menu-title">Returns</span>
        <i class="mdi mdi-undo-variant menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'reviews' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=reviews">
        <span class="menu-title">Reviews</span>
        <i class="mdi mdi-star menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'size_charts' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=size_charts">
        <span class="menu-title">Size Charts</span>
        <i class="mdi mdi-ruler menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'users' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=users">
        <span class="menu-title">Users</span>
        <i class="mdi mdi-account-group menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'addresses' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=addresses">
        <span class="menu-title">Addresses</span>
        <i class="mdi mdi-map-marker menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'settings' || $__ROUTE === 'seos' || $__ROUTE === 'banners' ? 'active' : ''; ?>">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-settings" aria-expanded="false" aria-controls="ui-settings">
        <span class="menu-title">Settings</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-cog menu-icon"></i>
      </a>
      <div class="collapse" id="ui-settings">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item <?php echo $__ROUTE === 'settings' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=settings">Settings</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'seos' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=seos">SEO</a>
          </li>
          <li class="nav-item <?php echo $__ROUTE === 'banners' ? 'active' : ''; ?>">
            <a class="nav-link" href="/admin/?p=banners">Banners</a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>