<?php
    $playerId = $_COOKIE['playerid'];
    $level = $_POST['value'];

    $db = new PDO('mysql:dbname=tictactoe;host=localhost','root','');
    $q = $db->prepare('UPDATE players SET level = ? WHERE id = ?');
    $q->execute(array($level, $playerId));

