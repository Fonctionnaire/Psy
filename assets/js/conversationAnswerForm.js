document.addEventListener('DOMContentLoaded', (event) => {
    const replyButton = document.querySelector('.chat-button-answer');
    const quoteButtons = document.querySelectorAll('.btn-style-chat');
    const chatFormAnswer = document.querySelector('.chat-form-answer');
    const closeButton = document.querySelector('.close-icon');
    const chatForm = document.getElementById('user_conversation_new_form');
    const toggledText = document.querySelector('.chat-answer-toggled-text');

    const toggleIcon = document.querySelector('.toggle-icon');
    let isExpanded = true; // État initial
    var element = document.querySelector("trix-editor");
    const toggleChatForm = () => {

        if (isExpanded) {
            chatFormAnswer.style.height = '50px'; // Hauteur réduite
            toggleIcon.innerHTML = '<i class="fa-solid fa-circle-arrow-up"></i>'; // Flèche vers le haut
            toggleIcon.setAttribute('title', 'Augmenter');
            // Cacher le contenu ici, afficher "Votre message"
            chatForm.style.display = 'none';
            toggledText.style.display = 'inherit';
        } else {
            chatFormAnswer.style.height = 'initial'; // Hauteur initiale
            toggleIcon.innerHTML = '<i class="fa-solid fa-circle-arrow-down"></i>'; // Flèche vers le bas
            toggleIcon.setAttribute('title', 'Réduire');
            // Réafficher le contenu ici
            chatForm.style.display = 'inherit';
            toggledText.style.display = 'none';
        }
        isExpanded = !isExpanded; // Basculer l'état
    };

    toggleIcon.addEventListener('click', toggleChatForm);

    const showChatForm = () => {
        chatFormAnswer.style.display = 'inherit';
        chatFormAnswer.style.position = 'fixed';
        chatFormAnswer.style.bottom = '0';
        chatFormAnswer.style.left = '5%';
        chatFormAnswer.style.right = '5%';
        chatFormAnswer.style.zIndex = '2';
        chatFormAnswer.style.transition = 'all 0.5s ease-in-out';
    };

    const hideChatForm = () => {
        chatFormAnswer.style.display = 'none';
        element.editor.loadHTML('');
    };

    if (replyButton) {
        replyButton.addEventListener('click', (e) => {
            showChatForm();
        });
    }

    quoteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            showChatForm();
        });
    });

    closeButton.addEventListener('click', (e) => {
        hideChatForm();
    });
});
