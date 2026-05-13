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
                            <a href="join_game.php?id=${post.id}" class="btn btn-primary">
                                Gå med
                            </a>
                        </div>
                    </div>
                `;

                container.appendChild(col);
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