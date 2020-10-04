<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $_time_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $_time_log_out;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTimeCreated(): ?\DateTimeInterface
    {
        return $this->_time_created;
    }

    public function setTimeCreated(\DateTimeInterface $_time_created): self
    {
        $this->_time_created = $_time_created;

        return $this;
    }

    public function getTimeLogOut(): ?\DateTimeInterface
    {
        return $this->_time_log_out;
    }

    public function setTimeLogOut(?\DateTimeInterface $_time_log_out): self
    {
        $this->_time_log_out = $_time_log_out;

        return $this;
    }
}
