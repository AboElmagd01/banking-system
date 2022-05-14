<?php
require_once '../../model/user.php';
require_once '../../Controllers/DBcontroller.php';
class AuthController
{
    protected $db;

    public function login(User $user){
        $this->db = new DBcontroller();
        if ($this->db->open_connection()){
            $query ="SELECT * FROM users WHERE fingerprint_hash='$user->fingerprint_hash'";
            $result = $this->db->select($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Errpr in query";
                $this->db->close_connection();
                return false;
            } else{
                if (count($result)==0){
                    session_start();
                    $_SESSION['errorMsg']="Incorrect Name or Password";
                    $this->db->close_connection();
                    return false;
                }else {
                    session_start();
                    $_SESSION['userId']= $result[0]["id"];
                    $userid =$result[0]["id"];
                    $_SESSION['name']= $result[0]["name"];
                    $_SESSION['role']= $result[0]["role"];
                    $query = "select * from account where owner='$userid'";
                    $result2 = $this->db->select($query);
                    if (count($result2)!=0){
                        $_SESSION['balance']= $result2[0]['balance'];
                        $_SESSION['type']= $result2[0]['type'];
                        $_SESSION['AccountNo']= $result2[0]['id'];
                    }
                    $this->db->close_connection();
                    return true;
                }
            }
        }
        else{
            $_SESSION['errorMsg']= "Error in DB connection";
            $this->db->close_connection();
            return false;
        }
    }
}