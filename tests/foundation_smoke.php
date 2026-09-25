<?php

declare(strict_types=1);

/*
 * Phase 1 foundation smoke test (CLI only, no framework, no database access).
 *
 *     php tests/foundation_smoke.php      (or: composer smoke)
 *
 * The only connection attempt targets 127.0.0.1:1 (closed port) to verify the failure path;
 * no real database is contacted and nothing is written outside the system temp directory.
 */

use App\Bootstrap\Application;
use App\Bootstrap\ErrorHandler;
use App\Config\Config;
use App\Config\Env;
use App\Config\Repository;
use App\Database\Connection;
use App\Database\ConnectionException;
use App\Http\Request;
use App\Http\Response;
use App\Logging\Logger;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';

$failures = 0;
$check = static function (string $label, bool $ok) use (&$failures): void {
    echo ($ok ? '  [OK]   ' : '  [FAIL] ') . $label . PHP_EOL;
    if (!$ok) {
        $failures++;
    }
};

echo "Env\n";
$parsed = Env::parse("# comment\nA=1\nexport B=\"two words\"\nC='x=y'\nD=plain # trailing\n1BAD=no\nEMPTY=\n");
$check('parses plain, export, quotes and inline comments', $parsed === ['A' => '1', 'B' => 'two words', 'C' => 'x=y', 'D' => 'plain', 'EMPTY' => '']);
$env = Env::fromArray(['T' => 'true', 'F' => 'off', 'N' => '42', 'LEGACY' => 'old']);
$check('typed bool/int helpers', $env->bool('T') === true && $env->bool('F', true) === false && $env->int('N') === 42 && $env->int('MISSING', 7) === 7);
$check('first() falls back to legacy names', $env->first(['NEW_NAME_NOT_SET', 'LEGACY']) === 'old');
$check('missing .env file yields empty Env', Env::fromFile(__DIR__ . '/does-not-exist.env')->get('ANY') === null);
$priorityKey = 'LAWFIRM_SMOKE_PRIORITY_' . bin2hex(random_bytes(3));
putenv($priorityKey . '=from-real-env');
$check('real environment variables take priority over .env values', Env::fromArray([$priorityKey => 'from-file'])->get($priorityKey) === 'from-real-env');
putenv($priorityKey);
$check('.env value used when no real variable exists', Env::fromArray([$priorityKey => 'from-file'])->get($priorityKey) === 'from-file');

echo "Config\n";
$repo = new Repository(['app' => ['debug' => false, 'nested' => ['k' => 'v']], 'database' => ['port' => 3306]]);
$check('dot notation get', $repo->get('app.nested.k') === 'v' && $repo->get('app.none', 'd') === 'd');
$check('has() distinguishes false/null from missing', $repo->has('app.debug') && !$repo->has('app.missing'));
$check('typed getters', $repo->int('database.port') === 3306 && $repo->bool('app.debug', true) === false);

$app = new Application(dirname(__DIR__));
$app->boot(registerErrorHandler: false);
$check('Application boots and installs Config facade', Config::isLoaded() && Config::repository() === $app->config());
$check('config files loaded (app, database, security)', Config::has('app.env') && Config::has('database.host') && Config::has('security.session.name'));
$check('charset forced to utf8mb4', Config::string('database.charset') === 'utf8mb4');
$configDir = dirname(__DIR__) . '/config';
$debugFor = static fn (array $vars): bool => Repository::fromDirectory($configDir, Env::fromArray($vars))->bool('app.debug', true);
$check('debug off by default (no APP_ENV / APP_DEBUG)', $debugFor([]) === false);
$check('APP_DEBUG=true ignored when APP_ENV=production', $debugFor(['APP_ENV' => 'production', 'APP_DEBUG' => 'true']) === false);
$check('APP_DEBUG=true ignored for typos/unknown envs (prod, staging)', $debugFor(['APP_ENV' => 'prod', 'APP_DEBUG' => 'true']) === false && $debugFor(['APP_ENV' => 'staging', 'APP_DEBUG' => 'true']) === false);
$check('debug possible only in local with APP_DEBUG=true', $debugFor(['APP_ENV' => 'local', 'APP_DEBUG' => 'true']) === true && $debugFor(['APP_ENV' => 'local']) === false);
if (getenv('DB_PASSWORD') === false && getenv('MYSQL_PASSWORD') === false && getenv('DB_USERNAME') === false && getenv('MYSQL_USER') === false) {
    $emptyDb = Repository::fromDirectory($configDir, Env::fromArray([]));
    $check('no hardcoded DB credentials in config (password/username default to empty)', $emptyDb->string('database.password', 'x') === '' && $emptyDb->string('database.username', 'x') === '');
} else {
    echo "  [SKIP] hardcoded-credentials check (real DB_* / MYSQL_* variables are set in this process)\n";
}

echo "Database\n";
$check('App\\Database\\Connection autoloads', class_exists(Connection::class));
$shared = $app->connection();
$check('connection() is lazy and shared', !$shared->isConnected() && $shared === $app->connection());
$check('__debugInfo hides credentials', !array_key_exists('password', $shared->__debugInfo()) && !array_key_exists('username', $shared->__debugInfo()));

$canary = 'canary-secret-' . bin2hex(random_bytes(4));
$broken = new Connection('127.0.0.1', 1, 'nodb', 'nouser', $canary, 'utf8mb4', 2);
try {
    $broken->pdo();
    $check('unreachable server throws', false);
} catch (ConnectionException $e) {
    $dump = $e->getMessage() . $e->getTraceAsString() . (string) $e;
    $check('unreachable server throws ConnectionException', true);
    $check('exception exposes no password / DSN / user', !str_contains($dump, $canary) && !str_contains($dump, 'mysql:host') && !str_contains($dump, 'nouser'));
    $check('PDOException is not chained (its trace holds credentials)', $e->getPrevious() === null);
}
try {
    (new Connection('127.0.0.1', 3306, '', '', ''))->pdo();
    $check('missing configuration throws', false);
} catch (ConnectionException $e) {
    $check('missing configuration throws before connecting', str_contains($e->getMessage(), 'not configured'));
}

echo "Http\n";
$req = new Request(
    ['q' => 'x'],
    ['name' => '  Ana  '],
    ['REQUEST_METHOD' => 'post', 'REQUEST_URI' => '/admin/login.php?return=/x', 'HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'REMOTE_ADDR' => '10.0.0.1'],
    [],
    [],
    '{"message":"hola"}'
);
$check('method/path/query/input', $req->method() === 'POST' && $req->path() === '/admin/login.php' && $req->query('q') === 'x' && $req->string('name') === 'Ana');
$check('json body and headers', $req->json() === ['message' => 'hola'] && $req->header('Content-Type') === 'application/json' && $req->expectsJson());
$check('invalid json yields null', (new Request([], [], [], [], [], '{bad'))->json() === null);

$check('Response::json', Response::json(['ok' => true], 201)->status() === 201 && Response::json(['ok' => true])->body() === '{"ok":true}');
$check('Response::html sets content type', str_starts_with((string) Response::html('<p>x</p>')->header('content-type'), 'text/html'));
$check('internal redirect allowed', Response::redirect('/admin/dashboard.php')->header('Location') === '/admin/dashboard.php');
foreach (['//evil.example', '/\\evil.example', '/\\/evil.example', '\\\\evil.example', ' //evil.example', 'https://evil.example', 'javascript:alert(1)', "/\tevil", "/x\r\nSet-Cookie: a=b", 'evil.example', ''] as $bad) {
    try {
        Response::redirect($bad);
        $check('redirect rejects ' . json_encode($bad), false);
    } catch (InvalidArgumentException) {
        $check('redirect rejects ' . json_encode($bad), true);
    }
}

echo "Errors & logging\n";
$tmpLogs = sys_get_temp_dir() . '/lawfirm-smoke-' . bin2hex(random_bytes(4));
mkdir($tmpLogs);
$logger = new Logger($tmpLogs);
$boom = new RuntimeException('SQLSTATE[42S02] SELECT * FROM secret_table');
$prod = (new ErrorHandler($logger, false))->responseFor($boom, false);
$check('production HTML error is generic', $prod->status() === 500 && !str_contains($prod->body(), 'SQLSTATE') && !str_contains($prod->body(), __FILE__));
$prodJson = (new ErrorHandler($logger, false))->responseFor($boom, true);
$check('production JSON error is generic', $prodJson->body() === '{"error":"Error interno del servidor."}');
$dev = (new ErrorHandler($logger, true))->responseFor($boom, false);
$check('development error shows escaped detail', str_contains($dev->body(), 'secret_table'));
$logger->error('login failed', ['password' => 'hunter2', 'nested' => ['csrf_token' => 'abc'], 'email' => 'a@b.c']);
$logged = (string) @file_get_contents($tmpLogs . '/app-' . date('Y-m-d') . '.log');
$check('logger writes JSON lines and redacts secrets', str_contains($logged, 'login failed') && !str_contains($logged, 'hunter2') && !str_contains($logged, '"abc"'));
array_map('unlink', glob($tmpLogs . '/*') ?: []);
rmdir($tmpLogs);

// Last on purpose: bootstrap/app.php registers the global error handler.
echo "Bootstrap\n";
$first = require dirname(__DIR__) . '/bootstrap/app.php';
$second = require dirname(__DIR__) . '/bootstrap/app.php';
$check('bootstrap/app.php returns a booted Application', $first instanceof Application && Config::repository() === $first->config());
$check('bootstrap is a per-process singleton (same instance on re-include)', $first === $second && $first === Application::shared(dirname(__DIR__)));
$check('bootstrap shares one lazy Connection', $first->connection() === $second->connection() && !$first->connection()->isConnected());

echo PHP_EOL . ($failures === 0 ? 'ALL CHECKS PASSED' : "{$failures} CHECK(S) FAILED") . PHP_EOL;
exit($failures === 0 ? 0 : 1);
