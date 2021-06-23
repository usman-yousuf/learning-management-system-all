document.addEventListener('DOMContentLoaded', function() {

var clickelement = document.getElementsByClassName("search_Dropdown-d");
Array.from(clickelement).forEach( elm => {
    elm.addEventListener("click" , showDropMenu);
});

function showDropMenu() {
    document.getElementById("search_ref_option-d").classList.toggle("show");
}

});