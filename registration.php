<?php
$host = 'localhost';
$db = 'registration';
$user = "root";
$db_password = '1234'; 

$conn = new mysqli($host, $user, $db_password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$edit_data = null;

if ($edit_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM test WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $gender = $_POST['Gender'];
    $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : [];
    $hobbies_str = implode(",", $hobbies);
    $country = $_POST['country'];
    
    $submitted_edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    if ($submitted_edit_id > 0) {
        $stmt = $conn->prepare("UPDATE test SET 
            Name = ?, 
            Email = ?, 
            Gender = ?, 
            Hobbies = ?, 
            Country = ? 
            WHERE id = ?");
        
        $stmt->bind_param("sssssi", $name, $email, $gender, $hobbies_str, $country, $submitted_edit_id);
        
        if ($stmt->execute()) {
            header("Location: userlist.php");
            exit;
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } 
    
    else {
        $password = $_POST['password'];
        $confirmPassword = $_POST['ConfirmPassword'];

        if ($password !== $confirmPassword) {  
            echo "Passwords do not match.";
            exit;
        }

        $stmt = $conn->prepare("SELECT id FROM test WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo 'Email already exists';
            $stmt->close();
            exit;
        }
        $stmt->close();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO test (Name, Email, Password, Gender, Hobbies, Country) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $gender, $hobbies_str, $country);
        
        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: userlist.php");
            
            $name = $email = $password = $gender = $hobbies = $country = '';
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_id ? 'Edit User' : 'Registration Form' ?></title>
</head>
<body>
    <div class="Registration form">
        <h1><?= $edit_id ? 'Edit User' : 'Sign Up' ?></h1>
        <form action="registration.php" method="post">
            <?php if ($edit_id > 0 && $edit_data): ?>
                <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
            <?php endif; ?>

            <p>Full Name:</p>
            <input type="text" name="fullname" placeholder="Full Name"
            value="<?= $edit_data['Name'] ?? ($name ?? '') ?>" required>

            <p>Email:</p>
            <input type="email" name="email" placeholder="Email"
            value="<?= $edit_data['Email'] ?? ($email ?? '') ?>" required>

            <?php if (!$edit_id): ?>
                <p>Password:</p>
                <input type="password" name="password" placeholder="Password" required>
                <p>Confirm Password</p>
                <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>
            <?php endif; ?> 

            <p>Gender:</p>
            <input id="Male" type="radio" value="M" name="Gender"  
             <?= ($edit_data['Gender'] ?? ($gender ?? '')) === 'M' ? 'checked' : '' ?> required>
            <label for="Male">Male</label>
            
            <input id="Female" type="radio" value="F" name="Gender"
             <?= ($edit_data['Gender'] ?? ($gender ?? '')) === 'F' ? 'checked' : '' ?> required>
            <label for="Female">Female</label>
           
            <p>Hobbies:</p>
            <?php
            
            $selected_hobbies = [];
            
            
            if (isset($edit_data['Hobbies']) && !empty($edit_data['Hobbies'])) {
                $selected_hobbies = explode(',', $edit_data['Hobbies']);
            }
            
            elseif (isset($hobbies) && is_array($hobbies)) {
                $selected_hobbies = $hobbies;
            }
            ?>
            <input type="checkbox" name="hobbies[]" value="read" 
                   <?= in_array('read', $selected_hobbies) ? 'checked' : '' ?>>
            <label>Reading</label><br>
            
            <input type="checkbox" name="hobbies[]" value="travel"
                   <?= in_array('travel', $selected_hobbies) ? 'checked' : '' ?>>
            <label>Traveling</label><br>
            
            <input type="checkbox" name="hobbies[]" value="Sports"
                   <?= in_array('Sports', $selected_hobbies) ? 'checked' : '' ?>>
            <label>Sports</label><br>
            
            <p>Country:</p>
            <select name="country" id="country" required>
                <option value="Eg" <?= ($edit_data['Country'] ?? ($country ?? '')) === 'Eg' ? 'selected' :'' ?>>Egypt</option>
                <option value="US" <?= ($edit_data['Country'] ?? ($country ?? '')) === 'US' ? 'selected' : '' ?>>United States</option>
                <option value="Libya" <?= ($edit_data['Country'] ?? ($country ?? '')) === 'Libya' ? 'selected' : '' ?>>Libya</option>
                <option value="SA" <?= ($edit_data['Country'] ?? ($country ?? '')) === 'SA' ? 'selected' : '' ?>>Saudi Arabia</option>
            </select>
            <br><br>
    
            <button type="submit"><?= $edit_id ? 'Update' : 'Sign Up' ?></button>
        </form>
    </div>
</body>
</html>