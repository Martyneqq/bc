$(document).ready(function () {
    $('.btn btn-primary btn-sm').click(function () {
        var id = $(this).data('id');

        $('.modal-title').html(label_info);
        $.ajax({url: "select_modal_info.php",
            method: 'post',
            data: {id: id},
            success: function (result) {
                $('.modal-body').html(result);
                $('#infoModal').modal("show");
            }
        });
    });
});