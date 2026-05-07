<?php
require_once 'config.php';
require_once 'db_helpers.php';

$doctors = fetchDoctors($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Booking</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
            background: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #fff1eb 0%, #ace0f9 100%);
            padding: 28px;
        }

        .page {
            max-width: 1180px;
            margin: 0 auto;
        }

        .toolbar {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 14px;
            align-items: center;
            margin-bottom: 24px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 18px;
            border-radius: 14px;
            border: 2px solid #ff6b6b;
            background: rgba(255, 255, 255, 0.9);
            color: #ff6b6b;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            background: #ff6b6b;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .toolbar input {
            width: 100%;
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #ffffff;
            font-size: 1rem;
            outline: none;
        }

        .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 14px;
            border: 1px solid transparent;
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 50%, #48dbfb 100%);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .toolbar button:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(255, 107, 107, 0.3);
        }

        .status-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
        }

        .status-bar p {
            margin: 0;
            color: #334155;
            font-size: 0.95rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 11px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .card-list {
            display: grid;
            gap: 20px;
        }

        .provider-card {
            display: grid;
            grid-template-columns: 1fr 1.4fr 1.05fr auto;
            gap: 18px;
            padding: 24px;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
            align-items: center;
        }

        .provider-info {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 50%, #48dbfb 100%);
            display: grid;
            place-items: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .provider-meta {
            display: grid;
            gap: 6px;
        }

        .provider-meta h2 {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.2;
        }

        .provider-location {
            margin: 0;
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .provider-meta p {
            margin: 0;
            color: #475569;
            font-size: 0.92rem;
        }

        .badge-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .badge-row span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 12px;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 600;
            color: #0f172a;
            background: #f8fafc;
        }

        .provider-schedule {
            display: grid;
            gap: 12px;
        }

        .schedule-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
        }

        .schedule-card {
            display: grid;
            grid-template-columns: 72px 1fr;
            gap: 12px;
            align-items: center;
            padding: 14px 16px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .schedule-icon {
            width: 56px;
            height: 56px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eef2ff 0%, #eff6ff 100%);
            display: grid;
            place-items: center;
            color: #2563eb;
            font-size: 1.4rem;
        }

        .schedule-details {
            display: grid;
            gap: 4px;
        }

        .schedule-details small {
            display: block;
            color: #475569;
        }

        .provider-actions {
            display: grid;
            gap: 12px;
            justify-items: end;
        }

        .provider-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 132px;
            padding: 12px 18px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            color: #ffffff;
            background: #0c5cd9;
            transition: transform 0.2s ease;
        }

        .provider-actions a:hover {
            transform: translateY(-1px);
        }

        .provider-actions small {
            color: #475569;
            font-size: 0.86rem;
            text-align: right;
        }

        @media (max-width: 980px) {
            .toolbar {
                grid-template-columns: 1fr;
            }

            .status-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .provider-card {
                grid-template-columns: 1fr;
            }

            .provider-actions {
                justify-items: start;
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            body {
                padding: 18px;
            }

            .provider-card {
                padding: 18px;
            }

            .schedule-card {
                grid-template-columns: 1fr;
            }

            .provider-actions {
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="toolbar">
            <a href="index.php" class="back-link">← Back to site</a>
            <input type="search" placeholder="Pediatrics">
            <button type="button">Search</button>
        </div>

        <div class="status-bar">
            <span class="status-badge">AVAILABLE NOW</span>
        </div>

        <div class="card-list">
            <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <article class="provider-card">
                        <div class="provider-info">
                            <?php if (!empty($doctor['photo'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="Photo" class="avatar">
                            <?php else: ?>
                                <div class="avatar"><?php echo htmlspecialchars($doctor['avatar']); ?></div>
                            <?php endif; ?>
                            <div class="provider-meta">
                                <h2><?php echo htmlspecialchars($doctor['name']); ?></h2>
                                <p><?php echo htmlspecialchars($doctor['title']); ?></p>
                                <p class="provider-location"><?php echo htmlspecialchars($doctor['location']); ?></p>
                                <?php if (!empty($doctor['experience'])): ?>
                                    <p><?php echo htmlspecialchars($doctor['experience']); ?></p>
                                <?php endif; ?>
                                <div class="badge-row">
                                    <span>✔ Online Consultation</span>
                                    <?php if ($doctor['inperson_available']): ?>
                                        <span>✔ In-Person Consultation</span>
                                    <?php else: ?>
                                        <span>✕ In-Person Consultation</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="provider-schedule">
                            <p class="schedule-title">Earliest Available Schedule</p>
                            <div class="schedule-card">
                                <div class="schedule-icon">📱</div>
                                <div class="schedule-details">
                                    <strong><?php echo htmlspecialchars($doctor['consultation']); ?></strong>
                                    <small><?php echo htmlspecialchars($doctor['availability']); ?></small>
                                    <small>Fee: <?php echo htmlspecialchars($doctor['fee']); ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="provider-actions">
                            <small>BOOK APPOINTMENT</small>
                            <a href="profile.php?doctor=<?php echo urlencode($doctor['slug']); ?>">VIEW PROFILE</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No providers available at this time. Please try again later.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
