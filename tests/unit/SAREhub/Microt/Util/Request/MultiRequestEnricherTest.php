<?php

namespace SAREhub\Microt\Util\Request;

use PHPUnit\Framework\TestCase;
use SAREhub\Microt\Test\App\HttpHelper;

class MultiRequestEnricherTest extends TestCase
{

    public function testEnrich()
    {
        $enricher1 = \Mockery::mock(RequestEnricher::class);
        $enricher2 = \Mockery::mock(RequestEnricher::class);
        $multiEnricher = new MultiRequestEnricher([$enricher1, $enricher2]);
        $inRequest = HttpHelper::request();

        $enrichedRequest1 = HttpHelper::request();
        $enricher1->expects("enrich")->withArgs([$inRequest])->andReturn($enrichedRequest1);
        $enrichedRequest2 = HttpHelper::request();
        $enricher2->expects("enrich")->withArgs([$enrichedRequest1])->andReturn($enrichedRequest2);

        $current = $multiEnricher->enrich($inRequest);

        $this->assertSame($enrichedRequest2, $current);
    }
}
