<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

class EsList
{
    private int $id;
    private string $name;
    private string $friendlyName;
    private string $language;
    private string $optInMod;

    public function __construct(int $id, string $name, string $friendlyName, string $language, string $optInMod)
    {
        $this
            ->setId($id)
            ->setName($name)
            ->setFriendlyName($friendlyName)
            ->setLanguage($language)
            ->setOptInMod($optInMod);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): EsList
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): EsList
    {
        $this->name = $name;

        return $this;
    }

    public function getFriendlyName(): string
    {
        return $this->friendlyName;
    }

    public function setFriendlyName(string $friendlyName): EsList
    {
        $this->friendlyName = $friendlyName;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): EsList
    {
        $this->language = $language;

        return $this;
    }

    public function getOptInMod(): string
    {
        return $this->optInMod;
    }

    public function setOptInMod(string $optInMod): EsList
    {
        $this->optInMod = $optInMod;

        return $this;
    }
}
