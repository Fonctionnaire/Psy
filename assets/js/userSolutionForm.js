document.addEventListener("DOMContentLoaded", function() {
    // Sélectionner les boutons radio
    var radioYes = document.getElementById("user_solution_isPsyConsulted_0");
    var radioNo = document.getElementById("user_solution_isPsyConsulted_1");

    // Sélectionner la div à montrer/cacher
    var divToShowHide = document.querySelector(".solution-form-optional");

    // Fonction pour vérifier l'état des boutons radio et montrer/cacher la div
    function updateDivVisibility() {
        if (radioNo.checked) {
            divToShowHide.style.display = "block"; // Montrer la div
        } else if (radioYes.checked) {
            divToShowHide.style.display = "none"; // Cacher la div
        }
    }

    // Ajouter des écouteurs d'événements sur les boutons radio
    radioYes.addEventListener("change", updateDivVisibility);
    radioNo.addEventListener("change", updateDivVisibility);

    // Appel initial pour définir l'état initial de la div
    updateDivVisibility();
});