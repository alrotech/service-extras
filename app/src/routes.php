<?php

//$app->get('/', 'Bookshelf\AuthorController:listAuthors')->setName('list-authors');
//$app->map(['GET', 'POST'], '/authors/{author_id:[0-9]+}/edit', 'Bookshelf\AuthorController:editAuthor')->setName('edit-author');
//$app->get('/authors/{author_id:[0-9]+}', 'Bookshelf\AuthorController:listBooks')->setName('author');
//$app->get('/books', 'Bookshelf\BookController:listBooks')->setName('list-books');

$app->get('/verify', 'HomeController:verify')->setName('verify');
$app->get('/home', 'HomeController:index')->setName('home');

$app->get('/repository', 'RepositoryController:index')->setName('repository-list');
$app->get('/repository/{id:[0-9a-z]+}', 'RepositoryController:show')->setName('repository-single');
