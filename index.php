<?php

require('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
var_dump($_ENV['NAME']);

/*
// Include router class
include('route.php');

// Add base route (startpage)
Route::add('/', function(){
    echo 'Welcome';
});

Route::add('/test.php', function(){
    echo 'Hello from test.php';
});

Route::add('/posting', function(){
    echo 'Hey! The form has been sent!<br>';
    print_r($_POST);
}, 'post');

Route::run('/');
*/
/*
$request = $_SERVER['REQUEST_URI'];
//$request = trim($request,'/');

switch($request){
    case '/':
        require '/index.php';
        break;
    case '':
        require '/index.php';
        break;
    case '/test':
        require __DIR__ .'routes/test.php';
        break;
    default:
        http_response_code(404);
        break;
}
*/

?>

<!doctype html>
<html>
  <head>
    <title>Hello, World! | Foo</title>
  </head>
  <body>
    <h1>Hello, World!</h1>
    <p>Welcome to <strong>Foo</strong>.</p>
  </body>
</html>