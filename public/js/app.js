$(document).ready(() => {
    const opacity = "opacity-25";

    /* -- Afficher le menu -- */
    $(".navbar-toggler").on("click", function (e) {
        let target = $(this).data("target");
        $(target).toggleClass("show");
        $("main").addClass(opacity);
        $("input").prop("disabled", true);
    });

    /* -- Masquer le menu -- */
    $(".btn-close").on("click", function (e) {
        let target = $(this).data("target");
        $(target).removeClass("show");
        $("main").removeClass(opacity);
        $("input").prop("disabled", false);
    });

    /* -- Afficher une pop-up -- */
    $("#circle-info").on("click", () => {
        $("#print-info").show();
        $("#printer-item").addClass("none");
    });

    /* -- Fermer pop-up info impression -- */
    $("#close").on("click", () => {
        $("#print-info").hide();
        $("#printer-item").removeClass("none");
    });

    $(".delete-badgeage input").on("focus", () => {
        $("#badgeage").load();
    })

    /* -- Afficher une fenêtre de confirmation avant suppression -- */
    $(".delete-badgeage .form").submit(() => {
        confirm('Êtes-vous sûr de vouloir supprimer ce badgeage ?');
    })
});