<?php
class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $gender;
    private $hobbies;
    private $country;

    
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getGender() { return $this->gender; }
    public function getHobbies() { return $this->hobbies; }
    public function getCountry() { return $this->country; }

    
    public function setId($id) { $this->id = (int)$id; }
    public function setName($name) { $this->name = Validation::sanitize($name); }
    public function setEmail($email) { $this->email = Validation::sanitize($email); }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }
    public function setGender($gender) { $this->gender = Validation::sanitize($gender); }
    public function setHobbies($hobbies) { 
        $this->hobbies = is_array($hobbies) ? 
            array_map('Validation::sanitize', $hobbies) : 
            []; 
    }
    public function setCountry($country) { $this->country = Validation::sanitize($country); }

    public function load($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM test WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        if ($data) {
            $this->id = $data['id'];
            $this->name = $data['Name'];
            $this->email = $data['Email'];
            $this->gender = $data['Gender'];
            $this->hobbies = explode(',', $data['Hobbies']);
            $this->country = $data['Country'];
            return true;
        }
        return false;
    }

    public function save() {
        $pdo = Database::getInstance();
        $hobbies_str = implode(',', $this->hobbies);
        
        if ($this->id) {
            $stmt = $pdo->prepare("UPDATE test SET 
                Name = ?, 
                Gender = ?, 
                Hobbies = ?, 
                Country = ? 
                WHERE id = ?");
            $stmt->execute([
                $this->name, 
                $this->gender, 
                $hobbies_str, 
                $this->country, 
                $this->id
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO test 
                (Name, Email, Password, Gender, Hobbies, Country) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $this->name,
                $this->email,
                $this->password,
                $this->gender,
                $hobbies_str,
                $this->country
            ]);
            $this->id = $pdo->lastInsertId();
        }
    }

    public static function getAll() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM test");
        return $stmt->fetchAll();
    }
}
?>