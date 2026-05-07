<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consult Now</title>
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
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at top left, rgba(124, 58, 237, 0.12), transparent 28%),
                        radial-gradient(circle at bottom right, rgba(37, 99, 235, 0.12), transparent 32%),
                        #eef2ff;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        .frame {
            width: min(100%, 860px);
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 36px;
            padding: 36px;
            box-shadow: 0 40px 100px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(12px);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 34px;
        }

        .header__badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(124, 58, 237, 0.1);
            color: #5b21b6;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .header__status {
            color: #475569;
            font-size: 0.95rem;
        }

        .panel {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
        }

        .panel__card {
            background: #ffffff;
            border-radius: 32px;
            padding: 32px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.8);
        }

        .panel__title {
            margin: 0 0 12px;
            font-size: clamp(2rem, 2.5vw, 2.6rem);
            line-height: 1.05;
            font-weight: 800;
            color: #111827;
        }

        .panel__text {
            margin: 0 0 28px;
            color: #475569;
            font-size: 1rem;
            line-height: 1.8;
        }

        .info-list {
            display: grid;
            gap: 14px;
            margin-bottom: 28px;
        }

        .info-item {
            display: grid;
            gap: 8px;
            padding: 18px 20px;
            border-radius: 22px;
            background: #f8faff;
            border: 1px solid rgba(37, 99, 235, 0.12);
        }

        .info-item strong {
            font-size: 0.95rem;
            color: #0f172a;
            display: block;
        }

        .info-item span {
            color: #475569;
            font-size: 0.95rem;
        }

        .hero-actions {
            display: grid;
            gap: 12px;
        }

        .primary-action,
        .secondary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border-radius: 24px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .primary-action {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.22);
        }

        .primary-action:hover {
            transform: translateY(-1px);
            opacity: 0.98;
        }

        .secondary-action {
            background: rgba(15, 23, 42, 0.04);
            color: #111827;
            border: 1px solid rgba(15, 23, 42, 0.09);
        }

        .secondary-action:hover {
            opacity: 0.92;
        }

        .note-card {
            display: grid;
            gap: 10px;
            padding: 22px 24px;
            border-radius: 24px;
            background: rgba(99, 102, 241, 0.06);
            border: 1px dashed rgba(99, 102, 241, 0.22);
        }

        .note-card p {
            margin: 0;
            color: #334155;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .editable-input,
        .editable-select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 20px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #f8fbff;
            color: #0f172a;
            font-size: 0.97rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .editable-input:focus,
        .editable-select:focus {
            outline: none;
            border-color: rgba(124, 58, 237, 0.45);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08);
        }

        .sidebar {
            display: grid;
            gap: 18px;
        }

        .sidebar__card {
            padding: 28px 26px;
            border-radius: 30px;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.08), rgba(255, 255, 255, 0.9));
            border: 1px solid rgba(99, 102, 241, 0.16);
        }

        .sidebar__heading {
            margin: 0 0 16px;
            font-size: 1.1rem;
            color: #0f172a;
        }

        .sidebar__list {
            display: grid;
            gap: 14px;
        }

        .sidebar__item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .sidebar__icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(124, 58, 237, 0.12);
            color: #7c3aed;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .sidebar__item span {
            font-size: 0.95rem;
            color: #334155;
        }

        @media (max-width: 840px) {
            .panel {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 20px;
            }

            .frame {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <main class="frame">
        <header class="header">
            <span class="header__badge">Consult now</span>
            <span class="header__status">Appointment ready in 3 steps</span>
        </header>

        <section class="panel">
            <div class="panel__card">
                <h1 class="panel__title">Start your pediatric consultation</h1>
                <p class="panel__text">Complete the details below and proceed to chat with a specialist. This page is designed like a clean Figma mockup, with soft shadows, rounded cards, and clear action flow.</p>

                <div class="info-list">
                    <div class="info-item">
                        <strong>Phone number</strong>
                        <span><?php echo htmlspecialchars($_GET['phone'] ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>User concern</strong>
                        <input id="user-problem" class="editable-input" type="text" value="<?php echo htmlspecialchars($_GET['problem'] ?? 'Fever, cough, or diarrhea', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Describe your concern">
                    </div>
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <strong>Dr. Maria Santos</strong>
                        <span>Pediatrician • Child illness diagnosis</span>
                    </div>
                    <div class="info-item">
                        <strong>Dr. Paolo Reyes</strong>
                        <span>Neonatal specialist • Infant care and growth monitoring</span>
                    </div>
                    <div class="info-item">
                        <strong>Dr. Jenna Cruz</strong>
                        <span>Child development expert • Behavioral and nutrition support</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a class="primary-action" href="index.php">Continue</a>
                    <a class="secondary-action" href="index.php">Back to home</a>
                </div>

                <div class="note-card">
                    <p>Everything is set for your consultation: doctor profiles, problem summary, and easy next-step navigation.</p>
                </div>
            </div>

            <aside class="sidebar">
                <div class="sidebar__card">
                    <h2 class="sidebar__heading">Why consult now?</h2>
                    <div class="sidebar__list">
                        <div class="sidebar__item">
                            <span class="sidebar__icon">1</span>
                            <span>Quick pediatric triage</span>
                        </div>
                        <div class="sidebar__item">
                            <span class="sidebar__icon">2</span>
                            <span>Verified child health experts</span>
                        </div>
                        <div class="sidebar__item">
                            <span class="sidebar__icon">3</span>
                            <span>Secure real-time support</span>
                        </div>
                    </div>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
