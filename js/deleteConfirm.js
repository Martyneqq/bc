/*$(document).ready(function () {
    // Attach the click event handler outside of the success callback
    $('#close-modal').click(function () {
        $('#deleteModal').modal('hide');
    });

    $('.delete-button').click(function () {
        var id_info = $(this).data('id');

        $.ajax({
            url: "delete1.php",
            method: 'post',
            data: {idd: id_info, delete: 'delete'},
            success: function (result) {
                // Display the result in the modal footer
                $('.modal-footer').html(result);
            }
        });
    });
});*/