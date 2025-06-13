<?php

namespace App\Models;

use Core\Database;
use PDO;

class Booking
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTotalCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getAllBookingsWithDetails()
    {
        $sql = "SELECT 
            b.id,
            b.booking_date,
            b.time_slot,
            b.created_at,
            r.title as room_name,
            u.email as user_email,
            u.phone as user_phone
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        JOIN users u ON b.user_id = u.id
        ORDER BY b.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentBookings($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT 
            b.*, 
            u.name AS user_name, 
            u.phone AS user_phone,
            r.title AS room_title
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        ORDER BY b.created_at DESC
        LIMIT :limit
    ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingsByRoomAndDate($roomId, $date)
    {
        $sql = "SELECT time_slot, end_time FROM bookings WHERE room_id = :room_id AND booking_date = :booking_date";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':booking_date', $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBooking($userId, $roomId, $date, $timeSlot, $endTime)
    {
        try {
            $sql = "INSERT INTO bookings (user_id, room_id, booking_date, time_slot, end_time) 
                    VALUES (:user_id, :room_id, :booking_date, :time_slot, :end_time)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':booking_date', $date);
            $stmt->bindParam(':time_slot', $timeSlot);
            $stmt->bindParam(':end_time', $endTime);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Booking creation error: " . $e->getMessage());
            return false;
        }
    }

    public function isSlotAvailable($roomId, $date, $startTime, $endTime)
    {
        $sql = "SELECT COUNT(*) FROM bookings 
                WHERE room_id = :room_id 
                AND booking_date = :booking_date 
                AND (
                    (time_slot < :end_time AND end_time > :start_time)
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':booking_date', $date);
        $stmt->bindParam(':start_time', $startTime);
        $stmt->bindParam(':end_time', $endTime);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function userHasBookingOnDate($userId, $roomId, $date)
    {
        $sql = "SELECT COUNT(*) FROM bookings WHERE user_id = :user_id AND room_id = :room_id AND booking_date = :booking_date";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':booking_date', $date);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function getUserBookings($userId)
    {
        $sql = "SELECT 
            b.*, 
            r.title as room_title, 
            r.description as room_description,
            u.phone as user_phone
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        JOIN users u ON b.user_id = u.id
        WHERE b.user_id = :user_id 
        ORDER BY b.booking_date DESC, b.time_slot DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($bookingId)
    {
        $sql = "SELECT 
            b.*, 
            r.title as room_title,
            u.phone as user_phone
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        JOIN users u ON b.user_id = u.id
        WHERE b.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cancelBooking($bookingId)
    {
        try {
            $sql = "DELETE FROM bookings WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Booking cancellation error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllBookings()
    {
        $sql = "SELECT 
            b.*, 
            u.name as user_name, 
            u.email as user_email,
            u.phone as user_phone,
            r.title as room_title 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN rooms r ON b.room_id = r.id 
        ORDER BY b.booking_date DESC, b.time_slot DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingStats()
    {
        $stats = [];

        $sql = "SELECT COUNT(*) as total FROM bookings";
        $stmt = $this->db->query($sql);
        $stats['total'] = $stmt->fetchColumn();

        $sql = "SELECT COUNT(*) as today FROM bookings WHERE booking_date = CURDATE()";
        $stmt = $this->db->query($sql);
        $stats['today'] = $stmt->fetchColumn();

        $sql = "SELECT COUNT(*) as week FROM bookings WHERE booking_date >= CURDATE() - INTERVAL 7 DAY";
        $stmt = $this->db->query($sql);
        $stats['week'] = $stmt->fetchColumn();

        $sql = "SELECT r.title, COUNT(*) as bookings 
                FROM bookings b 
                JOIN rooms r ON b.room_id = r.id 
                GROUP BY b.room_id, r.title 
                ORDER BY bookings DESC 
                LIMIT 1";
        $stmt = $this->db->query($sql);
        $popular = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['popular_room'] = $popular ? $popular['title'] : 'Немає даних';

        return $stats;
    }
    public function generateTimeSlots($roomId, $date, $duration)
    {
        $workStart = '09:00';
        $workEnd = '20:00';
        $bufferMinutes = 2;

        $existingBookings = $this->getBookingsByRoomAndDate($roomId, $date);
        $availableSlots = [];

        $currentSlotTime = strtotime($workStart);
        $endTime = strtotime($workEnd);
        $isToday = ($date === date('Y-m-d'));
        $now = time();

        while ($currentSlotTime < $endTime) {
            $slotStart = date('H:i:s', $currentSlotTime);
            $slotEnd = date('H:i:s', $currentSlotTime + ($duration * 60));

            if (strtotime($slotEnd) > $endTime) {
                break;
            }

            $slotStatus = 'available';

            $slotStartTime = $currentSlotTime;
            $minBookTime = $now + ($bufferMinutes * 60);

            if ($isToday && $slotStartTime <= $minBookTime) {
                $slotStatus = 'past';
            }

            foreach ($existingBookings as $booking) {
                $bookingStart = strtotime($booking['time_slot']);
                $bookingEnd = strtotime($booking['end_time']);

                if (($currentSlotTime < $bookingEnd) && (strtotime($slotEnd) > $bookingStart)) {
                    $slotStatus = 'booked';
                    break;
                }
            }

            $availableSlots[] = [
                'start_time' => $slotStart,
                'end_time' => $slotEnd,
                'formatted_time' => date('H:i', $currentSlotTime) . ' - ' . date('H:i', strtotime($slotEnd)),
                'status' => $slotStatus
            ];

            $currentSlotTime += ($duration * 60);
        }

        return $availableSlots;
    }

    public function checkBookingConflict($roomId, $date, $timeSlot, $excludeBookingId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM bookings 
            WHERE room_id = :room_id 
            AND booking_date = :date 
            AND time_slot = :time_slot";

        $params = [
            ':room_id' => $roomId,
            ':date' => $date,
            ':time_slot' => $timeSlot
        ];

        if ($excludeBookingId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeBookingId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }
    public function getAvailableTimeSlots($roomId, $date, $excludeBookingId = null)
    {
        $allSlots = ['09:00', '11:00', '13:00', '15:00', '17:00', '19:00'];

        $sql = "SELECT time_slot FROM bookings 
            WHERE room_id = :room_id 
            AND booking_date = :date";

        $params = [
            ':room_id' => $roomId,
            ':date' => $date
        ];

        if ($excludeBookingId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeBookingId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_diff($allSlots, $bookedSlots);
    }
    public function getBookingWithDetails($id)
    {
        $sql = "SELECT b.*, u.email as user_email, r.title as room_name, r.price as room_price
            FROM bookings b
            LEFT JOIN users u ON b.user_id = u.id
            LEFT JOIN rooms r ON b.room_id = r.id
            WHERE b.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBooking($id, $data)
    {
        $sql = "UPDATE bookings SET 
            room_id = :room_id,
            booking_date = :booking_date,
            time_slot = :time_slot,
            updated_at = NOW()
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':room_id' => $data['room_id'],
            ':booking_date' => $data['booking_date'],
            ':time_slot' => $data['time_slot']
        ];

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function deleteBooking($id)
    {
        $sql = "DELETE FROM bookings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}