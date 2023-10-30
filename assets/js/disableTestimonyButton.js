document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.getElementById('testimony_submit');
    const testimonyForm = document.getElementById('testimony_new_form');

    testimonyForm.addEventListener('submit', function(event) {
        submitButton.setAttribute('disabled', true);
        submitButton.style.background = 'grey';
        submitButton.innerHTML = 'Envoi en cours...';
    });
});