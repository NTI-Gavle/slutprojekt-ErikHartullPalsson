function loadGames() {
    fetch("api/get_posts.php")
        .then(res => res.json())
        .then(data => {

            const container = document.getElementById("games-list");
            container.innerHTML = "";

            if (data.length === 0) {
                container.innerHTML = "<p class='text-center text-muted'>Inga öppna spel</p>";
                return;
            }

            data.forEach(post => {

                const col = document.createElement("div");
                col.className = "col-md-4 mb-4";

                col.innerHTML = `
                    <div class="card bg-dark text-white h-100">
                        <div class="card-body text-center">
                            <h5>${post.title}</h5>
                            <p>Skapad av: ${post.username}</p>
                            <button
                                class="btn btn-primary join-btn"
                                data-id="${post.id}"
                                data-owner="${post.user_id}">
                                Gå med
                            </button>
                        </div>
                    </div>`;

                container.appendChild(col);

                const button = col.querySelector(".join-btn");

                button.addEventListener("click", () => {

                    const ownerId = button.dataset.owner;
                    const gameId = button.dataset.id;

                    if (ownerId == CURRENT_USER_ID) {

                        showLobbyMessage(
                            "Du kan inte gå med i ditt egna spel"
                        );

                        return;
                    }

                    window.location.href =
                        "join_game.php?id=" + gameId;

                });
            });

        });
}

function checkGame() {
    fetch("api/check_game.php")
        .then(res => res.json())
        .then(game => {
            if (game && game.id) {
                window.location.href = "game.php?id=" + game.id;
            }
        });
}

document.addEventListener("DOMContentLoaded", () => {
    loadGames();

    setInterval(loadGames, 3000);
    setInterval(checkGame, 2000);
});

function showLobbyMessage(text) {

    document.getElementById(
        "lobby-message-text"
    ).innerText = text;

    document.getElementById(
        "lobby-message"
    ).style.display = "flex";
}

function closeLobbyMessage() {

    document.getElementById(
        "lobby-message"
    ).style.display = "none";
}