<?php

// create token when login //
function createToken($length)
{
    return bin2hex(random_bytes($length / 2));
}

// check string input //
function check($string)
{
    $string = trim($string);
    $string = stripcslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

// query sql //
function query(string $query, array $param, $connect)
{
    try {
        $connect = $GLOBALS['connect'];

        $stmt = $connect->prepare($query);
        $stmt->execute([...$param]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ["stmt" => $stmt ? true : false, "result" => $result, "numRow" => count($result)];
    } catch (PDOException $e) {
        die('[SQL] Error query' . $e->getMessage());
    }
}

// destroy token //
function destroyToken($token, $connect)
{
    if (!empty($token)) {
        return query("UPDATE user SET loginToken = null WHERE loginToken = ?", [$token], $connect)['stmt'];
    } else
        return false;
}

function Auth(string $token, $connect)
{
    $query = "SELECT permissionID, id FROM user WHERE loginToken = ?";

    $result = query($query, [$token], $connect);

    if ($result['numRow'] == 1) {
        $_SESSION['permissionID'] = $result['result'][0]['permissionID'];
        $_SESSION['userID'] = $result['result'][0]['id'];
        return $result['result'][0];
    } else {
        return false;
    }
}

function checkIssetMusic($musicID, $connect)
{
    $sql = "SELECT * FROM music_source_path WHERE id = ?";
    $execute = query($sql, [$musicID], $connect);
    if ($execute['numRow'] > 0) {
        return ['isset' => true, 'row' => $execute['numRow'], 'result' => $execute['result']];
    } else {
        return ['isset' => false];
    }
}
?>