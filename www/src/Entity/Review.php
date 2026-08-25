<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\ReviewRepository::class)]
class Review
{
    use IdTrait;

    #[ORM\Column]
    private int $rating;

    #[ORM\Column(type: 'text')]
    private string $comment;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $hostReply = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $hostReplyDate = null;

    #[ORM\Column]
    private bool $isDisplay = true;

    #[ORM\OneToOne(inversedBy: 'review', targetEntity: Booking::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Booking $booking;

    public function __construct(Booking $booking, int $rating, string $comment)
    {
        $this->initializeId();
        $this->booking = $booking;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function reply(string $hostReply, \DateTimeImmutable $hostReplyDate): self
    {
        $this->hostReply = $hostReply;
        $this->hostReplyDate = $hostReplyDate;

        return $this;
    }
}
