<?php

require_once 'Field.php';

class BotUnfair extends Bot
{
    //private ?Field $field = null;
    //private string $symbol;

    public function __construct(array $field, string $symbol)
    {
        parent::__construct($field, $symbol);
    }

    public function makeFinishMove(): bool
    {
        if ($this->countMove() > 1) {
            for($x = 0; $x < 3; $x++) {
                for($y = 0; $y < 3; $y++) {
                    $checkRes = $this->checkLines($x, $y, $this->symbol);
                    if ($checkRes) {
                        $this->field->fillCell($x, $y, $this->symbol);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function chooseMove()
    {
        if(!($this->makeFinishMove())){
            if(!($this->makeDefendMove())){
                $this->makeRandomMove();
            }
        }
    }


}