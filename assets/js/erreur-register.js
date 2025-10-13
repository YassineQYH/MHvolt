$(document).ready(function() {
    // Recherche des spans avec la classe "invalid-feedback"
    var invalidFeedbackSpans = $('span.invalid-feedback');

    // Vérification s'il y en a un ou plusieurs
    if (invalidFeedbackSpans.length > 0) {
        // Ouverture de la modal
        $('#divOuverture').addClass('active-popup active');
        console.log('error existantes')
    }
});