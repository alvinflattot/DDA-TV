<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec ce mail")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * Mot de passe en clair, non lié à la base de données, nécessaire pour permettre à easybundle de pouvoir hasher les mots de passe
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $activationToken;

    /**
     * @ORM\ManyToMany(targetEntity=Serie::class, inversedBy="followers")
     * @ORM\JoinTable(name="user_serie_favorite")
     */
    private $favoriteSeries;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="followers")
     * @ORM\JoinTable(name="user_movie_favorite")
     */
    private $favoriteMovies;

    /**
     * @ORM\ManyToMany(targetEntity=Episode::class, inversedBy="watchers")
     *  @ORM\JoinTable(name="user_episode_watched")
     */
    private $episodesWatched;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="watchers")
     * @ORM\JoinTable(name="user_movie_watched")
     */
    private $moviesWatched;

    public function __construct()
    {
        $this->favoriteSeries = new ArrayCollection();
        $this->favoriteMovies = new ArrayCollection();
        $this->episodesWatched = new ArrayCollection();
        $this->moviesWatched = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    /**
     * @return Collection|Serie[]
     */
    public function getFavoriteSeries(): Collection
    {
        return $this->favoriteSeries;
    }

    public function addFavoriteSeries(Serie $favoriteSeries): self
    {
        if (!$this->favoriteSeries->contains($favoriteSeries)) {
            $this->favoriteSeries[] = $favoriteSeries;
        }

        return $this;
    }

    public function removeFavoriteSeries(Serie $favoriteSeries): self
    {
        if ($this->favoriteSeries->contains($favoriteSeries)) {
            $this->favoriteSeries->removeElement($favoriteSeries);
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getFavoriteMovies(): Collection
    {
        return $this->favoriteMovies;
    }

    public function addFavoriteMovie(Movie $favoriteMovie): self
    {
        if (!$this->favoriteMovies->contains($favoriteMovie)) {
            $this->favoriteMovies[] = $favoriteMovie;
        }

        return $this;
    }

    public function removeFavoriteMovie(Movie $favoriteMovie): self
    {
        if ($this->favoriteMovies->contains($favoriteMovie)) {
            $this->favoriteMovies->removeElement($favoriteMovie);
        }

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodesWatched(): Collection
    {
        return $this->episodesWatched;
    }

    public function addEpisodesWatched(Episode $episodesWatched): self
    {
        if (!$this->episodesWatched->contains($episodesWatched)) {
            $this->episodesWatched[] = $episodesWatched;
        }

        return $this;
    }

    public function removeEpisodesWatched(Episode $episodesWatched): self
    {
        if ($this->episodesWatched->contains($episodesWatched)) {
            $this->episodesWatched->removeElement($episodesWatched);
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMoviesWatched(): Collection
    {
        return $this->moviesWatched;
    }

    public function addMoviesWatched(Movie $moviesWatched): self
    {
        if (!$this->moviesWatched->contains($moviesWatched)) {
            $this->moviesWatched[] = $moviesWatched;
        }

        return $this;
    }

    public function removeMoviesWatched(Movie $moviesWatched): self
    {
        if ($this->moviesWatched->contains($moviesWatched)) {
            $this->moviesWatched->removeElement($moviesWatched);
        }

        return $this;
    }

    //pour easyAdmin
    public function __toString() {

        // Le return doit renvoyer quelque chose permettant d'identifier facilement l'élément en question
        return $this->id . ' - ' . $this->email;
    }
}
