<?php
require_once 'config.php';
require_once 'db_helpers.php';

$slug = $_GET['doctor'] ?? '';
$doctor = fetchDoctorBySlug($link, $slug);

if (!$doctor) {
    header('HTTP/1.0 404 Not Found');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Doctor Not Found</title></head><body><h1>Doctor not found</h1><p>The requested profile was not found.</p><p><a href="doctors.php">Back to search</a></p></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #eef2ff;
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
            width: min(100%, 980px);
            display: grid;
            gap: 24px;
        }

        .profile-banner {
            display: grid;
            gap: 18px;
            padding: 24px 28px;
            border-radius: 28px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
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

        .profile-banner__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .profile-badge-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
        }

        .profile-main {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
        }

        .profile-card {
            border-radius: 32px;
            background: #ffffff;
            padding: 32px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
            display: grid;
            gap: 28px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-avatar {
            min-width: 110px;
            min-height: 110px;
            border-radius: 28px;
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
            color: #ffffff;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            font-weight: 800;
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.18);
        }

        .profile-meta {
            display: grid;
            gap: 10px;
        }

        .profile-meta h1 {
            margin: 0;
            font-size: clamp(2.2rem, 3vw, 3rem);
            line-height: 1.05;
        }

        .profile-meta p {
            margin: 0;
            color: #475569;
            font-size: 1rem;
            max-width: 760px;
        }

        .profile-badges {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, max-content));
            gap: 10px;
        }

        .profile-badge {
            padding: 12px 16px;
            border-radius: 16px;
            background: rgba(99, 102, 241, 0.12);
            color: #3730a3;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .profile-body {
            display: grid;
            gap: 24px;
        }

        .section-card {
            padding: 24px;
            border-radius: 28px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            display: grid;
            gap: 18px;
        }

        .section-card h2 {
            margin: 0;
            font-size: 1.1rem;
            color: #111827;
        }

        .section-card p,
        .section-card li {
            margin: 0;
            color: #475569;
            line-height: 1.8;
            font-size: 0.98rem;
        }

        .stat-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .stat-card {
            padding: 20px;
            border-radius: 22px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }

        .stat-card strong {
            display: block;
            margin-bottom: 10px;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .stat-card span {
            color: #475569;
            font-size: 0.95rem;
        }

        .actions-panel {
            display: grid;
            gap: 14px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 18px 34px rgba(37, 99, 235, 0.22);
        }

        .button-secondary {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .side-panel {
            display: grid;
            gap: 18px;
        }

        .side-card {
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.08);
            padding: 24px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            display: grid;
            gap: 16px;
        }

        .side-card h3 {
            margin: 0;
            font-size: 1rem;
            color: #111827;
        }

        .feature-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .feature-bullet {
            min-width: 32px;
            min-height: 32px;
            border-radius: 12px;
            background: #eef2ff;
            color: #1d4ed8;
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .feature-text {
            display: grid;
            gap: 4px;
        }

        .feature-text strong {
            display: block;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .feature-text span {
            color: #475569;
            font-size: 0.95rem;
        }

        @media (max-width: 980px) {
            .profile-main {
                grid-template-columns: 1fr;
            }

            .page-shell {
                gap: 20px;
            }
        }

        @media (max-width: 700px) {
            body {
                padding: 18px;
            }

            .profile-banner,
            .profile-card,
            .side-card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <section class="profile-banner">
            <div class="breadcrumb"><a href="doctors.php">Listado</a>• Profile</div>
            <div class="profile-banner__top">
                <div>
                    <p class="profile-badge-pill">Available now</p>
                    <h1 style="margin: 16px 0 8px; font-size: clamp(2rem, 3vw, 2.7rem); line-height: 1.05;"><?php echo htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p style="margin:0; color:#475569; font-size:1rem; max-width:760px;"><?php echo htmlspecialchars($doctor['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="profile-badges">
                    <span class="profile-badge"><?php echo htmlspecialchars($doctor['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="profile-badge"><?php echo htmlspecialchars($doctor['consultation'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>
        </section>

        <div class="profile-main">
            <article class="profile-card">
                <div class="profile-header">
                    <?php if (!empty($doctor['photo'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="Photo" class="profile-avatar" style="object-fit:cover;">
                    <?php else: ?>
                        <div class="profile-avatar"><?php echo htmlspecialchars($doctor['avatar'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <div class="profile-meta">
                        <p style="margin:0; color:#64748b; font-size:0.95rem;">Pediatric Specialist</p>
                        <p style="margin:0; color:#475569; font-size:0.95rem;"><?php echo htmlspecialchars($doctor['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <div class="profile-body">
                    <div class="section-card">
                        <h2>Consultation overview</h2>
                        <p>Get a child-centered pediatric consultation with a friendly, expert doctor. Review availability, fees, and next steps before booking.</p>
                    </div>

                    <div class="stat-grid">
                        <div class="stat-card">
                            <strong>Next available slot</strong>
                            <span><?php echo htmlspecialchars($doctor['availability'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="stat-card">
                            <strong>Fee estimate</strong>
                            <span><?php echo htmlspecialchars($doctor['fee'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>

                    <div class="section-card">
                        <h2>What to expect</h2>
                        <ul style="padding-left: 20px; margin:0; color:#475569; line-height:1.8; font-size:0.97rem;">
                            <li>Personalized child health assessment</li>
                            <li>Clear care guidance for symptoms and wellness</li>
                            <li>Recommended follow-up if needed</li>
                        </ul>
                    </div>

                    <div class="actions-panel">
                        <a class="button button-primary" href="book.php?doctor=<?php echo urlencode($doctor['slug']); ?>">Book now</a>
                        <a class="button button-secondary" href="doctors.php">Back to listings</a>
                    </div>
                </div>
            </article>

            <aside class="side-panel">
                <div class="side-card">
                    <h3>Doctor details</h3>
                    <div class="feature-item">
                        <div class="feature-bullet">1</div>
                        <div class="feature-text">
                            <strong>Clinic</strong>
                            <span><?php echo htmlspecialchars($doctor['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-bullet">2</div>
                        <div class="feature-text">
                            <strong>Specialty</strong>
                            <span><?php echo htmlspecialchars($doctor['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-bullet">3</div>
                        <div class="feature-text">
                            <strong>Consultation</strong>
                            <span><?php echo htmlspecialchars($doctor['consultation'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="side-card">
                    <h3>Patient support</h3>
                    <div class="feature-item">
                        <div class="feature-bullet">A</div>
                        <div class="feature-text">
                            <strong>Fast response</strong>
                            <span>Get booking confirmation quickly when available.</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-bullet">B</div>
                        <div class="feature-text">
                            <strong>Secure data</strong>
                            <span>Patient details stay private and protected.</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
