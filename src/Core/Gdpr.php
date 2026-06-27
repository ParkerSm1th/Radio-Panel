<?php

namespace RadioPanel\Core;

class Gdpr
{
    public static function isEnabled()
    {
        return (bool) Config::get('gdpr.enabled', true);
    }

    public static function hasConsent()
    {
        if (!self::isEnabled()) {
            return true;
        }

        return isset($_COOKIE['gdpr_consent']) && $_COOKIE['gdpr_consent'] === '1';
    }

    public static function renderCookieBanner()
    {
        if (!self::isEnabled() || self::hasConsent()) {
            return;
        }

        $privacyUrl = Security::escape(Config::get('gdpr.privacy_url', '/privacy.php'));
        ?>
        <div id="gdpr-banner" class="gdpr-banner" role="dialog" aria-label="Cookies">
            <div class="gdpr-banner__inner">
                <p>We use session cookies so you can stay logged in. <a href="<?php echo $privacyUrl; ?>">Privacy policy</a></p>
                <button type="button" class="gdpr-banner__btn">Got it</button>
            </div>
        </div>
        <?php
    }

    public static function renderPrivacyPage()
    {
        $appName = Security::escape(Config::get('app.name', 'Radio Panel'));
        $retention = (int) Config::get('gdpr.log_retention_days', 90);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Privacy — <?php echo $appName; ?></title>
            <link rel="stylesheet" href="<?php echo Security::escape(Asset::url('css/login.css')); ?>">
            <style>
                body{font-family:Roboto,-apple-system,sans-serif;background:#0c1b2d;color:#e8eef5;margin:0;padding:2rem;line-height:1.55}
                .privacy{max-width:640px;margin:0 auto}
                h1{font-size:1.5rem;margin:0 0 .5rem}
                h2{font-size:.75rem;margin:1.75rem 0 .5rem;color:#8fa3bc;font-weight:600;text-transform:uppercase;letter-spacing:.04em}
                ul{padding-left:1.2rem}
                a{color:#2989eb}
            </style>
        </head>
        <body>
            <article class="privacy">
                <h1>Privacy policy</h1>
                <p>How <?php echo $appName; ?> handles staff data.</p>
                <h2>What we store</h2>
                <ul>
                    <li>Username and hashed password</li>
                    <li>Session data for login</li>
                    <li>IP addresses in security logs</li>
                    <li>Profile photo and Discord link if you add them</li>
                </ul>
                <h2>Why</h2>
                <p>Authentication, access control, and audit trails for the station.</p>
                <h2>How long</h2>
                <p>Logs kept up to <?php echo $retention; ?> days unless an investigation needs longer.</p>
                <h2>Your rights</h2>
                <p>Contact your station admin to access, fix, or delete your data.</p>
                <h2>Cookies</h2>
                <p>Session cookie for login. Optional <code>currentPage</code> for navigation.</p>
                <p><a href="index.php">Back to login</a></p>
            </article>
        </body>
        </html>
        <?php
    }
}
