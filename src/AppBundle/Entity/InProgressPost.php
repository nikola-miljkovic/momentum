<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InProgressPost
 *
 * @ORM\Table(name="in_progress_post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InProgressPostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class InProgressPost
{
    /**
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\Column(name="posted_at", type="datetime")
     */
    private $startedWorkAt;

    /**
     * @ORM\PrePersist
     */
    public function setPostedAt()
    {
        $this->startedWorkAt = new \DateTime();
    }

    public function getPostedAt()
    {
        return $this->startedWorkAt;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return InProgressPost
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set post
     *
     * @param integer $post
     * @return InProgressPost
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return integer 
     */
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

    function jsonSerialize()
    {
        return array(
            'government' => $this->getGovernment(),
            'comment' => $this->getComment(),
            'date' => date_format($this->getPostedAt(), 'g:ia l jS F Y'),
        );
    }
}
