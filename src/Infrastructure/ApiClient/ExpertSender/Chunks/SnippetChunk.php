<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Chunks;

use App\Infrastructure\ApiClient\ExpertSender\Entities\Snippet;

class SnippetChunk implements ChunkInterface
{
    const PATTERN = <<<'EOD'
        <Snippet>
            <Name>%s</Name>
            <Value>%s</Value>
        </Snippet>
EOD;

    /** @var Snippet */
    protected $snippet;

    public function __construct(Snippet $snippet)
    {
        $this->snippet = $snippet;
    }

    public function getText()
    {
        return sprintf(self::PATTERN, $this->snippet->getName(), $this->snippet->getValue());
    }
}
