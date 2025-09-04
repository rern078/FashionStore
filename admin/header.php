<?php

/** @var array $__CONFIG */
/** @var string $__ROUTE */
/** @var string $pageTitle */

$site = $__CONFIG['site'] ?? [];
$assetsUrl = $site['assets_url'] ?? '/assets';
$siteName = $site['name'] ?? 'Admin';
$charset = $site['charset'] ?? 'UTF-8';
?>
<!DOCTYPE html>
<html lang="en">

<head>
      <?php $titlePartial = __DIR__ . '/title.php';
      if (is_file($titlePartial)) {
            include $titlePartial;
      } ?>
</head>

<body class="admin <?php echo htmlspecialchars($__ROUTE, ENT_QUOTES); ?>">
      <div class="container-scroller">
            <?php $navbarPartial = __DIR__ . '/partials/_navbar.php';
            if (is_file($navbarPartial)) {
                  include $navbarPartial;
            } ?>
            <div class="container-fluid page-body-wrapper">
                  <?php $sidebarPartial = __DIR__ . '/partials/_sidebar.php';
                  if (is_file($sidebarPartial)) {
                        include $sidebarPartial;
                  } ?>
                  <div class="main-panel">
                        <div class="content-wrapper">