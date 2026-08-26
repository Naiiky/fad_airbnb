<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Upload\WebpImageProcessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

class UserAvatarUploadSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly WebpImageProcessor $imageProcessor)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_UPLOAD => 'convertAvatarToWebp',
        ];
    }

    public function convertAvatarToWebp(Event $event): void
    {
        if ('user_avatar' !== $event->getMapping()->getMappingName()) {
            return;
        }

        $user = $event->getObject();
        if (!$user instanceof User || null === $user->getAvatarFile()) {
            return;
        }

        $webpPath = $this->imageProcessor->process($user->getAvatarFile());

        $user->setAvatarFile(new UploadedFile(
            $webpPath,
            'avatar.webp',
            'image/webp',
            null,
            true,
        ));
    }
}
