<?php

namespace MicroCMS\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Article;
use MicroCMS\Form\Type\ArticleType;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Domain\User;
use MicroCMS\Form\Type\UserType;


class AdminController
{

    // Page d'accueil administration
    public function indexAction(Application $app)
    {

        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAllBySignal();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'users' => $users,
            'comments' => $comments));
    }

    // Ajouter un article
    public function addArticleAction(Request $request, Application $app)
    {
        $article = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid())
            {
                $app['dao.article']->save($article);
                $app['session']->getFlashBag()->add('success', 'L\'article a été ajouté');
                return $app->redirect($app['url_generator']->generate('admin'));
            }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'New article',
            'articleForm' => $articleForm->createView()));
    }

   // Editer un article
    public function editArticleAction($id, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($id);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'L\'article a été mis à jour');
            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('article_form_edit.html.twig', array(
            'title' => 'Editer un article',
            'articleForm' => $articleForm->createView()));
    }

   // Supprimer un article
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

   // Editer un commentaire
    public function editCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire a été mis à jour');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('comment_form_edit.html.twig', array(
            'title' => 'Editer un commentaire',
            'commentForm' => $commentForm->createView()));
    }


    // Supprimer un commentaire
    public function deleteCommentAction($id, Request $request, Application $app)
    {
        $app['dao.comment']->delete($id);
        // Supprimer les commentaires associés (enfants)
        $app['dao.comment']->deleteAllChildrens($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire et ses enfants ont été supprimés.');
        // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));


        // Supprimer les commentaires associés (enfants)
        $app['dao.comment']->deleteAllChildrens($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire et ses enfants ont été supprimés.');
        // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));
    }



    //Modification signalement
    public function modifCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $app['dao.comment']->modifSignal($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire est affiché comme non signalé .');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

     // Editer l'administrateur
    public function editUserAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // find the encoder for the user
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'administrateur a été mis à jour');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Modification administrateur',
            'userForm' => $userForm->createView()));
    }

}