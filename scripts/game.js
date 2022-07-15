
class Game {
    #cells = {};
    #state;
    #winner;

    constructor() {
        this.#cells = {
            1: '',
            2: '',
            3: '',
            4: '',
            5: '',
            6: '',
            7: '',
            8: '',
            9: ''
        }
        this.#state = 0;
        this.#winner = [0, 0, 0];
    }

    isEmpty(number) {
        if (this.#cells[number] === '') {
            return true;
        }
        return false;
    }

    fillCell(number, symb) {
        if (this.isEmpty(number)) {
            this.#cells[number] = symb;
            this.checkCells();
            if (this.isFinished()) {
                this.getWinner();
            }
        }
    }

    getCells() {
        let cells = this.#cells;
        return cells;
    }

    isFinished() {
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
        if (this.#state !== 3) {
            for (let i = 0; i < 3; i++) {
                $('input.cell').eq(this.#winner[i] - 1).css('background-color', 'red');
            }
        }
        if (this.state === 1) {
            plusLevel();
        }
        showResetButton();
    }

    setCells(matrix) {
        this.#cells = matrix;
    }

    resetCells() {
        for (let index = 0; index < 9; index++) {
            this.#cells[index + 1] = '';
            $('input.cell').eq(index).val('');
            $('input.cell').eq(index).css('background-color', 'blue');
            this.#state = 0;
            this.#winner = [0,0,0];
        }

    }
}