<?php

// Basic CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../autoload.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\API;

// --- Route Definitions ---

// Base route
Router::get('/', function(Request $request) {
    return API::success([
        'message' => 'Welcome to the Folium API',
        'method' => $request->getMethod()
    ]);
});

// View route for templates (returns pure HTML)
Router::view('/home', 'views/home.view.php');

// Grouped routes
Router::group(['prefix' => '/api/v1'], function() {
    
    // Resourceful route for Users
    Router::resource('users', \App\Src\Controllers\UsersController::class);

    // Dynamic parameter example
    Router::get('/hello/{name}', function(Request $request, $name) {
        return API::success([
            'greeting' => "Hello, $name!",
            'all_data' => $request->all()
        ]);
    });

    // Nested custom route within the group
    Router::post('/auth/login', function(Request $request) {
        return API::success([
            'status' => 'Login attempt recorded',
            'user' => $request->input('username')
        ]);
    });
});

// --- Run the Router ---
try {
    Router::dispatch();
} catch (\Exception $e) {
    echo API::error($e->getMessage(), API::HTTP_INTERNAL_SERVER_ERROR);
}
