<?php
require_once '../../model/user.php';
require_once '../../Controllers/DBcontroller.php';
class InquiryController
{
    protected $db;

    public function getBalance(Account $account){
        $this->db = new DBcontroller();
        if ($this->db->open_connection()){
            $query ="SELECT * FROM account WHERE account.id='$account->id'";
            $result = $this->db->select($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Errpr in query";
                $this->db->close_connection();
                return false;
            } else{
                if (count($result)==0){
                    session_start();
                    $_SESSION['errorMsg']="Incorrect Account Number";
                    $this->db->close_connection();
                    return false;
                }else {
                    session_start();
//                    $_SESSION['balance']= $result[0]['balance'];
//                    $_SESSION['type']= $result[0]['type'];
//                    $_SESSION['AccountNo']= $result[0]['id'];
//                    $_SESSION['userId']= $result[0]['owner'];
                    $account->balance= $result[0]['balance'];
                    $account->type= $result[0]['type'];
                    $account->id= $result[0]['id'];

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
    public function getLastTransactions(Account $account){
        $this->db = new DBcontroller();
        if ($this->db->open_connection()){
            $query ="SELECT * FROM transactions WHERE transactions.account='$account->id'";
            $result = $this->db->select($query);
            if ($result===false){
                $_SESSION['errorMsg']= "Errpr in query";
                $this->db->close_connection();
                return false;
            } else{
                if (count($result)==0){
                    session_start();
                    $_SESSION['errorMsg']="Incorrect Account Number";
                    $this->db->close_connection();
                    return false;
                }else {
                    session_start();
                    $_SESSION['transactions']= $result;
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