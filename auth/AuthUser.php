<?php

function Auth(string $token, $connect)
{
  try {
    $query = "SELECT permissionID FROM user WHERE loginToken = ?";
    $stmt = $connect->prepare($query);

    if(!$stmt){
      echo "Prepare failed: (". $connect->errno.") ".$connect->error."<br>";
   }

    $stmt->bind_param('s', $token);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  } catch (PDOException $e) {
    die ('[sql] Error connect' . $e->getMessage());
  }
}
?>