const express = require('express');
var app = express();

const http = require('http');
const http_server = http.createServer(app).listen(3001);

const io = require('socket.io')(http_server);

// First listen to a connection and run the callback function
io.on('connection', (socket) => {

    // Listening for event of 'load_thumbs' from socket.class.php implemented from welcome_helper.php
    socket.on('load_thumbs',(data) => { // receive chat thumbnail record data

        var obj = JSON.parse(data[0]);
        var usernames;
        for(let i = 0; i < obj.length; i++){ // spruce up with some html
            usernames += "<button id='thumbnail" + obj[i].group_id + "' class='thumbnail' data-value='" + obj[i].user_id + "' value='" + obj[i].group_id + "'>" + obj[i].username + "</button>"; // we concatenate all usernames within JSON object
        }
        
        // emit the thumbnails to client side (welcome.php)
        io.emit('load_thumbs', usernames);
    });
    
    

});



/* http_server.listen(3001, () => {
    console.log('Server on port: 3001');
}); */

