<?php

require('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dan\Validate;

const SLACK_KEY = '5EVrWCHPRQTWP4y8ak4znfpr';

$app = new Silex\Application();
$app['debug'] = true;

$dbopts = parse_url(getenv('DATABASE_URL'));

$app->register(new Herrera\Pdo\PdoServiceProvider(),
    array(
        'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"],
        'pdo.username' => $dbopts["user"],
        'pdo.password' => $dbopts["pass"]
    )
);

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

/** Validate the request before doing anything else */
$app->before(function (Request $request) {

    if (! Validate\validate::request($request)) {
        return new Response("Oops I didn't quite get that, try again?", 500);
    }

    return new Response("Oops I didn't quite get that, try again?", 500);
});

// Our web handlers
$app->post('/lunchBot', function (Request $request){


    return new Response('Thank you for your feedback!', 200);
});



$app->run();





/**
token:5EVrWCHPRQTWP4y8ak4znfpr
<br>team_id:T0HKBFUVC
<br>team_domain:highballcollection
<br>channel_id:C0HKBAS7L
<br>channel_name:random
<br>user_id:U0HKC60GY
<br>user_name:danchurchill05
<br>command:/lunch
<br>text:test
<br>response_url:https://hooks.slack.com/commands/T0HKBFUVC/48156108743/HRwbi6m3C4bBMEEozL6bta7b
<br>Thank you for your feedback!
 *
 git add .
 git commit -m "files"
 git push heroku master
 */