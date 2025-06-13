<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Room;

class QuestController extends Controller
{
    private $roomModel;

    public function __construct()
    {
        $this->roomModel = new Room();
    }

    public function index()
    {
        $filters = [
            'category' => $_GET['category'] ?? '',
            'capacity' => $_GET['capacity'] ?? '',
            'priceRange' => $_GET['priceRange'] ?? '',
            'difficulty' => $_GET['difficulty'] ?? ''
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== '';
        });

        $rooms = $this->roomModel->getAllRooms($filters);
        $filterOptions = $this->roomModel->getFilterOptions();

        $this->view('quest/index', [
            'rooms' => $rooms,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'title' => 'Квести - QuestBooking',
            'currentPage' => 'quest'
        ]);
    }

    public function show($id)
    {
        $room = $this->roomModel->getRoomById($id);

        if (!$room) {
            $this->redirect('/quest');
            return;
        }

        $this->view('quest/show', [
            'room' => $room,
            'title' => $room['title'] . ' - QuestBooking',
            'currentPage' => 'quest'
        ]);
    }

    public function category($category = '')
    {
        if (empty($category)) {
            $this->redirect('/quest');
            return;
        }

        $rooms = $this->roomModel->getRoomsByCategory($category);
        $filterOptions = $this->roomModel->getFilterOptions();

        $categoryNames = [
            'horror' => 'Жахи',
            'adventure' => 'Пригоди',
            'mystery' => 'Детектив',
            'action' => 'Екшн'
        ];

        $this->view('quest/index', [
            'rooms' => $rooms,
            'filters' => ['category' => $category],
            'filterOptions' => $filterOptions,
            'title' => ($categoryNames[$category] ?? 'Квести') . ' - QuestBooking',
            'currentPage' => 'quest',
            'selectedCategory' => $categoryNames[$category] ?? 'Квести'
        ]);
    }
}