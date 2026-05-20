document.addEventListener("DOMContentLoaded", function () {

    const gameId =
        new URLSearchParams(window.location.search).get("id");

    const board = Chessboard("board", {
        onDrop: saveBoard
    });

    // flippy boi
    document
        .getElementById("flip-board")
        .addEventListener("click", () => {

            board.flip();

        });

    // laddar
    function loadBoard() {

        fetch(`/SlutprojektWEBB/public/api/get_latest_move.php?game_id=${gameId}`)

            .then(res => res.json())

            .then(data => {

                if (!data) return;

                if (!data.board_state) return;

                const parsed =
                    JSON.parse(data.board_state);

                board.setGameState(parsed);

                latestMoveId = data.id;

            });

    }

    // sparar plaese work wörk pls pls
    function saveBoard() {

        const gameState =
            board.getGameState();

        fetch("/SlutprojektWEBB/public/api/save_move.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                game_id: gameId,
                board_state: gameState
            })

        });

    }

    // live
    setInterval(() => {

        fetch(`/SlutprojektWEBB/public/api/get_latest_move.php?game_id=${gameId}`)

            .then(res => res.json())

            .then(data => {

                if (!data) return;

                if (data.id == latestMoveId) return;

                if (!data.board_state) return;

                const parsed =
                    JSON.parse(data.board_state);

                board.setGameState(parsed);

                latestMoveId = data.id;

            });

    }, 1000);

    loadBoard();

});