<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserUpdatedMessageHandler implements MessageHandlerInterface
{
    public function __invoke(UserUpdatedMessage $message)
    {
        // Журналируем изменения, а также дергаем любые другие обработчики связанные с изменениями юзера
    }
}
