
$(function(event) {
    // click stats container and show relavent content
    $('#student_course_details_stats_container-d').on('click', '.course_stats-d', function(e) {
        // alert('helo');
        $('.course_stats-d').removeClass('active');
        let elm = $(this);
        $(elm).addClass('active');

        let targetElm = '#' + $(elm).attr('data-target_elm');
        // console.log(targetElm);
        $('.main_work_container-d').hide();
  
        $(targetElm).show().removeClass('d-none');
        // $('.main_work_container-d').find(targetElm).show();

    });

        const container = document.querySelector('.rating');
        const items = container.querySelectorAll('.rating-item')
        container.onclick = e => {
        const elClass = e.target.classList;
        // change the rating if the user clicks on a different star
        if (!elClass.contains('active')) {
            items.forEach( // reset the active class on the star
            item => item.classList.remove('active')
            );
            console.log(e.target.getAttribute("data-rate"));
            elClass.add('active'); // add active class to the clicked star
            }
        };
});