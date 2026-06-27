<?php

use RadioPanel\Core\AzuraCast;
use RadioPanel\Core\Database;

header('Content-Type: text/plain; charset=utf-8');

$specific = isset($_GET['specific']) ? (string) $_GET['specific'] : '';
$withIcon = isset($_GET['icon']) && $_GET['icon'] === 'true';

$stats = AzuraCast::stats();

if (empty($stats['success'])) {
    echo 'Error';
    exit;
}

$conn = Database::connection();

switch ($specific) {
    case 'listeners':
        $current = (int) $stats['listeners']['current'];
        $peak = (int) $stats['listeners']['peak'];

        if ($withIcon) {
            if ($peak > $current) {
                echo $current . ' <i class="text-danger fas fa-caret-down"></i>';
            } elseif ($peak < $current) {
                echo $current . ' <i class="text-success fas fa-caret-up"></i>';
            } else {
                echo $current . ' <i class="text-success fas fa-caret-up"></i>';
            }
        } else {
            echo $current;
        }
        break;

    case 'likes':
        if (!empty($stats['currentDJ']['autoDJ'])) {
            echo '0';
            break;
        }

        $current = (int) ($stats['currentDJ']['id'] ?? 0);
        if ($current === 0) {
            echo '0';
            break;
        }

        $time = strtotime('-1 hour');
        $stmt = $conn->prepare('SELECT COUNT(*) FROM likes WHERE times > :time AND dj = :dj');
        $stmt->bindValue(':time', $time);
        $stmt->bindValue(':dj', $current, PDO::PARAM_INT);
        $stmt->execute();
        echo (int) $stmt->fetchColumn();
        break;

    case 'time':
        echo (60 - (int) date('i')) . 'mins';
        break;

    case 'listenersPeak':
        echo (int) $stats['listeners']['peak'];
        break;

    default:
        echo 'Error';
        break;
}
