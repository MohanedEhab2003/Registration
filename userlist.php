<?php
// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'registration');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT id, name, email, gender, hobbies, country FROM test");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
</head>
<body>
    <h1>User List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Hobbies</th>
            <th>Country</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?? '' ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['hobbies'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['country'] ?? '') ?></td>
            <td>
               <a href="registration.php?edit=<?= $row['id'] ?>">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>