<?php

namespace App\Http\Controllers;

use App\Services\Metrics;
use Illuminate\Http\Response;
use Prometheus\RenderTextFormat;

class MetricsController extends Controller
{
    public function __invoke(Metrics $metrics): Response
    {
        $renderer = new RenderTextFormat;
        $output = $renderer->render($metrics->registry()->getMetricFamilySamples());

        return response($output, 200, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}
