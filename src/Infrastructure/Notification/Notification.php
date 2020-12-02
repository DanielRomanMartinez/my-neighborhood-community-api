<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

final class Notification
{
    private string $subject;
    private string $from;
    private string $to;
    private array $replyTo;
    private string $body;
    private string $bodyText;

    public function __construct(
        string $subject,
        string $from,
        string $to,
        array $replyTo,
        string $body,
        ?string $bodyText = null
    ) {
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->replyTo = $replyTo;
        $this->body = $body;
        $this->bodyText = $bodyText;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getBodyText(): ?string
    {
        return $this->bodyText;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getReplyTo(): array
    {
        return $this->replyTo;
    }

    public static function create(
        string $subject,
        string $from,
        string $to,
        array $replyTo,
        string $body
    ): self {
        return new Notification(
            $subject,
            $from,
            $to,
            $replyTo,
            $body,
            strip_tags($body)
        );
    }
}
