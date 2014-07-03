<?php namespace Awellis13\Resque;

use Exception;

class ResqueJobHandler {

	public function setUp() 
	{
		require_once '/vagrant/bootstrap/start.php';
	}

	public function perform()
	{
		list($class, $method) = explode('@', $this->args['job']);

		if (!$method) {
			if (method_exists($class, 'fire')) {
				// prefer Laravel's fire
				$method = 'fire';
			} else if (method_exists($class, 'perform')) {
				// but still fall back to Resque's perform
				$method = 'perform';
			} else {
				throw new Exception('No such action: ' . $job);
			}
		}

		$classInstance = new $class;
		return $classInstance->{$method}($this->args['data']);
	}

}