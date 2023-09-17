$(document).ready(function () {
    $('.info-row').click(function () {
        var label_info = $(this).data('name');
        var id_info = $(this).data('id');
        var value = $(this).data('value');

        $('.modal-title').html(label_info);
        $.ajax({url: "select_modal_info.php",
            method: 'post',
            data: {id_info: id_info, value: value},
            success: function (result) {
                $('.modal-body').html(result);
                $('#infoModal').modal("show");
            }
        });
    });
});