//  graph  dashboard

$(function(event) {

    // setup Course Video Course to display Graph
    const videoData = {
        labels: month_names,
        datasets: [{
            label: 'Video Course Payments',
            backgroundColor: '#289DE5',
            borderColor: '#289DE5',
            fill: false,
            pointBorderColor: '#289DE5',
            pointBorderWidth: '5',
            pointHoverBorderWidth: '5',
            pointHoverBackgroundColor: '#fff',
            pointBackgroundColor: '#289DE5',
            pointHoverBorderColor: '#289DE5',
            data: videoCoursesData
        }]
    };
    const videoConfig = {
        type: 'line',
        data: videoData,
        options: {}
    };
    var videoCoursesChart = new Chart(
        document.getElementById('video_course_chart-d'),
        videoConfig
    );

    // setup Online Course to display Graph
    const onlineData = {
        labels: month_names,
        datasets: [{
            label: 'Online Course Payment',
            backgroundColor: '#289DE5',
            borderColor: '#289DE5',
            fill: false,
            pointBorderColor: '#289DE5',
            pointBorderWidth: '5',
            pointHoverBorderWidth: '5',
            pointHoverBackgroundColor: '#fff',
            pointBackgroundColor: '#289DE5',
            pointHoverBorderColor: '#289DE5',
            data: videoCoursesData
        }]
    };
    const onlineConfig = {
        type: 'line',
        data: onlineData,
        options: {}
    };
    var onlineCoursesChart = new Chart(
        document.getElementById('online_course_chart-d'),
        onlineConfig
    );
});

//  graph  dashboard End
