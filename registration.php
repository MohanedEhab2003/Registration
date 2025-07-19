<?php
$host = 'localhost';
$db = 'registration';
$user = "root";
$Password = '1234';

$conn = new mysqli($host, $user, $Password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Successfully connected to the database<br>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Name = $_POST['fullname'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];
    $gender = $_POST['Gender'];
    $hobbies =$_POST['hobbies'];
    $hobbies_str = implode(",", $_POST['hobbies']);
    $country = $_POST['country'];



if ($Password !== $_POST['ConfirmPassword']) {  
        echo "Passwords do not match.";
        exit;
    }

    $checkQuery = "SELECT * FROM test WHERE email = '$Email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
            echo 'Email already exists';
        exit;
    }

    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO test (Name, Email, Password, Gender, Hobbies, Country) VALUES ('$Name', '$Email', '$hashedPassword', '$gender', '$hobbies_str', '$country')";

    if ($conn->query($insertQuery) === true) {
        echo "Registered successful";
    echo "<p><strong>Name:</strong>". htmlspecialchars($Name)."</p>";
    echo "<p><strong>Email:</strong>". htmlspecialchars ($Email)."</p>";
    echo "<p><strong>Gender:</strong>". htmlspecialchars ($gender)."</p>";
    echo "<p><strong>Hobbies:</strong>". htmlspecialchars ($hobbies_str)."</p>";
    echo "<p><strong>Country:</strong>". htmlspecialchars ($country)."</p>";
}
    } else {
        header("location:./registration.html");
        echo ("Error");
    }
//Edit part
$conn2 = new mysqli('localhost', 'root', '1234', 'registration');
if ($conn2->connect_error) 
    die("Connection failed: " . $conn2->connect_error);

$Editdata = [
    'id' => '',
    'name' => '',
    'email' => '',
    'gender' => '',
    'hobbies' => '',
    'country' => ''
];



if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    
    $result2 = $conn2->query("SELECT * FROM test WHERE id = $id");
    
    
    if ($result2->num_rows > 0) {
        $Editdata = $result2->fetch_assoc();
    }
    
}


?>