<?php

namespace App\Controllers;

use App\Models\News;

class NewsController
{
    public function index()
    {
        $newsModel = new News();
        $newsList = $newsModel->getAll();

        $title = 'Новини';
        require_once __DIR__ . '/../views/news/index.php';
    }

    public function show($id)
    {
        $newsModel = new News();
        $newsItem = $newsModel->getById($id);

        if (!$newsItem) {
            http_response_code(404);
            echo "Новину не знайдено";
            return;
        }

        $title = $newsItem['title'];
        require_once __DIR__ . '/../views/news/show.php';
    }

    public function create()
    {
        $title = 'Додати новину';
        require_once __DIR__ . '/../views/news/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if ($title && $content) {
                $newsModel = new News();
                $newsModel->create($title, $content);
                header('Location: /news');
                exit;
            }
        }

        echo "Помилка створення новини.";
    }

    public function edit($id)
    {
        $newsModel = new News();
        $newsItem = $newsModel->getById($id);

        if (!$newsItem) {
            http_response_code(404);
            echo "Новину не знайдено";
            return;
        }

        $title = 'Редагувати новину';
        require_once __DIR__ . '/../views/news/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if ($title && $content) {
                $newsModel = new News();
                $newsModel->update($id, $title, $content);
                header('Location: /news/show/' . $id);
                exit;
            }
        }

        echo "Помилка оновлення новини.";
    }

    public function delete($id)
    {
        $newsModel = new News();
        $newsModel->delete($id);
        header('Location: /news');
        exit;
    }
}
