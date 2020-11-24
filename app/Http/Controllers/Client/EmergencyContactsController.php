<?php


namespace App\Http\Controllers\Client;


use App\Models\EmergencyContacts;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmergencyContactsController
{
    private $contact;

    public function __construct() {
        $this->contact = new EmergencyContacts;
    }


    /**
     * @OA\POST (
     *     tags={"Contatos de emergência"},
     *      summary="Cria o contato",
     *      path="/api/clients/contacts",
     *      description="Cria o contato de emergência",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *               type="object",
     *               required = {"name", "neighborhood", "street", "number", "zipcode", "city", "client_id"},
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="neighborhood",
     *                   description="Bairro do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="street",
     *                   description="Logradouro do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="number",
     *                   description="Numero da casa do contato",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="complement",
     *                   description="Complemento do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="zipcode",
     *                   description="CEP do contato",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="city",
     *                   description="Cidade do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="phone",
     *                   description="Telefone do contato",
     *                   type="integer"
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
     *          description="Retorna o contato que foi criado."
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

            $contact = $this->contact->create($request->all());


            return response()->json(compact('contact'), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }



    /**
     * @OA\Put(
     *     tags={"Contatos de emergência"},
     *      summary="Atualiza o contato",
     *      path="/api/clients/contacts/{id}",
     *      description="Atualiza o contato pelo id",
     *     @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="Id do contato",
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
     *              required = {"name", "neighborhood", "street", "number", "zipcode", "city", "client_id"},
     *              type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="Nome do contat0",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="neighborhood",
     *                   description="Bairro do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="street",
     *                   description="Logradouro do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="number",
     *                   description="Numero da casa do contato",
     *                   type="integer"
     *               ),
     *              @OA\Property(
     *                   property="complement",
     *                   description="Complemento do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="zipcode",
     *                   description="CEP do contato",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="city",
     *                   description="Cidade do contato",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="phone",
     *                   description="Telefone do contato",
     *                   type="integer"
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
     *          description="Retorna o contato que foi atualizada."
     *      ),
     *     @OA\Response (
     *          response="422",
     *          description="Parametros reprovaram na validação."
     *     ),
     *     @OA\Response (
     *          response="404",
     *          description="Contato não encontrado."
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      )
     * )
    */
    public function update(Request $request, int $id){
        try {
            $contact = $this->contact->findOrFail($id);

            print_r($request->all());
            $validator = $this->validateRequest($request->all());

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 422);

            $contact->update($request->all());

            return response()->json(compact('contact'), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'contact not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *      tags={"Contatos de emergência"},
     *      summary="Lista todos as contatos de emergência",
     *      path="/api/clients/contacts",
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
            $contacts = $this->contact->all();
            $contacts->load('client');
            return response()->json(compact('contacts'), 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }


    /**
     * @OA\Get(
     *     tags={"Contatos de emergência"},
     *      summary="Busca um contato",
     *      path="/api/clients/contacts/{id}",
     *      description="Busca a contato de emergência com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="EmergencyContacts id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Retorna o contato de emergência"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Contato não encontrado"
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
    */
    public function show($id){
        try {
            $contact = $this->contact->findOrFail($id);
            $contact->load('client');
            return response()->json(compact('contact'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Contact not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete (
     *     tags={"Contatos de emergência"},
     *      summary="Deleta um contato",
     *      path="/api/clients/contacts/{id}",
     *      description="Deleta contato com o id passado por parametro",
     *      @OA\Parameter (
     *          name="id",
     *          in="path",
     *          description="EmergencyContact id",
     *          required=true,
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Deleta o contato"
     *      ),
     *     @OA\Response (
     *          response="404",
     *          description="Contato não encontrada"
     *     ),
     *      @OA\Response(
     *          response="500",
     *          description="Retorna erro que ocorreu."
     *      ),
     * )
     */
    public function delete(int $id){
        try {
            $contact = $this->contact->findOrFail($id);
            $contact->delete();

            $message = 'Contact deleted';
            return response()->json(compact('message'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Contact not found'], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


    protected function validateRequest(array $request)
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
            'client_id' => 'required|numeric'
        ]);
    }


}
