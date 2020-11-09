<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\EmergencyContacts;
use App\Models\MedicalConditions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{


    private $client;

    /**
     * Create a new controller instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client){
        $this->client = $client;
    }



    /**
     * @OA\Post(
     *   path="/api/clients",
     *   tags={"Clientes"},
     *   summary="Cria um novo cliente",
     *   description="Cria um novo cliente",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               required = {"name", "email", "password", "birthday", "sex", "neighborhood", "street", "number", "zipcode",
     *               "city", "gym_id"},
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="O E-mail do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   description="A senha do cliente",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="birthday",
     *                   description="Dia do nascimento",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="sex",
     *                   description="O sexo do cliente",
     *                   type="string",
     *                   enum = {"Masculino", "Feminino"}
     *               ),
     *               @OA\Property(
     *                   property="neighborhood",
     *                   description="Bairro do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="street",
     *                   description="Logradouro do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="number",
     *                   description="Numero da casa do cliente",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="complement",
     *                   description="Complemento de endereço",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="zipcode",
     *                   description="CEP do cliente",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="city",
     *                   description="Cidade do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="phone",
     *                   description="Telefone do cliente",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="height",
     *                   description="Altura do cliente",
     *                   type="float"
     *               ),
     *               @OA\Property(
     *                   property="blood",
     *                   description="Tipo de sangue do cliente",
     *                   type="float"
     *               ),
     *               @OA\Property(
     *                   property="gym_id",
     *                   description="Id da gym",
     *                   type="integer"
     *               ),
     *              @OA\Property (
     *                  property="emergency_contacts",
     *                  description="Contatos de emergencia",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="neighborhood",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="street",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="number",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="complement",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="zipcode",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="city",
     *                          type="string"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property (
     *                  property="medical_conditions",
     *                  description="Condições médicas",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="medicine",
     *                          type="string"
     *                      ),
     *                 ),
     *              ),
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *          response="201",
     *          description="Retorna o cliente que foi atualizada."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Cliente não encontrado."
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
            $client = $this->client->create($request->all());

            if ($contacts = $request['emergency_contacts']){
                $contacts = is_array($contacts) ? $contacts : json_decode($contacts, true);
                foreach ($contacts as $contact){
                    $contact['client_id'] = $client->id;
                    $client->emergencyContacts()->create($contact);
                }
            }

            if ($conditions = $request['medical_conditions']){
                $conditions = is_array($conditions) ? $conditions : json_decode($conditions, true);
                foreach ($conditions as $condition){
                    $condition['client_id'] = $client->id;
                    $client->medicalConditions()->create($condition);
                }
            }

            $client->load('medicalConditions', 'emergencyContacts');

            return response()->json(compact('client'), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


    /**
     * @OA\Put(
     *   path="/api/clients/{id}",
     *   tags={"Clientes"},
     *   summary="Atualiza um cliente",
     *   description="Atualiza um cliente pelo id passado",
     *     @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Id do cliente",
     *          required=true,
     *          @OA\Schema (
     *             type="integer",
     *             format="int64"
     *          ),
     *      ),
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="O E-mail do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="birthday",
     *                   description="Dia do nascimento",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="sex",
     *                   description="O sexo do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="neighborhood",
     *                   description="Bairro do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="street",
     *                   description="Logradouro do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="number",
     *                   description="Numero da casa do cliente",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="complement",
     *                   description="Compleme",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="zipcode",
     *                   description="CEP do cliente",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="city",
     *                   description="Cidade do cliente",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="phone",
     *                   description="Telefone do cliente",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="height",
     *                   description="Altura do cliente",
     *                   type="float"
     *               ),
     *               @OA\Property(
     *                   property="blood",
     *                   description="Tipo de sangue do cliente",
     *                   type="float"
     *               ),
     *               @OA\Property(
     *                   property="gym_id",
     *                   description="Id da gym",
     *                   type="integer"
     *               ),
     *              @OA\Property (
     *                  property="emergency_contacts",
     *                  description="Contatos de emergencia",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="neighborhood",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="street",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="number",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="complement",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="zipcode",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="city",
     *                          type="string"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property (
     *                  property="medical_conditions",
     *                  description="Condições médicas",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="medicine",
     *                          type="string"
     *                      ),
     *                 ),
     *              ),
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *          response="201",
     *          description="Retorna o cliente que foi atualizada."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Cliente não encontrado."
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

            $client = $this->client->findOrFail($id);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $request['password'] = Hash::make($request['password']);
            $client->update($request->all());

            if ($contacts = $request['emergency_contacts']){
                $contacts = is_array($contacts) ? $contacts : json_decode($contacts, true);
                foreach ($contacts as $contact){
                    $client->emergencyContacts()->findOrFail($contact['id'])->update($contact);
                }
            }

            if ($conditions = $request['medical_conditions']){
                $conditions = is_array($conditions) ? $conditions: json_decode($conditions, true);
                foreach ($conditions as $condition){
                    $client->medicalConditions()->findOrFail($condition['id'])->update($condition);
                }
            }

            $client->load('medicalConditions', 'emergencyContacts');


            return response()->json(compact('client'), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'client not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }



    /**
     * @OA\Get(
     *      tags={"Clientes"},
     *      summary="Lista todos os clientes",
     *      path="/api/clients",
     *      description="Array de objetos contendo todos os clientes cadastrados",
     *      @OA\Response(
     *          response="200",
     *          description="Retorna todos os clientes cadastrados"
     *      ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function index(){
        try {
            $clients = $this->client->all();
            $clients->load('medicalConditions', 'emergencyContacts');


            return response()->json(compact('clients'), 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Clientes"},
     *      summary="Busca um cliente",
     *      path="/api/clients/{id}",
     *      description="Busca o cliente com o id passado por parametro",
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
     *          description="Retorna o cliente"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Cliente não encontrado"
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function show(int $id) {
        try {
            $client = $this->client->findOrFail($id);
            $client->load('medicalConditions', 'emergencyContacts');

            return response()->json(compact('client'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'client not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete (
     *     tags={"Clientes"},
     *      summary="Deleta um cliente",
     *      path="/api/clients/{id}",
     *      description="Deleta o cliente com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Client id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Deleta o cliente"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Cliente não encontrada"
     *     ),
     *      @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function delete(int $id){
        try {
            $client = $this->client->findOrFail($id);
            $client->delete();

            $message = 'Client deleted';
            return response()->json(compact('message'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    protected function validateRequest(array $request, $id = 0)
    {

        if ($conditions = $request['medical_conditions']) {
            $conditions = is_array($conditions) ? $conditions: json_decode($conditions, true);
            foreach ($conditions as $condition){
                $validator = Validator::make($condition, [
                    'name' => 'required|min:3|max:255',
                    'description' => 'required|min:3|max:300',
                    'medicine' => 'required|min:3|max:255',
                ]);
                if ($validator->fails()) return $validator;
            }
        }

        if ($contacts = $request['emergency_contacts']) {
            $contacts = is_array($contacts) ? $contacts: json_decode($contacts, true);
            foreach ($contacts as $contact) {
                $validator = Validator::make($contact, [
                    'name' => 'required|min:3|max:255',
                    'phone' => '',
                    'neighborhood' => 'required|min:4|max:255',
                    'street' => 'required|min:4|max:255',
                    'number' => 'required|min:1|max:6',
                    'complement' => 'min:4|max:255',
                    'zipcode' => 'required',
                    'city' => 'required|min:4|max:255',
                ]);
                if ($validator->fails()) return $validator;
            }
        }

       $validator = Validator::make($request, [
            'name' => 'required|min:3|max:255',
            'neighborhood' => 'required|min:4|max:255',
            'street' => 'required|min:4|max:255',
            'number' => 'required|min:1|max:6',
            'complement' => 'min:4|max:255',
            'zipcode' => 'required',
            'city' => 'required|min:4|max:255',
            'phone' => 'min:8|max:14',
            'email' => ['required', 'email', Rule::unique('clients')->ignore($id)],
            'birthday' => 'required|date',
            'sex' => 'required|string',
            'height' => 'numeric',
            'weight' => 'numeric',
            'blood' => 'max:3|min:1',
            'gym_id' => 'required|numeric',
            'password' => 'required|min:8|max:30',
        ]);

        return $validator;
    }

}
