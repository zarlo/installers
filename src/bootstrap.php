<?php
use Symfony\Component\Yaml\Yaml;

use FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

use Twig\Loader\Loader_String;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$config = Yaml::parseFile(__DIR__ . '/../config.yml');;

function makeRoutes() {
    global $config;
    return function(\FastRoute\RouteCollector $r) {
        global $config;
        $index = [ "template" => "index.html", "data" => $config["routes"]];
        $r->addRoute('GET', '/', $index);
        foreach ($config["routes"] as $route) {
         
            $r->addRoute('GET', $route['path'], $route);

        }

    };

}

function renderString($string, $data = [])
{

    $loader = new \Twig\Loader\ArrayLoader([
        sha1($string) => $string
    ]);
    $urlEnvironment = new Environment($loader,[
        'cache' => __DIR__ . '/../cache/string',
    ]);
    $url = $urlEnvironment->render(sha1($string), $data);

}

$routes;
$twig;

try {
    $dispatcher = \FastRoute\cachedDispatcher(makeRoutes(), [
        'cacheFile' => __DIR__ . "/../cache/route.cache",
    ]);
} catch (\Throwable $th) {
    $dispatcher = \FastRoute\simpleDispatcher(makeRoutes());
}
$twig_loader = new FilesystemLoader(__DIR__ . '/../templates');


try {
    $twig = new Environment($twig_loader, [
        'cache' => __DIR__ . '/../cache',
    ]);
} catch (\Throwable $th){
    $twig = new Environment($twig_loader, [
        'cache' => false,
    ]);
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        echo $twig->render("404.html");
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $twig->render("405.html");
        break;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        $data = [];
        
                    
        if (isset($vars))
        {
            for ($i = 1; $i < sizeof($vars); $i++) 
                array_merge($data, [ "parm_". $i => $vars[$i]]);
        }
        array_merge($data, [ "get" => $_GET]);
        array_merge($data, [ "domain" => $_SERVER['HTTP_HOST']]);
        if(isset($handler['data']))
        {
            array_merge($data, ['data'=>$handler['data']]);
        }
        if(isset($handler['30x']))
        {            
            header('Location: '. renderString($handler['30x'], $data));
        }
        elseif(isset($handler['script']))
        {

            $filename = __DIR__ . '/../scripts' . renderString($handler['script'], $data);

            echo renderString(file_get_contents($filename), $data);

        }
        elseif(isset($handler['template']))
        {
            array_merge($data, [ "data" => $handler['data'] ]);
            echo $twig->render($handler['template'], $data);
        }
        break;
}

