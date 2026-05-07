<?php
require_once 'config.php';
require_once 'db_helpers.php';

$slug = $_GET['doctor'] ?? '';
$doctor = fetchDoctorBySlug($link, $slug);

if (!$doctor) {
    header('HTTP/1.0 404 Not Found');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Doctor Not Found</title></head><body><h1>Doctor not found</h1><p>The requested profile was not found.</p><p><a href="doctors.php">Back to doctors</a></p></body></html>';
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = trim($_POST['patient_name'] ?? '');
    $patient_email = trim($_POST['patient_email'] ?? '');
    $patient_phone = trim($_POST['patient_phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    if ($patient_name && $patient_email && $patient_phone && $payment_method) {
        $patient_name = mysqli_real_escape_string($link, $patient_name);
        $patient_email = mysqli_real_escape_string($link, $patient_email);
        $patient_phone = mysqli_real_escape_string($link, $patient_phone);
        $payment_method = mysqli_real_escape_string($link, $payment_method);
        $notes = mysqli_real_escape_string($link, $notes);
        
        $booking_id = createBooking($link, $doctor['id'], $slug, $patient_name, $patient_email, $patient_phone, $payment_method, $notes);
        
        if ($booking_id) {
            $message = 'Booking confirmed! Your booking ID is: ' . $booking_id;
        } else {
            $error = 'Error creating booking. Please try again.';
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - <?php echo htmlspecialchars($doctor['name']); ?></title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(124, 58, 237, 0.16), transparent 28%),
                        radial-gradient(circle at bottom right, rgba(37, 99, 235, 0.14), transparent 34%),
                        #eef2ff;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        .page-shell {
            width: min(100%, 680px);
            display: grid;
            gap: 24px;
        }

        .booking-card {
            border-radius: 28px;
            background: #ffffff;
            padding: 32px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
            display: grid;
            gap: 24px;
        }

        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: 0.95rem;
        }

        .breadcrumb a {
            color: #2563eb;
            text-decoration: none;
        }

        .doctor-summary {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border-radius: 16px;
            background: #f8fafc;
        }

        .doctor-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .doctor-info h2 {
            margin: 0;
            font-size: 1.1rem;
        }

        .doctor-info p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .form-group {
            display: grid;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
        }

        .form-group label span {
            color: #ef4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .fee-display {
            padding: 16px 20px;
            border-radius: 12px;
            background: #eff6ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fee-display span {
            color: #475569;
            font-size: 0.95rem;
        }

        .fee-display strong {
            font-size: 1.2rem;
            color: #1d4ed8;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 18px 34px rgba(37, 99, 235, 0.22);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid rgba(15, 23, 42, 0.12);
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .actions {
            display: grid;
            gap: 12px;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            .booking-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="breadcrumb">
            <a href="doctors.php">Doctors</a> › <a href="profile.php?doctor=<?php echo urlencode($slug); ?>"><?php echo htmlspecialchars($doctor['name']); ?></a> › Book
        </div>

        <div class="booking-card">
            <div class="doctor-summary">
                <?php if (!empty($doctor['photo'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="Photo" class="doctor-avatar" style="object-fit:cover;">
                <?php else: ?>
                    <div class="doctor-avatar"><?php echo htmlspecialchars($doctor['avatar']); ?></div>
                <?php endif; ?>
                <div class="doctor-info">
                    <h2><?php echo htmlspecialchars($doctor['name']); ?></h2>
                    <p><?php echo htmlspecialchars($doctor['title']); ?></p>
                    <p><?php echo htmlspecialchars($doctor['location']); ?></p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <a href="doctors.php" class="btn btn-secondary" style="text-align:center;">Back to Doctors</a>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="fee-display">
                    <span>Consultation Fee</span>
                    <strong><?php echo htmlspecialchars($doctor['fee']); ?></strong>
                </div>

                <form method="POST" onsubmit="return validatePhone(this);">
                    <div class="form-group">
                        <label for="patient_name">Patient Name <span>*</span></label>
                        <input type="text" id="patient_name" name="patient_name" required placeholder="Enter your full name">
                    </div>

                    <div class="form-group">
                        <label for="patient_email">Email Address <span>*</span></label>
                        <input type="email" id="patient_email" name="patient_email" required placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label for="patient_phone">Phone Number <span>*</span></label>
                        <input type="tel" id="patient_phone" name="patient_phone" required placeholder="09xxxxxxxxx" maxlength="11" pattern="[0-9]{11}" title="Please enter exactly 11 digits">
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method <span>*</span></label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">Select payment method</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash on Visit</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes (optional)</label>
                        <textarea id="notes" name="notes" placeholder="Describe your symptoms or reason for visit..."></textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        <a href="profile.php?doctor=<?php echo urlencode($slug); ?>" class="btn btn-secondary" style="text-align:center;">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function validatePhone(form) {
            var phone = form.patient_phone.value.replace(/\D/g, '');
            if (phone.length !== 11) {
                alert('Please enter an 11-digit phone number.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>