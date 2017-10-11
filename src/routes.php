<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


$app->get('/gpio/write', function(Request $request, Response $response){
    
    $errors = [];
    $gpio_pins = [4, 17, 18, 27, 22, 23, 24, 25, 26, 19, 13, 6, 5, 21, 20, 16, 12];

    $gpio_pin =  $request->getQueryParam('gpio_pin');
    $gpio_state = $request->getQueryParam('gpio_state');

    if(!$gpio_pin || !is_int($gpio_pin) || !in_array($gpio_pin, $gpio_pins)){
        $errors[] = 'Veuillez verifier le numéro de PIN entré.';
    }

    if(!$gpio_state && !is_int($gpio_state)){
        $errors[] = 'Veuillez vérifier l\'état du PIN entré';
    }

    if(!empty($errors)){
        return $this->renderer->render($response, 'index.phtml');
    }else{
        exec('gpio mode '.$gpio_pin.' out');
        exec('gpio write '.$gpio_pin.' '.$gpio_state);
        return $this->renderer->render($response, 'index.phtml', ['errors' => $errors]);
    }

});


$app->get('/gpio/read', function(Request $request, Response $response){
    $errors = [];
    $gpio_pins = [4, 17, 18, 27, 22, 23, 24, 25, 26, 19, 13, 6, 5, 21, 20, 16, 12];

    $gpio_pin =  $request->getQueryParam('gpio_pin');

    if(!$gpio_pin || !is_int($gpio_pin) || !in_array($gpio_pin, $gpio_pins)){
        $errors[] = 'Veuillez verifier le numéro de PIN entré.';
    }

    if(!empty($errors)){
        return $this->renderer->render($response, 'index.phtml');
    }else{
        exec('gpio read '.$gpio_pin, $gpio_pin_status);
        echo $gpio_pin_status;
    }

});