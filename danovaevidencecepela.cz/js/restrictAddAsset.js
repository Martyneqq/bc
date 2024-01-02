document.addEventListener('DOMContentLoaded', function () {

    var castkaInput = document.querySelector('input[name="castka1"]');
    var typInput = document.querySelector('select[name="typ1"]');
    var errorMessage = document.querySelector('#error-message');
    var saveButton = document.getElementById('ulozitButton');
    var saveAndNextButton = document.getElementById('ulozitADalsiButton');

    function UpdateFields() {
        if (castkaInput.value < 80000 && typInput.value === 'Hmotný') {
            saveButton.disabled = true;
            saveAndNextButton.disabled = true;
            //console.log(castkaInput.value);
        } else {
            saveButton.disabled = false;
            saveAndNextButton.disabled = false;
            //console.log(castkaInput.value);
        }
    }

    if (castkaInput && typInput) {
        castkaInput.addEventListener('input', UpdateFields);
        typInput.addEventListener('change', UpdateFields);
    }
});