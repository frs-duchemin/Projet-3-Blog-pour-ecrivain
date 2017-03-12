<?php
namespace MicroCMS\Domain;
class Comment
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment content.
     *
     * @var integer
     */
    private $content;

    /**
     * Comment author.
     *
     * @var string
     */
    private $author;

    /**
     * Comment date.
     *
     * @var datetime
     */
    private $date;


    /**
     * Associated article.
     *
     * @var \MicroCMS\Domain\Article
     */
    private $article;

    /**
 * Associated parent.
 *
 * @var \MicroCMS\Domain\Comment
 */
    private $parent;

    /**
     *
     * @var boolean
     */
    private $signal;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    public function getDate() {
        return $this->date;
    }
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }


    public function getArticle() {
        return $this->article;
    }
    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }
    public function getParent() {
        return $this->parent;
    }
    public function setParent(Comment $parent) {
        $this->parent = $parent;
        return $this;
    }

    public function getSignal() {
        return $this->signal;
    }
    public function setSignal($signal) {
        $this->signal = $signal;
        return $this;
    }
}