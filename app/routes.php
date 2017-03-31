<?php

////////////////////////////////HOME///////////////////////////////

// Home page
$app->get('/',"MicroCMS\Controller\HomeController::indexAction")

    ->bind('home');
// Test page
$app->match('/index/{id}',"MicroCMS\Controller\HomeController::articleAction")

    ->bind('index');

// Détails article avec les commentaires
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::articleAction")
    ->bind('article');

// Ajouter un commentaire
$app->match('/article/{id}/comment/add/{parentId}', "MicroCMS\Controller\HomeController::addCommentAction")
    ->value('parentId', false)
    ->bind('comment_add');

// Signalement commentaire
$app->match('/comment/{id}/signal', "MicroCMS\Controller\HomeController::signalAction")
    ->bind('comment_signal');



// Page about
$app->get('/about', "MicroCMS\Controller\HomeController::aboutAction")
    ->bind('about');
////////////////////////////ADMIN///////////////////////////////////

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

$app->match('/admin/comment/{id}/modif', "MicroCMS\Controller\AdminController::modifCommentAction")
    ->bind('admin_comment_modif');

// Editer un commentaire
$app->match('/admin/comment/{id}/edit', "MicroCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Supprimer un commentaire
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');



