<?php


namespace App\Http\Controllers\Client;


use App\Models\MedicalConditions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalConditionsController
{
    private $condition;

    public function __construct() {
        $this->condition = new MedicalConditions;
    }


    /**
     * @OA\POST (
     *     tags={"Condições médicas"},
     *      summary="Cria a condição",
     *      path="/api/clients/conditions",
     *      description="Cria a condição médica",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *              required = {"name", "client_id"},
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome da condição",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="description",
     *                   description="A descrição da condição médica",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="medicine",
     *                   description="Medicamentos da condição",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="client_id",
     *                   description="Id do cliente",
     *                   type="integer"
     *               ),
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna a condição que foi criado."
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
    public function store(Request $request) {
        try {
            $validator = $this->validateRequest($request->all());

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $condition = $this->condition->create($request->all());


            return response()->json(compact('condition'), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


    /**
     * @OA\Put(
     *     tags={"Condições médicas"},
     *      summary="Atualiza a condição",
     *      path="/api/clients/conditions/{id}",
     *      description="Atualiza a condição pelo id",
     *     @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Id da condição",
     *          required=true,
     *          @OA\Schema (
     *             type="integer",
     *             format="int64"
     *          ),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *              type="object",
     *              required = {"name", "client_id"},
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome da condição",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="description",
     *                   description="A descrição da condição médica",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="medicine",
     *                   description="Medicamentos da condição",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="client_id",
     *                   description="Id do cliente",
     *                   type="integer"
     *               ),
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Retorna a condição que foi atualizada."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Condição não encontrado."
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
     */
    public function update(Request $request, int $id){
        try {
            $condition = $this->condition->findOrFail($id);

            $validator = $this->validateRequest($request->all());

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $condition->update($request->all());

            return response()->json(compact('condition'), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'condition not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *      tags={"Condições médicas"},
     *      summary="Lista todos as condições médicas",
     *      path="/api/clients/conditions",
     *      description="Array de objetos contendo todas os contatos de emergência cadastrados",
     *      @OA\Response(
     *          response="200",
     *          description="Retorna todos os contatos cadastrados"
     *      ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function index(){
        try {
            $conditions = $this->condition->all();
            $conditions->load('client');
            return response()->json(compact('conditions'), 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }


    /**
     * @OA\Get(
     *     tags={"Condições médicas"},
     *      summary="Busca uma condição",
     *      path="/api/clients/conditions/{id}",
     *      description="Busca a condição com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="MedicalConditions id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Retorna a condição"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Condição não encontrado"
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function show($id){
        try {
            $condition = $this->condition->findOrFail($id);
            $condition->load('client');
            return response()->json(compact('condition'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Condition not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete (
     *     tags={"Condições médicas"},
     *      summary="Deleta uma condição",
     *      path="/api/clients/conditions/{id}",
     *      description="Deleta a condição com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="MedicalCondition id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Deleta a condição"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Condição não encontrada"
     *     ),
     *      @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function delete(int $id){
        try {
            $condition = $this->condition->findOrFail($id);
            $condition->delete();

            $message = 'Condition deleted';
            return response()->json(compact('message'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Condition not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


    protected function validateRequest(array $request)
    {
        return Validator::make($request, [
            'name' => 'required|min:3|max:255',
            'description' => 'min:3|max:400',
            'medicine' => 'min:3|max:255',
            'client_id' => 'required|numeric'
        ]);
    }
}
