<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:gym|instructor|client' , ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Autenticação"},
     *   summary="Autentica o usuario",
     *   description="Autentica o usuario e retorna o token",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType = "multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               required = {"email", "password", "type"},
     *               @OA\Property(
     *                   property="email",
     *                   description="Email do usuario",
     *                   type="string"
     *              ),
     *               @OA\Property(
     *                   property="password",
     *                   description="Senha do usuario",
     *                   type="string"
     *              ),
     *               @OA\Property(
     *                   property="type",
     *                   description="Tipo do usuario (client, gym, instructor)",
     *                   type="string"
     *              ),
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *          response="200",
     *          description="Retorna o token de autenticação."
     *      ),
     *   @OA\Response(
     *          response="401",
     *          description="Usuario ou senha errados na autenticação"
     *    ),
     *    @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *    )
     * )
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);
            if (! $token = auth(request('type'))->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $e){
            return response()->json();
        }
    }


    /**
     * @OA\Post(
     *   path="/api/auth/me",
     *   tags={"Autenticação"},
     *  security={{ "apiAuth": {} }},
     *   summary="Retorna o usuario autenticado",
     *   description="Retorna o usuario autenticado pelo token",
     *   @OA\Response(
     *          response="200",
     *          description="Retorna o token de autenticação."
     *      ),
     *   @OA\Response(
     *          response="401",
     *          description="Retorna o erro de autenticação"
     *    ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function me()
    {
        try {
            return response()->json(auth()->user(), 200);
        } catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/auth/logout",
     *   tags={"Autenticação"},
     *  security={{ "apiAuth": {} }},
     *   summary="Revoga o token de autenticação",
     *   description="Revoga a autenticação do token tornando-o invalido",
     *   @OA\Response(
     *          response="200",
     *          description="Retorna a mensagem de sucesso"
     *      ),
     *   @OA\Response(
     *          response="401",
     *          description="Retorna o erro de autenticação"
     *    ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/auth/refresh",
     *   tags={"Autenticação"},
     *  security={{ "apiAuth": {} }},
     *   summary="Atualiza o token",
     *   description="Atualiza o token de autenticação",
     *   @OA\Response(
     *          response="200",
     *          description="Retorna o token"
     *      ),
     *   @OA\Response(
     *          response="401",
     *          description="Retorna o erro de autenticação"
     *    ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }
}
