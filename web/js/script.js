//Fonction pour générer une fenêtre de confirmation
$(function () {
    $('a[data-confirm]').click(function (ev) {
        var href = $(this).attr('href');

        if (!$('#dataConfirmModal').length) {
            $('body').append('<div id="dataConfirmModal" class="modal fade" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Non</button><a class="btn btn-danger" id="dataConfirmOK">Oui</a></div></div></div></div>');
        }
        $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show: true});

        return false;
    });
});


// script for datetimepicker
jQuery.datetimepicker.setLocale('fr');
jQuery('.datetimepicker').datetimepicker({
    format: 'd/m/Y H:i'
});

//script for datatables
$(document).ready(function () {
    $('#myTable').dataTable({
        "order": [[0, "desc"]],
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json"
        }
    });
});
// scrit for nav menu
$("span.menu").click(function () {
    $(".head-nav ul").slideToggle(300, function () {
        // Animation complete.
    });
});

//script for modals
$(function () {
    $("a.openmodal").click(function (e) {
        e.preventDefault();

        $("#modal .modal-content").load(this.href, function () {
            $("#modal").modal();
        })
    })
});

//script for ajax with get method
$(document).ready(function() {
    $('a.ajax').on('click', function(e){
        e.preventDefault();
        var route = $(this).attr('href');
        $.get(route,
            function(response){
                if(response.code === 100 && response.success){
                    document.getElementById("flash").innerHTML = "<div class='alert alert-success alert-dismissible fade in text-center' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>" + response.message + "</div>";
                }
                else
                {
                    document.getElementById("flash").innerHTML = '<div class="alert alert-danger alert-dismissible fade in text-center" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Une erreur est survenue</div>';
                }

            }, "json");
    })
});


