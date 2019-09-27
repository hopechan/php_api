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
    }
    
    public function index_options(){
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    public function index_get($id = null){
        if (!empty($id)) {
            $data = $this->db->get_where("tarea", ['id'=>$id])->row_array();
            if ($data == null) {
                $this->response(["El registro con ID $id no existe"], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data = $this->db->get("tarea")->result_array();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $data = [
            'nombre' => $this->post('nombre'),
            'descripcion' => $this->post('descripcion'),
            'duracion' => $this->post('duracion'),
            'estado' => $this->post('estado')
        ];
        $this->db->insert('tarea', $data);
        $query = $this->response($data, REST_Controller::HTTP_CREATED);
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