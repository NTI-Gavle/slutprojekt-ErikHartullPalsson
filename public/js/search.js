const searchInput =
    document.getElementById("search-input");

const searchResults =
    document.getElementById("search-results");

if (searchInput) {

    searchInput.addEventListener("input", () => {

        const query =
            searchInput.value.trim();

        if (query.length < 1) {

            searchResults.innerHTML = "";

            return;
        }

        fetch(`/SlutprojektWEBB/public/api/search.php?q=${query}`)

            .then(res => res.json())

            .then(data => {

                searchResults.innerHTML = "";

                if (data.length === 0) {

                    searchResults.innerHTML = `
                        <div class="list-group-item">
                            Ingen träff
                        </div>
                    `;

                    return;
                }

                data.forEach(item => {

                    const div =
                        document.createElement("a");

                    div.className =
                        "list-group-item list-group-item-action";

                    if (item.type === "user") {

                        div.href =
                            `profile.php?id=${item.id}`;

                        div.innerHTML =
                            `👤 ${item.username}`;

                    } else {

                        div.href =
                            `join_game.php?id=${item.id}`;

                        div.innerHTML =
                            `♟ ${item.title}`;
                    }

                    searchResults.appendChild(div);

                });

            });

    });

    document.addEventListener("click", (e) => {

        if (!searchInput.contains(e.target)) {

            searchResults.innerHTML = "";
        }

    });

}