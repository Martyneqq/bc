var clicked = false;
function changeLanguage()
{
    if (!clicked) {
        clicked = true;
        document.getElementById("language_icon").innerHTML = 'cz';
    } else {
        clicked = false;
        document.getElementById("language_icon").innerHTML = 'en';
    }
    // TODO
}