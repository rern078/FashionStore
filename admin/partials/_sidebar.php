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
    <li class="nav-item <?php echo $__ROUTE === 'category' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=category">
        <span class="menu-title">Categories</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'products' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=products">
        <span class="menu-title">Products</span>
        <i class="mdi mdi-rename-box-outline menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'variants' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=variants">
        <span class="menu-title">Variants</span>
        <i class="mdi mdi-shape-plus menu-icon"></i>
      </a>
    </li>
    <li class="nav-item <?php echo $__ROUTE === 'inventory' ? 'active' : ''; ?>">
      <a class="nav-link" href="/admin/?p=inventory">
        <span class="menu-title">Inventory</span>
        <i class="mdi mdi-warehouse menu-icon"></i>
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
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Basic UI Elements</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-crosshairs-gps menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="../pages/ui-features/buttons.html">Buttons</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../pages/ui-features/dropdowns.html">Dropdowns</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../pages/ui-features/typography.html">Typography</a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>