<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publication_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client")
     */
    private $corresponding_client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(\DateTimeInterface $date): self
    {
        $this->publication_date= $date;

        return $this;
    }

    public function getCorrespondingClient(): ?Client
    {
        return $this->corresponding_client;
    }

    public function setCorrespondingClient(Client $client): self
    {
        $this->corresponding_client = $client;

        return $this;
    }
}
