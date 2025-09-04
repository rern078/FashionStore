<?php
require_once __DIR__ . '/../admin/config/function.php';

$defaultAddress = db_one('SELECT line1, line2, city, state, postal, country FROM addresses WHERE is_default = 1 ORDER BY id DESC LIMIT 1');

$addressText = 'A108 Adam Street, New York, NY 535022';
if ($defaultAddress) {
      $parts = [];
      if (!empty($defaultAddress['line1'])) {
            $parts[] = (string)$defaultAddress['line1'];
      }
      if (!empty($defaultAddress['line2'])) {
            $parts[] = (string)$defaultAddress['line2'];
      }
      $cityStatePostal = [];
      if (!empty($defaultAddress['city'])) {
            $cityStatePostal[] = (string)$defaultAddress['city'];
      }
      if (!empty($defaultAddress['state'])) {
            $cityStatePostal[] = (string)$defaultAddress['state'];
      }
      if (!empty($defaultAddress['postal'])) {
            $cityStatePostal[] = (string)$defaultAddress['postal'];
      }
      if ($cityStatePostal) {
            $parts[] = implode(', ', $cityStatePostal);
      }
      if (!empty($defaultAddress['country'])) {
            $parts[] = strtoupper((string)$defaultAddress['country']);
      }
      if ($parts) {
            $addressText = implode(', ', $parts);
      }
}

// Load active social links
$socialLinks = db_all('SELECT platform, label, url, icon FROM social_links WHERE is_active = 1 ORDER BY position ASC, id DESC');

// Load admin user contact
$adminContact = db_one('SELECT name, email, phone FROM users WHERE role = ? ORDER BY id ASC LIMIT 1', ['admin']);
$adminEmail = is_array($adminContact) && !empty($adminContact['email']) ? (string)$adminContact['email'] : '';
$adminPhone = is_array($adminContact) && !empty($adminContact['phone']) ? (string)$adminContact['phone'] : '';
?>
<main class="main">
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Contact</li>
                        </ol>
                  </nav>
            </div>
      </div>

      <section id="contact-2" class="contact-2 section">

            <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                  <iframe style="border:0; width: 100%; height: 350px;"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                        frameborder="0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row gy-4">
                        <div class="col-lg-4">
                              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                                    <div>
                                          <h3>Address</h3>
                                          <p><?php echo htmlspecialchars($addressText, ENT_QUOTES); ?></p>
                                    </div>
                              </div>

                              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                    <i class="bi bi-telephone flex-shrink-0"></i>
                                    <div>
                                          <h3>Call Us</h3>
                                          <p>
                                                <?php if ($adminPhone !== '') { ?>
                                                      <a href="tel:<?php echo htmlspecialchars($adminPhone, ENT_QUOTES); ?>"><?php echo htmlspecialchars($adminPhone, ENT_QUOTES); ?></a>
                                                <?php } else { ?>
                                                      +1 5589 55488 55
                                                <?php } ?>
                                          </p>
                                    </div>
                              </div>

                              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                                    <i class="bi bi-envelope flex-shrink-0"></i>
                                    <div>
                                          <h3>Email Us</h3>
                                          <p>
                                                <?php if ($adminEmail !== '') { ?>
                                                      <a href="mailto:<?php echo htmlspecialchars($adminEmail, ENT_QUOTES); ?>"><?php echo htmlspecialchars($adminEmail, ENT_QUOTES); ?></a>
                                                <?php } else { ?>
                                                      —
                                                <?php } ?>
                                          </p>
                                    </div>
                              </div>

                              <?php if (!empty($socialLinks)) { ?>
                                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="600">
                                          <i class="bi bi-share flex-shrink-0"></i>
                                          <div>
                                                <h3>Follow Us</h3>
                                                <p>
                                                      <?php foreach ($socialLinks as $s) { ?>
                                                            <a href="<?php echo htmlspecialchars((string)$s['url'], ENT_QUOTES); ?>" target="_blank" rel="noopener" class="me-3 d-inline-flex align-items-center mb-1">
                                                                  <?php if (!empty($s['icon'])) { ?>
                                                                        <i class="<?php echo htmlspecialchars((string)$s['icon'], ENT_QUOTES); ?> me-1"></i>
                                                                  <?php } ?>
                                                                  <span><?php echo htmlspecialchars((string)($s['label'] !== '' ? $s['label'] : ucfirst((string)$s['platform'])), ENT_QUOTES); ?></span>
                                                            </a>
                                                      <?php } ?>
                                                </p>
                                          </div>
                                    </div>
                              <?php } ?>
                        </div>

                        <div class="col-lg-8">
                              <?php if (!empty($_GET['sent'])): ?>
                                    <div class="alert alert-success" role="alert">Your message has been sent. Thank you!</div>
                              <?php endif; ?>
                              <?php if (!empty($_GET['error'])): ?>
                                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars((string)$_GET['error'], ENT_QUOTES); ?></div>
                              <?php endif; ?>
                              <form action="/forms/contact.php" method="post" class="php-email-form" enctype="multipart/form-data"
                                    data-aos="fade-up" data-aos-delay="200">
                                    <div class="row gy-4">
                                          <div class="col-md-6">
                                                <input type="text" name="name" class="form-control"
                                                      placeholder="Your Name" required="">
                                          </div>
                                          <div class="col-md-6 ">
                                                <input type="email" class="form-control" name="email"
                                                      placeholder="Your Email" required="">
                                          </div>

                                          <div class="col-md-12">
                                                <input type="text" class="form-control" name="subject"
                                                      placeholder="Subject" required="">
                                          </div>

                                          <div class="col-md-12">
                                                <textarea class="form-control" name="message" rows="6"
                                                      placeholder="Message" required=""></textarea>
                                          </div>

                                          <div class="col-md-12">
                                                <label class="form-label d-block">Attachment (optional)</label>
                                                <input type="file" name="attachment" class="form-control" accept="image/*,application/pdf">
                                                <small class="text-muted">Max 3 MB. Images/PDFs only.</small>
                                          </div>

                                          <div class="col-md-12 text-center">
                                                <div class="loading">Loading</div>
                                                <div class="error-message"></div>
                                                <div class="sent-message">Your message has been sent. Thank you!
                                                </div>
                                                <button type="submit">Send Message</button>
                                          </div>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
      </section>
</main>