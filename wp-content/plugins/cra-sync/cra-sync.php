<?php
/**
 * Plugin Name: CRA Sync by GPPol
 * Plugin URI: https://gymratmid.com/landing
 * Description: Un plugin diseñado para CRA Sync, que permite la sincronización de datos entre sistemas.
 * Version: 1.0.0
 * Author: Pol Aguilar
 * Author URI: https://gymratmid.com/landing
 * License: GPL2
 */

define('MY_API_SECRET_HASH', '$2y$10$AYsn8skmrjqNxAURJOszTur2vAm3DcwOhHi/G7wa2lMCvbNF8CKjK');  // output of password_hash()

add_action('rest_api_init', function () {
    register_rest_route('myplugin/v1', '/secure/', [
        'methods'  => 'POST',
        'callback' => 'handle_my_secure_post',
        'permission_callback' => 'check_api_password',  // Allow access to all authenticated users
    ]);
});

function check_api_password(WP_REST_Request $request) {
    // Option A: Get password from header
    $password = $request->get_header('x-api-key');

    // Option B: Or get it from body
    // $password = $request->get_param('password');

    if (!$password || !password_verify($password, MY_API_SECRET_HASH)) {
        return new WP_Error('forbidden', 'Invalid API key', ['status' => 403]);
    }

    return true;  // Access granted
}

function handle_my_secure_post(WP_REST_Request $request) {
    $data = $request->get_json_params();
    // echo password_hash("cra-sync-99", PASSWORD_DEFAULT);
    // Do something with $data
    // Example: return it back
    return new WP_REST_Response([
        'status' => 'success',
        'received' => $data
    ], 200);
}

// curl -X POST http://localhost:8000/wp-json/myplugin/v1/secure/      -H "Content-Type: application/json"      -H "x-api-key: cra-sync-99"      -d '{"foo":"bar"}'