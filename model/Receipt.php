<?php

class Receipt
{
    public int $id;
    public Transaction $transaction;
    public array $extraFlags;

}