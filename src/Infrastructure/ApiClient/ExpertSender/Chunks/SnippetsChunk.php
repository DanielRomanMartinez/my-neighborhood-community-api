<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Chunks;

class SnippetsChunk extends ArrayChunk
{
    const PATTERN = <<<'EOD'
<Snippets>
            %s
</Snippets>
EOD;

    /**
     * @return string
     */
    protected function getPattern()
    {
        return self::PATTERN;
    }
}
