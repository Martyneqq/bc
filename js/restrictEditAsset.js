window.onload = function () {
    var castka = document.querySelector('input[name="castka"]');
    var datum = document.querySelector('input[name="datum"]');
    var odpis = document.querySelector('select[name="odpis"]');
    var zpusob = document.querySelector('select[name="zpusob"]');
    var typ = document.querySelector('select[name="typ"]');

    disableFields();

    var buttonName = document.querySelector('button[name="update5"]');
    buttonName.addEventListener('click', function () {
        enableFields();
    });

    function disableFields() {
        castka.disabled = true;
        datum.disabled = true;
        odpis.disabled = true;
        zpusob.disabled = true;
        typ.disabled = true;
    }
    function enableFields() {
        castka.disabled = false;
        datum.disabled = false;
        odpis.disabled = false;
        zpusob.disabled = false;
        typ.disabled = false;
    }
};