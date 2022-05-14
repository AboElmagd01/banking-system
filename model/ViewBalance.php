<?php

class ViewBalance extends Inquiry
{
    public function requestBalance(){
        return $this->Account->balance;
    }
}