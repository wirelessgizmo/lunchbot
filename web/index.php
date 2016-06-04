<?php


require('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Validate;

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

/** Before doing anything with routing, let's see if it's a valid request with the definitions we require */
$app->before(function (Request $request, Silex\Application $app) {

    if(! Validate::request($request)){
        return new Response('Oops I didn\'t quite get that! Try again?',500);
    }

});


/** Entry point for the Slack request */
$app->post('/lunchBot', function (Request $request){

    $components = explode(' ', $request->get('text'));

    /**
    /order oporto #adds the list
    /order oporto add large chips #adds an item
    /order oporto #already exists, outputs the list
    /order list #lists all current orders
    /order oporto close #closes an order
*/

    return new Response('Thank you for your feedback!', 200);
});



$app->run();

/**
<br>team_id:T0HKBFUVC
<br>team_domain:highballcollection
<br>channel_id:C0HKBAS7L
<br>channel_name:random
<br>user_id:U0HKC60GY
<br>user_name:danchurchill05
<br>command:/lunch
<br>text:test

 *
 git add .
 git commit -m "files"
 git push heroku master
 */