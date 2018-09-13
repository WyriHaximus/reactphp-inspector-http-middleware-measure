<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Inspector\Http\Middleware;

use ApiClients\Tools\TestUtilities\TestCase;
use Rx\React\Promise;
use WyriHaximus\React\Http\Middleware\MeasureMiddleware;
use WyriHaximus\React\Inspector\Http\Middleware\MeasureMiddlewareCollector;
use WyriHaximus\React\Inspector\Metric;

final class MeasureMiddlewareCollectorTest extends TestCase
{
    public function testCollect()
    {
        $middleware =  new MeasureMiddleware();
        $collector = new MeasureMiddlewareCollector();
        $collector->register('http-server', $middleware);
        $metrics = $this->await(Promise::fromObservable($collector->collect()->toArray()));

        self::assertCount(6, $metrics);
        foreach ($metrics as $metric) {
            self::assertInstanceOf(Metric::class, $metric);
        }
    }
}
