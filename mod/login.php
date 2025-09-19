<?php
// Handle login POST
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

require_once __DIR__ . '/../admin/config/function.php';

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
      $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

      if ($email === '' || $password === '') {
            $loginError = 'Email and password are required.';
      } else {
            $user = db_one('SELECT id, name, email, password_hash, role FROM users WHERE email = ?', [$email]);
            if (!$user || !password_verify($password, $user['password_hash'])) {
                  $loginError = 'Invalid email or password.';
            } else {
                  $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'name' => (string)$user['name'],
                        'email' => (string)$user['email'],
                        'role' => (string)$user['role'],
                  ];
                  $frontendBase = isset($__CONFIG['site']['base_url']) ? (string)$__CONFIG['site']['base_url'] : '/';
                  $adminBase = '/admin/';
                  $adminConfigPath = __DIR__ . '/../admin/config/config.php';
                  if (is_file($adminConfigPath)) {
                        $adminConf = require $adminConfigPath;
                        if (isset($adminConf['site']['base_url'])) {
                              $adminBase = (string)$adminConf['site']['base_url'];
                        }
                  }
                  // Support redirect back after login
                  $next = isset($_GET['next']) ? (string)$_GET['next'] : '';
                  if ($next !== '' && strpos($next, '://') === false) {
                        header('Location: ' . $next);
                        exit;
                  }
                  if (isset($user['role']) && (string)$user['role'] === 'admin') {
                        header('Location: ' . $adminBase);
                  } else {
                        header('Location: ' . $frontendBase);
                  }
                  exit;
            }
      }
}
?>

<main class="main">

      <!-- Page Title -->
      <div class="page-title light-background position-relative">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Login</li>
                        </ol>
                  </nav>
                  <h1>Login</h1>
            </div>
      </div><!-- End Page Title -->

      <!-- Login Section -->
      <section id="login" class="login section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-8" data-aos="zoom-in" data-aos-delay="200">
                              <div class="login-form-wrapper">
                                    <div class="login-header text-center">
                                          <h2>Login</h2>
                                          <p>Welcome back! Please enter your details</p>
                                    </div>

                                    <form action="" method="POST">
                                          <?php if (!empty($loginError)) { ?>
                                                <div class="alert alert-danger" role="alert">
                                                      <?php echo htmlspecialchars($loginError, ENT_QUOTES); ?>
                                                </div>
                                          <?php } ?>
                                          <div class="mb-4">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required="" autocomplete="email">
                                          </div>

                                          <div class="mb-3">
                                                <div class="d-flex justify-content-between">
                                                      <label for="password" class="form-label">Password</label>
                                                      <a href="#" class="forgot-link">Forgot password?</a>
                                                </div>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required="" autocomplete="current-password">
                                          </div>

                                          <div class="mb-4 form-check">
                                                <input type="checkbox" class="form-check-input" id="remember">
                                                <label class="form-check-label" for="remember">Remember for 30 days</label>
                                          </div>

                                          <div class="d-grid gap-2 mb-4">
                                                <button type="submit" class="btn btn-primary">Sign in</button>
                                                <button type="button" class="btn btn-outline">
                                                      <i class="bi bi-google me-2"></i>Sign in with Google
                                                </button>
                                          </div>

                                          <div class="signup-link text-center">
                                                <span>Don't have an account?</span>
                                                <?php $nextParam = isset($_GET['next']) ? '&next=' . rawurlencode((string)$_GET['next']) : ''; ?>
                                                <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=register<?php echo $nextParam; ?>">Sign up for free</a>
                                          </div>
                                    </form>
                              </div>
                        </div>
                  </div>
            </div>
      </section><!-- /Login Section -->

</main>