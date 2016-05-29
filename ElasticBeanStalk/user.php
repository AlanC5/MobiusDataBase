<?php

class user
{
    protected $connection;
    protected $DB;

    public function  __construct(connection $connection)
    {
        $this->connection = $connection;
        $this->DB = $this->connection->DB;
    }

    public function insertUser($name, $email, $password)
    {

        if ($this->DB != null)
        {
            $statement = $this->DB->prepare("INSERT INTO user (userId, email, name, password, imagefile) VALUES (:userId, :email, :name, :password, :imagefile)");
            $statement->execute(array(':userId' => NULL, ':email' => $email, ':name' => $name, ":password" => md5($password), ":imagefile" => 'No'));

        }

        else
        {
            echo "Not connected";
        }
    }


    public function logInUser($email, $password) {

    }
}

?>