var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var db = require('./db.js');
var mydb = new db();

app.get('/', function(req, res) {
    res.send('Working Fine');
});
var sockets = {};
var arr = [];
io.on('connection', function(socket) {


    socket.on('chat_message_send', function(data) {
        io.emit('chat_message_receive', {
            message_uuid: data.message_uuid,
            message_body: data.message_body,
            tagged_message: data.tagged_message,
            message_time: data.message_time,

            sender: data.sender,

            chat_uuid: data.chat_uuid,
            chat_total_messages_count: data.chat_total_messages_count,
        });
    });

    // socket.on('appointment_status_send', function(data) {
    //     io.emit('appointment_status_receive', {
    //         appointment: data.appointment,
    //     });
    // });

    // socket.on('review_send', function(data) {
    //     io.emit('review_receive', {
    //         review: data.review,
    //     });
    // });
    // socket.on('comment_send', function(data) {
    //     io.emit('comment_receive', {
    //         'comment_id': data.comment_id,
    //         'post_id': data.post_id,
    //         'user_id': data.user_id,
    //         'user_name': data.user_name,
    //         'user_image': data.user_image,
    //         'comment': data.comment,
    //         'created_at': data.created_at,
    //         // 'comments_count': data.comments_count
    //     });
    // });

    socket.on('disconnect', function() {
        if (sockets[socket.id] != undefined) {
            mydb.releaseRequest(sockets[socket.id].user_id).then(function(result) {
                console.log('disconected: ' + sockets[socket.id].request_id);
                io.emit('request-released', {
                    'request_id': sockets[socket.id].request_id
                });
                delete sockets[socket.id];
            });
        }
    });

    socket.on("connect_error", (err) => {
        console.log(`connect_error due to ${err.message}`);
    });
});

http.listen(1028, function() {
    console.log('working fine');
});
