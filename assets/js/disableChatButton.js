document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.getElementById('user_message_submit');
    const reviewForm = document.getElementById('user_conversation_new_form');

    reviewForm.addEventListener('submit', function(event) {
        submitButton.setAttribute('disabled', true);
        submitButton.style.background = 'grey';
        submitButton.innerHTML = 'Envoi en cours...';
    });
});