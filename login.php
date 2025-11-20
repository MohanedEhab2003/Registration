<?php
require_once 'Db.php';
require_once 'Session.php';
require_once 'Validation.php';

$session = new Session();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Validation::sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM test WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['Password'])) {
        $session->set('user_id', $user['id']);
        $session->set('logged_in', true);
        header('Location: userlist.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>.error { color: red; }</style>
</head>
<body>
    <h1>Login</h1>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="registration.php">Register</a></p>
</body>
</html>