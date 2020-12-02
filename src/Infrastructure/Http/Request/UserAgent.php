<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request;

use Jenssegers\Agent\Agent;

final class UserAgent
{
    private ?string $description;
    private ?string $device;
    private ?string $deviceVersion;
    private ?string $browser;
    private ?string $browserVersion;
    private ?string $operatingSystem;
    private ?string $operatingSystemVersion;

    public function __construct(?string $description)
    {
        $agent = new Agent(null, $description);

        $this->description = $description;

        $device = $agent->device($this->description);
        $this->device = !empty($device) ? $device : null;

        $deviceVersion = $agent->version($this->device);
        $this->deviceVersion = !empty($deviceVersion) ? $deviceVersion : null;

        $browser = $agent->browser($this->description);
        $this->browser = !empty($browser) ? $browser : null;

        $browserVersion = $agent->version($this->browser);
        $this->browserVersion = !empty($browserVersion) ? $browserVersion : null;

        $operatingSystem = $agent->platform($this->description);
        $this->operatingSystem = !empty($operatingSystem) ? $operatingSystem : null;

        $operatingSystemVersion = $agent->version($this->operatingSystem);
        $this->operatingSystemVersion = !empty($operatingSystemVersion) ? $operatingSystemVersion : null;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function device(): ?string
    {
        return $this->device;
    }

    public function deviceVersion(): ?string
    {
        return $this->deviceVersion;
    }

    public function browser(): ?string
    {
        return $this->browser;
    }

    public function browserVersion(): ?string
    {
        return $this->browserVersion;
    }

    public function operatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function operatingSystemVersion(): ?string
    {
        return $this->operatingSystemVersion;
    }
}
