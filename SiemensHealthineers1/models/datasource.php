<?php
$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);

class DataSource
{

    const DBHOST = 'localhost';
    const USERNAME = 'coacteh9_coact';
    const PASSWORD = 'Coact@2020#';
    // const USERNAME = 'root';
    // const PASSWORD = '';
    const DATABASENAME = 'SiemensHealthineers1';
    const DBPORT = '3306';

    // const DBHOST = 'localhost';
    // const USERNAME = 'root';
    // const PASSWORD = '';
    // const DATABASENAME = 'anubhav2021';
    // const DBPORT = '3306';

    /*  const DBHOST = 'localhost';
    const USERNAME = 'genseer';
    const PASSWORD = 'genseer123';
    const DATABASENAME = 'anubhav2021';
    const DBPORT = '3306';
 */


    private $conn;

    function __construct()
    {
        $this->conn = $this->getConnection();
    }

    public function getConnection()
    {
        $conn = new \mysqli(self::DBHOST, self::USERNAME, self::PASSWORD, self::DATABASENAME, self::DBPORT);

        if (mysqli_connect_errno()) {
            trigger_error("Problem with connecting to database.");
        }

        $conn->set_charset("utf8");
        return $conn;
    }

    public function select($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conn->prepare($query);

        if (!empty($paramType) && !empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (!empty($resultset)) {
            return $resultset;
        }
    }

    public function insert($query, $paramType, $paramArray)
    {
        $stmt = $this->conn->prepare($query);
        $this->bindQueryParams($stmt, $paramType, $paramArray);
        //echo $stmt;
        $stmt->execute();
        $insertId = $stmt->insert_id;
        return $insertId;
    }

    public function execute($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conn->prepare($query);

        if (!empty($paramType) && !empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
    }

    public function bindQueryParams($stmt, $paramType, $paramArray = array())
    {


        $paramValueReference[] = &$paramType;
        for ($i = 0; $i < count($paramArray); $i++) {
            $paramValueReference[] = &$paramArray[$i];
        }
        call_user_func_array(array(
            $stmt,
            'bind_param'
        ), $paramValueReference);
    }

    public function getRecordCount($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conn->prepare($query);
        if (!empty($paramType) && !empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $stmt->store_result();
        $recordCount = $stmt->num_rows;

        return $recordCount;
    }
}
