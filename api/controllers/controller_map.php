<?php

class Controller_map extends Controller {

  function __construct() {
    $this->model = new Model_map();
    $this->view = new View();
  }
  

  function getAction($options) {
    $id = $options[0];
    
    $urlFile = $this->model->getData($id);

    if ($urlFile) {
      $this->view->responseWithFile($urlFile, 200);
    } else {
      $this->view->response('', 404);
    }
  }

  function createAction($arg) {
    $data = [
      "x" => 10,
      "y" => 10,
    ];
    $this->view->response($data, 200);
  }
}