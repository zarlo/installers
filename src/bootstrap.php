<?php

use FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

use Twig\Loader\Loader_String;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// use Cascade\Cascade;
// $loggerConfigFile;
// if(file_exists(__DIR__ . '/../logger.json'))
// {
//     $loggerConfigFile = __DIR__ . '/../logger.json';
// }
// else
// {
//     $loggerConfigFile = __DIR__ . '/../logger-base.json';  
// }


// Cascade::fileConfig($loggerConfigFile);

$routes = makeFolder("/", (array)json_decode(file_get_contents(__DIR__ . '/../config.json'), True)["routes"] );

function makeFolder($url, $folder)
{
    $data = [];
    $i = 0;
    foreach ($folder as $item)
    {
        
        $itemName = $item["name"];
        $item["path"] = $url . $itemName;
        if(isset($item["items"]))
        {
            $item["folder"] = true;
            $item["template"] = "folder.html";
            array_push($data, $item);
            $data = array_merge($data, makeFolder($item["path"] . "/", $item["items"]));
        }
        else
        {
            
            array_push($data, $item);
        }
        $i++;
    }

    return $data;
}


$makeRoutes_data = null;
function makeRoutes() {
    global $routes;

    return function(\FastRoute\RouteCollector $r) {
        global $routes;

        $index = [ "template" => "index.html", "data" => (array)json_decode(file_get_contents(__DIR__ . '/../config.json'), True)["routes"] ];
        
        $r->addRoute('GET', '/', $index);
        foreach ($routes as $route) {
            
            if(!isset($route->folder))
            {
                if(isset($route['path']))
                {
                    $r->addRoute('GET', $route['path'], $route);
                }
            }
            else
            {
                $folder = [ "data" => json_decode(json_encode($route['items']), True)];
                $r->addRoute('GET', $route->path, $folder);
                //print_r($folder);
            }
        }


    };


}

function version() {

    if(file_exists(__DIR__ . '/../.git'))
    {
        exec('git describe --always',$version_mini_hash);
        exec('git rev-list HEAD | wc -l',$version_number);
        exec('git log -1',$line);
        if(isset($version_number[0])){
            $version['short'] = trim($version_number[0]).".".$version_mini_hash[0];
            $version['full'] =  trim($version_number[0]).".$version_mini_hash[0] (".str_replace('commit ','',$line[0]).")";
        }
        else{
            $version['short'] = "unknown";
            $version['full']  = "unknown";  
        }

        $version['hash'] =  str_replace('commit ','',$line[0]);
        return $version;
    }

    return "debug";

}

function renderString($string, $data = [])
{

    $loader = new \Twig\Loader\ArrayLoader([
        sha1($string) => $string
    ]);
    $urlEnvironment = new Environment($loader,[
        'cache' => __DIR__ . '/../cache/string',
    ]);
    return $urlEnvironment->render(sha1($string), $data);

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
        'debug' => true,
    ]);
} catch (\Throwable $th){
    $twig = new Environment($twig_loader, [
        'cache' => false,
    ]);
}
$twig->addExtension(new \Twig\Extension\DebugExtension());
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
        $data = array_merge($data, [ "version" => version()]);
        
                    
        if (isset($vars))
        {
            for ($i = 1; $i < sizeof($vars); $i++) 
                $data = array_merge($data, [ "parm_". $i => $vars[$i]]);
        }
        $data = array_merge($data, [ "get" => $_GET]);

        $data = array_merge($data, [ "domain" => $_SERVER['HTTP_HOST']]);
        $data = array_merge($data, [ "base" => $_SERVER['REQUEST_URI']]);
        if(isset($handler['data']))
        {
            $data = array_merge($data, ['data'=>$handler['data']]);
        }
        if(isset($handler['30x']))
        {           
            echo renderString($handler['30x'], $data);
            header('Location: '. renderString($handler['30x'], $data));
        }
        elseif(isset($handler['script']))
        {

            $filename = __DIR__ . '/../scripts' . renderString($handler['script'], $data);
            header("Content-Type: text/plain");
            echo renderString(file_get_contents($filename), $data);

        }
        elseif(isset($handler['template']))
        {
            if (isset($handler['data']))
                $data = array_merge($data, [ "data" => $handler['data'] ]);
            else
                $data = array_merge($data, [ "data" => $handler ]);
            // print_r($data);
            // echo "</br>----</br>";
            // print_r($handler);
            // echo "</br>----</br>";
            echo $twig->render($handler['template'], $data);
        }
        else
        {
            
            $data = array_merge($data, [ "data" => $handler ]);
            echo $twig->render("folder.html", $data);
        }
        break;
}

