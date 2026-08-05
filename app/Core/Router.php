<?php
namespace App\Core;

class Router {
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Register a POST route
     */
    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Dispatch the current request to the correct handler
     */
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Get subfolder (like /irt) and strip it from the requested URI
        $basePath = getProjectBasePath();

        // Clean up URI and strip base path
        $path = str_replace($basePath, '', $uri);
        $path = parse_url($path, PHP_URL_PATH);
        
        if (empty($path)) {
            $path = '/';
        }

        // Standardize ending slash (except for the root '/')
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        // Check if route exists
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $controllerClass = $handler[0];
            $action = $handler[1];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        // Return 404
        $this->handle404();
    }

    /**
     * Render the 404 page
     */
    private function handle404(): void {
        header("HTTP/1.0 404 Not Found");
        $title = "404 Not Found - IRT";
        
        $viewsPath = dirname(__DIR__, 2) . '/views';
        
        // Render 404 page using layout
        if (file_exists($viewsPath . '/templates/header.php')) {
            require $viewsPath . '/templates/header.php';
        }
        
        ?>
        <div class="card p-5 text-center my-5 shadow-sm rounded-lg border-0" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
            <div class="mb-4">
                <span class="display-1 text-danger font-weight-bold" style="font-size: 6rem; letter-spacing: -2px;">404</span>
            </div>
            <h2 class="mb-3 text-dark font-weight-bold">Halaman Tidak Ditemukan</h2>
            <p class="lead text-muted mb-4">Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.</p>
            <div>
                <a href="<?= baseUrl('/') ?>" class="btn btn-primary px-4 py-2 rounded-pill font-weight-semibold shadow-sm transition-all">
                    <span class="d-flex align-items-center justify-content-center">
                        <ion-icon name="home-outline" class="mr-2" style="font-size: 1.2rem;"></ion-icon>
                        Kembali ke Dashboard
                    </span>
                </a>
            </div>
        </div>
        <?php

        if (file_exists($viewsPath . '/templates/footer.php')) {
            require $viewsPath . '/templates/footer.php';
        }
    }
}
