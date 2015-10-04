var app = require('http').createServer(handler);
var io = require('socket.io')(app);

var Redis = require('ioredis');
var redis = new Redis(6379, '127.0.0.1');


app.listen(6001, function() {
    console.log('Server is running on port 6001');
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function(socket) {
    socket.emit('ping', { payload: 'connected' });
    console.log('client connected')
});

redis.psubscribe('*', function(err, count) {
  //
});

redis.on('pmessage', function(subscribed, channel, message) {
    message = JSON.parse(message);
//    console.log(message);
    io.emit(channel + ':' + message.event, message.data);
});
