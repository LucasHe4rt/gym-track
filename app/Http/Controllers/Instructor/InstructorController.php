<?php


namespace App\Http\Controllers\Instructor;

use \App\Http\Controllers\Controller;
use App\Entities\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{

    /**
     * @OA\Info(
     *      title="Gym-track API",
     *      version="0.1"
     * )
     */

    /**
     * @OA\Get(
     *     tags={"Instrutores"},
     *      summary="Lista todos os instrutores",
     *      path="/api/instructors",
     *      description="Array de objetos contendo todos os intrutores cadastrados",
     *      @OA\Response(
     *          response="200",
     *          description="Retorna todos os instrutores cadastrados"
     *      ),
     * )
     */

    public function index()
    {
        return response()->json(Instructor::all(), 200);
    }

    /**
     * @OA\Get(
     *     tags={"Instrutores"},
     *      summary="Busca um instrutor",
     *      path="/api/instructors/{id}",
     *      description="Busca o instrutor com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Instructor id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Retorna o instrutor"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Instrutor não encontrado"
     *     ),
     * )
     */

    public function show(int $id)
    {
        return response()->json(Instructor::findOrFail($id), 200);
    }

    /**
     * @OA\Post(
     *     tags={"Instrutores"},
     *      summary="Adiciona um novo instrutor",
     *      path="/api/instructors",
     *      description="Cria um novo instrutor",
     *      @OA\Parameter (
     *          name="name",
     *          in="query",
     *          description="Nome do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          in="query",
     *          description="Email do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phone",
     *          in="query",
     *          description="telefone do instrutor",
     *          required=false,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="password",
     *          in="query",
     *          description="senha do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="arrive",
     *          in="query",
     *          description="horario de entrada do instrutor",
     *          required=true
     *      ),
     *     @OA\Parameter (
     *          name="leave",
     *          in="query",
     *          description="horario de saída do instrutor",
     *          required=true,
     *      ),
     *     @OA\Parameter (
     *          name="gym_id",
     *          in="query",
     *          description="Id da academia que o instrutor irá pertencer",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna o instrutor que foi criado."
     *      ),
     *     @OA\Response (
     *          response="400",
     *          description="Parametros reprovaram na validação."
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        return response()->json(Instructor::create($request->all()), 201);
    }

    /**
     * @OA\Put(
     *     tags={"Instrutores"},
     *      summary="Atualiza um instrutor",
     *      path="/api/instructors/{id}",
     *      description="Atualiza um instrutor pelo id",
     *     @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Id do instrutor",
     *          required=true,
     *          @OA\Schema (
     *             type="integer",
     *             format="int64"
     *          ),
     *      ),
     *      @OA\Parameter (
     *          name="name",
     *          in="query",
     *          description="Nome do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          in="query",
     *          description="Email do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phone",
     *          in="query",
     *          description="telefone do instrutor",
     *          required=false,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="password",
     *          in="query",
     *          description="senha do instrutor",
     *          required=true,
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter (
     *          name="arrive",
     *          in="query",
     *          description="horario de entrada do instrutor",
     *          required=true
     *      ),
     *     @OA\Parameter (
     *          name="leave",
     *          in="query",
     *          description="horario de saída do instrutor",
     *          required=true,
     *      ),
     *     @OA\Parameter (
     *          name="gym_id",
     *          in="query",
     *          description="Id da academia que o instrutor irá pertencer",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna o instrutor que foi criado."
     *      ),
     *     @OA\Response (
     *          response="400",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Instrutor não encontrado"
     *     )
     * )
     */

    public function update(Request $request, int $id)
    {
        $validator = $this->validateRequest($request->all(), $id);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $instructor = Instructor::findOrFail($id);
        $instructor->update($request->all());

        return response()->json($instructor, 200);
    }

    /**
     * @OA\Delete (
     *     tags={"Instrutores"},
     *      summary="Deleta um instrutor",
     *      path="/api/instructors/{id}",
     *      description="Deleta o instrutor com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Instructor id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Deleta o instrutor"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Instrutor não encontrado"
     *     ),
     * )
     */

    public function delete(int $id)
    {
        Instructor::findOrFail($id)->delete();
        return response('Instructor deleted', 200);
    }

    protected function validateRequest(array $request, $id = 0)
    {
        return Validator::make($request, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('instructors')->ignore($id)],
            'phone' => 'nullable|numeric',
            'password' => 'required|string|max:255',
            'arrive' => 'required|date_format:H:i|before:leave',
            'leave' => 'required|date_format:H:i|after:arrive',
            'gym_id' => 'required|exists:gyms,id|numeric'
        ]);
    }
}
