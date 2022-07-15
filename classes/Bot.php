<?php

require_once 'Field.php';

abstract class Bot
{
    protected ?Field $field = null;
    protected string $symbol;

    public function __construct(array $field, string $symbol)
    {
        $this->field = new Field($field);
        $this->symbol = $symbol;
    }

    public function countMove(): int
    {
        $result = 0;
        for($i = 0; $i < 3; $i++) {
            for($j = 0; $j < 3; $j++) {
                if(!($this->field->isEmpty($i, $j))){
                    $result++;
                }
            }
        }
        $result++;
        return ceil($result / 2);
    }

    public function makeRandomMove(): void
    {   
        while (1) {
            $x = mt_rand(0, 2);
            $y = mt_rand(0, 2);
            if ($this->field->isEmpty($x, $y)) {
                $this->field->fillCell($x, $y, $this->symbol);
                return;
            }
        }
    }

    public function chooseMove() {}

    public function makeDefendMove(): bool
    {
        if ($this->countMove() > 1) {
            for($x = 0; $x < 3; $x++) {
                for($y = 0; $y < 3; $y++) {
                    if ($this->field->isEmpty($x, $y)) {
                        $checkRes = $this->checkLines($x, $y, $this->getOppositeSymbol());
                        if ($checkRes) {
                            $this->field->fillCell($x, $y, $this->symbol);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function makeFinishMove(): bool
    {
        if ($this->countMove() > 1) {
            for($x = 0; $x < 3; $x++) {
                for($y = 0; $y < 3; $y++) {
                    if ($this->isCellEmpty($x, $y)) {
                        $checkRes = $this->checkLines($x, $y, $this->symbol);
                        if ($checkRes) {
                            $this->field->fillCell($x, $y, $this->symbol);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function isOpposite(int $x, int $y): bool {
        if ($this->symbol === 'X') { 
            if ($this->field->checkCell($x, $y) === 0) {
                return true;
            }
            return false;
        } else if ($this->symbol === 'O') {
            if ($this->field->checkCell($x, $y) === 1) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function isCellEmpty(int $x, int $y){
        return $this->field->isEmpty($x, $y);
    }
    public function getOppositeSymbol(): string {
        if ($this->symbol === 'X') {
            return 'O';
        } else if ($this->symbol === 'O') {
            return 'X';
        }
        return '';
    }

    public function checkLines(int $x, int $y, string $symbol): bool 
    {
        $line = $x;
        $point = $y;
        $curSymbol = '';
       
        if ($point !== 1) {
            if ($point === 0) {
                if ($this->field->getCell($line, $point + 2) ===
                    $this->field->getCell($line, $point + 1)) {
                    if (!$this->field->isEmpty($line, $point + 2)) {
                        $curSymbol = $this->field->getCell($line, $point + 2);
                    }
                }
            } else {
                if ($this->field->getCell($line, $point - 2) ===
                    $this->field->getCell($line, $point - 1)) {
                    if (!$this->field->isEmpty($line, $point - 2)) {
                        $curSymbol = $this->field->getCell($line, $point - 2);
                    }
                }
            }
        } else {
            if ($this->field->getCell($line, $point - 1) ===
                $this->field->getCell($line, $point + 1)) {
                if (!$this->field->isEmpty($line, $point - 1)) {
                    $curSymbol = $this->field->getCell($line, $point - 1);
                }
            }
        }
        if ($curSymbol === '') {
            $line = $y;
            $point = $x;
            if ($point !== 1) {
                if ($point === 0) {
                    if ($this->field->getCell($point + 2, $line) ===
                        $this->field->getCell($point + 1, $line) ) {
                        if (!$this->field->isEmpty($point + 2, $line)) {
                            $curSymbol = $this->field->getCell($point + 2, $line);
                        }
                    }
                } else {
                    if ($this->field->getCell($point - 2, $line)  ===
                        $this->field->getCell($point - 1, $line) ) {
                        if (!$this->field->isEmpty($point - 2, $line)) {
                            $curSymbol = $this->field->getCell($point - 2, $line);
                        }
                    }
                }
            } else {
                if ($this->field->getCell($point - 1, $line)  ===
                    $this->field->getCell($point + 1, $line) ) {
                    if (!$this->field->isEmpty($point - 1, $line)) {
                        $curSymbol = $this->field->getCell($point - 1, $line);
                    }
                }
            }
        }
        if ($this->field->isAngular($x, $y)) {
            if ($x === $y) {
                if (($x === 0) && ($this->field->getCell(2, 2)  ===
                        $this->field->getCell(1, 1))) {
                    $curSymbol = $this->field->getCell(1, 1);

                } else if ($this->field->getCell(0, 0) ===
                    $this->field->getCell(1, 1)) {
                    $curSymbol = $this->field->getCell(1, 1);
                }
            } else {
                if (($this->field->getCell($y, $x) === $this->field->getCell(1, 1))) {
                    $curSymbol = $this->field->getCell(1, 1);
                }
            }
        }
        if (($curSymbol !== '') && ($curSymbol === $symbol)) {
            return true;
        }
        return false;
    }

    public function getCell($x, $y): string
    {
        return $this->field->getCell($x, $y);
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field): void
    {
        $this->field = $field;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getField(): Field
    {
        return $this->field;
    }
}