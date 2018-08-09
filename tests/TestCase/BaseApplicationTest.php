<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2018 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\WebTools\Test\TestCase;

use BEdita\WebTools\BaseApplication;
use Cake\Console\CommandCollection;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Shell\HelpShell;
use Cake\Shell\VersionShell;
use Cake\TestSuite\IntegrationTestCase;

/**
 * {@see BEdita\WebTools\BaseApplication} Test Case
 *
 * @coversDefaultClass \BEdita\WebTools\BaseApplication
 */
class BaseApplicationTest extends IntegrationTestCase
{
    /**
     * Test `console` method
     *
     * @return void
     *
     * @covers ::console
     */
    public function testConsole() : void
    {
        $app = new BaseApplication(dirname(dirname(__DIR__)) . '/config');
        $app->bootstrap();
        $commands = new CommandCollection([
            'version' => VersionShell::class,
            'help' => HelpShell::class,
        ]);

        $commands = $app->console($commands);
        $cache = $commands->get('cache');
        static::assertNotEmpty($cache);
        static::assertEquals('BEdita\WebTools\Shell\CacheShell', $cache);
    }

    /**
     * Test `middleware` method
     *
     * @return void
     *
     * @covers ::middleware
     */
    public function testMiddleware() : void
    {
        $app = new BaseApplication(dirname(dirname(__DIR__)) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        static::assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->get(0));
        static::assertInstanceOf(AssetMiddleware::class, $middleware->get(1));
        static::assertInstanceOf(RoutingMiddleware::class, $middleware->get(2));
    }
}