<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GymController extends Controller
{

    /**
     * @OA\Info(
     *      title="Gym-track API",
     *      version="0.1"
     * )
     */

    private $gym;

    /**
     * Create a new controller instance.
     *
     * @param Gym $gym
     */
    public function __construct(Gym $gym){
        $this->gym = $gym;
    }


    /**
     * @OA\Post(
     *     tags={"Academias"},
     *      summary="Cria uma academia",
     *      path="/api/gyms",
     *      description="Cria uma academia",
     *      @OA\Parameter (
     *          name="name",
     *          in="query",
     *          description="Nome da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="neighborhood",
     *          in="query",
     *          description="Bairro da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="street",
     *          in="query",
     *          description="Rua da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="number",
     *          in="query",
     *          description="Numero (endereço) da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="complement",
     *          in="query",
     *          description="Referencia da localização da academia",
     *          required=false,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="zipcode",
     *          in="query",
     *          description="CEP da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="city",
     *          in="query",
     *          description="Cidade da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          in="query",
     *          description="Email da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phone",
     *          in="query",
     *          description="Telefone da academia",
     *          required=false,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="password",
     *          in="query",
     *          description="Senha da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna a academia que foi criado."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function store(Request $request){
        try {
            $validator = $this->validateRequest($request->all());
            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $request['password'] = Hash::make($request['password']);
            $gym = $this->gym->create($request->all());

            return response()->json(compact('gym'), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     tags={"Academias"},
     *      summary="Atualiza uma academia",
     *      path="/api/gyms/{id}",
     *      description="Atualiza uma academia pelo id",
     *     @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Id da academia",
     *          required=true,
     *          @OA\Schema (
     *             type="integer",
     *             format="int64"
     *          ),
     *      ),
     *      @OA\Parameter (
     *          name="name",
     *          in="query",
     *          description="Nome da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="neighborhood",
     *          in="query",
     *          description="Bairro da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="street",
     *          in="query",
     *          description="Rua da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="number",
     *          in="query",
     *          description="Numero (endereço) da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="complement",
     *          in="query",
     *          description="Referencia da localização da academia",
     *          required=false,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="zipcode",
     *          in="query",
     *          description="CEP da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="city",
     *          in="query",
     *          description="Cidade da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          in="query",
     *          description="Email da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phone",
     *          in="query",
     *          description="Telefone da academia",
     *          required=false,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="password",
     *          in="query",
     *          description="Senha da academia",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna a academia que foi atualizada."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Academia não encontrado."
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function update(Request $request, int $id) {
        try {
            $validator = $this->validateRequest($request->all(), $id);

            $gym = $this->gym->findOrFail($id);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $gym->update($request->all());
            return response()->json(compact('gym'), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'gym not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }




    /**
     * @OA\Get(
     *      tags={"Academias"},
     *      summary="Lista todos os academias",
     *      path="/api/gyms",
     *      description="Array de objetos contendo todos os academias cadastrados",
     *      @OA\Response(
     *          response="200",
     *          description="Retorna todos os instrutores cadastrados"
     *      ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function index(){
        try {
            $gyms = $this->gym->all();
            return response()->json(compact('gyms'), 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Academias"},
     *      summary="Busca uma academia",
     *      path="/api/gyms/{id}",
     *      description="Busca a academia com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Gym id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Retorna a academia"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Instrutor não encontrado"
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function show(int $id) {
        try {
            $gym = $this->gym->findOrFail($id);

            return response()->json(compact('gym'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'gym not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete (
     *     tags={"Academias"},
     *      summary="Deleta uma academia",
     *      path="/api/gyms/{id}",
     *      description="Deleta a academia com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Gym id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Deleta o academia"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Academia não encontrada"
     *     ),
     *      @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function delete(int $id){
        try {
            $gym = $this->gym->findOrFail($id);
            $gym->delete();

            $message = 'Gym deleted';
            return response()->json(compact('message'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'gym not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    protected function validateRequest(array $request, $id = 0)
    {
        return Validator::make($request, [
            'name' => 'required|min:3|max:255',
            'neighborhood' => 'required|min:4|max:255',
            'street' => 'required|min:4|max:255',
            'number' => 'required|min:1|max:6',
            'complement' => 'min:4|max:255',
            'zipcode' => 'required',
            'city' => 'required|min:4|max:255',
            'phone' => 'min:8|max:14',
            'email' => ['required', 'email', Rule::unique('gyms')->ignore($id)],
            'password' => 'required|min:8|max:30'
        ]);
    }

}
