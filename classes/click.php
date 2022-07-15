<?php
    require_once ('Bot.php');
    require_once ('BotEasy.php');
    require_once ('BotHard.php');
    require_once ('BotMedium.php');
    require_once ('BotUnfair.php');
    require_once ('Field.php');

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
        $bot = new BotEasy($matrix, 'X');
    } elseif ($level < 10) {
        $bot = new BotMedium($matrix, 'X');
    } elseif ($level < 15){
        $bot = new BotHard($matrix, 'X');
    } else {
        $bot = new BotUnfair($matrix, 'X');
    }
   // $bot = new BotUnfair($matrix, 'X');
    $bot->chooseMove();

    for($i = 0; $i < 3; $i++){
        for($j = 0; $j < 3; $j++){
            $matrix[$i][$j] = $bot->getCell($i, $j);
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