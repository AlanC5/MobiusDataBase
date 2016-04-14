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
        if ($this->connection->DB != null)
        {
            $statement = $this->connection->DB->prepare("INSERT INTO user (userId, email, name, password, imagefile) VALUES (:userId, :email, :name, :password, :imagefile)");
            $result = $statement->execute(array(':userId' => NULL, ':email' => $email, ':name' => $name, ":password" => $password, ":imagefile" => 'No'));

        }

        else
        {
            echo "Not connected";
        }
    }
}

?>