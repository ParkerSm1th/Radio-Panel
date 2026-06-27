<?php

require_once __DIR__ . '/init.php';

use RadioPanel\Core\Asset;
use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Gdpr;
use RadioPanel\Core\Paths;
use RadioPanel\Core\Security;
use RadioPanel\View\View;

$user = Auth::user();
if ($user !== null && !empty($user['id'])) {
    header('Location: ' . Paths::absoluteApp('Staff.Dashboard'));
    exit;
}

if (isset($_GET['ref'])) {
    $ref = Security::sanitizeRedirect($_GET['ref']);
    if ($ref !== '') {
        $_SESSION['ref'] = $ref;
    }
    header('Location: ' . Paths::webPath('index.php'));
    exit;
}

$csrfToken = Security::csrfToken();
$appName = Config::get('app.name', 'Radio Panel');
$redirectUrl = Paths::absoluteApp('Staff.Dashboard', 'welcome=1');

if (isset($_SESSION['ref']) && $_SESSION['ref'] !== '') {
    $ref = Security::sanitizeRedirect($_SESSION['ref']);
    if ($ref !== '') {
        $redirectUrl = Paths::absoluteFromWebPath($ref);
        $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'welcome=1';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo Security::escape($appName); ?> — Sign in</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?php echo Security::escape(Asset::url('css/login.css')); ?>">
  <link rel="shortcut icon" href="<?php echo Security::escape(Asset::url('images/default.png')); ?>">
</head>
<body class="login-page">
  <main class="login-shell">
    <section class="login-card" id="login-card">
      <header class="login-card__header">
        <img src="<?php echo Security::escape(Asset::url('images/white-words.png')); ?>" alt="<?php echo Security::escape($appName); ?>" class="login-card__logo" width="180" height="42">
        <p class="login-card__subtitle">Staff login</p>
      </header>

      <div class="login-card__avatar-wrap">
        <img id="user-avatar" class="login-card__avatar" src="<?php echo Security::escape(Asset::url('images/square.png')); ?>" alt="" width="72" height="72">
      </div>

      <div id="login-alert" class="login-alert" role="alert" hidden></div>

      <form id="login-form" class="login-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo Security::escape($csrfToken); ?>">
        <div class="login-field">
          <label for="username">Username</label>
          <div class="login-field__control">
            <input type="text" id="username" name="username" autocomplete="username" required>
            <span class="login-field__status" id="username-status" aria-hidden="true"></span>
          </div>
        </div>
        <div class="login-field">
          <label for="password">Password</label>
          <div class="login-field__control">
            <input type="password" id="password" name="password" autocomplete="current-password" required>
            <button type="button" class="login-field__toggle" id="toggle-password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
            <span class="login-field__status" id="password-status" aria-hidden="true"></span>
          </div>
        </div>
        <button type="submit" class="login-submit" id="login-submit">
          <span class="login-submit__text">Sign in</span>
          <span class="login-submit__loader" hidden><i class="fa-solid fa-circle-notch fa-spin"></i></span>
        </button>
      </form>

      <footer class="login-card__footer">
        <a href="privacy.php">Privacy</a>
      </footer>
    </section>

    <section class="login-success" id="login-success" hidden>
      <div class="login-success__inner">
        <img id="success-avatar" class="login-success__avatar" src="<?php echo Security::escape(Asset::url('images/square.png')); ?>" alt="" width="80" height="80">
        <h2 id="success-message">Welcome back</h2>
        <p class="login-success__hint">Taking you in…</p>
      </div>
    </section>
  </main>

  <?php Gdpr::renderCookieBanner(); ?>

  <script>
    window.RadioPanelLogin={
      redirectUrl:<?php echo Security::escapeJs($redirectUrl); ?>,
      searchUrl:<?php echo Security::escapeJs(View::api('loginSearch')); ?>,
      loginUrl:<?php echo Security::escapeJs(View::api('login')); ?>,
      defaultAvatar:<?php echo Security::escapeJs(Asset::url('images/square.png')); ?>,
      gdprDays:<?php echo (int) Config::get('gdpr.cookie_consent_days', 365); ?>
    };
  </script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo Security::escape(Asset::url('js/login.js')); ?>"></script>
</body>
</html>
