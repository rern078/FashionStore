<?php
require_once __DIR__ . '/../admin/config/function.php';
$aboutRows = db_all('SELECT title, content, image_url FROM about_us ORDER BY id ASC');
$first = $aboutRows[0] ?? null;
$aboutTitle = (string)($first['title'] ?? 'About Our Company');
$aboutContent = (string)($first['content'] ?? '');
$aboutImage = (string)($first['image_url'] ?? '');
?>
<main class="main">

      <!-- Page Title -->
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">About</li>
                        </ol>
                  </nav>
                  <h1>About</h1>
            </div>
      </div><!-- End Page Title -->

      <!-- About 2 Section -->
      <section id="about-2" class="about-2 section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                  <div class="row mb-lg-5">
                        <span class="text-uppercase small-title mb-2">About Our Company</span>
                        <div class="col-lg-6">
                              <h2 class="about-title">Sed ut perspiciatis unde omnis iste natus error sit
                                    voluptatem.</h2>
                        </div>
                        <div class="col-lg-6 description-wrapper">
                              <p class="about-description">Nemo enim ipsam voluptatem quia voluptas sit aspernatur
                                    aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                    voluptatem sequi nesciunt.</p>
                        </div>
                  </div>

                  <div class="row g-4">

                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                              <div class="content-card">
                                    <div class="card-image">
                                          <img src="assets/img/about/about-portrait-16.webp" alt=""
                                                class="img-fluid">
                                    </div>
                                    <div class="card-content">
                                          <h3>Ut enim ad minima veniam</h3>
                                          <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse
                                                quam nihil molestiae consequatur.</p>
                                          <a href="#" class="read-more">
                                                Explore More <i class="bi bi-arrow-right"></i>
                                          </a>
                                    </div>
                              </div>
                        </div><!-- End Content Card -->

                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                              <div class="content-card">
                                    <div class="card-image">
                                          <img src="assets/img/about/about-portrait-4.webp" alt=""
                                                class="img-fluid">
                                    </div>
                                    <div class="card-content">
                                          <h3>Quis autem vel eum iure</h3>
                                          <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui
                                                blanditiis praesentium voluptatum.</p>
                                          <a href="#" class="read-more">
                                                Learn More <i class="bi bi-arrow-right"></i>
                                          </a>
                                    </div>
                              </div>
                        </div><!-- End Content Card -->

                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                              <div class="content-card">
                                    <div class="card-image">
                                          <img src="assets/img/about/about-portrait-1.webp" alt=""
                                                class="img-fluid">
                                    </div>
                                    <div class="card-content">
                                          <h3>Nam libero tempore</h3>
                                          <p>Temporibus autem quibusdam et aut officiis debitis aut rerum
                                                necessitatibus saepe eveniet ut et voluptates.</p>
                                          <a href="#" class="read-more">
                                                Discover More <i class="bi bi-arrow-right"></i>
                                          </a>
                                    </div>
                              </div>
                        </div>
                        <!-- End Content Card -->

                  </div>

            </div>

      </section><!-- /About 2 Section -->

      <!-- Stats Section -->
      <section id="stats" class="stats section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row gy-4">
                        <div class="col-lg-3 col-md-6">
                              <div class="stats-item">
                                    <i class="bi bi-emoji-smile"></i>
                                    <span data-purecounter-start="0" data-purecounter-end="232"
                                          data-purecounter-duration="1" class="purecounter"></span>
                                    <p><strong>Happy Clients</strong> <span>consequuntur quae</span></p>
                              </div>
                        </div><!-- End Stats Item -->

                        <div class="col-lg-3 col-md-6">
                              <div class="stats-item">
                                    <i class="bi bi-journal-richtext"></i>
                                    <span data-purecounter-start="0" data-purecounter-end="521"
                                          data-purecounter-duration="1" class="purecounter"></span>
                                    <p><strong>Projects</strong> <span>adipisci atque cum quia aut</span></p>
                              </div>
                        </div><!-- End Stats Item -->

                        <div class="col-lg-3 col-md-6">
                              <div class="stats-item">
                                    <i class="bi bi-headset"></i>
                                    <span data-purecounter-start="0" data-purecounter-end="1453"
                                          data-purecounter-duration="1" class="purecounter"></span>
                                    <p><strong>Hours Of Support</strong> <span>aut commodi quaerat</span></p>
                              </div>
                        </div><!-- End Stats Item -->

                        <div class="col-lg-3 col-md-6">
                              <div class="stats-item">
                                    <i class="bi bi-people"></i>
                                    <span data-purecounter-start="0" data-purecounter-end="32"
                                          data-purecounter-duration="1" class="purecounter"></span>
                                    <p><strong>Hard Workers</strong> <span>rerum asperiores dolor</span></p>
                              </div>
                        </div><!-- End Stats Item -->

                  </div>

            </div>

      </section>

      <!-- Testimonials Section -->
      <section id="testimonials" class="testimonials section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                  <div class="testimonials-slider swiper init-swiper">
                        <script type="application/json" class="swiper-config">
                              {
                                    "slidesPerView": 1,
                                    "loop": true,
                                    "speed": 600,
                                    "autoplay": {
                                          "delay": 5000
                                    },
                                    "navigation": {
                                          "nextEl": ".swiper-button-next",
                                          "prevEl": ".swiper-button-prev"
                                    }
                              }
                        </script>

                        <div class="swiper-wrapper">
                              <?php foreach ($aboutRows as $row) { ?>
                                    <div class="swiper-slide">
                                          <div class="testimonial-item">
                                                <div class="row">
                                                      <div class="col-lg-8">
                                                            <h2><?php echo htmlspecialchars((string)$row['title'], ENT_QUOTES); ?></h2>
                                                            <p>
                                                                  <?php echo nl2br(htmlspecialchars((string)$row['content'], ENT_QUOTES)); ?>
                                                            </p>
                                                            <?php if (!empty($row['image_url'])) { ?>
                                                                  <div class="featured-img-wrapper">
                                                                        <img src="admin/<?php echo htmlspecialchars((string)$row['image_url'], ENT_QUOTES); ?>" class="featured-img" alt="">
                                                                  </div>
                                                            <?php } ?>
                                                            <div class="profile d-flex align-items-center">
                                                                  <?php if (!empty($row['image_url'])) { ?>
                                                                        <img src="admin/<?php echo htmlspecialchars((string)$row['image_url'], ENT_QUOTES); ?>" class="profile-img" alt="">
                                                                  <?php } ?>
                                                                  <div class="profile-info">
                                                                        <h3><?php echo htmlspecialchars((string)$row['title'], ENT_QUOTES); ?></h3>
                                                                        <span>Client</span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-lg-4 d-none d-lg-block">
                                                            <?php if (!empty($row['image_url'])) { ?>
                                                                  <div class="featured-img-wrapper">
                                                                        <img src="admin/<?php echo htmlspecialchars((string)$row['image_url'], ENT_QUOTES); ?>" class="featured-img" alt="">
                                                                  </div>
                                                            <?php } ?>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              <?php } ?>
                        </div>

                        <div class="swiper-navigation w-100 d-flex align-items-center justify-content-center">
                              <div class="swiper-button-prev"></div>
                              <div class="swiper-button-next"></div>
                        </div>
                  </div>
            </div>
      </section>

</main>