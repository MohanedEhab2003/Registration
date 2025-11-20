<?php
class Validation {
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map('self::sanitize', $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function emailExists($email, $excludeId = null) {
        $pdo = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM test WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    
}
?>