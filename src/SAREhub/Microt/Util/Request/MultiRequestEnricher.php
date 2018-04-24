<?php


namespace SAREhub\Microt\Util\Request;


use Slim\Http\Request;

class MultiRequestEnricher implements RequestEnricher
{
    /**
     * @var RequestEnricher[]
     */
    private $enrichers;

    public function __construct(array $enrichers)
    {
        $this->enrichers = $enrichers;
    }

    public function enrich(Request $request): Request
    {
        $enrichedRequest = $request;

        foreach ($this->enrichers as $enricher) {
            $enrichedRequest = $enricher->enrich($enrichedRequest);
        }

        return $enrichedRequest;
    }
}