<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    private $bookingModel;
    private $roomModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->roomModel = new Room();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function index($roomId = null)
    {
        if (!$roomId) {
            $this->redirect('/quest');
            return;
        }

        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$this->isValidDate($date)) {
            $date = date('Y-m-d');
        }

        if ($date < date('Y-m-d')) {
            $date = date('Y-m-d');
        }

        $room = $this->roomModel->getRoomById($roomId);
        if (!$room) {
            $_SESSION['error'] = 'Кімнату не знайдено';
            $this->redirect('/quest');
            return;
        }

        $duration = $room['duration'] ?? 60;

        $availableSlots = $this->bookingModel->generateTimeSlots($roomId, $date, $duration);

        $this->view('booking/index', [
            'room' => $room,
            'date' => $date,
            'availableSlots' => $availableSlots,
            'duration' => $duration,
            'title' => 'Бронювання - ' . $room['title'],
            'currentPage' => 'booking'
        ]);
    }

    public function store()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Для бронювання необхідно увійти в систему';
            $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? '/quest';
            $this->redirect('/auth/login');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
            $_SESSION['error'] = 'Недійсний токен безпеки';
            $this->redirect('/quest');
            return;
        }

        $roomId = $_POST['room_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $timeSlot = $_POST['time_slot'] ?? null;
        $endTime = $_POST['end_time'] ?? null;

        if (!$roomId || !$date || !$timeSlot || !$endTime) {
            $_SESSION['error'] = 'Всі поля обов\'язкові для заповнення';
            $this->redirect('/quest');
            return;
        }

        if (!$this->isValidDate($date) || $date < date('Y-m-d')) {
            $_SESSION['error'] = 'Невірна дата бронювання';
            $this->redirect("/booking/index/$roomId");
            return;
        }

        if (!$this->isValidBookingTime($date, $timeSlot)) {
            $_SESSION['error'] = $this->getTimeValidationError($date, $timeSlot);
            $this->redirect("/booking/index/$roomId?date=$date");
            return;
        }

        if (!$this->isValidTimeSlot($timeSlot) || !$this->isValidTimeSlot($endTime)) {
            $_SESSION['error'] = 'Невірний часовий слот';
            $this->redirect("/booking/index/$roomId?date=$date");
            return;
        }

        $room = $this->roomModel->getRoomById($roomId);
        if (!$room) {
            $_SESSION['error'] = 'Кімнату не знайдено';
            $this->redirect('/quest');
            return;
        }

        if (!$this->bookingModel->isSlotAvailable($roomId, $date, $timeSlot, $endTime)) {
            $_SESSION['error'] = 'Цей часовий слот вже зайнятий або перетинається з іншим бронюванням';
            $this->redirect("/booking/index/$roomId?date=$date");
            return;
        }

        if ($this->bookingModel->userHasBookingOnDate($userId, $roomId, $date)) {
            $_SESSION['error'] = 'У вас вже є бронювання цієї кімнати на цю дату';
            $this->redirect("/booking/index/$roomId?date=$date");
            return;
        }

        $result = $this->bookingModel->createBooking($userId, $roomId, $date, $timeSlot, $endTime);

        if ($result) {
            $timeFormatted = date('H:i', strtotime($timeSlot)) . ' - ' . date('H:i', strtotime($endTime));
            $dateFormatted = date('d.m.Y', strtotime($date));
            $_SESSION['success'] = "Бронювання успішно створено на $dateFormatted о $timeFormatted";
        } else {
            $_SESSION['error'] = 'Помилка при створенні бронювання';
        }

        $this->redirect("/booking/index/$roomId?date=$date");
    }
    private function isValidBookingTime($date, $timeSlot)
    {
        $currentTime = time();
        $bookingTime = strtotime($date . ' ' . $timeSlot);
        $bufferTime = 2 * 60;

        return $bookingTime > ($currentTime + $bufferTime);
    }

    private function getTimeValidationError($date, $timeSlot)
    {
        $currentTime = time();
        $bookingTime = strtotime($date . ' ' . $timeSlot);
        $bufferTime = 2 * 60;

        if ($bookingTime <= $currentTime) {
            return 'Неможливо забронювати час, який вже минув';
        }

        if ($bookingTime <= ($currentTime + $bufferTime)) {
            return 'Бронювання доступне не пізніше ніж за 2 хвилини до початку';
        }

        return 'Невірний час бронювання';
    }

    public function my()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['redirect_after_login'] = '/booking/my';
            $this->redirect('/auth/login');
            return;
        }

        $bookings = $this->bookingModel->getUserBookings($userId);

        $this->view('booking/my', [
            'bookings' => $bookings,
            'title' => 'Мої бронювання',
            'currentPage' => 'booking'
        ]);
    }

    public function cancel()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Доступ заборонено';
            $this->redirect('/auth/login');
            return;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $csrfToken = $_POST['csrf_token'] ?? '';

        if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
            $_SESSION['error'] = 'Недійсний токен безпеки';
            $this->redirect('/booking/my');
            return;
        }

        if (!$bookingId) {
            $_SESSION['error'] = 'Невірний ID бронювання';
            $this->redirect('/booking/my');
            return;
        }

        $booking = $this->bookingModel->getBookingById($bookingId);
        if (!$booking || $booking['user_id'] != $userId) {
            $_SESSION['error'] = 'Бронювання не знайдено або доступ заборонено';
            $this->redirect('/booking/my');
            return;
        }

        $bookingDateTime = $booking['booking_date'] . ' ' . $booking['time_slot'];
        $bookingTimestamp = strtotime($bookingDateTime);
        $currentTimestamp = time();
        $timeDiff = $bookingTimestamp - $currentTimestamp;

        if ($timeDiff < 7200) {
            $_SESSION['error'] = 'Бронювання можна скасувати не пізніше ніж за 2 години до початку';
            $this->redirect('/booking/my');
            return;
        }

        if ($this->bookingModel->cancelBooking($bookingId)) {
            $_SESSION['success'] = 'Бронювання успішно скасовано';
        } else {
            $_SESSION['error'] = 'Помилка при скасуванні бронювання';
        }

        $this->redirect('/booking/my');
    }

    private function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function isValidTimeSlot($timeSlot)
    {
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeSlot)) {
            return false;
        }

        $hour = (int)substr($timeSlot, 0, 2);
        return $hour >= 9 && $hour < 20;
    }
}