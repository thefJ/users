<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;



use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object $event)
    {
        $this->messageBus->dispatch($event);
    }
}
