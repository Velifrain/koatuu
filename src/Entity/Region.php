<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", options={"default"="nextval('region_id_seq'::regclass)"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
