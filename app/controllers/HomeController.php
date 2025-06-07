<?php

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', [
            'title' => 'Головна сторінка',
            'welcome' => 'Ласкаво просимо на сайт бронювання квест-кімнат!'
        ]);
    }
}
