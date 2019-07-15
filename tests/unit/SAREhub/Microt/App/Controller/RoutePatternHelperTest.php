<?php

namespace SAREhub\Microt\App\Controller;

use PHPUnit\Framework\TestCase;

class RoutePatternHelperTest extends TestCase
{

    public function testPattern()
    {
        $this->assertEquals("a/b/c", RoutePatternHelper::pattern("a", "b", "c"));
    }

    public function testAttr()
    {
        $this->assertEquals('{abc}', RoutePatternHelper::attr("abc"));
    }
}
