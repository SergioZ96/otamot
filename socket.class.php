<?php
/*
 *  Socket class
 *      - utilizes ElephantIO\Client as a dependency for emitting data to app.js(socket.io)
 *
 */ 

class Socket{
    
    public $client;

    // Dependency Injection for ElephantIO\Client object
    public function __construct($client){
        $this->client = $client;
    }

    function thumbLoader($data){
        $this->client->initialize();
        $this->client->emit('load_thumbs', [$data]);
        $this->client->close();
    }
}

?>