<?php
require_once '../../model/Transaction.php';
require_once '../../model/Deposit.php';
require_once '../../model/Withdraw.php';
class Transfer extends Transaction
{
    public $type="Transfer";
    public $deposit;
    public $withdraw;
    public function __construct($sender,$receiver,$amount)
    {
        $this->withdraw = new Withdraw($sender, $amount);
        $this->deposit = new Deposit($receiver, $amount);
        $this->withdraw->type="Transfer Send";
        $this->deposit->type="Transfer Receive";
    }
    public function validate(): bool
    {
        return $this->withdraw->validate() && $this->deposit->validate();
    }

}