<?php
require_once '../../model/user.php';
require_once '../../model/Account.php';
require_once '../../Controllers/DBcontroller.php';
class UserController
{
    protected $db;

    public function getUser(User $user){
        if ($this->db->open_connection()) {
            $query ="select *  from users where id='$user->id'";
            $result = $this->db->select($query);
            if ($result === false) {
                $_SESSION['errorMsg'] = "Error in query";
                return false;
            } else {
                if (count($result)==0){
                    session_start();
                    $_SESSION['errorMsg'] = "No User";
                    return false;
                }else {
                    $user->name = $result[0]['name'];
                    return true;
                }
            }
        } else {
            echo "Error in connection";
            return false;
        }
    }
    public function addUser(User $user){
        if ($this->db->open_connection()) {
            $query ="insert into users (name,role, fingerprint_hash) values ('$user->name', '$user->role',$user->fingerprint_hash)";
            $result = $this->db->insert($query);
            if ($result === false) {
                $_SESSION['errorMsg'] = "Error in query";
                return false;
            } else {
                return $result;
            }
        } else {
            echo "Error in connection";
            return false;
        }
    }
    public function addAccount(Account $account): bool
    {
        $this->db = new DBcontroller();
        if ($this->db->open_connection()){
            if (!$this->checkUser($account->Owner))
                $id = $this->addUser($account->Owner);
            else
                $id = $account->Owner->id;
            $query = "insert into account ( owner, balance, type) values ('$id','$account->balance','$account->type')";
            $result = $this->db->insert($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Error in query";
                $this->db->close_connection();
                return false;
            } else{
                    session_start();
                    $this->db->close_connection();
                    return true;
                }
            }
        else{
            $_SESSION['errorMsg']= "Error in DB connection";
            $this->db->close_connection();
            return false;
        }
    }
    public function checkUser(User $user): bool{
        $this->db = new DBcontroller();
        if ($this->db->open_connection()){
            $query ="SELECT * FROM users WHERE name ='$user->name' ";
            $result = $this->db->select($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Error in query";
                $this->db->close_connection();
                return false;
            } else{
                if (count($result)==0){
                    return false;
                }else {
                    $user->id = $result[0]['id'];
                    $user->name = $result[0]['name'];
                    return true;
                }
            }
        }
        return false;
    }
    public function getAllAccounts(){
        $this->db = new DBcontroller();
        $accounts = array();
        if ($this->db->open_connection()){
            $query = "select * from account";
            $result = $this->db->select($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Error in query";
                $this->db->close_connection();
                return false;
            } else{
                if (count($result)==0){
                    session_start();
                    $_SESSION['errorMsg']="No Accounts";
                    $this->db->close_connection();
                    return false;
                }else {
                    foreach ($result as $row){
                        $account = new Account();
                        $account->balance= $row['balance'];
                        $account->type= $row['type'];
                        $account->id= $row['id'];
                        $user = new User();
                        $user->id = $row['owner'];
                        $this->getUser($user);
                        $account->Owner=  $user;
                        $accounts[] = $account;
                    }
                    $_SESSION['accounts']= $accounts;
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