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
    });

    /* -- Afficher une fenêtre de confirmation avant suppression -- */
    $(".delete-badgeage form").on('submit', () => {
        return confirm('Êtes-vous sûr de vouloir supprimer ce badgeage ?');
    });

    /* -- DataTables -- */
    $('#datatables').DataTable({
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Résultats par page :_MENU_",
            info: "Résulats _START_ &agrave; _END_ sur _TOTAL_",
            infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Préc.",
                next: "Suiv.",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }
    });
});