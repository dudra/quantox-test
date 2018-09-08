<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/8/18
 * Time: 6:47 PM
 */

class Router {

	private $request;
	private $supportedHttpMethods = array(
		"GET",
		"POST"
	);

	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	public function __call($name, $args)
	{
		list($route, $method) = $args;

		if(!in_array(strtoupper($name), $this->supportedHttpMethods))
		{
			$this->invalidMethodHandler();
		}

		$this->{strtolower($name)}[$this->formatRoute($route)] = $method;
	}

	/**
	 * Removes trailing forward slashes from the right of the route.
	 * @param route (string)
	 */
	private function formatRoute($route)
	{
		$result = rtrim($route, '/');
		if ($result === '')
		{
			return '/';
		}
		return $result;
	}

	private function invalidMethodHandler()
	{
		header("{$this->request->serverProtocol} 405 Method Not Allowed");
	}

	private function defaultRequestHandler()
	{
		header("{$this->request->serverProtocol} 404 Not Found");
	}

	/**
	 * Resolves a route
	 */
	public function resolve()
	{
		$methodDictionary = $this->{strtolower($this->request->requestMethod)};
		$formatedRoute = $this->formatRoute($this->request->requestUri);
		$method = $methodDictionary[$formatedRoute];

		if(is_null($method))
		{
			$this->defaultRequestHandler();
			return;
		}

		if($this->isClosure($method)){
			echo call_user_func_array($method, array($this->request));
		}
		else{
			echo $method;
		}
	}

	public function isClosure($input)
	{
		return is_object($input) && ($input instanceof Closure);
	}

	public function __destruct()
	{
		$this->resolve();
	}

}