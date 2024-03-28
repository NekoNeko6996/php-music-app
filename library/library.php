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

class DATABASE
{
    public $connect;


    function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function select($query, array $param)
    {
        try {
            if (count($param) > 0) {
                $stmt = $this->connect->prepare($query);
                $stmt->bind_param("s", ...$param);
                $result = $stmt->execute();
                if ($result !== false) {
                    if ($result->rowCount() > 0)
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    else
                        return [];
                } else {
                    throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}

?>