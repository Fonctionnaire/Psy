function copyText(button) {
    // Trouver la div qui contient le texte
    var chatItemBody = button.parentNode.previousElementSibling.textContent;
    var element = document.querySelector("trix-editor");
    element.editor.setSelectedRange([0, 0]);

    element.editor.insertHTML('<div><blockquote>' + chatItemBody.trim() + '</blockquote></div><br>');
    element.editor.insertLineBreak();
}

