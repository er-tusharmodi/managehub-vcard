<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/debug/login-test', function () {
    $output = [];
    
    // Get all users
    $users = User::all();
    $output['total_users'] = $users->count();
    
    foreach ($users as $user) {
        $userData = [
            'email' => $user->email,
            'username' => $user->username,
            'name' => $user->name,
            'roles' => $user->roles,
            'has_password' => !empty($user->password),
        ];
        
        // Test passwords
        $passwords = ['admin123', 'password', 'admin'];
        foreach ($passwords as $pass) {
            if (Hash::check($pass, $user->password)) {
                $userData['working_password'] = $pass;
                
                // Test actual Auth::attempt
                $emailTest = Auth::attempt(['email' => $user->email, 'password' => $pass]);
                $userData['email_auth_works'] = $emailTest;
                Auth::logout();
                
                if ($user->username) {
                    $usernameTest = Auth::attempt(['username' => $user->username, 'password' => $pass]);
                    $userData['username_auth_works'] = $usernameTest;
                    Auth::logout();
                }
                
                break;
            }
        }
        
        $output['users'][] = $userData;
    }
    
    return response()->json($output, 200, [], JSON_PRETTY_PRINT);
})->name('debug.login');

Route::post('/debug/test-login', function (\Illuminate\Http\Request $request) {
    $output = [
        'submitted_data' => $request->all(),
        'login_field' => $request->input('login'),
        'password_field' => $request->input('password'),
    ];
    
    $login = $request->input('login');
    $password = $request->input('password');
    
    // Determine if email or username
    $credentials = [];
    if (str_contains($login, '@')) {
        $credentials = ['email' => $login, 'password' => $password];
        $output['detected_as'] = 'email';
    } else {
        $credentials = ['username' => $login, 'password' => $password];
        $output['detected_as'] = 'username';
    }
    
    $output['credentials_used'] = array_merge($credentials, ['password' => '***']);
    
    // Try authentication
    $result = Auth::attempt($credentials);
    $output['auth_result'] = $result;
    
    if ($result) {
        $output['authenticated_user'] = [
            'id' => Auth::id(),
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'roles' => Auth::user()->roles,
        ];
        Auth::logout();
    } else {
        // Try to find user manually
        if (str_contains($login, '@')) {
            $user = User::where('email', $login)->first();
        } else {
            $user = User::where('username', $login)->first();
        }
        
        if ($user) {
            $output['user_found'] = true;
            $output['password_hash_check'] = Hash::check($password, $user->password);
        } else {
            $output['user_found'] = false;
        }
    }
    
    return response()->json($output, 200, [], JSON_PRETTY_PRINT);
})->name('debug.test-login');
