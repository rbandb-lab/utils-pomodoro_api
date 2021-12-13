<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Pomodoro\Domain\Worker\Model\CycleParameters;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
final class OrmWorker implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="integer")
     */
    private int $pomodoroDuration;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shortBreakDuration;

    /**
     * @ORM\Column(type="integer")
     */
    private int $longBreakDuration;

    /**
     * @ORM\Column(type="integer")
     */
    private int $startFirstTaskIn;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $emailValidated;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\Token",
     *     mappedBy="worker",
     *     cascade={"all"}
     * )
     */
    private Collection $tokens;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Symfony5\Persistence\ORM\Doctrine\Entity\ActivityInventory",
     *     inversedBy="worker",
     *     cascade={"all"}
     * )
     */
    private ActivityInventory $activityInventory;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    public function __construct(string $id, string $username, string $firstName)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->tokens = new ArrayCollection();
        $this->emailValidated = false;
        $this->roles[] = "ROLE_USER";
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isEmailValidated(): bool
    {
        return $this->emailValidated;
    }

    /**
     * @param bool $emailValidated
     */
    public function setEmailValidated(bool $emailValidated): void
    {
        $this->emailValidated = $emailValidated;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getTokens(): ArrayCollection|Collection
    {
        return $this->tokens;
    }

    /**
     * @param ArrayCollection|Collection $tokens
     */
    public function setTokens(ArrayCollection|Collection $tokens): void
    {
        $this->tokens = $tokens;
    }

    public function getCycleParameters(): CycleParameters
    {
        return new CycleParameters(
            $this->pomodoroDuration,
            $this->shortBreakDuration,
            $this->longBreakDuration,
            $this->startFirstTaskIn,
        );
    }

    public function addToken(Token $token)
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
        }
    }

    public function setActivityInventory(ActivityInventory $activityInventory)
    {
        $this->activityInventory = $activityInventory;
    }

    public function setPomodoroDuration(int $pomodoroDuration): void
    {
        $this->pomodoroDuration = $pomodoroDuration;
    }

    public function setShortBreakDuration(int $shortBreakDuration): void
    {
        $this->shortBreakDuration = $shortBreakDuration;
    }

    public function setLongBreakDuration(int $longBreakDuration): void
    {
        $this->longBreakDuration = $longBreakDuration;
    }

    public function setStartFirstTaskIn(int $startFirsTaskIn): void
    {
        $this->startFirstTaskIn = $startFirsTaskIn;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getLongBreakDuration(): int
    {
        return $this->longBreakDuration;
    }

    public function getPomodoroDuration(): int
    {
        return $this->pomodoroDuration;
    }

    public function getShortBreakDuration(): int
    {
        return $this->shortBreakDuration;
    }

    public function getStartFirstTaskIn(): int
    {
        return $this->startFirstTaskIn;
    }

    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function setParameters(CycleParameters $cycleParameters)
    {
        $this->setPomodoroDuration($cycleParameters->getPomodoroDuration());
        $this->setShortBreakDuration($cycleParameters->getShortBreakDuration());
        $this->setLongBreakDuration($cycleParameters->getLongBreakDuration());
        $this->setStartFirstTaskIn($cycleParameters->getStartFirstTaskIn());
    }
}
