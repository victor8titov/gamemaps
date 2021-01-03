<?php

class View
{
	public function __construct() {
		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Methods: *");
	}
	
	function response($data, $status = 500) {
		header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
		header("Content-Type: application/json");
		return json_encode($data);
	}

	function responseWithFile($file, $status = 500) {
		header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
				ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			exit;
		} 
	}

	private function requestStatus($code) {
		$status = array(
				200 => 'OK',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				500 => 'Internal Server Error',
		);
		return ($status[$code])?$status[$code]:$status[500];
	}
}
