<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var \MicroCMS\DAO\ArticleDAO
     */
    private $articleDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

   // Trouve un commentaire associé à un article
    public function find($id)
    {
        $sql = "select * from t_comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

   // Supprime le commentaire de la BDD
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('t_comment', array('com_id' => $id));
    }

    // Trouve tous les commentaires associés à un article
    public function findAllByArticle($articleId) {
        $sql = "select com_id, com_author, com_content, com_date from t_comment where art_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleId));
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    // Trouve tous les commentaires parents associés à un article
    public function findAllParentByArticle($articleId) {
        $sql = "select * from t_comment where art_id=? and parent_id is NULL order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleId));
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comments[$comId] = $comment;
        }
        return $comments;
    }
// Trouve tous les commentaires enfants associés à un commentaire parent
    public function findAllChildren($comment) {
        $sql = "select * from t_comment where parent_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($comment->getId()));
        // Convert query result to an array of domain objects
        $childrenComments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $childrenComment = $this->buildDomainObject($row);

            $childrenComments[$comId] = $childrenComment;
        }
        return $childrenComments;
    }

    // Signalement commentaire
    public function addSignal($comment)
    {
        $comment->setSignal(true);
        $this->save($comment);
    }
    //Modification signalement commentaire
    public function modifSignal($comment)
    {
        $comment->setSignal(null);
        $this->save($comment);
    }
    // Trouve tous les commentaires signalés
    public function findAllBySignal(){
        $sql = "select * from t_comment order by signale desc, com_id desc";
        $result = $this->getDb()->fetchAll($sql, array());
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            // $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

   // Supprime tous les commentaires d'un article
    public function deleteAllByArticle($articleId) {
        $this->getDb()->delete('t_comment', array('art_id' => $articleId));
    }

    // Supprime tous les commentaires enfants associés à un commentaire parent
    public function deleteAllChildrens($parentId) {
        $this->getDb()->delete('t_comment', array('parent_id' => $parentId));
    }

  // Enregistre le commentaire dans la BDD
    public function save(Comment $comment) {
        if ($comment->getParent()){
            $parent = $comment->getParent()->getId();
        } else {
            $parent = null;
        }
        $commentData = array(
            'com_content' => $comment->getContent(),
            'com_author' => $comment->getAuthor(),
            'art_id' => $comment->getArticle()->getId(),
            'parent_id' => $parent,
            'signale' => $comment->getSignal(),
        );
        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        }
        else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }
    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setAuthor($row['com_author']);
        $comment->setContent($row['com_content']);
        $comment->setDate($row['com_date']);
        $comment->setSignal($row['signale']);

        if (array_key_exists('art_id', $row)) {
            // Find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }

        if (array_key_exists('parent_id', $row) && $row['parent_id']) {
            // Find and set the associated article
            $parentId = $row['parent_id'];
            $parent = $this->find($parentId);
            $comment->setParent($parent);
        }
        return $comment;
    }
}