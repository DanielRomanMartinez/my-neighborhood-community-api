<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Results;

use App\Infrastructure\ApiClient\ExpertSender\Entities\StateOnList;
use App\Infrastructure\ApiClient\ExpertSender\ExpertSenderEnum;

class GetShortSubscribersResult extends ApiResult
{
    /**
     * @var StateOnList[]
     */
    protected $stateOnLists = [];

    protected bool $blackList = false;

    public function buildData()
    {
        parent::buildData();

        if ($this->isOk()) {
            try {
                $content = $this->response->getBody()->__toString();
                $xml = new \SimpleXMLElement($content);
                $xmlStateOnLists = $xml->xpath('/ApiResponse/Data/StateOnLists/StateOnList');
                $this->blackList = (string) $xml->xpath('/ApiResponse/Data/BlackList')[0] === 'true';
                foreach ($xmlStateOnLists as $xmlStateOnList) {
                    $this->stateOnLists[] = new StateOnList(
                        (int) $xmlStateOnList->xpath('ListId')[0],
                        (string) $xmlStateOnList->xpath('Name')[0],
                        (string) $xmlStateOnList->xpath('Status')[0],
                        \DateTime::createFromFormat(
                            ExpertSenderEnum::DATE_TIME_FORMAT,
                            (string) $xmlStateOnList->xpath('SubscribedOn')[0],
                            new \DateTimeZone(ExpertSenderEnum::DATE_TIME_TIMEZONE)
                        )
                    );
                }
            } catch (\RuntimeException $exception) {
                $this->errorCode = 500;
                $this->errorMessage = $exception->getMessage();
            }
        }
    }

    /**
     * @return StateOnList[]
     */
    public function getStateOnLists()
    {
        return $this->stateOnLists;
    }

    /**
     * @return bool
     */
    public function isBlackList()
    {
        return $this->blackList;
    }
}
