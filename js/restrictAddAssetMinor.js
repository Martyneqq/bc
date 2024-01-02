document.addEventListener('DOMContentLoaded', function () {

    var prijemvydajSelect = document.querySelector('select[name="prijemvydaj"]');
    var castkaInput = document.querySelector('input[name="castka"]');
    var buttonName = document.getElementById('ulozitButton');

    //console.log(window.location.href);
    if (window.location.href === "https://danovaevidencecepela.cz/majetek_drobny.php") {
        prijemvydajSelect.disabled = true;
        prijemvydajSelect.value = "Výdaj";
    }
    function UpdateFields() { // disable for greater price
        if (castkaInput.value >= 80000) {
            //console.log(castkaInput.value);
            disableFields();
        } else {
            buttonName.disabled = false;
        }
    }
    if (castkaInput) {
        castkaInput.addEventListener('input', UpdateFields); // update
    }

    buttonName.addEventListener('click', function () { // enable before db save
        enableFields();
    });

    function enableFields() {
        prijemvydajSelect.disabled = false;
    }
    function disableFields() {
        buttonName.disabled = true;
    }
});