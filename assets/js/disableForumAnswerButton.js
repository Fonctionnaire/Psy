document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.getElementById('forum_answer_submit');
    const reviewForm = document.getElementById('forum_answer_new_form');

    reviewForm.addEventListener('submit', function(event) {
        submitButton.setAttribute('disabled', true);
        submitButton.style.background = 'grey';
        submitButton.innerHTML = 'Envoi en cours...';
    });
});