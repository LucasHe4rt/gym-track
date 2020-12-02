<?php


namespace App\Http\Controllers\Instructor;

use \App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Instrutores"},
     *      summary="Lista todos os instrutores",
     *      path="/api/instructors/gym/{gym_id}",
     *      description="Array de objetos contendo todos os intrutores cadastrados pelo gym_id",
     *      @OA\Parameter (
     *          name="gym_id",
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
     *          description="Retorna todos os instrutores cadastrados"
     *      ),
     *     @OA\Response (
     *          response="500",
     *          description="Retorna a mensagem do erro interno."
     *     )
     * )
     */
    public function index(int $gym_id)
    {
        try {
            $instructors = Instructor::where('gym_id', $gym_id)->paginate(10);

            return response()->json(['instructors' => $instructors], 200);
        }
        catch (\Exception $exception)
        {
            return response($exception->getMessage(), 500);
        }
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
     *     @OA\Response (
     *          response="500",
     *          description="Retorna a mensagem do erro interno."
     *     )
     * )
     */

    public function show(int $id)
    {
        try {
            return response()->json(['instructor' => Instructor::findOrFail($id)], 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response('Instructor not found', 404);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), 500);
        }
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
     *              type="string"
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
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="500",
     *          description="Retorna a mensagem do erro interno."
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            $validator = $this->validateRequest($request->all());

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $request['password'] = Hash::make($request['password']);

            return response()->json(['instructor' => Instructor::create($request->all())], 201);
        } catch (\Exception $exception)
        {
            return response($exception->getMessage(), 500);
        }
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
     *              type="string"
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
     *          response="200",
     *          description="Instrutor atualizado"
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Instrutor não encontrado"
     *     ),
     *     @OA\Response (
     *          response="500",
     *          description="Retorna a mensagem do erro interno."
     *     )
     * )
     */

    public function update(Request $request, int $id)
    {
        try {

            $instructor = Instructor::findOrFail($id);

            $validator = $this->validateRequest($request->all(), $id);

            if ($validator->fails()) {
                return response($validator->errors(), 422);
            }

            $request['password'] = Hash::make($request['password']);
            $instructor->update($request->all());

            return response()->json(['instructor' => $instructor], 200);

        }
        catch (ModelNotFoundException $e)
        {
            return response('Instructor not found', 404);
        }
        catch (\Exception $exception)
        {
            return response($exception->getMessage(), 500);
        }
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
     *     @OA\Response (
     *          response="500",
     *          description="Retorna a mensagem do erro interno."
     *     )
     * )
     */

    public function delete(int $id)
    {
        try {
            Instructor::findOrFail($id)->delete();
            return response('Instructor deleted', 200);
        }
        catch (ModelNotFoundException $exception)
        {
            return response('Instructor not found', 404);
        }
        catch (\Exception $exception)
        {
            return response($exception->getMessage(), 500);
        }
    }

    protected function validateRequest(array $request, $id = 0)
    {
        return Validator::make($request, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('instructors')->ignore($id)],
            'phone' => 'nullable',
            'password' => 'required|string|max:255',
            'arrive' => 'required|date_format:H:i|before:leave',
            'leave' => 'required|date_format:H:i|after:arrive',
            'gym_id' => 'required|exists:gyms,id|numeric'
        ]);
    }
}
