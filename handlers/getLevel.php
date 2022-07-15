<?php

    $db = new PDO('mysql:dbname=tictactoe;host=localhost','root','');
    $playerId = '';
    $level = 1;
    if (!(isset($_COOKIE['playerid']))){
        $playerId = microtime();
        $q = $db->prepare('INSERT INTO players VALUES (?, ?)');
        $q->execute(array($playerId, 1));
    } else {
        $playerId = $_COOKIE['playerid'];
        $q = $db->prepare('SELECT level FROM players WHERE id = ?');
        $q->execute(array($playerId));
        $level = $q->fetch()[0];
    }
    setcookie('playerid', $playerId, time() + 3600*24*30);

    $result = array ('level' => $level);
    echo json_encode($result);
