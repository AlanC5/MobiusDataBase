<?php
class connection
{
//    protected $dsn = 'mysql:host=aaavvlvgxl15b5.cn04yza62psx.us-east-1.rds.amazonaws.com;port=3306;dbname=mobiusData';
//    protected $username = 'ebroot';
//    protected $password = '12345678';
    private $DB;

    public function __construct($dsn = 'mysql:host=aaavvlvgxl15b5.cn04yza62psx.us-east-1.rds.amazonaws.com;port=3306;dbname=mobiusData', $username = 'ebroot', $password = '12345678', $options = [])
    {

        try {
            $this->DB = new PDO($dsn, $username, $password, $options);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            echo "Connected SONG";

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());

        }
    }

    public function __get($DB)
    {
        return $this->DB;
    }

    public function disconnect() {
        $this->DB = null;
    }


}

?>