<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Controllers\AnalysisController;
use App\Helpers\ResponseHelper;
use App\Services\SEOWebAnalyzer;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create DI container
$container = new Container();
AppFactory::setContainer($container);

// Configure dependencies
$container->set('responseHelper', fn() => new ResponseHelper());
$container->set('analyzer', fn() => new SEOWebAnalyzer());
$container->set(AnalysisController::class, function ($container) {
    return new AnalysisController(
        $container->get('analyzer'),
        $container->get('responseHelper')
    );
});

// Initialize app
$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// Configure database
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Accept')
        ->withHeader('Access-Control-Allow-Methods', 'POST');
});
// Routes
$app->post('/analyze', [AnalysisController::class, 'analyze']);

// Error middleware
$app->addErrorMiddleware($_ENV['APP_ENV'] === 'development', true, true);
$app->get('/docs', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/docs/index.html')->withStatus(302);
});
$app->run();