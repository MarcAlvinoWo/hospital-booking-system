<?php
function initializeDoctors($link)
{
    $createTableSql = "CREATE TABLE IF NOT EXISTS doctors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(100) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        location VARCHAR(255) NOT NULL,
        availability VARCHAR(255) NOT NULL,
        fee VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        consultation VARCHAR(255) NOT NULL,
        avatar VARCHAR(10) NOT NULL,
        photo VARCHAR(255) DEFAULT NULL,
        online_available TINYINT(1) NOT NULL DEFAULT 1,
        inperson_available TINYINT(1) NOT NULL DEFAULT 0,
        experience VARCHAR(100) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (!mysqli_query($link, $createTableSql)) {
        die('ERROR: Could not create doctors table. ' . mysqli_error($link));
    }

    $result = mysqli_query($link, "SELECT COUNT(*) AS count FROM doctors");
    if (!$result) {
        die('ERROR: Could not query doctors table. ' . mysqli_error($link));
    }

    $row = mysqli_fetch_assoc($result);
    if (!$row || intval($row['count']) === 0) {
        seedDoctors($link);
    }
}

function seedDoctors($link)
{
    $doctors = [
        [
            'slug' => 'karina-velilla',
            'name' => 'Dr. Karina Velilla',
            'title' => 'MD, DPPS - Pediatrics',
            'location' => 'Esther Hospital',
            'availability' => 'Today, 08:00 AM - 07:00 PM',
            'fee' => '₱400.00',
            'description' => 'Specializes in pediatric wellness checks, acute child care, and vaccination guidance.',
            'consultation' => 'Online Clinic',
            'avatar' => 'KS',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
        [
            'slug' => 'melissa-tisado',
            'name' => 'Dr. Melissa Tisado',
            'title' => 'MD - Family Medicine, Adult Diseases, Family and Community Health',
            'location' => 'Seventhday Adventist Hospital',
            'availability' => 'Today, 12:00 AM - 10:00 PM',
            'fee' => '₱500.00',
            'description' => 'Experienced in family care and pediatric-adult health coordination for every stage of childhood.',
            'consultation' => 'Online Clinic',
            'avatar' => 'MT',
            'online_available' => 1,
            'inperson_available' => 0,
            'experience' => NULL,
        ],
        [
            'slug' => 'reulyssa-peralta',
            'name' => 'Dr. Reulyssa Peralta',
            'title' => 'MD - Pediatrics',
            'location' => 'Blanco Hospital',
            'availability' => 'Today, 05:00 PM - 11:30 PM',
            'fee' => '₱500.00',
            'description' => 'Focuses on pediatric respiratory and developmental care with compassionate, child-friendly support.',
            'consultation' => 'Online Clinic',
            'avatar' => 'RP',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
        [
            'slug' => 'abegail-chomapoy',
            'name' => 'Dr. Abegail Chomapoy',
            'title' => 'MD, DPPS - Pediatrics',
            'location' => 'Medidas Hospital',
            'availability' => 'Today, 08:00 AM - 04:00 PM',
            'fee' => '₱400.00',
            'description' => 'Care for infants and children with a background in pediatric acute care and family guidance.',
            'consultation' => 'Online Clinic',
            'avatar' => 'AC',
            'online_available' => 1,
            'inperson_available' => 0,
            'experience' => '7 yrs experience',
        ],
        [
            'slug' => 'tricia-ojon',
            'name' => 'Dr. Tricia Ojon',
            'title' => 'MD, DPPS - Pediatrics',
            'location' => 'Esther Hospital',
            'availability' => 'Today, 09:30 AM - 03:00 PM',
            'fee' => '₱450.00',
            'description' => 'Provides pediatric evaluation and in-person care with deep experience in childhood health.',
            'consultation' => 'Balbido\'s Clinical Laboratory',
            'avatar' => 'TO',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
        [
            'slug' => 'liza-fernandez',
            'name' => 'Dr. Liza Fernandez',
            'title' => 'MD - Pediatrics, Allergies and Asthma',
            'location' => 'Seventhday Adventist Hospital',
            'availability' => 'Today, 10:00 AM - 06:00 PM',
            'fee' => '₱450.00',
            'description' => 'Expert in pediatric allergies, asthma management, and long-term child wellness.',
            'consultation' => 'Online Clinic',
            'avatar' => 'LF',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
        [
            'slug' => 'janine-ramos',
            'name' => 'Dr. Janine Ramos',
            'title' => 'MD - Pediatrics, Infectious Diseases',
            'location' => 'Blanco Hospital',
            'availability' => 'Today, 02:00 PM - 09:00 PM',
            'fee' => '₱520.00',
            'description' => 'Experienced in infectious disease treatment and pediatric recovery plans.',
            'consultation' => 'Online Clinic',
            'avatar' => 'JR',
            'online_available' => 1,
            'inperson_available' => 0,
            'experience' => NULL,
        ],
        [
            'slug' => 'mae-cabrera',
            'name' => 'Dr. Mae Cabrera',
            'title' => 'MD - Pediatric Nutrition and Growth',
            'location' => 'Medidas Hospital',
            'availability' => 'Today, 11:00 AM - 05:00 PM',
            'fee' => '₱470.00',
            'description' => 'Specializes in growth monitoring, nutrition counseling, and child development plans.',
            'consultation' => 'Online Clinic',
            'avatar' => 'MC',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
        [
            'slug' => 'eliza-kim',
            'name' => 'Dr. Eliza Kim',
            'title' => 'MD - Pediatrics, Child Development',
            'location' => 'Esther Hospital',
            'availability' => 'Today, 03:00 PM - 08:00 PM',
            'fee' => '₱480.00',
            'description' => 'Focused on child development assessments and family-centered pediatric advice.',
            'consultation' => 'Online Clinic',
            'avatar' => 'EK',
            'online_available' => 1,
            'inperson_available' => 1,
            'experience' => NULL,
        ],
    ];

    foreach ($doctors as $doctor) {
        $slug = mysqli_real_escape_string($link, $doctor['slug']);
        $name = mysqli_real_escape_string($link, $doctor['name']);
        $title = mysqli_real_escape_string($link, $doctor['title']);
        $location = mysqli_real_escape_string($link, $doctor['location']);
        $availability = mysqli_real_escape_string($link, $doctor['availability']);
        $fee = mysqli_real_escape_string($link, $doctor['fee']);
        $description = mysqli_real_escape_string($link, $doctor['description']);
        $consultation = mysqli_real_escape_string($link, $doctor['consultation']);
        $avatar = mysqli_real_escape_string($link, $doctor['avatar']);
        $online = intval($doctor['online_available']);
        $inperson = intval($doctor['inperson_available']);
        $experience = mysqli_real_escape_string($link, $doctor['experience'] ?? '');

        $insertSql = "INSERT INTO doctors (slug, name, title, location, availability, fee, description, consultation, avatar, online_available, inperson_available, experience) VALUES ('$slug', '$name', '$title', '$location', '$availability', '$fee', '$description', '$consultation', '$avatar', $online, $inperson, '" . $experience . "')";
        mysqli_query($link, $insertSql);
    }
}

function fetchDoctors($link)
{
    initializeDoctors($link);
    $sql = "SELECT * FROM doctors ORDER BY name";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        die('ERROR: Could not load doctors. ' . mysqli_error($link));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function fetchDoctorBySlug($link, $slug)
{
    initializeDoctors($link);
    $slug = mysqli_real_escape_string($link, $slug);
    $sql = "SELECT * FROM doctors WHERE slug = '$slug' LIMIT 1";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        die('ERROR: Could not load doctor profile. ' . mysqli_error($link));
    }
    return mysqli_fetch_assoc($result);
}

function initializeBookings($link)
{
    $createTableSql = "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        doctor_slug VARCHAR(100) NOT NULL,
        patient_name VARCHAR(255) NOT NULL,
        patient_email VARCHAR(255) NOT NULL,
        patient_phone VARCHAR(50) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (!mysqli_query($link, $createTableSql)) {
        die('ERROR: Could not create bookings table. ' . mysqli_error($link));
    }
}

function createBooking($link, $doctorId, $doctorSlug, $patientName, $patientEmail, $patientPhone, $paymentMethod, $notes)
{
    initializeDoctors($link);
    initializeBookings($link);

    $doctorId = intval($doctorId);
    $doctorSlug = mysqli_real_escape_string($link, $doctorSlug);
    $patientName = mysqli_real_escape_string($link, $patientName);
    $patientEmail = mysqli_real_escape_string($link, $patientEmail);
    $patientPhone = mysqli_real_escape_string($link, $patientPhone);
    $paymentMethod = mysqli_real_escape_string($link, $paymentMethod);
    $notes = mysqli_real_escape_string($link, $notes);

    $sql = "INSERT INTO bookings (doctor_id, doctor_slug, patient_name, patient_email, patient_phone, payment_method, notes) VALUES ($doctorId, '$doctorSlug', '$patientName', '$patientEmail', '$patientPhone', '$paymentMethod', '$notes')";
    if (!mysqli_query($link, $sql)) {
        die('ERROR: Could not save booking. ' . mysqli_error($link));
    }

    return mysqli_insert_id($link);
}

function fetchBookings($link, $phone = null)
{
    initializeBookings($link);
    
    if ($phone) {
        $phone = mysqli_real_escape_string($link, $phone);
        $sql = "SELECT b.*, d.name AS doctor_name, d.title AS doctor_title, d.location AS doctor_location, d.photo AS doctor_photo, d.avatar AS doctor_avatar 
                FROM bookings b 
                LEFT JOIN doctors d ON b.doctor_slug = d.slug 
                WHERE b.patient_phone = '$phone' 
                ORDER BY b.created_at DESC";
    } else {
        $sql = "SELECT b.*, d.name AS doctor_name, d.title AS doctor_title, d.location AS doctor_location, d.photo AS doctor_photo, d.avatar AS doctor_avatar 
                FROM bookings b 
                LEFT JOIN doctors d ON b.doctor_slug = d.slug 
                ORDER BY b.created_at DESC";
    }
    
    $result = mysqli_query($link, $sql);
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    return $bookings;
}

function deleteBooking($link, $bookingId)
{
    initializeBookings($link);
    
    $bookingId = intval($bookingId);
    $sql = "DELETE FROM bookings WHERE id = $bookingId";
    if (!mysqli_query($link, $sql)) {
        die('ERROR: Could not delete booking. ' . mysqli_error($link));
    }
    return true;
}
