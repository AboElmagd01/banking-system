<?php

require_once '../../model/user.php';
require_once '../../model/Deposit.php';
require_once '../../model/Withdraw.php';
require_once '../../model/Transfer.php';
require_once '../../Controllers/DBcontroller.php';
require_once '../../Controllers/ReceiptController.php';
require_once '../../Controllers/InquiryController.php';
class TransactionController
{
    protected $db;


    public function deposit($account, $amount){
        $deposit = new Deposit($account, $amount);
        return $this->make_transaction($deposit);
    }
    public function withdraw($account, $amount){
        $withdraw = new Withdraw($account, $amount);
        return $this->make_transaction($withdraw);
    }
    public function transfer(Account $AccountSender ,Account $AccountReceiver, $amount){
        $controller = new InquiryController();
        $controller->getBalance($AccountReceiver);
        $transfer = new Transfer($AccountSender,$AccountReceiver,$amount);
        if (!$transfer->validate())
            return false;
        return ($this->make_transaction($transfer->deposit) && $this->make_transaction($transfer->withdraw));
    }


    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function make_transaction(Transaction $transaction): bool
    {
        if (!$transaction->validate())
            return false;
        $this->db = new DBcontroller();
        if ($this->db->open_connection()) {
            $id = $transaction->Account->id;
            $query = "update account set  account.balance='$transaction->postBalance' WHERE account.id='$id'";
            $result = $this->db->update($query);
            if ($result === false) {
                $_SESSION['errorMsg'] = "Error in query";
                $this->db->close_connection();
                return false;
            } else {
                session_start();
                $_SESSION['balance'] = $transaction->postBalance;
                $this->save_transaction($transaction);
                if ($_SESSION['receipt'] && $transaction->type != "Transfer Receive") {
                    $receiptCon =  new ReceiptController();
                    $receiptCon->printReceipt($transaction);
                    $_SESSION['receipt'] =false;
                }
                $this->db->close_connection();
                return true;
            }
        } else {
            echo "Error in connection";
            return false;
        }
    }
    public function save_transaction(Transaction $transaction): bool
    {
        if ($this->db->open_connection()) {
            $id = $transaction->Account->id;
            $transaction->date = date("Y-m-d H:i:s");
            $query = "insert into transactions (preBalance, postBalance, type, amount,date,account) values ('$transaction->preBalance', '$transaction->postBalance', '$transaction->type', '$transaction->amount', '$transaction->date' , '$id')";
            $result = $this->db->insert($query);
            if ($result === false) {
                $_SESSION['errorMsg'] = "Error in query";
                return false;
            } else {
                $transaction->id = $result;
                return true;
            }
        } else {
            echo "Error in connection";
            return false;
        }

    }

}