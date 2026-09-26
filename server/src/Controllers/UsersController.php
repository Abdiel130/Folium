<?php

namespace App\Src\Controllers;

use App\Core\Request;
use App\Core\API;

class UsersController {
    
    public function index(Request $request) { 
        return API::success([
            'users' => [],
            'query' => $request->query()
        ], 'Users fetched successfully'); 
    }

    public function store(Request $request) { 
        return API::success(
            $request->input(),
            'User created successfully',
            API::HTTP_CREATED // Using constant instead of magic number
        ); 
    }

    public function show(Request $request, $id) { 
        // Example check: if id is non-numeric, return error
        if (!is_numeric($id)) {
            return API::error("Invalid user ID: $id", API::HTTP_BAD_REQUEST);
        }

        return API::success([
            'id' => (int) $id,
            'name' => 'Demo User',
            'headers' => $request->header('User-Agent')
        ]); 
    }

    public function update(Request $request, $id) { 
        return API::success(
            $request->input(),
            "User $id updated successfully"
        ); 
    }

    public function destroy(Request $request, $id) { 
        return API::success(null, "User $id deleted"); 
    }
}
