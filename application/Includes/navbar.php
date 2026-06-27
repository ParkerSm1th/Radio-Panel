<?php

require APP_INCLUDES . '/bootstrap.php';
require_once APP_INCLUDES . '/helpers.php';

use RadioPanel\Core\Auth;
use RadioPanel\Core\Database;
use RadioPanel\Core\Logger;
use RadioPanel\Core\Session;

if (!Auth::check()) {
    echo '<script>login();</script>';
    return;
}

$user = Auth::user();
$userId = (int) $user['id'];
$conn = Database::connection();

try {
    $awayStmt = $conn->prepare('SELECT * FROM post_away WHERE user = :id AND status = 1');
    $awayStmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $awayStmt->execute();
    $awayRows = $awayStmt->fetchAll(PDO::FETCH_ASSOC);
    $away = 0;

    if (!empty($awayRows)) {
        $awayDet = $awayRows[0];
        $now = date('d-m-Y');
        $timestampSeconds = (int) $awayDet['length'] / 1000;
        $done = date('d-m-Y', $timestampSeconds);
        $dateDiff = abs(round((strtotime($now) - strtotime($done)) / 86400));

        if ($dateDiff < 1) {
            $updateStmt = $conn->prepare('UPDATE post_away SET status = 3 WHERE user = :user ORDER BY id DESC LIMIT 1');
            $updateStmt->bindValue(':user', $userId, PDO::PARAM_INT);
            $updateStmt->execute();
            $away = 0;
            ?>
            <script>
              refreshNav();
              urlRoute.loadPage("Staff.Dashboard");
              setTimeout(function () {
                newSuccess("Welcome back from being posted away!");
              }, 1000);
            </script>
            <?php
        } else {
            $away = 1;
            ?>
            <script>urlRoute.loadPage('Staff.PostAway');</script>
            <?php
        }
    }

    $userStmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
    $userStmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $userStmt->execute();
    $details = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$details) {
        echo '<ul class="nav"><li class="nav-item"><span class="nav-link">Unable to load navigation.</span></li></ul>';
        return;
    }

    $loggedIn = [
        'username' => $details['username'],
        'id' => (int) $details['id'],
        'avatarURL' => $details['avatarURL'] ?? '',
        'permRole' => (int) ($details['permRole'] ?? 1),
        'radio' => (int) ($details['radio'] ?? 0),
        'developer' => (int) ($details['developer'] ?? 0),
        'media' => (int) ($details['media'] ?? 0),
        'social' => (int) ($details['social'] ?? 0),
        'trial' => (int) ($details['trial'] ?? 0),
        'displayRole' => $details['displayRole'] ?? '',
        'discord' => $details['discord'] ?? '',
        'discordID' => $details['discord_id'] ?? '',
        'postedAway' => $away,
        'pending' => (int) ($details['pending'] ?? 0),
    ];
    Session::set('loggedIn', $loggedIn);

    $currentRoute = isset($_COOKIE['currentPage']) ? (string) $_COOKIE['currentPage'] : 'Staff.Dashboard';
    $currentRouteParts = explode('.', $currentRoute, 2);
    $currentPrefix = isset($currentRouteParts[0]) ? $currentRouteParts[0] : 'Staff';
    $pendingLevel = (int) ($loggedIn['pending'] ?? 0);
    $devLevel = (int) ($loggedIn['developer'] ?? 0);
    $isPostedAway = ($away === 1);
    ?>
    <ul class="nav">
    <?php
    if ($isPostedAway) {
        ?>
        <li class="nav-item mainLink">
          <a class="nav-link drop" data-toggle="collapse" href="#ui-away" aria-expanded="true" aria-controls="ui-away">
            <div class="navOverlay"></div>
            <i class="menu-icon fa fa-user"></i>
            <span class="menu-title">Staff</span>
          </a>
          <div class="collapse show" id="ui-away">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <a class="nav-link web-page" href="Staff.PostAway">&#187; Post Away</a>
              </li>
            </ul>
          </div>
        </li>
        <?php
    } else {
        $navStmt = $conn->prepare(
            'SELECT * FROM nav_ranks WHERE permRole <= :role AND radio <= :radio AND media <= :media AND dev <= :dev AND social <= :social ORDER BY permRole'
        );
        $navStmt->bindValue(':role', (int) $loggedIn['permRole'], PDO::PARAM_INT);
        $navStmt->bindValue(':radio', (int) $loggedIn['radio'], PDO::PARAM_INT);
        $navStmt->bindValue(':media', (int) $loggedIn['media'], PDO::PARAM_INT);
        $navStmt->bindValue(':dev', (int) $loggedIn['developer'], PDO::PARAM_INT);
        $navStmt->bindValue(':social', (int) $loggedIn['social'], PDO::PARAM_INT);
        $navStmt->execute();
        $navRows = $navStmt->fetchAll(PDO::FETCH_ASSOC);

        $pagesSql = 'SELECT * FROM panel_pages WHERE nav_rank = :id AND dev <= :dev';
        if (Database::hasColumn('panel_pages', 'pending')) {
            $pagesSql .= ' AND pending >= :pending';
        }
        $pagesSql .= ' ORDER BY position';
        $pagesStmt = $conn->prepare($pagesSql);

        foreach ($navRows as $navRow) {
            $navId = (int) ($navRow['id'] ?? 0);
            $navClass = trim((string) ($navRow['class'] ?? ''));
            if ($navClass === '') {
                $navClass = 'dstaff';
            }

            $expanded = ((string) ($navRow['prefix'] ?? '') === $currentPrefix) ? 'true' : 'false';
            $collapseClass = ($expanded === 'true') ? 'show' : '';
            ?>
            <li class="nav-item mainLink">
              <a class="nav-link drop" data-toggle="collapse" href="#ui-<?php echo $navId; ?>" aria-expanded="<?php echo $expanded; ?>" aria-controls="ui-<?php echo $navId; ?>">
                <div class="navOverlay <?php echo htmlspecialchars($navClass, ENT_QUOTES, 'UTF-8'); ?>-background"></div>
                <i class="menu-icon <?php echo htmlspecialchars((string) ($navRow['icon'] ?? 'fa fa-circle'), ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($navClass, ENT_QUOTES, 'UTF-8'); ?>-text"></i>
                <span class="menu-title"><?php echo htmlspecialchars((string) ($navRow['name'] ?? 'Section'), ENT_QUOTES, 'UTF-8'); ?></span>
              </a>
              <div class="collapse <?php echo $collapseClass; ?>" id="ui-<?php echo $navId; ?>">
                <ul class="nav flex-column sub-menu">
                <?php
                $pagesStmt->bindValue(':id', (string) $navId);
                $pagesStmt->bindValue(':dev', $devLevel, PDO::PARAM_INT);
                if (Database::hasColumn('panel_pages', 'pending')) {
                    $pagesStmt->bindValue(':pending', $pendingLevel, PDO::PARAM_INT);
                }
                $pagesStmt->execute();
                $pageRows = $pagesStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($pageRows as $pageRow) {
                    $pageName = htmlspecialchars((string) ($pageRow['name'] ?? 'Page'), ENT_QUOTES, 'UTF-8');
                    $pageUrl = htmlspecialchars((string) ($pageRow['url'] ?? 'Staff.Dashboard'), ENT_QUOTES, 'UTF-8');
                    $isDevPage = (int) ($pageRow['dev'] ?? 0) === 1;
                    $isRedirect = Database::hasColumn('panel_pages', 'redirect') && (int) ($pageRow['redirect'] ?? 0) === 1;
                    ?>
                    <li class="nav-item">
                      <?php if ($isRedirect) { ?>
                        <a class="nav-link" target="_blank" href="<?php echo $pageUrl; ?>">&#187; <?php echo $pageName; ?> <i style="margin-left: 3px;" class="fas fa-external-link"></i></a>
                      <?php } else { ?>
                        <a class="nav-link web-page" href="<?php echo $pageUrl; ?>">&#187; <?php echo $pageName; ?><?php if ($isDevPage) { ?> <i style="margin-left: 3px;" class="fal fa-file-code"></i><?php } ?></a>
                      <?php } ?>
                    </li>
                    <?php
                }
                ?>
                </ul>
              </div>
            </li>
            <?php
        }

        $hasPermShow = false;
        if (Database::hasTable('perm_shows')) {
            $permStmt = $conn->prepare('SELECT hosts FROM perm_shows ORDER BY id DESC');
            $permStmt->execute();
            foreach ($permStmt->fetchAll(PDO::FETCH_ASSOC) as $showRow) {
                foreach (explode(',', (string) ($showRow['hosts'] ?? '')) as $hostId) {
                    if ((string) $userId === trim($hostId)) {
                        $hasPermShow = true;
                        break 2;
                    }
                }
            }
        }

        if ($hasPermShow) {
            $expanded = ($currentPrefix === 'Perm') ? 'true' : 'false';
            $collapseClass = ($expanded === 'true') ? 'show' : '';
            ?>
            <li class="nav-item mainLink">
              <a class="nav-link drop" data-toggle="collapse" href="#ui-permShow" aria-expanded="<?php echo $expanded; ?>" aria-controls="ui-permShow">
                <div class="navOverlay" style="background: #08b39d !important"></div>
                <i class="menu-icon fas fa-calendar-star" style="color: #08b39d;"></i>
                <span class="menu-title">Perm Shows</span>
              </a>
              <div class="collapse <?php echo $collapseClass; ?>" id="ui-permShow">
                <ul class="nav flex-column sub-menu">
                <?php
                $pagesStmt->bindValue(':id', '30');
                $pagesStmt->bindValue(':dev', $devLevel, PDO::PARAM_INT);
                if (Database::hasColumn('panel_pages', 'pending')) {
                    $pagesStmt->bindValue(':pending', $pendingLevel, PDO::PARAM_INT);
                }
                $pagesStmt->execute();
                foreach ($pagesStmt->fetchAll(PDO::FETCH_ASSOC) as $pageRow) {
                    $pageName = htmlspecialchars((string) ($pageRow['name'] ?? 'Page'), ENT_QUOTES, 'UTF-8');
                    $pageUrl = htmlspecialchars((string) ($pageRow['url'] ?? 'Staff.Dashboard'), ENT_QUOTES, 'UTF-8');
                    ?>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="<?php echo $pageUrl; ?>">&#187; <?php echo $pageName; ?></a>
                    </li>
                    <?php
                }
                ?>
                </ul>
              </div>
            </li>
            <?php
        }
    }
    ?>
    </ul>
    <?php
} catch (\Throwable $e) {
    Logger::exception($e, ['component' => 'navbar']);
    ?>
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link web-page" href="Staff.Dashboard">&#187; Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link web-page" href="Staff.Profile">&#187; Profile</a>
      </li>
    </ul>
    <?php
}
