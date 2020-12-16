<?php

use rest_server\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

    class Novel extends REST_Controller {
        function __construct()
        {
            parent::__construct();
            $this->load->model('novel_model', 'novel');
        }
        // Get Data
        public function index_get() {
            $id = $this->get('id');
            // jika id tidak ada (tidak panggil) 
            if($id === null) {
                // maka panggil semua data
                $novel = $this->novel->getNovel();
                // tapi jika id di panggil maka hanya id tersebut yang akan muncul pada data tersebut
            } else {
                $novel = $this->novel->getNovel($id);
            }
            if($novel) {
                $this->response([
                    'status' => true,
                    'data' => $novel
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'id not found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            
            }
        }
        // delete data
        public function index_delete() {
            $id = $this->delete('id');
            if($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'provide an id'
                ], REST_Controller::HTTP_BAD_REQUEST); 
            } else {
                if($this->novel->deleteNovel($id) > 0) {
                    // Ok
                    $this->response([
                        'status' => true,
                        'id' => $id,
                        'message' => 'deleted success'
                    ], REST_Controller::HTTP_NO_CONTENT);
                } else {
                    // id not found
                    $this->response([
                        'status' => false,
                        'message' => 'id not found'
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                
                }
            }
        }
        // post data
        public function index_post() {
            $data = [
                'judul' => $this->post('judul'),
                'genre' => $this->post('genre'),
                'penulis' => $this->post('penulis'),
            ];
            if ($this->novel->createNovel($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'new novel has been created'
                ], REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'failed create data'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
        // update data
        public function index_put() {
            $id = $this->put('id');
            $data = [
                'judul' => $this->post('judul'),
                'genre' => $this->post('genre'),
                'penulis' => $this->post('penulis'),
            ];
            if ($this->novel->updateNovel($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'update novel has been updated'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'failed to update data'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
?>