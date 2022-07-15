<?php

class Field
{
    private array $cells;

    public function __construct(array $field){
        $this->cells = $field;
    }

    public function checkCell(int $x, int $y): int
    {
        if ($this->cells[$x][$y] == '') {
            return -1; //''
        } elseif ($this->cells[$x][$y] == 'X') {
            return 1; //'X'
        } else {
            return 0; //'O'
        }
    }

    public function fillCell(int $x, int $y, string $symbol): void
    {
        $this->cells[$x][$y] = $symbol;
        return;
    }

    public function getCell($x, $y): string
    {
        return $this->cells[$x][$y];
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function isAngular(int $x, int $y): bool //no. laravel only
    {
        if (($x !== 1) && (($x + $y) % 2 === 0)) {
            return true;
        }   
        return false;
    }
    
    public function isSide(int $x, int $y): bool //no stupid jokes here
    {
        if ((($x !== 1) || ($y !== 1)) && (($x + $y) % 2 === 1)) {
            return true;
        }   
        return false;
    }

    public function isEmpty(int $x, int $y): bool
    {
        if ($this->cells[$x][$y] === '') {
            return true;
        }
        return false;
    }
}