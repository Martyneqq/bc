$(document).ready(function () {
    $('.prodej').click(function () {
        var id_asset = $(this).closest('tr').find('.asset_id').val();

        console.log(id_asset);
        $.ajax({
            url: window.location.href,
            method: 'post',
            data: { id_asset: id_asset },
            success: function (result) {
                //$('.show-asset-sale').html(result);
                $('#sale').modal("show");
            }
        });
    });
});