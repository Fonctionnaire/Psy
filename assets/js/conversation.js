function copyText(button) {
    // Trouver la div qui contient le texte
    var chatItemBody = button.parentNode.previousElementSibling.innerHTML;
    var element = document.querySelector("trix-editor");

    chatItemBody = removeBlockQuotes(chatItemBody);

    element.editor.setSelectedRange([0, 0]);

    element.editor.insertHTML('<div><blockquote>' + chatItemBody.trim() + '</blockquote></div>');
    // element.editor.insertLineBreak();
}

function removeBlockQuotes(text) {
    var cleanedText = text.replace(/<blockquote.*?>.*?<\/blockquote>/g, '');
    // Supprimer les balises br immédiatement après l'ouverture de la div
    cleanedText = cleanedText.replace(/<div>\s*<br\s*\/?>/g, '<div>');

    // Supprimer les balises br immédiatement avant la fermeture de la div
    cleanedText = cleanedText.replace(/<br\s*\/?>\s*<\/div>/g, '</div>');

    return cleanedText;
}