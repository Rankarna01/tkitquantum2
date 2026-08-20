<?php

/**
 * Router sederhana untuk pola Custom MVC.
 * Format URL: /controller/method/param1/param2
 * Jika kosong -> HomeController@index
 */
class App
{
    protected string $controllerName = 'HomeController';
    protected object $controller;
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = dirname(__DIR__) . '/controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                $this->controllerName = $controllerName;
                unset($url[0]);
            }
        }

        require dirname(__DIR__) . '/controllers/' . $this->controllerName . '.php';
        $this->controller = new $this->controllerName();

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            // Cegah pemanggilan method internal/private lewat URL
            $reflection = new ReflectionMethod($this->controller, $url[1]);
            if ($reflection->isPublic() && strpos($url[1], '__') !== 0) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
