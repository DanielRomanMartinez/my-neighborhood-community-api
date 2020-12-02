<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Chunks;

class ReceiversChunk extends ArrayChunk
{
    const PATTERN = <<<'EOD'
<Receivers>
            %s
</Receivers>
EOD;

    /**
     * @return string
     */
    protected function getPattern()
    {
        return self::PATTERN;
    }
}
