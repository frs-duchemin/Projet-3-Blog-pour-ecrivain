<?php
namespace MicroCMS\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Domain\CommentDAO;
use MicroCMS\Domain\User;


class HomeController {

    // Page d'accueil
    public function indexAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles));
    }

   // Détail article avec commentaires
    public function articleAction($id, Request $request, Application $app) {
        $article = $app['dao.article']->find($id);
        $comments = $app['dao.comment']->findAllParentByArticle($id);
        $childrenComments = [];
        $childrenCommentsLevel2 = [];
        foreach ($comments as $comment) {
            $childrenComments[$comment->getId()]= $app['dao.comment']->findAllChildren($comment);
            foreach ($childrenComments[$comment->getId()] as $children) {
                $childrenCommentsLevel2[$children->getId()]= $app['dao.comment']->findAllChildren($children);
            }
        }
        return $app['twig']->render('article.html.twig', array(
            'article' => $article,
            'comments' => $comments,
            'childrenComments' => $childrenComments,
            'childrenCommentsLevel2' => $childrenCommentsLevel2,
        ));
    }

    // Ajouter un commentaire et commentaires enfants
    public function addCommentAction(  $id, $parentId, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($id);
        $comment = new Comment();
        $comment->setArticle($article);
        if ($parentId) {
            $parent = $app['dao.comment']->find($parentId);
            $comment->setParent($parent);
        }
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire a été créé.');
            return $app->redirect($app['url_generator']->generate('article', ['id'=>$id]));
        }
        return $app['twig']->render('comment_form.html.twig', array(
            'article' => $article,
            'commentForm' => $commentForm->createView()));
    }

    // Signalement commentaire
    public function signalAction(  $id,  Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $app['dao.comment']->addSignal($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a été signalé au modérateur.');
        return $app->redirect($app['url_generator']->generate('article', ['id' => $comment->getArticle()->getId()]));
    }

    // Page de Login
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    // Page à propos
    public function aboutAction(Application $app) {
        return $app['twig']->render('about.html.twig');
    }
}