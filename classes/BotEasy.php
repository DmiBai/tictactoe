<?php

require_once 'Field.php';

class BotEasy extends Bot
{
    //private ?Field $field = null;
    //private string $symbol;

    public function __construct(array $field, string $symbol)
    {
        parent::__construct($field, $symbol);
    }

    public function chooseMove()
    {
        $this->makeRandomMove();
    }
}