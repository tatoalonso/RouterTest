<?php

namespace Mpweb\Router;


use Exception;
use InvalidArgumentException;


class Router

{
    private $paths;

    public function __construct(array $paths){

        $this->paths = $paths;

    }

    private function  extractor(string $uri,string  $regexUri): array{

        $params = [];


        preg_match_all('\'' . '{(\w+)}' . '\'', $regexUri, $matches);


        $matches = $matches[0];

        foreach ($matches as $key => $value) {
            $matches[$key] = str_replace('{', '', $matches[$key]);
            $matches[$key] = str_replace('}', '', $matches[$key]);
        }

        //Replace parameter names to transform URL to regex format.
        $regexUri = preg_replace('%' . '{(\w+)}' . '%', '(\w+|\d+)', $regexUri);
        $regexUri .= '$';
        $regexUri = '%^' . $regexUri . '$%';
        $res = preg_match($regexUri, $uri, $params);
        if (!$res || $res == 0) {

            return array();
        }

        $paramLength = count($matches);
        $keyParams = array();
        for ($i = 0; $i < $paramLength; $i++) {
            $keyParams[$matches[$i]] = $params[$i + 1];
        }

        return $keyParams;


    }


    public function routing(string $uri): string{

        if(empty($uri)){

            throw new InvalidArgumentException('404, not uri provided');

        }
        foreach ($this->paths as $path => $controller)
        {
            $matchedPath = $this->extractor($uri, $path);
            
            if(!empty($matchedPath)){

                return $controller;

            }
        }
        throw new Exception('404,  not found.');
    }



}