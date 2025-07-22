<?php
require_once 'Db.php';
require_once 'Session.php';
require_once 'User.php';

$session = new Session();


if (!$session->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$users = User::getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
</head>
<body>
    <h1>Registered Users</h1>
    <p><a href="logout.php">Logout</a></p>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Hobbies</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['Name']) ?></td>
            <td><?= htmlspecialchars($user['Email']) ?></td>
            <td><?= $user['Gender'] === 'M' ? 'Male' : 'Female' ?></td>
            <td><?= htmlspecialchars($user['Hobbies']) ?></td>
            <td>
                <?php 
                $countries = [
                    'Eg' => 'Egypt',
                    'US' => 'United States',
                    'Libya' => 'Libya',
                    'SA' => 'Saudi Arabia'
                ];
                echo $countries[$user['Country']] ?? $user['Country']; 
                ?>
            </td>
            <td>
                <a href="registration.php?edit=<?= $user['id'] ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>