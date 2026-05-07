<?php
require_once 'config.php';
require_once 'db_helpers.php';

$phone = $_GET['phone'] ?? '';
$isAdmin = !isset($_GET['phone']) || $_GET['phone'] === '';

// Handle delete request
if (isset($_GET['delete']) && $isAdmin) {
    deleteBooking($link, $_GET['delete']);
    header('Location: history.php');
    exit;
}

// If no phone provided, show all bookings (admin view)
// If phone provided, show only that user's bookings
$bookings = fetchBookings($link, $isAdmin ? null : $phone);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - Pedia Care</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Inter, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #fff1eb 0%, #ace0f9 100%);
            padding: 32px;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 1.8rem;
            color: #1f2937;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            background: white;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .booking-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .doctor-avatar {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 50%, #48dbfb 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }

        .booking-info h2 {
            font-size: 1.2rem;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .booking-info p {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .booking-info .location {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .booking-info .patient-info {
            color: #6366f1;
            font-size: 0.85rem;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        .booking-meta {
            text-align: right;
        }

        .booking-id {
            display: inline-block;
            padding: 6px 12px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .booking-date {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #059669;
        }

        .delete-btn {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            background: #ef4444;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .delete-btn:hover {
            background: #dc2626;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
        }

        .empty-state h2 {
            color: #6b7280;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #9ca3af;
        }

        @media (max-width: 600px) {
            .booking-card {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .doctor-avatar {
                margin: 0 auto;
            }

            .booking-meta {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1><?php echo $isAdmin ? '📋 All Bookings (Admin)' : '📋 Your Bookings'; ?></h1>
            <a href="<?php echo $isAdmin ? 'admin.php' : 'index.php'; ?>" class="back-link">← Back to <?php echo $isAdmin ? 'Admin' : 'Home'; ?></a>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <h2>No bookings found</h2>
                <p>You haven't booked any doctors yet.</p>
                <a href="doctors.php" class="back-link" style="margin-top: 20px; display: inline-block;">Browse Doctors</a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <div class="doctor-avatar">
                        <?php if (!empty($booking['doctor_photo'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($booking['doctor_photo']); ?>" alt="Doctor Photo">
                        <?php else: ?>
                            <?php echo htmlspecialchars($booking['doctor_avatar'] ?? 'DR'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="booking-info">
                        <h2><?php echo htmlspecialchars($booking['doctor_name'] ?? 'Doctor'); ?></h2>
                        <p><?php echo htmlspecialchars($booking['doctor_title'] ?? ''); ?></p>
                        <p class="location">📍 <?php echo htmlspecialchars($booking['doctor_location'] ?? ''); ?></p>
                        <?php if ($isAdmin): ?>
                        <p class="patient-info">👤 Patient: <?php echo htmlspecialchars($booking['patient_name'] ?? 'N/A'); ?> | 📞 <?php echo htmlspecialchars($booking['patient_phone'] ?? ''); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="booking-meta">
                        <div class="booking-id">#<?php echo htmlspecialchars($booking['id']); ?></div>
                        <div class="booking-date"><?php echo date('M d, Y h:i A', strtotime($booking['created_at'])); ?></div>
                        <span class="status-badge status-confirmed">Confirmed</span>
                        <?php if ($isAdmin): ?>
                        <a href="?delete=<?php echo $booking['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')" class="delete-btn">🗑️ Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>