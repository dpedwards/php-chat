/* Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en */
// JavaScript for dynamic user search and listing
const searchBar = document.querySelector(".search input"),
      searchIcon = document.querySelector(".search button"),
      usersList = document.querySelector(".users-list");

let updateTimer;

function fetchUsers(searchTerm = "") {
    clearTimeout(updateTimer);

    const fetchURL = `php/${searchTerm ? 'search.php' : 'users.php'}`;
    const fetchBody = searchTerm ? `searchTerm=${encodeURIComponent(searchTerm)}` : null;
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: fetchBody
    };

    fetch(fetchURL, requestOptions)
        .then(response => response.text())
        .then(data => {
            usersList.innerHTML = data;
            if (!searchTerm) {
                queueUpdate();
            }
        })
        .catch(error => console.error('Error:', error));
}

function queueUpdate() {
    clearTimeout(updateTimer);
    updateTimer = setTimeout(() => {
        fetchUsers();
    }, 5000);
}

searchBar.addEventListener('keyup', () => {
    const searchTerm = searchBar.value.trim();
    searchBar.classList.toggle("active", searchTerm !== "");
    fetchUsers(searchTerm);
});

queueUpdate();