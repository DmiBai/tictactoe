<?php

require_once 'Field.php';

class BotHard extends Bot
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
            if ($this->field->isEmpty(1, 1)) {
                $this->field->fillCell(1, 1, $this->symbol);
                return;
            } else if ($this->field->isEmpty(0, 0)) {
                $this->field->fillCell(0, 0, $this->symbol);
                return;
            }
        }
        if(!($this->makeFinishMove())){
            if(!($this->makeDefendMove())){
                $this->makeRandomMove();
            }
        }
    }
}