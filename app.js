const socket = require('socket.io');
const express = require('express');
const http = require('http');

var app = express();

var http_server = http.createServer(app).listen(3001);

