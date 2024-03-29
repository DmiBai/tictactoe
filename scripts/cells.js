class Game {
    #cells = {};
    #state;
    #winner;
    #level;

    constructor() {
        this.#cells = { 1: '', 2: '', 3: '', 4: '',
            5: '', 6: '', 7: '', 8: '', 9: '' }
        this.#state = 0;
        this.#winner = [0, 0, 0];
        this.#level = 1;
    }

    isEmpty(number) {
        if (this.#cells[number] === '') {
            return true;
        }
        return false;
    }

    fillCell(number, symbol) {
        if ((this.isEmpty(number)) && (this.#state === 0)) {
            this.#cells[number] = symbol;
            this.checkCells();
            if (this.getState()) {
                this.getWinner();
            }
        }
    }

    getCells() {
        return this.#cells;
    }

    getState() {
        return this.#state;
    }

    checkCells() {
        for (let i = 0; i < 3; i++) {
            if ((this.#cells[1 + (i * 3)] !== '') && (this.#cells[1 + (i * 3)] === this.#cells[2 + (i * 3)]) && (this.#cells[2 + (i * 3)] === this.#cells[3 + (i * 3)])) {
                if (this.#cells[1 + (i * 3)] === 'X') {
                    this.#state = 1;
                } else {
                    this.#state = 2;
                }
                this.#winner = [1 + (i * 3), 2 + (i * 3), 3 + (i * 3)];
                return;
            }
        }
        for (let i = 0; i < 3; i++) {
            if ((this.#cells[1 + i] !== '') && (this.#cells[1 + i] === this.#cells[4 + i]) && (this.#cells[4 + i] === this.#cells[7 + i])) {
                if (this.#cells[1 + i] === 'X') {
                    this.#state = 1;
                } else {
                    this.#state = 2;
                }
                this.#winner = [1 + i, 4 + i, 7 + i];
                return;
            }
        }
        if ((this.#cells[5] != '') && (this.#cells[1] === this.#cells[5]) && (this.#cells[9] === this.#cells[5])) {
            if (this.#cells[5] === 'X') {
                this.#state = 1;
            } else {
                this.#state = 2;
            }
            this.#winner = [1, 5, 9];
        }
        if ((this.#cells[5] != '') && (this.#cells[3] === this.#cells[5]) && (this.#cells[7] === this.#cells[5])) {
            if (this.#cells[5] === 'X') {
                this.#state = 1;
            } else {
                this.#state = 2;
            }
            this.#winner = [3, 5, 7];
        }
        let trigger = 0;
        for (let i = 1; i < 10; i++) {
            if (this.#cells[i] === '') {
                trigger++;
            }
        }
        if (trigger === 0) {
            this.#winner = [0, 0, 0];
            this.#state = 3;
        }
    }

    getWinner() {
        if (this.#state !== 3) { //3 - row
            for (let i = 0; i < 3; i++) {
                $('input.cell').eq(this.#winner[i] - 1).css('background-color', 'red');
            }
        }
        if (this.#state === 2) {
            this.increaseLevel();
        } else {
            if (this.#state === 1) {
                this.decreaseLevel();
            }
        }
        showResetButton();
    }

    setLevel(level){
        if(level >= 1) {
            this.#level = level;
        }
        this.changeLevel();
    }

    setCells(matrix) {
        this.#cells = matrix;
    }

    resetCells() {
        for (let index = 0; index < 9; index++) {
            this.#cells[index + 1] = '';
            $('input.cell').eq(index).val('');
            $('input.cell').eq(index).css('background-color', 'blue');
        }
        this.#state = 0;
        this.#winner = [0,0,0];
    }

    increaseLevel(){
        this.#level++;
        this.changeLevel();
    }

    decreaseLevel(){
        this.#level--;
        this.changeLevel();
    }

    changeLevel() {
        sendLevel(this.#level);
        this.showBotLevel();
        $('#userLevelVal').text(this.#level);
    }

    getLevel(){
        return this.#level;
    }

    showBotLevel(){
        if(this.#level < 5){
            $('#botLevelVal').text('Easy');
        } else if(this.#level < 10){
            $('#botLevelVal').text('Medium');
        } else if(this.#level < 15){
            $('#botLevelVal').text('Hard');
        } else if(this.#level < 10) {
            $('#botLevelVal').text('Unfair');
        }
    }
}
///////////////////////////////////////////
let game = new Game();

function sendLevel(level){
    $.ajax({
        url: 'handlers/setLevel.php',
        type: 'POST',
        dataType: 'html',
        data: {value : level },
        success: function (response) {
            let result = $.parseJSON(response);
            game.setLevel(result.level);
        },
        error: function (response) {
        }
    });
}

function getLevel(){
    $.ajax({
        url: 'handlers/getLevel.php',
        type: 'POST',
        dataType: 'html',
        data: null,
        success: function (response) {
            console.log(response);
            let result = $.parseJSON(response);
            game.setLevel(result.level);
        },
        error: function (response) {
        }
    });
}

$(document).ready(function () {
    getLevel();

    $(document).on('click', 'input.cell', function () {
        if (($(this).val() === '') && !(game.getState())){
            let num = Number($(this).attr('name').slice(1));
            game.fillCell(num, 'O');
            $(this).val('O');
            if(!game.getState()) {
                ajaxQuery('handlers/click.php', game.getCells());
            }
        }
    });
});

$(document).ready(function () {
    $(document).on('click', '#again_btn', function () {
        game.resetCells();
        showResetButton();
    });
});

function ajaxQuery(url, sendData) {
    $.ajax({
        url: url,
        type: 'POST', //метод отправки
        dataType: 'html', //формат данных
        data: { field: sendData , level: game.getLevel() },
        success: function (response) {
            console.log(response);
            let result = $.parseJSON(response);
            $('input.cell').eq(result.cellNum - 1).val(result.cellVal);
            game.fillCell(result.cellNum, result.cellVal);
        },
        error: function (response) {
        }
    });
}

function showResetButton() {
    $("#again_btn").toggle();
}