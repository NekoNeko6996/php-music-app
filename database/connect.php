<?php
include __DIR__ . '/../config/config.php';

try {
    $connect = new PDO("mysql:host=$hostname;port=$port;dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->exec("SET NAMES 'utf8'");

    function destroyToken($token, $email)
    {
        if (!empty ($token) && !empty ($email)) {
            $query = "UPDATE user SET loginToken = null WHERE loginToken = :token AND email = :email";
            $stmt = $GLOBALS['connect']->prepare($query);
            $result = $stmt->execute(array(':token' => $token, ':email' => $email));
            return $result;
        } else
            return false;
    }
} catch (PDOException $e) {
    die ('[sql] Error connect' . $e->getMessage());
}

?>