<?php
require_once '../../model/Transaction.php';
class Withdraw extends Transaction
{
    public $type="Withdraw";

    public function __construct($account, $amount)
    {
        $this->amount=$amount;
        $this->preBalance= $account->balance;
        $this->Account=$account;
        $this->postBalance = $this->preBalance - $amount;
    }

    public function validate(): bool
    {
        if ($this->amount < 0 or $this->postBalance < 0 ) {
            $this->postBalance = $this->preBalance;
            session_start();
            $_SESSION['errorMsg'] = "Invalid Amount";
            return false;
        }
        return true;
    }

}