<?php
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Domain\Article;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\ArticleType;


// Page d'accueil
    $app->get('/', function () use ($app) {
        $articles = $app['dao.article']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles));
    })->bind('home');

// Page d'accueil
$app->get('/about', function () use ($app) {
       return $app['twig']->render('about.html.twig');
})->bind('about');

// Détails article avec les commentaires
    $app->match('/article/{id}', function ($id, Request $request) use ($app) {
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
    })->bind('article');

// Page de login
    $app->get('/login', function(Request $request) use ($app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    })->bind('login');

// Page accueil administration
    $app->get('/admin', function() use ($app) {
        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAllBySignal();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'comments' => $comments));
    })->bind('admin');

// Formulaire ajout article
    $app->match('/admin/article/add', function(Request $request) use ($app) {
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
    })->bind('admin_article_add');

// Editer un article
    $app->match('/admin/article/{id}/edit', function($id, Request $request) use ($app) {
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
    })->bind('admin_article_edit');

// Supprimer un article
    $app->get('/admin/article/{id}/delete', function($id, Request $request) use ($app) {
    // Supprimer tous les commentaires associés à l'article
        $app['dao.comment']->deleteAllByArticle($id);
    // Supprimer l'article
        $app['dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'article a été supprimé');
    // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));
    })->bind('admin_article_delete');

// Ajouter un commentaire
    $app->match('/article/{id}/comment/add/{parentId}', function($id, $parentId, Request $request) use ($app) {
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
            'commentForm' => $commentForm->createView()));
        })
        ->value('parentId', false)
        ->bind('comment_add');
        $app->match('/comment/{id}/signal', function($id) use ($app) {
            $comment = $app['dao.comment']->find($id);
            $app['dao.comment']->addSignal($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire a été signalé au modérateur.');
            return $app->redirect($app['url_generator']->generate('article', ['id' => $comment->getArticle()->getId()]));
        })
    ->bind('comment_signal');

// Supprimer un commentaire
    $app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
        $app['dao.comment']->delete($id);
    // Supprimer les commentaires associés (enfants)
        $app['dao.comment']->deleteAllChildrens($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire et ses enfants ont été supprimés.');
    // Redirection page d'accueil administration
        return $app->redirect($app['url_generator']->generate('admin'));
    })->bind('admin_comment_delete');



