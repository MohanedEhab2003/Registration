<?php
session_start();

class Authentication {
    private $conn;
    private $host = 'localhost';
    private $db = 'registration';
    private $user = "root";
    private $Password = '1234';

    public function __construct($host, $user, $Password, $db) {
        $this->conn = new mysqli($host, $user, $Password, $db);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function auth($email, $password) {
        
        $stmt = $this->conn->prepare("SELECT name, email, password FROM test WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['name'] = $user['name'];  
                $_SESSION['email'] = $user['email'];
                return true;
            }
        }
        return false;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $auth = new Authentication("localhost", "root", "1234", "registration");
    
    if ($auth->auth($_POST['email'], $_POST['password'])) {
        header("Location: ./userlist.php"); 
        exit();
    } else {
        $error = "Invalid credentials.";
        echo $error;  
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Form</title>
</head>
<body>
    <div class="Login form">
        <h1>Login</h1>
        <form action="" method="post">  
            <p>Email:</p>
            <input type="email" name="email" placeholder="Email" required>

            <p>Password:</p>
            <input type="password" name="password" placeholder="Password" required>
           
            <br><br>
            <button type="submit" name="login">Login</button> 
        </form>
    </div>
</body>
</html>