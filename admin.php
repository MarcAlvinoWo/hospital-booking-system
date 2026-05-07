<?php

require_once 'config.php';
require_once 'db_helpers.php';
require_once 'db_image_helper.php';

initializeDoctors($link);

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $slug = trim($_POST['slug'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $availability = trim($_POST['availability'] ?? '');
        $fee = trim($_POST['fee'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $consultation = trim($_POST['consultation'] ?? '');
        $avatar = trim($_POST['avatar'] ?? '');
        $online_available = isset($_POST['online_available']) ? 1 : 0;
        $inperson_available = isset($_POST['inperson_available']) ? 1 : 0;
        $experience = trim($_POST['experience'] ?? '');
        
        // Handle file upload
        $photo_filename = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] <= $maxSize) {
                $photo_filename = uniqid('doctor_', true) . '.' . $ext;
                $upload_dir = __DIR__ . '/uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $tmp_path = $_FILES['photo']['tmp_name'];
                $dest_path = $upload_dir . $photo_filename;
                // Resize and crop to 300x300
                if (!resize_and_crop($tmp_path, $dest_path, 300, 300)) {
                    move_uploaded_file($tmp_path, $dest_path); // fallback
                }
            } else {
                $error = 'Invalid photo file. Only JPG, PNG, GIF up to 2MB.';
            }
        }
        
        if ($slug && $name && $title && !$error) {
            $slug = mysqli_real_escape_string($link, $slug);
            $name = mysqli_real_escape_string($link, $name);
            $title = mysqli_real_escape_string($link, $title);
            $location = mysqli_real_escape_string($link, $location);
            $availability = mysqli_real_escape_string($link, $availability);
            $fee = mysqli_real_escape_string($link, $fee);
            $description = mysqli_real_escape_string($link, $description);
            $consultation = mysqli_real_escape_string($link, $consultation);
            $avatar = mysqli_real_escape_string($link, $avatar);
            $experience = mysqli_real_escape_string($link, $experience);
            $photo_sql = $photo_filename ? "'$photo_filename'" : 'NULL';
            
            $sql = "INSERT INTO doctors (slug, name, title, location, availability, fee, description, consultation, avatar, photo, online_available, inperson_available, experience) 
                    VALUES ('$slug', '$name', '$title', '$location', '$availability', '$fee', '$description', '$consultation', '$avatar', $photo_sql, $online_available, $inperson_available, '$experience')";
            
            if (mysqli_query($link, $sql)) {
                $message = 'Doctor added successfully!';
            } else {
                $error = 'Error adding doctor: ' . mysqli_error($link);
            }
        } else {
            $error = 'Please fill in required fields (slug, name, title)';
        }
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $sql = "DELETE FROM doctors WHERE id = $id";
            if (mysqli_query($link, $sql)) {
                $message = 'Doctor deleted successfully!';
            } else {
                $error = 'Error deleting doctor: ' . mysqli_error($link);
            }
        }
    }
    
    // Update photo for existing doctor
    if ($action === 'update_photo') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0 && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $maxSize = 2 * 1024 * 1024;
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] <= $maxSize) {
                // Get existing photo filename to delete later
                $result = mysqli_query($link, "SELECT photo FROM doctors WHERE id = $id");
                $oldRow = mysqli_fetch_assoc($result);
                $oldPhoto = $oldRow['photo'] ?? '';
                
                // Upload new photo
                $photo_filename = uniqid('doctor_', true) . '.' . $ext;
                $upload_dir = __DIR__ . '/uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $tmp_path = $_FILES['photo']['tmp_name'];
                $dest_path = $upload_dir . $photo_filename;
                // Resize and crop to 300x300
                if (!resize_and_crop($tmp_path, $dest_path, 300, 300)) {
                    move_uploaded_file($tmp_path, $dest_path); // fallback
                }
                
                // Update database
                $photo_filename_escaped = mysqli_real_escape_string($link, $photo_filename);
                $sql = "UPDATE doctors SET photo = '$photo_filename_escaped' WHERE id = $id";
                if (mysqli_query($link, $sql)) {
                    // Delete old photo file if exists
                    if ($oldPhoto && file_exists(__DIR__ . '/uploads/' . $oldPhoto)) {
                        unlink(__DIR__ . '/uploads/' . $oldPhoto);
                    }
                    $message = 'Photo updated successfully!';
                } else {
                    $error = 'Error updating photo: ' . mysqli_error($link);
                }
            } else {
                $error = 'Invalid photo file. Only JPG, PNG, GIF up to 2MB.';
            }
        } else {
            $error = 'Please select a photo to upload.';
        }
    }
    
    // Edit existing doctor
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $slug = trim($_POST['slug'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $availability = trim($_POST['availability'] ?? '');
        $fee = trim($_POST['fee'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $consultation = trim($_POST['consultation'] ?? '');
        $avatar = trim($_POST['avatar'] ?? '');
        $online_available = isset($_POST['online_available']) ? 1 : 0;
        $inperson_available = isset($_POST['inperson_available']) ? 1 : 0;
        $experience = trim($_POST['experience'] ?? '');
        
        if ($id > 0 && $slug && $name && $title) {
            $slug_escaped = mysqli_real_escape_string($link, $slug);
            $name_escaped = mysqli_real_escape_string($link, $name);
            $title_escaped = mysqli_real_escape_string($link, $title);
            $location_escaped = mysqli_real_escape_string($link, $location);
            $availability_escaped = mysqli_real_escape_string($link, $availability);
            $fee_escaped = mysqli_real_escape_string($link, $fee);
            $description_escaped = mysqli_real_escape_string($link, $description);
            $consultation_escaped = mysqli_real_escape_string($link, $consultation);
            $avatar_escaped = mysqli_real_escape_string($link, $avatar);
            $experience_escaped = mysqli_real_escape_string($link, $experience);
            
            $sql = "UPDATE doctors SET 
                    slug = '$slug_escaped',
                    name = '$name_escaped',
                    title = '$title_escaped',
                    location = '$location_escaped',
                    availability = '$availability_escaped',
                    fee = '$fee_escaped',
                    description = '$description_escaped',
                    consultation = '$consultation_escaped',
                    avatar = '$avatar_escaped',
                    online_available = $online_available,
                    inperson_available = $inperson_available,
                    experience = '$experience_escaped'
                    WHERE id = $id";
            
            if (mysqli_query($link, $sql)) {
                $message = 'Doctor updated successfully!';
            } else {
                $error = 'Error updating doctor: ' . mysqli_error($link);
            }
        } else {
            $error = 'Please fill in required fields (slug, name, title)';
        }
    }
}

// Fetch all doctors
$result = mysqli_query($link, "SELECT * FROM doctors ORDER BY name");
$doctors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row;
}

// Fetch all bookings for admin
$allBookings = fetchBookings($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Doctor Management</title>
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
            background: url('uploads/bg-login.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #0f172a;
            padding: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .header a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 600;
        }

        .header-links {
            display: flex;
            gap: 16px;
        }

        .header-links a {
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
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

        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card h2 {
            margin: 0 0 20px;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            font-size: 0.95rem;
        }

        .avatar-cell {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            object-fit: cover;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-yes {
            background: #dcfce7;
            color: #166534;
        }

        .badge-no {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        .photo-upload-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .photo-upload-form input[type="file"] {
            width: 130px;
            padding: 6px 8px;
            font-size: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .btn-upload {
            background: #10b981;
            color: #ffffff;
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-upload:hover {
            background: #059669;
        }

        .btn-edit {
            background: #f59e0b;
            color: #ffffff;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal h2 {
            margin: 0 0 20px;
            font-size: 1.4rem;
        }

        .modal-close {
            float: right;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .modal-close:hover {
            color: #0f172a;
        }

        .modal .form-group {
            margin-bottom: 14px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .modal-actions .btn {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍⚕️ Doctor Management</h1>
            <div class="header-links">
                <a href="history.php">📋 View All Bookings</a>
                <a href="doctors.php">← Back to Site</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="grid">
            <!-- Add Doctor Form -->
            <div class="card">
                <h2>➕ Add New Doctor</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="slug">Slug (URL) *</label>
                        <input type="text" id="slug" name="slug" placeholder="e.g., john-doe" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Dr. John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="title">Title/Specialty *</label>
                        <input type="text" id="title" name="title" placeholder="MD - Pediatrics" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Hospital/Clinic</label>
                        <input type="text" id="location" name="location" placeholder="City Hospital">
                    </div>

                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <input type="text" id="availability" name="availability" placeholder="Today, 09:00 AM - 05:00 PM">
                    </div>

                    <div class="form-group">
                        <label for="fee">Consultation Fee</label>
                        <input type="text" id="fee" name="fee" placeholder="₱500.00">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Brief description of the doctor..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="consultation">Consultation Type</label>
                        <input type="text" id="consultation" name="consultation" placeholder="Online Clinic">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Avatar Initials</label>
                        <input type="text" id="avatar" name="avatar" placeholder="JD" maxlength="10">
                    </div>

                    <div class="form-group">
                        <label for="photo">Profile Photo (optional, JPG/PNG, max 2MB)</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience</label>
                        <input type="text" id="experience" name="experience" placeholder="5 yrs experience">
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="online_available" checked>
                                Online Available
                            </label>
                            <label>
                                <input type="checkbox" name="inperson_available">
                                In-Person Available
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Doctor</button>
                </form>
            </div>

            <!-- Doctor List -->
            <div class="card">
                <h2>📋 Existing Doctors (<?php echo count($doctors); ?>)</h2>
                
                <?php if (empty($doctors)): ?>
                    <div class="empty-state">No doctors found. Add one using the form!</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Fee</th>
                                <th>Online</th>
                                <th>In-Person</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($doctors as $doctor): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($doctor['photo'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="Photo" class="avatar-cell" style="object-fit:cover;">
                                        <?php else: ?>
                                            <div class="avatar-cell"><?php echo htmlspecialchars($doctor['avatar']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($doctor['name']); ?></strong><br>
                                        <small style="color: #64748b;"><?php echo htmlspecialchars($doctor['title']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($doctor['location']); ?></td>
                                    <td><?php echo htmlspecialchars($doctor['fee']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $doctor['online_available'] ? 'badge-yes' : 'badge-no'; ?>">
                                            <?php echo $doctor['online_available'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $doctor['inperson_available'] ? 'badge-yes' : 'badge-no'; ?>">
                                            <?php echo $doctor['inperson_available'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" enctype="multipart/form-data" style="display:inline;">
                                            <input type="hidden" name="action" value="update_photo">
                                            <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
                                            <input type="file" name="photo" accept="image/*" style="width:120px; font-size:0.8rem; padding:6px;">
                                            <button type="submit" class="btn btn-upload">Upload</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this doctor?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-edit" onclick="openEditModal(<?php echo $doctor['id']; ?>, '<?php echo htmlspecialchars($doctor['slug']); ?>', '<?php echo htmlspecialchars($doctor['name']); ?>', '<?php echo htmlspecialchars($doctor['title']); ?>', '<?php echo htmlspecialchars($doctor['location']); ?>', '<?php echo htmlspecialchars($doctor['availability']); ?>', '<?php echo htmlspecialchars($doctor['fee']); ?>', '<?php echo htmlspecialchars($doctor['description']); ?>', '<?php echo htmlspecialchars($doctor['consultation']); ?>', '<?php echo htmlspecialchars($doctor['avatar']); ?>', '<?php echo $doctor['online_available']; ?>', '<?php echo $doctor['inperson_available']; ?>', '<?php echo htmlspecialchars($doctor['experience']); ?>')">Edit</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal">
            <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
            <h2>✏️ Edit Doctor</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_slug">Slug (URL) *</label>
                    <input type="text" id="edit_slug" name="slug" required>
                </div>

                <div class="form-group">
                    <label for="edit_name">Full Name *</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="edit_title">Title/Specialty *</label>
                    <input type="text" id="edit_title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="edit_location">Hospital/Clinic</label>
                    <input type="text" id="edit_location" name="location">
                </div>

                <div class="form-group">
                    <label for="edit_availability">Availability</label>
                    <input type="text" id="edit_availability" name="availability">
                </div>

                <div class="form-group">
                    <label for="edit_fee">Consultation Fee</label>
                    <input type="text" id="edit_fee" name="fee">
                </div>

                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_consultation">Consultation Type</label>
                    <input type="text" id="edit_consultation" name="consultation">
                </div>

                <div class="form-group">
                    <label for="edit_avatar">Avatar Initials</label>
                    <input type="text" id="edit_avatar" name="avatar" maxlength="10">
                </div>

                <div class="form-group">
                    <label for="edit_experience">Experience</label>
                    <input type="text" id="edit_experience" name="experience">
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" id="edit_online_available" name="online_available">
                            Online Available
                        </label>
                        <label>
                            <input type="checkbox" id="edit_inperson_available" name="inperson_available">
                            In-Person Available
                        </label>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn" onclick="closeEditModal()" style="background:#64748b; color:#fff;">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, slug, name, title, location, availability, fee, description, consultation, avatar, online, inperson, experience) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_slug').value = slug;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_availability').value = availability;
            document.getElementById('edit_fee').value = fee;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_consultation').value = consultation;
            document.getElementById('edit_avatar').value = avatar;
            document.getElementById('edit_experience').value = experience;
            document.getElementById('edit_online_available').checked = online == '1';
            document.getElementById('edit_inperson_available').checked = inperson == '1';
            
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>