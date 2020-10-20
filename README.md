# Conway's Game of Life php implementation

The Game of Life, also known simply as Life, is a cellular automaton devised by the British mathematician John Horton Conway in 1970. 

It is a zero-player game, meaning that its evolution is determined by its initial state, requiring no further input. One interacts with the Game of Life by creating an initial configuration and observing how it evolves.

https://en.wikipedia.org/wiki/Conway's_Game_of_Life

## Run the game
### Parameters
- Rows: the universe grid rows (100 max)
- Columns: the universe grid columns (100 max)
- Density: float number from 0 to 1 for initial density. 
```bash
composer install
php src/index.php [rows] [columns] [density]
```