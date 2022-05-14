<?php
require_once '../../model/user.php';

class Account
{
    public $id;
    public $balance;
    public $Owner;
    public $transactions;
    public $type;


    public function __construct()
    {
    }
    public static function withID( $id ) : Account{

        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}