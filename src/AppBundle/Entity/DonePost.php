<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DonePost
 *
 * @ORM\Table(name="done_post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DonePostRepository")
 */
class DonePost
{
    /**
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="government_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $government;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $user;

    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set government
     *
     * @param integer $government
     * @return InProgressPost
     */
    public function setGovernment($government)
    {
        $this->government = $government;

        return $this;
    }

    /**
     * Get government
     *
     * @return integer
     */
    public function getGovernment()
    {
        return $this->government;
    }

    /**
     * Set user
     *
     * @param integer $user
     * @return InProgressPost
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}
