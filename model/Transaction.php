<?php

abstract class  Transaction
{
    public $id;
    public $amount;
    public $preBalance;
    public $postBalance;
    public Account $Account;
    public $type;
    public $date;
    public function askForPrint(){
        echo "Receipt";
    }
    abstract public function validate() : bool;
}
