<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/** 
 * Table posts
 * IDPost
 * IDUser
 * Content
 * Time and Date ~ createdAt
 * VoteCount
 * State
 */

/**
 * AppBundle\Entity\Post
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PostRepository")
 */
class Post implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 40,
     *     max = 400,
     *     minMessage = "Post must be at least {{ limit }} characters long",
     *     maxMessage = "Post cannot be longer than {{ limit }} characters"
     * )
     */
    private $content;

    /**
     * @ORM\Column(name="posted_at", type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\Column(name="vote_count", type="integer")
     */
    private $voteCount = 0;

    /**
     * @ORM\Column(name="state", type="smallint")
     */
    private $state = 0;
    
    /**
    * @ORM\PrePersist
    */
    public function setPostedAtValue()
    {
        $this->postedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'content' => $this->content,
            'date' => date_format($this->postedAt, 'g:ia l jS F Y'),
            'voteCount' => $this->voteCount,
            'state' => $this->state,
            'voted' => true,
        );
    }
}