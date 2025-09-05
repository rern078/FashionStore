<?php
function getCurrencies()
{

      $currencies = [];
      $defaultCurrencyCode = 'USD';
      try {
            $stmtCurrencies = db()->query("SELECT code, name, symbol, decimal_places, position, is_default FROM currencies WHERE is_active=1 ORDER BY is_default DESC, code ASC");
            $currencies = $stmtCurrencies ? $stmtCurrencies->fetchAll(PDO::FETCH_ASSOC) : [];
      } catch (Throwable $e) {
            // ignore if DB not available
      }

      // Determine default currency code from DB rows if available
      foreach ($currencies as $row) {
            if ((int)($row['is_default'] ?? 0) === 1 && !empty($row['code'])) {
                  $defaultCurrencyCode = (string)$row['code'];
                  break;
            }
      }

      // Handle explicit currency change via query parameter
      $requestedCode = isset($_GET['set_currency']) ? strtoupper((string)$_GET['set_currency']) : null;
      if ($requestedCode !== null) {
            foreach ($currencies as $row) {
                  if (strcasecmp((string)$row['code'], (string)$requestedCode) === 0) {
                        $_SESSION['currency'] = $requestedCode;
                        break;
                  }
            }
      }

      $currentCurrencyCode = strtoupper((string)($_SESSION['currency'] ?? $defaultCurrencyCode));

      $currentCurrencyLabel = $currentCurrencyCode;
      $currentCurrencySymbol = $currentCurrencyCode;
      foreach ($currencies as $row) {
            if (strcasecmp((string)$row['code'], (string)$currentCurrencyCode) === 0) {
                  $currentCurrencyLabel = (string)($row['name'] ?: $row['code']);
                  $currentCurrencySymbol = (string)($row['symbol'] ?: $row['code']);
                  break;
            }
      }

      return [
            'currencies' => $currencies,
            'currentCurrencyCode' => $currentCurrencyCode,
            'currentCurrencyLabel' => $currentCurrencyLabel,
            'currentCurrencySymbol' => $currentCurrencySymbol,
      ];
}

function getAbout()
{
      $row = db_one('SELECT title, content, content_2, image_url FROM about_us ORDER BY id ASC LIMIT 1');
      $aboutTitle = (string)($row['title'] ?? 'About Our Company');
      $aboutContent = (string)($row['content'] ?? '');
      $aboutContent2 = (string)($row['content_2'] ?? '');
      $aboutImage = (string)($row['image_url'] ?? '');
      return [
            'title' => $aboutTitle,
            'content' => $aboutContent,
            'content_2' => $aboutContent2,
            'image_url' => $aboutImage,
      ];
}

// end about logic

function getDefaultAddress()
{
      $defaultAddress = db_one('SELECT line1, line2, city, state, postal, country FROM addresses WHERE is_default = 1 ORDER BY id DESC LIMIT 1');
      $addressText = 'Sangkat Teuk Thla, Kan Sensok, Phnom Penh, Cambodia';
      $parts = [];
      if (is_array($defaultAddress) && !empty($defaultAddress)) {
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
            if (!empty($cityStatePostal)) {
                  $parts[] = implode(', ', $cityStatePostal);
            }
            if (!empty($defaultAddress['country'])) {
                  $parts[] = strtoupper((string)$defaultAddress['country']);
            }
      }
      if (!empty($parts)) {
            $addressText = implode(', ', $parts);
      }
      return $addressText;
}


function getContact()
{
      $socialLinks = db_all('SELECT platform, label, url, icon FROM social_links WHERE is_active = 1 ORDER BY position ASC, id DESC');
      $socialLinks = is_array($socialLinks) ? $socialLinks : [];

      $adminContact = db_one('SELECT name, email, phone FROM users WHERE role = ? ORDER BY id ASC LIMIT 1', ['admin']);
      $adminEmail = is_array($adminContact) && !empty($adminContact['email']) ? (string)$adminContact['email'] : '';
      $adminPhone = is_array($adminContact) && !empty($adminContact['phone']) ? (string)$adminContact['phone'] : '';

      return [
            'socialLinks' => $socialLinks,
            'adminEmail' => $adminEmail,
            'adminPhone' => $adminPhone,
      ];
}
