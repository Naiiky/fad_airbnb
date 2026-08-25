<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\MessageRepository::class)]
class Message
{
    use IdTrait;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Conversation $conversation;

    #[ORM\ManyToOne(inversedBy: 'sentMessages')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private User $sender;

    public function __construct(Conversation $conversation, User $sender, string $content)
    {
        $this->initializeId();
        $this->conversation = $conversation;
        $this->sender = $sender;
        $this->content = $content;
        $this->conversation->markLastMessageAt(new \DateTimeImmutable());
    }

    public function markAsRead(\DateTimeImmutable $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }
}
