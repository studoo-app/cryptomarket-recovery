<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Asset::class, mappedBy: 'wallet', orphanRemoval: true)]
    private Collection $assets;

    #[ORM\OneToOne(mappedBy: 'wallet', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $hashId = null;

    public function __construct(string $hashId)
    {
        $this->assets = new ArrayCollection();
        $this->hashId = $hashId;
        //$this->capital = $this->getCapital();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Asset>
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): static
    {
        if (!$this->assets->contains($asset)) {
            $this->assets->add($asset);
            $asset->setWallet($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): static
    {
        if ($this->assets->removeElement($asset)) {
            // set the owning side to null (unless already changed)
            if ($asset->getWallet() === $this) {
                $asset->setWallet(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        // set the owning side of the relation if necessary
        if ($user->getWallet() !== $this) {
            $user->setWallet($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getHashId(): ?string
    {
        return $this->hashId;
    }

    public function setHashId(string $hashId): static
    {
        $this->hashId = $hashId;

        return $this;
    }

    public function getCapital(): float
    {
        $capital = 0;
        foreach ($this->assets as $asset) {
            $capital += $asset->getValorization();
        }
        return $capital;
    }
}
