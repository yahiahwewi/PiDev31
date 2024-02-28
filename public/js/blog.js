document.addEventListener('DOMContentLoaded', function () {
    const commentButtons = document.querySelectorAll('.comment-btn');

    commentButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const blogPostId = button.dataset.blogPostId;
            const container = document.getElementById('comment-form-container-' + blogPostId);

            // Make AJAX request to fetch the comment form
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/comment/form/' + blogPostId); // Update the URL to match your Symfony route
            xhr.onload = function () {
                if (xhr.status === 200) {
                    container.innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to load comment form');
                }
            };
            xhr.send();
        });
    });
});
