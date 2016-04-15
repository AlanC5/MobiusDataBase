<?php

class user
{
    protected $connection;

    public function  __construct(connection $connection)
    {
        $this->connection = $connection;
    }

    public function insertUser($name, $email, $password)
    {
        $DB = $this->connection->DB;

        if ($DB != null)
        {
            $statement = $DB->prepare("INSERT INTO user (userId, email, name, password, imagefile) VALUES (:userId, :email, :name, :password, :imagefile)");
            $statement->execute(array(':userId' => NULL, ':email' => $email, ':name' => $name, ":password" => $password, ":imagefile" => 'No'));

        }

        else
        {
            echo "Not connected";
        }
    }
}

?>