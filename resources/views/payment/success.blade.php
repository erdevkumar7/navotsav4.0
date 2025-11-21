<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>50/50 Raffle Success</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Jost", sans-serif;
            background: linear-gradient(135deg, #4a00e0, #8e2de2);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow-x: hidden;
            /* height: 100vh; */
            margin: 60px 0px;
        }

        ::-webkit-scrollbar {
            width: 0px;
        }

        .success-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            max-width: 450px;
            width: 100%;
            margin: 35px 0;
            animation: fadeIn 1s ease-out;
        }

        /* Trophy or success icon */
        .icon {
            font-size: 60px;
            color: #ffcc00;
            margin-bottom: 15px;
            animation: bounce 1.5s infinite;
        }

        .sub-text {
            color: #555;
            font-size: 17px;
            margin-bottom: 25px;
        }

        .winner-name {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            background: #ffe259;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 0px;
        }

        .button {
            text-decoration: none;
            background: linear-gradient(90deg, #ff512f, #dd2476);
            color: #fff;
            padding: 12px 28px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.3s;
            display: inline-block;
        }

        .button:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #dd2476, #ff512f);
        }

        /* Confetti Animation */
        .confetti {
            position: absolute;
            width: 8px;
            height: 14px;
            top: -10px;
            opacity: 0.8;
            animation: fall 3s linear infinite;
        }

        .confetti:nth-child(3n) {
            background: #ff512f;
        }

        .confetti:nth-child(3n+1) {
            background: #22c55e;
        }

        .confetti:nth-child(3n+2) {
            background: #facc15;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            display: inline-block;
            margin-bottom: 15px;
            position: relative;
        }

        .success-icon::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle at center, #22c55e33 0%, transparent 70%);
            animation: glow-pulse 1.5s ease-in-out infinite;
        }

        .success-ring {
            stroke: #22c55e;
            stroke-width: 3;
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-linecap: round;
            fill: none;
            animation: ring-animate 0.6s ease forwards;
        }

        .success-check {
            stroke: #22c55e;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: check-animate 0.4s ease forwards 0.6s;
        }

        .success-container h1 span {
            color: #f9037a;
        }

        .success-container h1 {
            color: #000;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .winner_top_add {
            /* display: grid; */
            grid-template-columns: repeat(1, 1fr);
            gap: 10px;
            width: 60%;
            margin: auto;
        }




        @media only screen and (max-width: 767px) {
            .success-container {
                width: 80%;
                padding: 35px 20px;
            }

            .winner_top_add {
                display: block;
            }

            .winner_top_add {
                width: 80%;
            }

            .winner-name {
                margin-bottom: 10px;
            }

        }





        @keyframes fall {
            to {
                transform: translateY(110vh) rotate(360deg);
            }
        }


        /* Animations */
        @keyframes ring-animate {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes check-animate {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes glow-pulse {

            0%,
            100% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.1);
            }
        }

        @keyframes fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 10px #ffe259;
            }

            100% {
                box-shadow: 0 0 20px #ffa751;
            }
        }

        @media (max-width: 480px) {
            .success-container {
                padding: 35px 20px;
            }

            .success-container h1 {
                font-size: 22px;
            }

            .winner-name {
                font-size: 18px;
                padding: 8px 16px;
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="icon success-icon">
            <svg viewBox="-5 -5 60 60">
                <circle class="success-ring" cx="26" cy="26" r="25" fill="none" />
                <path class="success-check" fill="none" d="M14 27l7 7 16-16" />
            </svg>
        </div>

        <h1>🎉 <span>{{ $event->title }}</span> Raffle Donate Confirmed 🎉</h1>
        <h2>Your Booking Details</h2>
        <p class="sub-text">Congratulations! Your entry has been successfully submitted.</p>
        <div class="winner_top_add">
            <div class="winner-name">Donate #{{ $ticketNumber }}</div>

        </div>
        <br />
        <a href="{{ env('WEB_URL') }}" class="button">Back to Home</a>
    </div>

    <!-- Confetti Effect -->
    <script>
        function createConfetti() {
            const colors = ["#ffcc00", "#ff69b4", "#4ade80", "#38bdf8", "#f87171"];
            for (let i = 0; i < 50; i++) {
                const conf = document.createElement("div");
                conf.classList.add("confetti");
                conf.style.left = Math.random() * 100 + "vw";
                conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                conf.style.animationDuration = Math.random() * 3 + 2 + "s";
                conf.style.width = Math.random() * 8 + 6 + "px";
                conf.style.height = Math.random() * 12 + 8 + "px";
                document.body.appendChild(conf);
                setTimeout(() => conf.remove(), 5000);
            }
        }
        createConfetti();
        setInterval(createConfetti, 4000);
    </script>
</body>

</html>
