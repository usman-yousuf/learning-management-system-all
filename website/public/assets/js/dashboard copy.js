//  graph  dashboard

$(function(event) {

    // const month_names = JSON.parse(data_month_names);
    // const online_course_data = JSON.parse(onlineCoursesData);
    // const video_course_data = JSON.parse(videoCoursesData);

    // const month_names = data_month_names;
    // const video_course_data = videoCoursesData;
    // const online_course_data = onlineCoursesData;

    // setup Course Video Course to display Graph
    const videoData = {
        labels: data_month_names,
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
        data: videoCoursesData,
        options: {}
    };
    var videoCoursesChart = new Chart(
        document.getElementById('video_course_chart-d'),
        videoConfig
    );

    // setup Online Course to display Graph
    const onlineData = {
        labels: data_month_names,
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
            data: onlineCoursesData
        }]
    };
    const onlineConfig = {
        type: 'line',
        data: onlineCoursesData,
        options: {}
    };
    var onlineCoursesChart = new Chart(
        document.getElementById('online_course_chart-d'),
        onlineConfig
    );

    // console.log(month_names, video_course_data, online_course_data);
});

//  graph  dashboard End
