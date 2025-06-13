<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\News;
use App\Models\Booking;

class AdminController extends Controller
{
    private $userModel;
    private $roomModel;
    private $newsModel;
    private $bookingModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel = new User();
        $this->roomModel = new Room();
        $this->newsModel = new News();
        $this->bookingModel = new Booking();
    }

    public function index()
    {
        $stats = [
            'total_users' => $this->userModel->getTotalCount(),
            'total_quests' => $this->roomModel->getTotalCount(),
            'total_news' => $this->newsModel->getTotalCount(),
            'total_bookings' => $this->bookingModel->getTotalCount(),
            'recent_bookings' => $this->bookingModel->getRecentBookings(5)
        ];

        $this->view('admin/index', [
            'stats' => $stats,
            'title' => 'Адміністрування - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function quests()
    {
        $quests = $this->roomModel->getAllRooms();

        $this->view('admin/quests', [
            'quests' => $quests,
            'title' => 'Управління квестами - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function createQuest()
    {
        $this->view('admin/create-quest', [
            'title' => 'Створити новий квест - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function storeQuest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if (empty(trim($_POST['title']))) {
                $errors[] = 'Назва квесту є обов\'язковою';
            }

            if (empty(trim($_POST['description']))) {
                $errors[] = 'Опис квесту є обов\'язковим';
            }

            if (empty($_POST['category'])) {
                $errors[] = 'Категорія є обов\'язковою';
            }

            if (empty($_POST['difficulty'])) {
                $errors[] = 'Складність є обов\'язковою';
            }

            if (empty($_POST['duration']) || $_POST['duration'] < 30 || $_POST['duration'] > 180) {
                $errors[] = 'Тривалість має бути від 30 до 180 хвилин';
            }

            if (empty($_POST['capacity']) || $_POST['capacity'] < 1 || $_POST['capacity'] > 10) {
                $errors[] = 'Місткість має бути від 1 до 10 осіб';
            }

            if (empty($_POST['price']) || $_POST['price'] < 0) {
                $errors[] = 'Ціна має бути більше 0';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $_POST;
                $this->redirect('/admin/create-quest');
                return;
            }

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => $_POST['category'],
                'difficulty' => $_POST['difficulty'],
                'duration' => (int)$_POST['duration'],
                'capacity' => (int)$_POST['capacity'],
                'price' => (float)$_POST['price'],
                'image' => trim($_POST['image']) ?: '/images/placeholder.jpeg'
            ];

            if ($this->roomModel->createRoom($data)) {
                $_SESSION['success'] = 'Квест успішно створено';
                $this->redirect('/admin/quests');
            } else {
                $_SESSION['error'] = 'Помилка при створенні квесту';
                $this->redirect('/admin/create-quest');
            }
        } else {
            $this->redirect('/admin/create-quest');
        }
    }

    public function editQuest($id)
    {
        $quest = $this->roomModel->getRoomById($id);

        if (!$quest) {
            $this->redirect('/admin/quests');
            return;
        }

        $this->view('admin/edit-quest', [
            'quest' => $quest,
            'title' => 'Редагувати квест - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function updateQuest($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => $_POST['category'],
                'difficulty' => $_POST['difficulty'],
                'duration' => (int)$_POST['duration'],
                'capacity' => (int)$_POST['capacity'],
                'price' => (float)$_POST['price'],
                'image' => trim($_POST['image'])
            ];

            if ($this->roomModel->updateRoom($id, $data)) {
                $_SESSION['success'] = 'Квест успішно оновлено';
            } else {
                $_SESSION['error'] = 'Помилка при оновленні квесту';
            }
        }

        $this->redirect('/admin/quests');
    }

    public function deleteQuest($id)
    {
        if ($this->roomModel->deleteRoom($id)) {
            $_SESSION['success'] = 'Квест успішно видалено';
        } else {
            $_SESSION['error'] = 'Помилка при видаленні квесту';
        }

        $this->redirect('/admin/quests');
    }

    public function news()
    {
        $news = $this->newsModel->getAll();

        $this->view('admin/news', [
            'news' => $news,
            'title' => 'Управління новинами - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function createNews()
    {
        $this->view('admin/create-news', [
            'title' => 'Створити новину - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function storeNews()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if (empty(trim($_POST['title']))) {
                $errors[] = 'Заголовок новини є обов\'язковим';
            }

            if (empty(trim($_POST['content']))) {
                $errors[] = 'Контент новини є обов\'язковим';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $_POST;
                $this->redirect('/admin/create-news');
                return;
            }

            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content'])
            ];

            if ($this->newsModel->create($data)) {
                $_SESSION['success'] = 'Новину успішно створено';
                $this->redirect('/admin/news');
            } else {
                $_SESSION['error'] = 'Помилка при створенні новини';
                $this->redirect('/admin/create-news');
            }
        } else {
            $this->redirect('/admin/create-news');
        }
    }

    public function editNews($id)
    {
        $news = $this->newsModel->getById($id);

        if (!$news) {
            $this->redirect('/admin/news');
            return;
        }

        $this->view('admin/edit-news', [
            'news' => $news,
            'title' => 'Редагувати новину - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function updateNews($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if (empty(trim($_POST['title']))) {
                $errors[] = 'Заголовок новини є обов\'язковим';
            }

            if (empty(trim($_POST['content']))) {
                $errors[] = 'Контент новини є обов\'язковим';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $_POST;
                $this->redirect("/admin/edit-news/$id");
                return;
            }

            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content'])
            ];

            if ($this->newsModel->update($id, $data)) {
                $_SESSION['success'] = 'Новину успішно оновлено';
                $this->redirect('/admin/news');
            } else {
                $_SESSION['error'] = 'Помилка при оновленні новини';
                $this->redirect("/admin/edit-news/$id");
            }
        } else {
            $this->redirect('/admin/news');
        }
    }

    public function deleteNews($id)
    {
        if ($this->newsModel->delete($id)) {
            $_SESSION['success'] = 'Новину успішно видалено';
        } else {
            $_SESSION['error'] = 'Помилка при видаленні новини';
        }

        $this->redirect('/admin/news');
    }

    public function users()
    {
        $users = $this->userModel->getAllUsers();

        $this->view('admin/users', [
            'users' => $users,
            'title' => 'Управління користувачами - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function banUser($id)
    {
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Користувача успішно заблоковано';
        } else {
            $_SESSION['error'] = 'Помилка при блокуванні користувача';
        }

        $this->redirect('/admin/users');
    }

    public function bookings()
    {
        $bookings = $this->bookingModel->getAllBookingsWithDetails();

        $this->view('admin/bookings', [
            'bookings' => $bookings,
            'title' => 'Управління бронюваннями - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function editBooking($id)
    {
        $booking = $this->bookingModel->getBookingWithDetails($id);
        $quests = $this->roomModel->getAllRooms();

        if (!$booking) {
            $this->redirect('/admin/bookings');
            return;
        }

        $this->view('admin/edit-booking', [
            'booking' => $booking,
            'quests' => $quests,
            'title' => 'Редагувати бронювання - QuestBooking',
            'currentPage' => 'admin'
        ]);
    }

    public function updateBooking($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'room_id' => (int)$_POST['room_id'],
                'booking_date' => $_POST['booking_date'],
                'time_slot' => $_POST['time_slot']
            ];

            if ($this->bookingModel->updateBooking($id, $data)) {
                $_SESSION['success'] = 'Бронювання успішно оновлено';
            } else {
                $_SESSION['error'] = 'Помилка при оновленні бронювання';
            }
        }

        $this->redirect('/admin/bookings');
    }

    public function deleteBooking($id)
    {
        if ($this->bookingModel->deleteBooking($id)) {
            $_SESSION['success'] = 'Бронювання успішно видалено';
        } else {
            $_SESSION['error'] = 'Помилка при видаленні бронювання';
        }

        $this->redirect('/admin/bookings');
    }
}