<?php
// backend/auth.php
require_once __DIR__ . '/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function adminLogin($email, $password){
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, email, password_hash, name FROM usuarios_admin WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])){
        // regenerate session id
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_name'] = $user['name'];
        return true;
    }
    return false;
}

function requireAdmin(){
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function adminLogout(){
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

?>