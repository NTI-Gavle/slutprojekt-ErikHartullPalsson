document.addEventListener("DOMContentLoaded", function () {

    const gameId =
        new URLSearchParams(window.location.search).get("id");

    const board = Chessboard("board", {
        onDrop: saveBoard
    });

    let gameFinished = false;

    window.showGameOver = function (title, text) {

        gameFinished = true;

        board.stopGame();

        document.getElementById("game-over-title")
            .innerText = title;

        document.getElementById("game-over-text")
            .innerText = text;

        document.getElementById("game-over-overlay")
            .style.display = "flex";
    };

    document
        .getElementById("close-game-over")
        .addEventListener("click", () => {

            document
                .getElementById("game-over-overlay")
                .style.display = "none";

        });

    // flippy boi   
    document
        .getElementById("flip-board")
        .addEventListener("click", () => {

            board.flip();

        });

    document
        .getElementById("send-chat")
        .addEventListener("click", sendMessage);

    document
        .getElementById("chat-input")
        .addEventListener("keypress", e => {

            if (e.key === "Enter") {
                sendMessage();
            }

        });

    document
        .getElementById("resign-btn")
        .addEventListener("click", resignGame);

    document
        .getElementById("draw-btn")
        .addEventListener("click", offerDraw);

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

    function checkGameStatus() {

        if (gameFinished) return;

        fetch(`/SlutprojektWEBB/public/api/get_game.php?id=${gameId}`)

            .then(res => res.json())

            .then(game => {

                if (!game) return;

                if (game.status === "draw") {

                    gameFinished = true;

                    board.stopGame();

                    showGameOver(
                        "Remi",
                        "Matchen slutade oavgjort"
                    );

                    return;
                }

                if (game.status === "finished") {

                    gameFinished = true;

                    board.stopGame();

                    let winnerText = "Någon vann";

                    if (game.winner === "white") {
                        winnerText = "Vit vann";
                    }

                    if (game.winner === "black") {
                        winnerText = "Svart vann";
                    }

                    showGameOver(
                        "Schackmatt",
                        winnerText
                    );

                }

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

    function resignGame() {

        const winner =
            PLAYER_COLOR === "white"
                ? "black"
                : "white";

        fetch("/SlutprojektWEBB/public/api/finish_game.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({

                game_id: gameId,

                result: "finished",

                winner: winner

            })

        })

            .then(() => {

                showGameOver(
                    "Du gav upp",
                    "Matchen är avslutad"
                );

            });

    }

    function offerDraw() {

        fetch("/SlutprojektWEBB/public/api/offer_draw.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                game_id: gameId
            })

        })

        const box =
            document.getElementById("draw-notification");

        box.style.display = "block";

        box.innerHTML = `
        <p>Remi erbjudande skickat</p>`;

    }

    function checkDrawOffer() {

        if (gameFinished) {

            const box =
                document.getElementById("draw-notification");

            box.style.display = "none";

            return;
        }

        fetch(`/SlutprojektWEBB/public/api/get_game.php?id=${gameId}`)

            .then(res => res.json())

            .then(game => {

                const box =
                    document.getElementById("draw-notification");

                if (
                    game.status === "draw" ||
                    game.status === "finished"
                ) {

                    box.style.display = "none";

                    gameFinished = true;

                    board.stopGame();

                    return;
                }

                if (!game.draw_offer_by) {

                    box.style.display = "none";

                    return;
                }

                if (game.draw_offer_by == CURRENT_USER_ID) {

                    box.style.display = "block";

                    box.innerHTML = `
                    <p>Remi erbjudande skickat</p>
                `;

                    return;
                }

                box.style.display = "block";

                box.innerHTML = `

                <p>Motståndaren erbjuder remi</p>

                <div class="draw-buttons">

                    <button
                        id="accept-draw"
                        class="btn btn-success btn-sm">
                        Acceptera
                    </button>

                    <button
                        id="decline-draw"
                        class="btn btn-danger btn-sm">
                        Neka
                    </button>

                </div>`;

                document
                    .getElementById("accept-draw")
                    ?.addEventListener("click", () => {

                        fetch("/SlutprojektWEBB/public/api/accept_draw.php", {

                            method: "POST",

                            headers: {
                                "Content-Type": "application/json"
                            },

                            body: JSON.stringify({
                                game_id: gameId
                            })

                        })

                            .then(() => {

                                gameFinished = true;

                                board.stopGame();

                                box.style.display = "none";

                                showGameOver(
                                    "Remi",
                                    "Matchen slutade oavgjort"
                                );

                            });

                    });

                document
                    .getElementById("decline-draw")
                    ?.addEventListener("click", () => {

                        fetch("/SlutprojektWEBB/public/api/decline_draw.php", {

                            method: "POST",

                            headers: {
                                "Content-Type": "application/json"
                            },

                            body: JSON.stringify({
                                game_id: gameId
                            })

                        });

                        box.style.display = "none";

                    });

            });

    }

    function loadMessages() {

        fetch(`/SlutprojektWEBB/public/api/get_messages.php?game_id=${gameId}`)

            .then(res => res.json())

            .then(messages => {

                const chatBox =
                    document.getElementById("chat-box");

                chatBox.innerHTML = "";

                messages.forEach(msg => {

                    const div =
                        document.createElement("div");

                    div.className = "chat-message";

                    div.innerHTML = `
                    <div class="chat-user">
                        ${msg.username}
                    </div>

                    <div>
                        ${msg.message}
                    </div>
                `;

                    chatBox.appendChild(div);

                });

                chatBox.scrollTop =
                    chatBox.scrollHeight;

            });

    }

    function sendMessage() {

        const input =
            document.getElementById("chat-input");

        const message =
            input.value.trim();

        if (message === "") return;

        fetch("/SlutprojektWEBB/public/api/send_message.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                game_id: gameId,
                message: message
            })

        })

            .then(() => {

                input.value = "";

                loadMessages();

            });

    }

    // live
    setInterval(() => {

        fetch(`/SlutprojektWEBB/public/api/get_latest_move.php?game_id=${gameId}`)

            .then(res => res.json())

            .then(data => {

                if (!data) return;

                if (data.board_state && data.id != latestMoveId) {

                    const parsed =
                        JSON.parse(data.board_state);

                    board.setGameState(parsed);

                    latestMoveId = data.id;

                }

                checkDrawOffer();
            });

    }, 1000);

    loadBoard();
    setInterval(checkDrawOffer, 2000);

    loadMessages();

    setInterval(loadMessages, 2000);

    setInterval(checkGameStatus, 1000);

});