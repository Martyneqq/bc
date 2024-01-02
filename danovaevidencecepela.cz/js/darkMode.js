document.addEventListener("DOMContentLoaded", function () {
    var body = document.body;
    var toggleDark = document.getElementById('going_dark');
    var lower = document.getElementsByClassName('container-lower');
    var upper = document.getElementsByClassName('container-upper');

    if (sessionStorage.getItem('backgroundColor') === "black" && sessionStorage.getItem('color') === "white") {
        body.style.backgroundColor = "black";
        body.style.color = "white";
    } else {
        body.style.backgroundColor = "white";
        body.style.color = "black";
    }

    toggleDark.addEventListener("click", function () {
        if (body.style.backgroundColor === "black") {
            body.style.backgroundColor = "white";
            body.style.color = "black";
            sessionStorage.setItem("backgroundColor", "white");
            sessionStorage.setItem("color", "black");
        } else {
            body.style.backgroundColor = "black";
            body.style.color = "white";
            sessionStorage.setItem("backgroundColor", "black");
            sessionStorage.setItem("color", "white");
        }
    });
});