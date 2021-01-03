<?php

// try {
//   $api = new usersApi();
//   echo $api->run();
// } catch (Exception $e) {
//   echo json_encode(Array('error' => $e->getMessage()));
// }

class Route {
  
  static $method = '';
  static $requestUri = [];
  static $requestParams = [];
  static $controller_name = '';
  
  static function start() {
    self::$requestUri = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
    self::$requestParams = $_REQUEST;

    self::includeToClassConstructorsForControllerModelView();
    
    $controller = self::createController();
    $method = self::getMethod();
    $action = self::getActionName($method);
    $options = array_slice(self::$requestUri, 2);

    if (method_exists($controller, $action)) {
      $controller->$action($options);
    } else {
      throw new RuntimeException('Invalid Action', 405);
    }
  }

  protected function includeToClassConstructorsForControllerModelView() {
    if (!empty(self::$requestUri[1])) {
      self::$controller_name = self::$requestUri[1];
    }

    // подцепляем файл с классом контроллера
		$controller_file = "controller_".strtolower(self::$controller_name).'.php';
		$controller_path = "api/controllers/".$controller_file;
		if(file_exists($controller_path)) {
			include $controller_path;
		}
		else {
			throw new Exception("Unexpected controller");
    }

    // подцепляем файл с классом модели
    $model_file = "model_".strtolower(self::$controller_name).'.php';
		$model_path = "api/models/".$model_file;
		if(file_exists($model_path)) {
			include $model_path;
		}
    
    // подцепляем файл с классом вьюшки
    $view_file = "view_".strtolower(self::$controller_name).'.php';
		$view_path = "api/views/".$view_file;
		if(file_exists($view_path)) {
			include $view_path;
		}
  }

  protected function getMethod() {
    //Определение метода запроса
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
      if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
          $method = 'DELETE';
      } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
          $method = 'PUT';
      } else {
          throw new Exception("Unexpected Header");
      }
    }
    return $method;
  }

  protected function createController() {
    // создаем контроллер
    $controller_name = 'Controller_'.self::$controller_name;
    return new $controller_name;
  }

  protected function getActionName($method) {
    switch ($method) {
        case 'GET':
            return 'getAction';
            break;
        case 'POST':
            return 'createAction';
            break;
        case 'PUT':
            return 'updateAction';
            break;
        case 'DELETE':
            return 'deleteAction';
            break;
        default:
            return null;
    }
  }
}