<?php
// Handle register POST
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

require_once __DIR__ . '/../admin/config/function.php';

$regError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $first = isset($_POST['firstName']) ? trim((string)$_POST['firstName']) : '';
      $last = isset($_POST['lastName']) ? trim((string)$_POST['lastName']) : '';
      $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
      $phone = isset($_POST['phone']) ? trim((string)$_POST['phone']) : '';
      $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
      $confirm = isset($_POST['confirmPassword']) ? (string)$_POST['confirmPassword'] : '';

      if ($first === '' || $last === '' || $email === '' || $password === '' || $confirm === '') {
            $regError = 'All fields are required.';
      } elseif ($password !== $confirm) {
            $regError = 'Passwords do not match.';
      } else {
            $existing = db_one('SELECT id FROM users WHERE email = ?', [$email]);
            if ($existing) {
                  $regError = 'Email already registered.';
            } else {
                  $name = trim($first . ' ' . $last);
                  $hash = password_hash($password, PASSWORD_DEFAULT);
                  db_exec('INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)', [$name, $email, ($phone === '' ? null : $phone), $hash, 'customer']);
                  $userId = db_last_insert_id();
                  $_SESSION['user'] = [
                        'id' => (int)$userId,
                        'name' => (string)$name,
                        'email' => (string)$email,
                        'role' => 'customer',
                  ];
                  $frontendBase = isset($__CONFIG['site']['base_url']) ? (string)$__CONFIG['site']['base_url'] : '/';
                  // New customers go to frontend home
                  header('Location: ' . $frontendBase);
                  exit;
            }
      }
}
?>

<main class="main">

      <!-- Page Title -->
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Register</li>
                        </ol>
                  </nav>
                  <h1>Register</h1>
            </div>
      </div><!-- End Page Title -->

      <!-- Register Section -->
      <section id="register" class="register section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                  <div class="row justify-content-center">
                        <div class="col-lg-6">

                              <div class="registration-form-wrapper" data-aos="zoom-in" data-aos-delay="200">

                                    <div class="section-header mb-4 text-center">
                                          <h2>Create Your Account</h2>
                                          <p>Sign up to start shopping and enjoy exclusive offers</p>
                                    </div>

                                    <form action="" method="POST">
                                          <?php if (!empty($regError)) { ?>
                                                <div class="alert alert-danger" role="alert">
                                                      <?php echo htmlspecialchars($regError, ENT_QUOTES); ?>
                                                </div>
                                          <?php } ?>

                                          <div class="row">
                                                <div class="col-md-6 mb-3">
                                                      <div class="form-group">
                                                            <label for="firstName">First Name</label>
                                                            <input type="text" class="form-control"
                                                                  name="firstName" id="firstName" required=""
                                                                  minlength="2" placeholder="John">
                                                      </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                      <div class="form-group">
                                                            <label for="lastName">Last Name</label>
                                                            <input type="text" class="form-control"
                                                                  name="lastName" id="lastName" required=""
                                                                  minlength="2" placeholder="Doe">
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="form-group mb-3">
                                                <label for="email">Email Address</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                      required="" placeholder="you@example.com">
                                          </div>

                                          <div class="form-group mb-3">
                                                <label for="phone">Phone</label>
                                                <input type="tel" class="form-control" name="phone" id="phone" maxlength="30" placeholder="+1 234 567 8900">
                                          </div>

                                          <div class="form-group mb-3">
                                                <label for="password">Password</label>
                                                <div class="password-input">
                                                      <input type="password" class="form-control" name="password"
                                                            id="password" required="" minlength="8"
                                                            placeholder="At least 8 characters">
                                                      <i class="bi bi-eye toggle-password"></i>
                                                </div>
                                                <small class="password-requirements">
                                                      Must be at least 8 characters long and include uppercase,
                                                      lowercase, number, and special character
                                                </small>
                                          </div>

                                          <div class="form-group mb-4">
                                                <label for="confirmPassword">Confirm Password</label>
                                                <div class="password-input">
                                                      <input type="password" class="form-control"
                                                            name="confirmPassword" id="confirmPassword"
                                                            required="" minlength="8"
                                                            placeholder="Repeat your password">
                                                      <i class="bi bi-eye toggle-password"></i>
                                                </div>
                                          </div>

                                          <div class="form-group mb-4">
                                                <div class="form-check">
                                                      <input class="form-check-input" type="checkbox"
                                                            name="newsletter" id="newsletter">
                                                      <label class="form-check-label" for="newsletter">
                                                            Subscribe to our newsletter for exclusive offers and
                                                            updates
                                                      </label>
                                                </div>
                                          </div>

                                          <div class="form-group mb-4">
                                                <div class="form-check">
                                                      <input class="form-check-input" type="checkbox" name="terms"
                                                            id="terms" required="">
                                                      <label class="form-check-label" for="terms">
                                                            I agree to the <a href="#">Terms of Service</a> and <a
                                                                  href="#">Privacy Policy</a>
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="text-center mb-4">
                                                <button type="submit" class="btn btn-primary w-100">Create
                                                      Account</button>
                                          </div>

                                          <div class="text-center">
                                                <p class="mb-0">Already have an account? <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=login">Sign in</a>
                                                </p>
                                          </div>

                                    </form>

                              </div>

                        </div>
                  </div>

            </div>

      </section><!-- /Register Section -->

</main>