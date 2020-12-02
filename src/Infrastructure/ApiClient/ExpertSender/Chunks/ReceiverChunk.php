<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Chunks;

use App\Infrastructure\ApiClient\ExpertSender\Entities\Receiver;

class ReceiverChunk implements ChunkInterface
{
    const PATTERN = <<<'EOD'
        <Receiver>
            %s
        </Receiver>
EOD;

    protected Receiver $receiver;

    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    public function getText()
    {
        $textStrings = [];

        if ($this->receiver->getId() !== null) {
            $chunk = new SimpleChunk('Id', $this->receiver->getId());
            $textStrings[] = $chunk->getText();
        }

        if ($this->receiver->getEmail() !== null) {
            $chunk = new SimpleChunk('Email', $this->receiver->getEmail());
            $textStrings[] = $chunk->getText();
        }

        return sprintf(self::PATTERN, implode(PHP_EOL, $textStrings));
    }
}
