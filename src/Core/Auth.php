<?php

namespace RadioPanel\Core;

use PDO;

class Auth
{
    
    public static function user()
    {
        $loggedIn = Session::get('loggedIn');
        return is_array($loggedIn) ? $loggedIn : null;
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function id()
    {
        $user = self::user();
        return $user !== null && isset($user['id']) ? (int) $user['id'] : null;
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            if (self::isAjax()) {
                Logger::info('Unauthorized API request', ErrorHandler::requestContext(401));
                http_response_code(401);
                echo 'unauthorized';
                exit;
            }

            header('Location: ' . Paths::absolute('index.php') . '?ref=' . urlencode(Paths::appPath() . self::safeRequestUri()));
            exit;
        }
    }

    
    public static function requireAccess($minRole, array $options = [])
    {
        self::requireLogin();

        $user = self::user();
        if ($user === null) {
            exit;
        }

        if (!empty($options['dev']) && (string) ($user['developer'] ?? '0') !== '1') {
            self::denyAccess('Development Page');
        }

        if (!empty($options['media']) && (string) ($user['media'] ?? '0') !== '1') {
            self::denyAccess();
        }

        if (!empty($options['social']) && (string) ($user['social'] ?? '0') !== '1') {
            self::denyAccess();
        }

        if (!empty($options['radio']) && (string) ($user['radio'] ?? '0') !== '1') {
            self::denyAccess();
        }

        if ((int) ($user['pending'] ?? 0) === 1 && empty($options['pending'])) {
            self::denyAccess();
        }

        if ((int) ($user['postedAway'] ?? 0) === 1 && empty($options['allowPost'])) {
            self::redirectPostAway();
        }

        if ((int) ($user['permRole'] ?? 0) < $minRole) {
            self::denyAccess();
        }
    }

    
    public static function login(array $userRow)
    {
        Session::regenerate(true);

        Session::set('loggedIn', [
            'username' => $userRow['username'],
            'id' => (int) $userRow['id'],
            'avatarURL' => $userRow['avatarURL'] ?? '',
            'permRole' => (int) $userRow['permRole'],
            'radio' => $userRow['radio'] ?? 0,
            'developer' => $userRow['developer'] ?? 0,
            'media' => $userRow['media'] ?? 0,
            'social' => $userRow['social'] ?? 0,
            'trial' => $userRow['trial'] ?? 0,
            'displayRole' => $userRow['displayRole'] ?? '',
            'discord' => $userRow['discord'] ?? '',
            'discordID' => $userRow['discord_id'] ?? '',
            'pending' => (int) ($userRow['pending'] ?? 0),
            'postedAway' => (int) ($userRow['postedAway'] ?? 0),
        ]);

        Session::set('logout', false);
    }

    public static function logout()
    {
        Session::destroy();
    }

    
    public static function attempt($username, $password)
    {
        $conn = Database::connection();
        $username = strtoupper(trim($username));
        $ip = Security::clientIp();

        $stmt = $conn->prepare('SELECT * FROM users WHERE UPPER(username) = :username LIMIT 1');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $details = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$details) {
            self::logFailedLogin($username, $ip, 'Invalid User');
            return 'error';
        }

        if (!password_verify($password, $details['pass'])) {
            self::logFailedLogin($username, $ip, 'Wrong Password');
            return 'error';
        }

        if (($details['inactive'] ?? '') === 'true') {
            return 'suspend';
        }

        self::login($details);
        self::afterLogin($conn, $details, $username, $ip);

        return 'good';
    }

    
    private static function afterLogin($conn, array $details, $username, $ip)
    {
        if ((string) ($details['radio'] ?? '0') === '1') {
            $stmt = $conn->prepare('SELECT COUNT(*) FROM timetable WHERE booked = :id');
            $stmt->bindValue(':id', $details['id'], PDO::PARAM_INT);
            $stmt->execute();
            $count = (int) $stmt->fetchColumn();

            if ($count < 3) {
                $stmt = $conn->prepare(
                    "SELECT COUNT(*) FROM notifications WHERE userID = :id AND header = 'Required Slots Reminder' AND active = '1'"
                );
                $stmt->bindValue(':id', $details['id'], PDO::PARAM_INT);
                $stmt->execute();

                if ((int) $stmt->fetchColumn() === 0) {
                    $type = 'warning';
                    $header = 'Required Slots Reminder';
                    $content = 'Remember to DJ at least 3 slots this week!';
                    $icon = 'far fa-clock';

                    $stmt = $conn->prepare(
                        'INSERT INTO notifications (userID, type, header, content, icon) VALUES (:userID, :type, :header, :content, :icon)'
                    );
                    $stmt->bindValue(':userID', $details['id'], PDO::PARAM_INT);
                    $stmt->bindValue(':type', $type);
                    $stmt->bindValue(':header', $header);
                    $stmt->bindValue(':content', $content);
                    $stmt->bindValue(':icon', $icon);
                    $stmt->execute();
                }
            }
        }

        $stmt = $conn->prepare(
            'UPDATE users SET lastLogin = CURRENT_TIMESTAMP, lastLoginIP = :ip WHERE id = :id'
        );
        $stmt->bindValue(':id', (int) $details['id'], PDO::PARAM_INT);
        $stmt->bindValue(':ip', $ip);
        $stmt->execute();

        $sessionId = session_id();
        $userId = (int) $details['id'];
        $page = '/Staff.Dashboard';

        $stmt = $conn->prepare('DELETE FROM sessions WHERE user = :user');
        $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare(
            'INSERT INTO sessions (user, session, page, times) VALUES (:user, :session, :page, CURRENT_TIMESTAMP)'
        );
        $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':session', $sessionId);
        $stmt->bindValue(':page', $page);
        $stmt->execute();
    }

    private static function logFailedLogin($username, $ip, $reason)
    {
        $conn = Database::connection();
        $action = 'Attempted login. Login failed (' . $reason . ')';

        $stmt = $conn->prepare(
            'INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)'
        );
        $stmt->bindValue(':name', $username);
        $stmt->bindValue(':ip', $ip);
        $stmt->bindValue(':action', $action);
        $stmt->execute();
    }

    private static function denyAccess($context = null)
    {
        $message = 'You do not have permission to access that page.';
        if ($context !== null) {
            $message .= ' (' . $context . ')';
        }

        Logger::warning('Access denied', array_merge(
            ErrorHandler::requestContext(403),
            ['context' => $context]
        ));

        if (self::isAjax()) {
            ?>
            <script>
                newError(<?php echo json_encode($message); ?>);
                urlRoute.loadPage("Staff.Dashboard");
            </script>
            <?php
            exit;
        }

        http_response_code(403);
        exit($message);
    }

    private static function redirectPostAway()
    {
        ?>
        <script>
            newError('You can not access that page while away.');
            urlRoute.loadPage("Staff.PostAway");
        </script>
        <?php
        exit;
    }

    private static function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }

        $uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';

        return strpos($uri, '/api/') !== false;
    }

    private static function safeRequestUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        $uri = Paths::normalizeRequestPath($uri);
        $root = Paths::webRootPath();

        if ($root !== '' && strpos($uri, $root) === 0) {
            $uri = substr($uri, strlen($root));
        }

        $app = Paths::appPath();
        if ($app !== '' && strpos($uri, $app) === 0) {
            $uri = substr($uri, strlen($app));
        }

        return Security::sanitizeRedirect($uri);
    }

    
    public static function roleColorClass($role)
    {
        $map = [
            1 => 'dstaff-text',
            2 => 'sstaff-text',
            3 => 'manager-text',
            4 => 'admin-text',
            5 => 'executive-text',
            6 => 'owner-text',
        ];

        return isset($map[$role]) ? $map[$role] : 'dstaff-text';
    }

    
    public static function roleHexColor($role)
    {
        $map = [
            1 => '#2989eb',
            2 => '#9a1790',
            3 => '#006729',
            4 => '#e60505',
            5 => '#d08017',
            6 => 'rgb(103, 2, 165)',
        ];

        return isset($map[$role]) ? $map[$role] : '#2989eb';
    }

    
    public static function renderUserSpan($userId)
    {
        $conn = Database::connection();
        $stmt = $conn->prepare('SELECT id, username, permRole FROM users WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo '<span class="text-muted">Unknown</span>';
            return;
        }

        echo self::buildUserSpan($user);
    }

    public static function renderThisUserSpan()
    {
        $user = self::user();
        if ($user === null) {
            return;
        }

        echo self::buildUserSpan([
            'id' => $user['id'],
            'username' => $user['username'],
            'permRole' => $user['permRole'],
        ]);
    }

    
    public static function returnUserSpan($userId)
    {
        $conn = Database::connection();
        $stmt = $conn->prepare('SELECT id, username, permRole FROM users WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return '<span class="text-muted">Unknown</span>';
        }

        return self::buildUserSpan($user);
    }

    
    private static function buildUserSpan(array $user)
    {
        $color = self::roleColorClass((int) $user['permRole']);
        $id = (int) $user['id'];
        $username = Security::escape($user['username']);

        return sprintf(
            '<span class="%s userLink" onclick="loadProfile(%d)">%s</span>',
            $color,
            $id,
            $username
        );
    }
}
