<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group('Authenticating Users', 'Endpoints for loging and logout operations for the users')]
final class AuthenticationController extends Controller
{
    // Authentication Logic
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Authenticate Users to The System
     *
     * @return \Illuminate\Http\Response
     */
    #[BodyParam('email', 'string', 'The email of the user',true,'test@example.com')]
    #[BodyParam('password', 'string', 'The password of the user',true,'********')]
    #[ResponseFromFile("storage/app/responses/auth/user.login.json", 200, [], "Login Successfully")]
    #[ResponseFromFile("storage/app/responses/auth/user.login.wrongpass.json", 422, [], "Email or Password are incorrect")]
    #[ResponseFromFile("storage/app/responses/auth/user.login.emailnotexist.json", 422, [], "Email does not exist")]
    #[ResponseFromFile("storage/app/responses/auth/user.login.unauthorized.json", 422, [], "User is not authorized")]
    public function login(UserLoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = $this->authService->login($email, $password, $request->device_name ?? 'Android');
        return $this->respondWithResource(new AuthResource($user), "Login Successfully.");
    }

    /**
     * Logout user account.
     */
    #[Authenticated()]
    #[Response([], 204, 'Logout Successfully')]
    #[ResponseFromFile("storage/responses/errors/401.json", 401, [], "Expired token or Unauthenticated user")]
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return Response(null, 204);
    }
}
