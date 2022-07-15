<?php
class Botasdf
{
    private $field;
    private $symbol;

    public function __construct(array $field, string $symbol)
    {
        $this->field = $field;
        $this->symbol = $symbol;
    }

    public function countMove(): int
    {
        $result = 0;
        foreach ($this->field as $key) {
            foreach($key as $value) {
                if ($value != '') {
                    $result++;
                }
            }
        }
        $result++;
        return $result / 2;
    }

    //0 = '0', 1 = 'X', -1 = ''
    public function checkField(int $x, int $y): int
    {
        if ($this->field[$x][$y] == '') {
            return -1;
        } else if ($this->field[$x][$y] == 'X') {
            return 1;
        } else {
            return 0;
        }
    }


    public function makeMove(int $x, int $y): void
    {   
        if($this->isEmpty($x, $y)){
            $this->field[$x][$y] = $this->symbol;
        }
    }

    public function makeRandomMove(): void
    {   
        while(1){
            $x = mt_rand(0, 2);
            $y = mt_rand(0, 2);
            if($this->checkField($x, $y) == -1){
                $this->makeMove($x, $y);
                return;
            }
        }
    }

    public function chooseMove()
    {
        if ($this->countMove() < 2) {
            if ($this->checkField(1,1) == -1) {
                $this->makeMove(1,1);
            } else {
                $this->makeMove(0,0);
            }
        } else {
            if(!($this->makeFinishMove())){
                if(!($this->makeDefendMove())){
                    $this->makeRandomMove();
                }
            }
        }
    }

    public function getCell($x, $y) : string
    {
        return $this->field[$x][$y];
    }

    public function getField() : array
    {
        return $this->field;
    }
    
    
    public function checkMove($number) : array
    {
        $field = $this->field;
        $this->makeMove($x, $y);
        $swapVar = $this->field;
        $this->field = $field;
        $field = $swapVar;
        return $field;
    }

    public function makeDefendMove() : bool
    {
        if($this->countMove() > 1){
            for($x = 0; $x < 3; $x++){
                for($y = 0; $y < 3; $y++){
                    if($this->isEmpty($x, $y)){
                        $checkRes = $this->checkLines($x, $y, $this->getOppositeSymbol());
                        if($checkRes){
                            $this->makeMove($x, $y);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function makeFinishMove() : bool
    {
        if($this->countMove() > 1){
            for($x = 0; $x < 3; $x++){
                for($y = 0; $y < 3; $y++){
                    if($this->isEmpty($x, $y)){
                        $checkRes = $this->checkLines($x, $y, $this->symbol);
                        if($checkRes){
                            $this->makeMove($x, $y);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function isAngular(int $x, int $y) : bool //no. laravel only
    {
        if(($x !== 1) && (($x + $y) % 2 === 0)){
            return true;
        }   
        return false;
    }

    public function isSide(int $x, int $y) : bool //no stupid jokes here
    {
        if((($x !== 1) || ($y !== 1)) && (($x + $y) % 2 === 1)){
            return true;
        }   
        return false;
    }

    public function isOpposite(int $x, int $y) : bool {
        if($this->symbol === 'X'){ 
            if($this->checkField($x, $y) === 0){
                return true;
            }
            return false;
        } else if ($this->symbol === 'O'){
            if($this->checkField($x, $y) === 1){
                return true;
            }
            return false;
        }
        return false;
    }

    public function isEmpty(int $x, int $y) : bool {
        if($this->checkField($x, $y) === -1){
            return true;
        }
        return false;
    }

    public function getOppositeSymbol(){
        if($this->symbol === 'X'){
            return 'O';
        } else if ($this->symbol === 'O'){
            return 'X';
        }
        return '';
    }
    
    public function checkLines(int $x, int $y, string $symbol) : bool {
        $line = $x;
        $point = $y;
        $curSymbol = '';
       
        if($point !== 1){
            if($point === 0) {
                if ($this->field[$line][$point + 2] === $this->field[$line][$point + 1]){
                    if(!$this->isEmpty($line, $point + 2)){
                        $curSymbol = $this->field[$line][$point+ 2];   
                    }
                }
            } else {
                if ($this->field[$line][$point - 2] === $this->field[$line][$point - 1]){
                    if(!$this->isEmpty($line, $point - 2)){
                        $curSymbol = $this->field[$line][$point - 2];
                    }
                }
            }
        } else {
            if ($this->field[$line][$point - 1] === $this->field[$line][$point + 1]){
                if(!$this->isEmpty($line, $point - 1)){
                    $curSymbol = $this->field[$line][$point - 1];
                }
            }
        }
        if($curSymbol === ''){
            $line = $y;
            $point = $x;
            if($point !== 1){
                if($point === 0) {
                    if ($this->field[$point + 2][$line] === $this->field[$point + 1][$line]){
                        if(!$this->isEmpty($point + 2, $line)){
                            $curSymbol = $this->field[$point+ 2][$line];   
                        }
                    }
                } else {
                    if ($this->field[$point - 2][$line] === $this->field[$point - 1][$line]){
                        if(!$this->isEmpty($point - 2, $line)){
                            $curSymbol = $this->field[$point - 2][$line];
                        }
                    }
                }
            } else {
                if ($this->field[$point - 1][$line] === $this->field[$point + 1][$line]){
                    if(!$this->isEmpty($point - 1, $line)){
                        $curSymbol = $this->field[$point - 1][$line];
                    }
                }
            }
        }
        if($this->isAngular($x, $y)){
            if($x === $y){
                if(($x === 0) && ($this->field[2][2] === $this->field[1][1])){
                    $curSymbol = $this->field[1][1];                    
                } else if ($this->field[0][0] === $this->field[1][1]){
                    $curSymbol = $this->field[1][1];
                }
            } else {
                if(($this->field[$y][$x] === $this->field[1][1])){
                    $curSymbol = $this->field[1][1];
                }
            }
        }
        if(($curSymbol !== '') && ($curSymbol === $symbol)){
            return true;
        }
        return false;
    }
}


?>