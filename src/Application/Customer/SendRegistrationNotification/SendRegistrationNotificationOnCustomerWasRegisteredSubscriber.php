<?php

declare(strict_types=1);

namespace App\Application\Customer\SendRegistrationNotification;

use App\Domain\Customer\Event\CustomerWasCreated;
use App\Domain\Customer\Service\CustomerFinder;
use App\Infrastructure\EventPublisher\EventConsumer;
use App\Infrastructure\Notification\Notification;
use App\Infrastructure\Notification\Notifier\NotifierInterface;
use App\Infrastructure\Template\TemplateInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class SendRegistrationNotificationOnCustomerWasRegisteredSubscriber extends EventConsumer
{
    const TEMPLATE_PATH = 'email/customer_registered.html.twig';
    const EMAIL_SUBJECT = 'Welcome to My Neighborhood Community';
    const INFO_EMAIL = 'roman.martinez.daniel@gmail.com';
    const SITENAME = 'myneighborhoodcommunity.com';

    private TemplateInterface $template;
    //private NotifierInterface $notifier;
    private CustomerFinder $finder;
    private string $imagesPath;

    public function __construct(
        TemplateInterface $template,
        //NotifierInterface $notifier,
        CustomerFinder $finder,
        string $imagesPath
    ) {
        $this->template = $template;
        //$this->notifier = $notifier;
        $this->finder = $finder;
        $this->imagesPath = $imagesPath;
    }

    public function __invoke(CustomerWasCreated $event): void
    {
        $attributes = $event->toArray()['attributes'];
        $customerId = $attributes['customerId'];

        $customer = $this->finder->__invoke(new Uuid($customerId));

        $subject = sprintf(self::EMAIL_SUBJECT);
        $body = $this->template->render(self::TEMPLATE_PATH, [
            'siteName'    => self::SITENAME,
            'userName'    => $customer->email(),
            'imagesPath'  => $this->imagesPath,
        ]);

        /*
        $this->notifier->notify(Notification::create(
            $subject,
            self::INFO_EMAIL,
            $customer->email(),
            [],
            $body
        ));
        */
    }
}
