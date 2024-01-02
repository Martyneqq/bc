$(document).ready(function () {
    $('.asset_depreciation').click(function () {

        var id_info = $(this).closest('tr').find('.asset_id').val();
        var label_info = $(this).closest('tr').find('.label_info').text();

        console.log(id_info);
        console.log(label_info);

        $('#showAssetTableTitle').html(label_info);
        $.ajax({
            url: "select_modal_info.php",
            method: 'post',
            data: { id_info: id_info },
            success: function (result) {
                $('.show-depreciation').html(result);
                $('#infoModal').modal("show");
            }
        });
    });
});