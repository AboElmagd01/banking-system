<?php

class DBcontroller{
    public $dbhost="127.0.0.1";
    public $dbUser="root";
    public $dbPassword="";
    public $dbName="test_db";
    public $connection;
    public function open_connection(){
        $this->connection = new mysqli($this->dbhost,$this->dbUser,$this->dbPassword,$this->dbName);
        if ($this->connection->connect_error){
            echo "Correction Error".$this->connection->connect_error;
            return false;
        }
        return true;
    }
    public function close_connection(){
        if ($this->connection){
            $this->connection->close();
        }else{
            echo "No open connection";
        }
    }
    public function select($qry){
        $result = $this->connection->query($qry);
        if (!$result){
            echo "Error: ".mysqli_error($this->connection);
            return false;
        }else {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
    public function insert($qry)
    {
        $result=$this->connection->query($qry);
        if(!$result)
        {
            echo "Error : ".mysqli_error($this->connection);
            return false;
        }
        else
        {
            return $this->connection->insert_id;
        }
    }
    public function delete($qry)
    {
        $result=$this->connection->query($qry);
        if(!$result)
        {
            echo "Error : ".mysqli_error($this->connection);
            return false;
        }
        else
        {
            return $result;
        }

    }
    public function update($qry)
    {
        $result=$this->connection->query($qry);
        if(!$result)
        {
            echo "Error : ".mysqli_error($this->connection);
            return false;
        }
        else
        {
            return $result;
        }

    }
}