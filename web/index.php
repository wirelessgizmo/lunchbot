<?php


require('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use App\Validate;
use App\Definitions;


$app = new Silex\Application();
$app['debug'] = true;

$dbopts = parse_url(getenv('DATABASE_URL'));

$app->register(new Herrera\Pdo\PdoServiceProvider(),
    array(
        'pdo.dsn' => 'pgsql:dbname=' . ltrim($dbopts["path"], '/') . ';host=' . $dbopts["host"] . ';port=' . $dbopts["port"],
        'pdo.username' => $dbopts["user"],
        'pdo.password' => $dbopts["pass"]
    )
);

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

/** Before doing anything with routing, let's see if it's a valid request with the definitions we require */
$app->before(function (Request $request) {

    if (!Validate::request($request)) {
        return new Response('Oops I didn\'t quite get that! Perhaps type `/lunch help`', 500);
    }

});

/**
 * Entry point for the Slack request.
 * Slack only gives us one endpoint so let's split it out into sub-requests here
 */
$app->post('/lunchBot', function (Request $request) use ($app) {

    $components = explode(' ', $request->get('text'));
    $subRequestRoute = '';
    $type = 'GET';

    /** User is after the hint text or a listing of all orders
     * /lunch list
     * /lunch help
     */
    if (in_array($components[0], array(Definitions::REQUEST_LIST, Definitions::REQUEST_HELP))) {
        $subRequestRoute = "/{$components[0]}";
    }

    /** User wants to add an item to a list
     * /lunch oporto add chips
     */
    if ($components[1] == 'add') {
        $subRequestRoute = "/{$components[0]}/add/{$components[1]}";
        $type = "POST";
    }

    /** User wants to delete an order
     * /lunch oporto close
     */
    if ($components[1] == Definitions::REQUEST_CLOSE) {
        $subRequestRoute = "/{$components[0]}/close";
        $type = "DELETE";
    }

    /** Default request is to try and list a specific order */
    if($subRequestRoute == '') {
        $subRequestRoute = "/{$components[0]}";
        $type = "GET";
    }


    /** Create and handle the sub request */
    $subRequest = Request::create($subRequestRoute, $type);

    $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);

    /**
     * /lunch oporto
     * /lunch oporto add large chips
     * /lunch list
     * /lunch oporto close
     */


    return $response;
});

/** Closes an order */
$app->delete('/{order}/close', function (Request $request) use ($app) {
    return new Response('That order has been removed', 200);
});

$app->post('/{order}/add/{item}', function (Request $request) use ($app) {
    return new Response('Added that item to the order', 200);
});


/**
 * When the user types /help
 */
$app->get('/help', function (Request $request) use ($app) {

    return $app->json(Definitions::$HINT_TEXT, 200);

});

$app->get('/list', function (Request $request) use ($app) {
    return new Response('here is a list of all the orders',200);

});

$app->get('/{order}', function (Request $request) use ($app) {

    return new Response('here is a list of all the items in that order',200);

});
$app->run();




/**
 * <br>team_id:T0HKBFUVC
 * <br>team_domain:highballcollection
 * <br>channel_id:C0HKBAS7L
 * <br>channel_name:random
 * <br>user_id:U0HKC60GY
 * <br>user_name:danchurchill05
 * <br>command:/lunch
 * <br>text:test
 *
  git add .
  git commit -m "files"
  git push heroku master
 */