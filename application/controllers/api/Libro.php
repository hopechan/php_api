<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/Format.php';
include_once(APPPATH . 'libraries/REST_Controller.php');
include_once(APPPATH . 'libraries/Format.php');

class libro extends REST_Controller {
    
    public function __construct(){
        parent::__construct("rest");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, ORIGIN, X-Requested-With, Content, DELETE");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    
    public function index_options(){
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    public function index_get($isbn = null){
        if (!empty($isbn)) {
            $this->db->select('l.*, g.titulo as genero')->from('libro as l')->join('genero as g', 'l.genero = g.id');
            $data = $this->db->get_where("libro", ['l.isbn'=>$isbn])->row_array();
            if ($data == null) {
                $this->response(["El registro con ID $isbn no existe"], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $this->db->select('l.*, g.titulo as genero')->from('libro as l')->join('genero as g', 'l.genero = g.id');
            $data = $this->db->get()->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post(){
        $data = [
            'isbn' => $this->post('isbn'),
            'titulo' => $this->post('titulo'),
            'autor' => $this->post('autor'),
            'genero' => $this->post('genero')
        ];
        $this->db->insert('libro', $data);
        $query = $this->response($data, REST_Controller::HTTP_CREATED);
    }

    public function index_put($isbn){
        $data = $this->put();
        $this->db->update('libro', $data, array('isbn' => $isbn));
        $this->response("Registro actualizado", REST_Controller::HTTP_OK);
    }

    public function index_delete($isbn){
        $this->db->delete('libro', array('isbn' => $isbn));
        $this->response("Registro Eliminado", REST_Controller::HTTP_OK);
    }

    //tarea : crear un controlador para logearse que genere un token 
}
?>