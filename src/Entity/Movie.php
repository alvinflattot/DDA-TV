<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    //pour easyAdmin
    public function __toString() {

        // Le return doit renvoyer quelque chose permettant d'identifier facilement l'élément en question
        return $this->id . ' - ' . $this->title;
    }
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $poster;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favoriteMovies")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="moviesWatched")
     */
    private $watchers;

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->watchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(User $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->addFavoriteMovie($this);
        }

        return $this;
    }

    public function removeFollower(User $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            $follower->removeFavoriteMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getWatchers(): Collection
    {
        return $this->watchers;
    }

    public function addWatcher(User $watcher): self
    {
        if (!$this->watchers->contains($watcher)) {
            $this->watchers[] = $watcher;
            $watcher->addMoviesWatched($this);
        }

        return $this;
    }

    public function removeWatcher(User $watcher): self
    {
        if ($this->watchers->contains($watcher)) {
            $this->watchers->removeElement($watcher);
            $watcher->removeMoviesWatched($this);
        }

        return $this;
    }
}
