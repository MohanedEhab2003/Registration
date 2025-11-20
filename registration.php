<?php
require_once 'Db.php';
require_once 'Session.php';
require_once 'Validation.php';
require_once 'User.php';

$session = new Session();
$user = new User();
$error = '';
$is_edit_mode = false;


if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($user->load($edit_id)) {
        $is_edit_mode = true;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_edit_mode = isset($_POST['edit_id']) && !empty($_POST['edit_id']);
    
   
    $user->setName($_POST['fullname']);
    $user->setEmail($_POST['email']);
    $user->setGender($_POST['Gender']);
    $user->setHobbies($_POST['hobbies'] ?? []);
    $user->setCountry($_POST['country']);
    
    if ($is_edit_mode) {
        $user->setId($_POST['edit_id']);
        $user->save();
        header('Location: userlist.php');
        exit;
    } else {
        $password = $_POST['password'];
        $confirmPassword = $_POST['ConfirmPassword'];
        
        
        
         if (Validation::emailExists($user->getEmail())) {
            $error = 'Email already exists';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match';
        }
         else {
            $user->setPassword($password);
            $user->save();
            
           
            $session->set('user_id', $user->getId());
            $session->set('logged_in', true);
            header('Location: userlist.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $is_edit_mode ? 'Edit User' : 'Registration' ?></title>
    <style>
        .error { color: red; padding: 10px; background: #ffdddd; }
        input[readonly] { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1><?= $is_edit_mode ? 'Edit User' : 'Registration Form' ?></h1>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <?php if ($is_edit_mode): ?>
            <input type="hidden" name="edit_id" value="<?= $user->getId() ?>">
        <?php endif; ?>
        
        <p>Full Name:</p>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user->getName() ?? '') ?>" required>
        
        <p>Email:</p>
        <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail() ?? '') ?>" 
               <?= $is_edit_mode ? 'readonly' : '' ?> required>
        
        <?php if (!$is_edit_mode): ?>
            <p>Password:</p>
            <input type="password" name="password" required>
            
            <p>Confirm Password:</p>
            <input type="password" name="ConfirmPassword" required>
        <?php endif; ?>
        
        <p>Gender:</p>
        <input type="radio" name="Gender" value="M" 
            <?= $user->getGender() === 'M' ? 'checked' : '' ?> required> Male
        <input type="radio" name="Gender" value="F" 
            <?= $user->getGender() === 'F' ? 'checked' : '' ?> required> Female
        
        <p>Hobbies:</p>
        <?php $hobbies = $user->getHobbies() ?: []; ?>
        <input type="checkbox" name="hobbies[]" value="read" 
            <?= in_array('read', $hobbies) ? 'checked' : '' ?>> Reading<br>
        <input type="checkbox" name="hobbies[]" value="travel" 
            <?= in_array('travel', $hobbies) ? 'checked' : '' ?>> Traveling<br>
        <input type="checkbox" name="hobbies[]" value="Sports" 
            <?= in_array('Sports', $hobbies) ? 'checked' : '' ?>> Sports<br>
        
        <p>Country:</p>
        <select name="country" required>
            <option value="Eg" <?= $user->getCountry() === 'Eg' ? 'selected' : '' ?>>Egypt</option>
            <option value="US" <?= $user->getCountry() === 'US' ? 'selected' : '' ?>>United States</option>
            <option value="Libya" <?= $user->getCountry() === 'Libya' ? 'selected' : '' ?>>Libya</option>
            <option value="SA" <?= $user->getCountry() === 'SA' ? 'selected' : '' ?>>Saudi Arabia</option>
        </select>
        
        <br><br>
        <button type="submit"><?= $is_edit_mode ? 'Update' : 'Sign Up' ?></button>
    </form>
    
    <?php if (!$is_edit_mode): ?>
        <p>Already have an account? <a href="login.php">Login</a></p>
    <?php endif; ?>
</body>
</html>