$(document).ready(function () {
    $('.info-row').click(function () {
        var id_info = $(this).data('id');
        var label_info = $(this).data('name');
        $('.modal-title').html(label_info);
        $.ajax({url: "select_modal_info.php",
            method: 'post',
            data: {id_info: id_info},
            success: function (result) {
                $('.modal-body').html(result);
                $('#infoModal').modal("show");
            }
        });
    });
});