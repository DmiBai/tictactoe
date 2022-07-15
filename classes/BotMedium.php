<?php

require_once 'Field.php';

class BotMedium extends Bot
{
    //private ?Field $field = null;
    //private string $symbol;

    public function __construct(array $field, string $symbol)
    {
        parent::__construct($field, $symbol);
    }

    public function chooseMove()
    {
        if ($this->countMove() < 2) {
            $this->makeRandomMove();
        } else {
            if(!($this->makeFinishMove())){
                if(!($this->makeDefendMove())){
                    $this->makeRandomMove();
                }
            }
        }
    }
}