document.addEventListener('DOMContentLoaded', (event) => {
    const replyButton = document.querySelectorAll('.button-answer');
    const quoteButtons = document.querySelectorAll('.btn-quote');
    const chatFormAnswer = document.querySelector('.editor-form-answer');
    const closeButton = document.querySelector('.close-icon');
    const chatForm = document.querySelector('.answer_form');
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
        chatFormAnswer.style.zIndex = '9';
        chatFormAnswer.style.transition = 'all 0.5s ease-in-out';

        if(replyButton.length > 0){
            replyButton.forEach(button => {
                button.setAttribute('disabled', true);
            });
        }else{
            replyButton.setAttribute('disabled', true);
        }


        quoteButtons.forEach(button => {
            button.setAttribute('disabled', true);
        });
    };

    const hideChatForm = () => {
        chatFormAnswer.style.display = 'none';
        element.editor.loadHTML('');
        if(replyButton.length > 0) {
            replyButton.forEach(button => {
                button.removeAttribute('disabled');
            });
        }else{
            replyButton.removeAttribute('disabled');
        }
        quoteButtons.forEach(button => {
            button.removeAttribute('disabled');
        });
    };

    if (replyButton) {
        replyButton.forEach(button => {
            button.addEventListener('click', (e) => {
                showChatForm();
            });
        })
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
