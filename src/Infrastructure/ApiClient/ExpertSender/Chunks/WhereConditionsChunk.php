<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Chunks;

class WhereConditionsChunk extends ArrayChunk
{
    const PATTERN = <<<'EOD'
<WhereConditions>
            %s
</WhereConditions>
EOD;

    /**
     * @return string
     */
    protected function getPattern()
    {
        return self::PATTERN;
    }
}
