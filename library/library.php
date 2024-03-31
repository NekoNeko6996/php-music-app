<?php
function createToken($length)
{
    return bin2hex(random_bytes($length / 2));
}

function check($string)
{
    $string = trim($string);
    $string = stripcslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

function query(string $query, array $param, $connect)
{
    try {
        $connect = $GLOBALS['connect'];

        $stmt = $connect->prepare($query);
        $stmt->execute([...$param]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ["stmt" => $stmt, "result" => $result, "numRow" => count($result)];
    } catch (PDOException $e) {
        die('[SQL] Error query' . $e->getMessage());
    }
}

function destroyToken($token, $email, $connect)
{
    if (!empty($token) && !empty($email)) {
        $query = "UPDATE user SET loginToken = null WHERE loginToken = :token AND email = :email";
        $stmt = $connect->prepare($query);
        $result = $stmt->execute(array(':token' => $token, ':email' => $email));
        return $result;
    } else
        return false;
}

function Auth(string $token, $connect)
{
    $query = "SELECT permissionID, id FROM user WHERE loginToken = ?";

    $result = query($query, [$token], $connect);

    if ($result['numRow'] == 1) {
        return $result['result'][0];
    } else {
        return false;
    }
}
?>