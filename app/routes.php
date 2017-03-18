
<?php

// Home page
$app->get('/',"MicroCMS\Controller\HomeController::indexAction")

    ->bind('home');

// Détails article avec les commentaires
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::articleAction")
    ->bind('article');

// Ajouter un commentaire
$app->match('/article/{id}/comment/add/{parentId}', "MicroCMS\Controller\HomeController::addCommentAction")
    ->value('parentId', false)
    ->bind('comment_add');

// Signalement comentaire
$app->match('/comment/{id}/signal', "MicroCMS\Controller\HomeController::signalAction")
    ->bind('comment_signal');

// Page de login
$app->get('/login', "MicroCMS\Controller\HomeController::loginAction")
    ->bind('login');

// Page accueil administration
$app->get('/admin', "MicroCMS\Controller\AdminController::indexAction")
    ->bind('admin');

// Formulaire ajout article
$app->match('/admin/article/add', "MicroCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Editer un article
$app->match('/admin/article/{id}/edit', "MicroCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Supprimer un article
$app->get('/admin/article/{id}/delete', "MicroCMS\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');

// Editer un commentaire
$app->match('/admin/comment/{id}/edit', "MicroCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Supprimer un commentaire
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Page about
$app->get('/about', "MicroCMS\Controller\HomeController::aboutAction")
    ->bind('about');

$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {

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

        $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');

    }

    return $app['twig']->render('user_form.html.twig', array(

        'title' => 'Edit user',

        'userForm' => $userForm->createView()));

})->bind('admin_user_edit');





