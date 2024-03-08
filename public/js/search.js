// public/js/search.js

(function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;

        if (query.length >= 3) {
            fetch('/search?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(results => {
                    // Clear the search results
                    searchResults.innerHTML = '';

                    // Append the search results to the searchResults element
                    results.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.innerHTML = `<strong>${result.title}</strong>: ${result.description}`;
                        searchResults.appendChild(resultItem);
                    });
                })
                .catch(error => {
                    console.error('An error occurred during the search:', error);
                });
        } else {
            // Clear the search results if the query is less than 3 characters
            searchResults.innerHTML = '';
        }
    });
})();