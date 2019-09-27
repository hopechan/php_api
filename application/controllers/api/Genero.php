<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/Format.php';
include_once(APPPATH . 'libraries/REST_Controller.php');
include_once(APPPATH . 'libraries/Format.php');
class Genero extends REST_Controller {
    
    public function __construct(){
        parent::__construct("rest");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, ORIGIN, X-Requested-With, Content, DELETE");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Authorization');
    }
    
    public function index_options(){
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    public function index_get($id = null){
        if (!empty($id)) {
            $data = $this->db->get_where("genero", ['id'=>$id])->row_array();
            if ($data == null) {
                $this->response(["El registro con ID $id no existe"], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data = $this->db->get("genero")->result_array();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $data = [
            'titulo' => $this->post('titulo')
        ];
        $this->db->insert('genero', $data);
        $query = $this->response($data, REST_Controller::HTTP_CREATED);
    }

    public function index_put($id){
        $data = $this->put();
        $this->db->update('genero', $data, array('id' => $id));
        $this->response("Registro actualizado", REST_Controller::OK);
    }

    public function index_delete($id){
        $this->db->delete('genero', array('id' => $id));
        $this->response("Registro Eliminado", REST_Controller::OK);
    }
}
?>