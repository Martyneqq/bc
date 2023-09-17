$(document).ready(function () {
    $('#close-modal').click(function () {
        $('#deleteModal').modal('hide');
    });

    $('.delete-button').click(function () {
        var id_info = $(this).data('id');
        var label_info = $(this).data('name');

        $.ajax({
            url: "delete1.php",
            method: 'post',
            data: {idd: id_info, label_info: 'label_info'},
            success: function (result) {
                // Display the result in the modal footer
                $('.modal-footer').html(result);
                
            }
        });
    });
}); 