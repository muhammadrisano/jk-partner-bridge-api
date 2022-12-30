<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function __invoke(Request $request)
    {
        $accessToken = $request->bearerToken();
    
        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);
    
        // Revoke token
        $token->delete();
        return ApiFormatter::createApi(200, [
            'message'=> 'success logout'
        ]);
    }
}
