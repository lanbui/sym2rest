<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

/**
 * Class Programmer
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="battle_programmer")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ProgrammerRepository")
 */
class Programmer
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nickname", type="string", length=100, unique=true)
     */
    private $nickname;

    /**
     * @var integer
     * @ORM\Column(name="avatarName", type="integer")
     */
    private $avatarNumber;

    /**
     * @var string
     * @ORM\Column(name="tagLine", type="string", length=255, nullable=true)
     */
    private $tagLine;

    /**
     * @var integer
     * @ORM\Column(name="powerLevel", type="integer")
     */
    private $powerLevel = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    function __construct($nickname = null, $avatarNumber = null)
    {
        $this->nickname = $nickname;
        $this->avatarNumber = $avatarNumber;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Programmer
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $nickname
     * @return Programmer
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAvatarNumber()
    {
        return $this->avatarNumber;
    }

    /**
     * @param integer $avatarNumber
     * @return Programmer
     */
    public function setAvatarNumber($avatarNumber)
    {
        $this->avatarNumber = $avatarNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTagLine()
    {
        return $this->tagLine;
    }

    /**
     * @param string $tagLine
     * @return Programmer
     */
    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;
        return $this;
    }

    /**
     * @return integer
     */
    public function getPowerLevel()
    {
        return $this->powerLevel;
    }

    /**
     * @param integer $powerLevel
     * @return Programmer
     */
    public function setPowerLevel($powerLevel)
    {
        $this->powerLevel = $powerLevel;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}