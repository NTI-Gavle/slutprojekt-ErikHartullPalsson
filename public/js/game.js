const gameId = new URLSearchParams(window.location.search).get("id");

let board = null;
let game = new Chess();

let lastMoveId = 0;

//  board
board = Chessboard('board', {
    draggable: true,
    position: 'start',
    onDrop: onDrop
});

setInterval(() => {
    fetch(`/api/get_moves.php?game_id=${gameId}&last_id=${lastMoveId}`)
        .then(res => res.json())
        .then(data => {

            data.forEach(move => {
                game.load(move.fen);
                board.position(move.fen);
                lastMoveId = move.id;
            });

        });
}, 1000);

// drag
function onDrop(source, target) {

    let move = game.move({
        from: source,
        to: target,
        promotion: 'q'
    });

    if (move === null) return 'snapback';

    // Skicka 
    fetch('/api/send_move.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            game_id: gameId,
            from: source,
            to: target,
            fen: game.fen()
        })
    });
}