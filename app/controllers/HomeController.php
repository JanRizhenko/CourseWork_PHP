<?php

namespace App\Controllers;

use App\Models\News;

class HomeController
{
    public function index()
    {
        $title = "Головна сторінка";
        $welcome = "Ласкаво просимо до нашої системи управління квест-кімнатами";

        try {
            $newsModel = new News();
            $news = $newsModel->getLatest(3);
        } catch (Exception $e) {
            $news = [];
            $newsError = $e->getMessage();
        }

        require_once __DIR__ . '/../views/home/index.php';
    }
}