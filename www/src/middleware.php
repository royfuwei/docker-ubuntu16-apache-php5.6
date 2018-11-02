<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// use Tuupola\Middleware\HttpBasicAuthentication;
 
$container = $app->getContainer();

 
$container["jwt"] = function ($container) {
    return new StdClass;
};
// log
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
$logger = new Logger("slim");
$rotating = new RotatingFileHandler(__DIR__ . "/logs/slim.log", 0, Logger::DEBUG);
$logger->pushHandler($rotating);



$app->add(new \Slim\Middleware\JwtAuthentication([
    "attribute" => "JWT_Auth",
    "secure" => false,
    // "secure" => true,
    // "relaxed" => ["localhost", "203.74.124.84", "10.66.2.83"],
    "path" => ["/d3", "/auth"],
    "passthrough" => ["/auth/token", "/auth/not-secure"],
    "environment" => ["HTTP_JWT_AUTH", "REDIRECT_HTTP_JWT_AUTH"],
    "header" => "Authorization", //"X-Token"// "Authorization"
    "regexp" => "/(.*)/",
    "secret" => "supersecretkeyyoushouldnotcommittogithub",
    "cookie" => "nekot",
    "algorithm" => ["HS256", "HS384"],
    "logger" => $logger,
    "error" => function ($request, $response, $args) {
        $data["status"] = "error";
        $data["message"] = $args["message"];
        // admin 首頁沒有jwt 回到login
        /* if ($request->getUri()->getPath() == 'admin') {
            return $response
                ->withStatus(200)
                ->withRedirect("login");
        } */
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    }
]));
