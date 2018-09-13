<?php declare(strict_types=1);

namespace WyriHaximus\React\Inspector\Http\Middleware;

use Rx\ObservableInterface;
use WyriHaximus\React\Http\Middleware\MeasureMiddleware;
use WyriHaximus\React\Inspector\CollectorInterface;
use WyriHaximus\React\Inspector\Metric;
use function ApiClients\Tools\Rx\observableFromArray;

final class MeasureMiddlewareCollector implements CollectorInterface
{
    /** @var MeasureMiddleware[] */
    private $middleware = [];

    public function register(string $key, MeasureMiddleware $middleware)
    {
        $this->middleware[$key] = $middleware;
    }

    public function collect(): ObservableInterface
    {
        $metrics = [];

        /**
         * @var string            $key
         * @var MeasureMiddleware $middleware
         */
        foreach ($this->middleware as $key => $middleware) {
            /** @var Metric $metric */
            foreach ($middleware->collect() as $metricKey => $metricValue) {
                $metrics[] = new Metric(
                    $key . '.' . $metricKey,
                    $metricValue
                );
            }
        }

        return observableFromArray($metrics);
    }

    public function cancel(): void
    {
        $this->middleware = [];
    }
}
