<?php
namespace MicroCMS\DAO;
use MicroCMS\Domain\Article;
class ArticleDAO extends DAO
{
    // Liste tous les articles
    public function findAll() {
        $sql = "select  * from t_article order by art_id desc ";
        $result = $this->getDb()->fetchAll($sql);
        // Convert query result to an array of domain objects
        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        return $articles;
    }

    // Liste un article en fonction de son ID
    public function find($id) {
        $sql = "select * from t_article where art_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No article matching id " . $id);
    }

    // Enregistre article dans la BDD
    public function save(Article $article) {
        $articleData = array(
            'art_title' => $article->getTitle(),
            'art_content' => $article->getContent(),
        );
        if ($article->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('t_article', $articleData, array('art_id' => $article->getId()));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('t_article', $articleData);
            // Get the id of the newly created article and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);
        }
    }

    // Supprime un article de la BDD
    public function delete($id) {
        // Delete the article
        $this->getDb()->delete('t_article', array('art_id' => $id));
    }

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */
    protected function buildDomainObject(array $row) {
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);
        $article->setDate($row['art_date']);

        return $article;
    }
}