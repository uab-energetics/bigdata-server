<?php
// Application middleware

$app->add(new \Slim\Middleware\JwtAuthentication([
	"path" => ["/"],
    "passthrough" => ["/users/", "/reportError"],
    "secure" => false,
    "secret" => $app->getContainer()->get("settings")['JWT_secret'],
]));