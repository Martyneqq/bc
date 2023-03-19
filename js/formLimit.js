$(document).ready(function () {
    var html = '<tr><td><input class="form-control" type="text" name="nazevf[]" required=""></td><td><input class="form-control" type="date" name="datumf[]" required=""></td><td><select name="prijemvydajf[]" class="form-control"><option value="">--Vybrat--</option><option value="Příjem">Příjem</option><option value="Výdaj">Výdaj</option></select></td><td><input class="form-control" type="text" name="castkaf[]" required=""></td><td><select name="danf[]" class="form-control"><option value="">--Vybrat--</option><option value="Ano">Ano</option><option value="Ne">Ne</option></select></td><td><input class="form-control" type="text" name="dokladf[]" required=""></td><td><select name="uhradaf[]" class="form-control"><option value="">--Vybrat--</option><option value="Z účtu">Z účtu</option><option value="Hotovost">Hotovost</option></select></td><td><input class="form-control" type="text" name="popisf[]"></td><td><input class="btn btn-danger" type="button" id="remove" value="Odstranit"></td></tr>';
    
    var val = 5;
    var x = 1;

    $("#pridat").click(function () {
        if (x <= val) {
            $("#default-table").append(html);
            x++;
        }
    });
    $("#default-table").on('click', '#remove', function () {
        $(this).closest('tr').remove();
        x--;
    });
});