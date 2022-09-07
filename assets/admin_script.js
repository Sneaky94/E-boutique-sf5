import './styles/admin_style.css';

//// code JS pour fixer le bug des inputs type file de Bootstrap 4
//// ce code permet aussi d'afficher l'image uploadée
let dataSrcUploadedImage = (inputElement, imgElement) => {  // version narrow function
    // let dataSrcUploadedImage = function(inputElement, imgElement) {  // version fonction anonyme
    if (inputElement.files && inputElement.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imgElement).prop("src", e.target.result);
            // ❗ fix Bootstrap File type input doesn't display name of uploaded file
            $(inputElement).next('.custom-file-label').html(inputElement.files[0].name);
        };
        reader.readAsDataURL(inputElement.files[0]);
    }
}

/* Comme jQuery a été intégré, on utilise l'évènement ready de jQuery pour ajouter le code JS */
$(function(){
    console.log('%c images ', 'background: #222; color: #bada55');

    // forms
    $("[type='file']").on("change", function(){
        var id = $(this).prop("id");            // je récu)ère l'identifiant de l'input
        var label = $("[for='" + id + "']");    // le label lié à l'input à l'attribut 'for' qui vaut l'id de l'input
        label.append("<img class='miniature ml-3'id='" + id + "img' >");  // j'ajoute une balise 'img'à ce label
        dataSrcUploadedImage(this, $('#' + id + 'img'));
    });
});