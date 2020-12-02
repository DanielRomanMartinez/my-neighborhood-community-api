<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

class StateOnList
{
    private string $listId;
    private string $name;
    private string $status;
    private \DateTime $subscribedOn;

    public function __construct(string $listId, string $name, string $status, \DateTime $subscribedOn)
    {
        $this->listId = $listId;
        $this->name = $name;
        $this->status = $status;
        $this->subscribedOn = $subscribedOn;
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function setListId(string $listId): StateOnList
    {
        $this->listId = $listId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): StateOnList
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): StateOnList
    {
        $this->status = $status;

        return $this;
    }

    public function getSubscribedOn(): \DateTime
    {
        return $this->subscribedOn;
    }

    public function setSubscribedOn(\DateTime $subscribedOn): StateOnList
    {
        $this->subscribedOn = $subscribedOn;

        return $this;
    }
}
