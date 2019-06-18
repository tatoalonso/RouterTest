<?php

namespace MpwebUnit\Test;

use Mpweb\Router\Router;
use PHPUnit_Framework_TestCase;
use InvalidArgumentException;
use Exception;


final class RouterTest extends PHPUnit_Framework_TestCase
{

    private $router;


    /**
     * @See https://phpunit.de/manual/current/en/fixtures.html
     */
    protected function setUp()
    {
        $this->router = new Router( array (
            '/{category}/{product}/' => 'controller1',
            '/{category}/' => 'controller2',
            '/{category}' => 'controller3',
            '/\\\{category}/' =>'controller4' ));
    }

    /**
     * @See https://phpunit.de/manual/current/en/fixtures.html
     */
    protected function tearDown()
    {

        $this->router = null;

    }

    /** @test */
    public function dummyTest()
    {
        $this->router;
    }

    /**
     * @dataProvider additionProvider
     */
    public function testPathFounds($expected, $uri)
    {
        $this->assertSame($expected, $this->router->routing($uri));
    }

    public function additionProvider()
    {
        return [
            'first path matches'  => ['controller1','/balones/mikasa/'],
            'second path matches' => ['controller2','/balones/'],
            'third path matches' => ['controller3','/balones'],
            'fourth path matches' => ['controller4','/'.'\\'.'balones/'],

        ];
    }

    /** @test */
    public function shouldThrowExceptionIfMissingPath()
    {
        $this->expectException(Exception::class);
        $this->router->routing('/category/subcategory/product');
    }

    /** @test */
    public function shouldThrowExceptionIfRightPathButIncorrectCharacterInRegex()
    {
        $this->expectException(Exception::class);
        $this->router->routing('/%/product/');
    }

    /** @test */
    public function shouldThrowInvalidArgumentExceptionIfNotUriProvided()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->router->routing('');
    }

}