<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/Format.php';
include_once(APPPATH . 'libraries/REST_Controller.php');
include_once(APPPATH . 'libraries/Format.php');
class Tarea extends REST_Controller {
    
    public function __construct(){
        parent::__construct("rest");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, ORIGIN, X-Requested-With, Content, DELETE");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->helper(['jwt', 'authorization']); 
    }
    
    public function index_options(){
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    private function sinToken(){
        //esta funcion se ejecutara siempre que no exista un token
        $status = parent::HTTP_UNAUTHORIZED;
        $response = ['status' => $status, 'msg' => 'Acceso no autorizado'];
        $this->response($response, $status);
    }

    private function obtenerUnRegistro($id){
        $respuesta = $this->db->get_where("tarea", ['id'=>$id])->row_array();
        if ($respuesta == null) {
            $this->response(["El registro con ID $id no existe"], REST_Controller::HTTP_NOT_FOUND);
        }
        return $respuesta;
    }

    private function cabeceraAutenticacion(){
        //devuelve (si existe) el token sino su return sera false
        $headers = $this->input->request_headers();
        $token = $headers['Authorization'];
        return $token;
    }

    private function obtenerTodosLosRegistros(){
        return $this->db->get("tarea")->result_array();
    }

    private function nuevoRegistro(){
        $data = [
            'nombre' => $this->post('nombre'),
            'descripcion' => $this->post('descripcion'),
            'duracion' => $this->post('duracion'),
            'estado' => $this->post('estado')
        ];
        $this->db->insert('tarea', $data);
        $query = $this->response("Registro creado", REST_Controller::HTTP_CREATED);
    }

    public function index_get($id = null){
        //obtenemos el token de la peticion
        $token = $this->cabeceraAutenticacion();
        try {
            //se verifica que el token sea valido
            $auth = AUTHORIZATION::validateToken($token);
            //si no es valido devolver la respuesta que no tiene autorizacion
            if ($auth === false) {
                $this->sinToken();
            }else{
                //se uso un operador ternario para que el codigo sea mas limpio
                //si se recibe un parametro se devulve el registro que coincida 
                //sino devuelve todos los registros de la db
                $respuesta = (!empty($id)) ? $this->obtenerUnRegistro($id):$this->obtenerTodosLosRegistros();
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        }catch (Exception $e) {
            $this->sinToken();
        }
    }

    public function index_post(){
        $token = $this->cabeceraAutenticacion();
        try {
            /*si el token es valido se procede a obtener los datos de la
                peticion e ingresarlos a la base de datos sino
                se lanza un mensaje de error
            */
            $auth = AUTHORIZATION::validateToken($token) ? $this->nuevoRegistro(): $this->sinToken();
        } catch (Exception $e) {
            sinToken();
        }
    }

    public function index_put($id){
        $data = $this->put();
        $this->db->update('tarea', $data, array('id' => $id));
        $this->response("Registro actualizado", REST_Controller::OK);
    }

    public function index_delete($id){
        $this->db->delete('tarea', array('id' => $id));
        $this->response("Registro Eliminado", REST_Controller::OK);
    }
}
?>