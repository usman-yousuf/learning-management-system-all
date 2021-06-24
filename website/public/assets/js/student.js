document.addEventListener('DOMContentLoaded', function() {

    var clickelement = document.getElementsByClassName("search_dropdown-d");
    Array.from(clickelement).forEach( elm => {
        elm.addEventListener("click" , showDropMenu);
    });
    
    function showDropMenu() {
        this.classList.add("outline_none-s");
        document.getElementById("search_ref_option-d").classList.remove("display_none-s");
        document.getElementById("search_ref_option-d").classList.toggle("display_block-s");
    }
    // close dropdown when click anywhere else
    onclick = function(event) {
        if (!event.target.matches('.search_dropdown-d')) {
            var dropdowns = document.getElementsByClassName("search_ref_option-d");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('display_block-s')) {
                   openDropdown.classList.remove('display_block-s');
                }
            }
        }
    } 
    
});