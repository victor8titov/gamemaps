<?php

class Controller {
	
	public $model;
	public $view;

	function __construct() {
		$this->view = new View();
	}
	
	function getAction($arg) {}
	function createAction($arg) {}
	function updateAction($arg) {}
	function deleteAction($arg) {}
}

