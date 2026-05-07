<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DoctorAppointer</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Inter, sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url('image.png') no-repeat center center/cover;
        position: relative;
    }

    body::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
    }

    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 500px;
        background: rgba(255, 255, 255, 0.92);
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        backdrop-filter: blur(8px);
    }

    .title {
        text-align: center;
        margin-bottom: 10px;
        font-size: 2rem;
        font-weight: 800;
    }

    .subtitle {
        text-align: center;
        margin-bottom: 30px;
        color: #555;
        font-size: 0.95rem;
    }

    .input-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .phone-input {
        width: 100%;
        padding: 16px;
        border: 1px solid #ddd;
        border-radius: 14px;
        font-size: 1rem;
        margin-bottom: 20px;
        outline: none;
    }

    .phone-input:focus {
        border-color: #4f46e5;
    }

    .hero__button {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #ff6b6b, #feca57, #48dbfb);
        color: white;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
    }

    .hero__button:hover {
        opacity: 0.95;
    }

    .hero__note {
        margin-top: 15px;
        text-align: center;
        color: #666;
        font-size: 0.9rem;
    }

    .history-btn {
        display: block;
        width: 100%;
        padding: 14px;
        margin-top: 15px;
        border: 2px solid #4f46e5;
        border-radius: 14px;
        background: transparent;
        color: #4f46e5;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
    }

    .history-btn:hover {
        background: #4f46e5;
        color: white;
    }
</style>
</head>
<body>

<div class="login-container">
    <h1 class="title">Pedia Care Appointment Center</h1>
    <p class="subtitle">Book your appointment online</p>

    <label class="input-label" for="phone-input">Phone Number</label>
    <input 
        id="phone-input" 
        class="phone-input" 
        type="tel" 
        placeholder="Input your number"
        maxlength="11"
        pattern="[0-9]{11}"
    >

    <a id="consult-now" class="hero__button" href="doctors.php">Consult Now</a>
    <p class="hero__note">Tap consult to instantly start a secure pediatrics appointment.</p>
    
    <a id="history-btn" class="history-btn" href="#" onclick="openHistory(); return false;">📋 View Booking History</a>
</div>

<script>
    document.getElementById('consult-now').addEventListener('click', function(event) {
        event.preventDefault();
        let phone = document.getElementById('phone-input').value.replace(/\D/g, '');

        if (phone.length !== 11) {
            alert('Please enter an 11-digit phone number.');
            return;
        }

        window.location.href = 'doctors.php?phone=' + encodeURIComponent(phone);
    });

    function openHistory() {
        let phone = document.getElementById('phone-input').value.replace(/\D/g, '');

        if (phone.length !== 11) {
            alert('Please enter an 11-digit phone number first.');
            return;
        }

        window.location.href = 'history.php?phone=' + encodeURIComponent(phone);
    }
</script>

</body>
</html>
