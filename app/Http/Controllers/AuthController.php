<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (! $credentials) {
            return response()->json(['Validation Error.' => $request->errors()]);
        }

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors'  => [
                    'email' => ['These credentials do not match our records.'],
                ],
            ], 422);
        }

        // $user  = Auth::user();
        $user = Auth::user()->load(['roles.permissions', 'permissions']);

        $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'token'      => $token,
        //     'user'       => $user,
        //     'role'       => $user->load('roles', 'permissions'),
        //     'token_type' => 'Bearer',
        // ]);

        return response()->json([
            'token'      => $token,
            'user'       => $user,
            // 'user'       => $user->load('permissions','roles.permissions'),
            'token_type' => 'Bearer',
        ]);

    }

    public function register(RegisterRequest $request)
    {

        $defaultRole = Role::where('name', 'User')->where('guard_name', 'api')->first();

        $credentials = $request->validated();
        if (! $credentials) {
            return response()->json(['Validation Error.' => $request->errors()]);
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            //'role_id'  => $defaultRole?->id,
        ]);

        $user->syncRoles('User');
        $user->givePermissionTo([
            'view users',
            'view roles',
            'view permissions',

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token'      => $token,
            'user'       => $user,
            'role'       => $user->load('roles.permissions', 'permissions'),
            'token_type' => 'Bearer',
        ], 201);
    }

    public function logout(Request $request)
    {

        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return response()->json([
                'message' => 'No authentication token provided',
                'success' => false,
            ], 401);
        }

        // Get the token ID from the plain token
        $tokenId = explode('|', $plainToken)[0] ?? null;

        if (! $tokenId) {
            return response()->json([
                'message' => 'Malformed token',
                'success' => false,
            ], 401);
        }

        // Find the token by ID instead of hashed value
        $token = PersonalAccessToken::find($tokenId);

        if (! $token) {
            return response()->json([
                'message' => 'Invalid token',
                'success' => false,
            ], 401);
        }

        // Verify the token belongs to the authenticated user
        if ($request->user() && $token->tokenable_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Token does not belong to this user',
                'success' => false,
            ], 403);
        }

        // Delete the token
        $token->delete();

        return response()->json([
            'message' => 'Successfully logged out',
            'success' => true,
        ]);

    }

    public function user(Request $request)
    {

        // $user  = Auth::user();
        $user  = Auth::user()->load(['roles.permissions', 'permissions']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token'      => $token,
            'user'       => $user,
            // 'user'       => $user->load('permissions','roles.permissions'),
            'token_type' => 'Bearer',
        ]);

        // return response()->json($request->user());
    }
}

// 'roles.permissions', // THIS is the important part
//         'permissions'        // if the user has direct permissions
