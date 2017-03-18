<?php

namespace MicroCMS\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Article;
use MicroCMS\Domain\User;
use MicroCMS\Form\Type\ArticleType;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\UserType;

class AdminController
{

    /**
     * Admin home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app)
    {

        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAllBySignal();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'comments' => $comments));
    }

    /**
     * Add article controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addArticleAction(Request $request, Application $app)
    {
        $article = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'L\'article a été ajouté');
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'New article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Edit article controller.
     *
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editArticleAction($id, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($id);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'L\'article a été mis à jour');
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Editer un article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Delete article controller.
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     */
    public function deleteArticleAction($id, Application $app)
    {
        // Supprimer tous les commentaires associés à l'article
        $app['dao.comment']->deleteAllByArticle($id);
        // Supprimer l'article
        $app['dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'article a été supprimé');
        // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Edit comment controller.
     *
     * @param integer $id Comment id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'L\'comment a été mis à jour');
        }
        return $app['twig']->render('comment_form.html.twig', array(
            'title' => 'Editer un commentaire',
            'commentForm' => $commentForm->createView()));
    }

    /**
     * Delete comment controller.
     *
     * @param integer $id Comment id
     * @param Application $app Silex application
     */
    public function deleteCommentAction($id, Request $request, Application $app)
    {
        $app['dao.comment']->delete($id);
        // Supprimer les commentaires associés (enfants)
        $app['dao.comment']->deleteAllChildrens($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire et ses enfants ont été supprimés.');
        // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));
    }

}