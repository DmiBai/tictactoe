<?php
    require_once './classes/BotEasy.php';
    require_once './classes/BotMedium.php';
    require_once './classes/BotHard.php';
    require_once './classes/BotUnfair.php';
    require_once './classes/Bot.php';

    $field = $_POST['field'];
    $level = $_POST['level'];

    $matrix = array( 
        array('','',''),
        array('','',''),
        array('','','')
        );

    $number = 1;
    for($i = 0; $i < 3; $i++){
        for($j = 0; $j < 3; $j++){
            $matrix[$i][$j] = $field[$number];
            $number++;
        }
    }

    if($level < 5){
        $bot = new \Classes\BotUnfair($matrix, 'X');
    } elseif ($level < 10) {
        $bot = new \Classes\BotEasy($matrix, 'X');
    } elseif ($level < 15){
        $bot = new \Classes\BotMedium($matrix, 'X');
    } else {
        $bot = new \Classes\BotHard($matrix, 'X');
    }
    $bot->chooseMove();

    for($i = 0; $i < 3; $i++){
        for($j = 0; $j < 3; $j++){
            $matrix[$i][$j] = $bot->getField()->getCell($i, $j);
        }
    }
    
    $field = array (1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 6 => '', 7 => '', 8 => '', 9 => '');
    $number = 1;
    for($i = 0; $i < 3; $i++){
        for($j = 0; $j < 3; $j++){
            $field[$number] = $matrix[$i][$j];
            $number++;
        }
    }
    $diff = array_diff_assoc($field, $_POST['field']);
    $key = array_key_first($diff);
    $result = array('cellNum' => $key,
                    'cellVal' => $diff[$key]);

    echo json_encode($result);