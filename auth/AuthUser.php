<?php
include '../library/library.php';

function Auth(string $token, $connect)
{
  $query = "SELECT permissionID FROM user WHERE loginToken = ?";

  $result = query($query, [$token], $connect);

  if ($result['numRow'] == 1) {
    return $result['result'][0];
  } else {
    return false;
  }
}
?>