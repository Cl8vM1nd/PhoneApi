<?php 
namespace Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;


class Application extends \Silex\Application
{
	public function __construct($classLoader, $httpHost) {
        ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        parent::__construct(array(
            'class_loader' => $classLoader,
            'http_host' => $httpHost
        ));
    }

    /**
    *   Making base application setup
    **/
    public function setup() {
    	$app = $this;
    	$app->register(new ServiceControllerServiceProvider());

        require_once(__DIR__ . '/../config.php');
        
        //Registering all main files
    	$app ->registerAll($app, array('Controller', 'Model', 'Service'));

    	$app->match('/{action}', 'AppController:route')
            ->value('action', 'index')
            ->bind('default');

        // Check every query if token is right
        $app ->before(function() use ($app) {
            $app['AccessTokenService'] ->check();
        });

    }

    /**
    *   Register all significant components
    *   @param $app         - main app
    *   @param $services    - Array of services list
    **/
    private function registerAll(&$app, $services) {

        $baseDIR =  __DIR__ . '/../';
        foreach ($services as $value) {
           if(is_dir($baseDIR . $value)) {
                foreach (glob($baseDIR . $value . "/*.php") as $filename) {
                    $file = substr($filename, strrpos($filename, '/') + 1);
                    $file = explode('.', $file);
                    $serviceClass = '\\' . $value . '\\' . $file[0];
                    $app[$file[0]] = $app->share(function() use ($app, $serviceClass) {
                            return new $serviceClass($app);
                    });
                }
           } else {
                throw new \Exception("dir '" . $value . "' not found!");
           }
        }
        
    }
}