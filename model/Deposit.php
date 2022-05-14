<?php
require_once '../../model/Transaction.php';
class Deposit extends Transaction
{
    public $type="Deposit";
    public function __construct($account, $amount)
    {
        $this->amount=$amount;
        $this->preBalance= $account->balance;
        $this->Account=$account;
        $this->postBalance = $this->preBalance + $amount;
    }

    public  function validate(): bool
    {
        if ($this->amount < 0) {
            session_start();
            $_SESSION['errorMsg'] = "Invalid Amount";
            return false;
        }
        return true;
    }
}