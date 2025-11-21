<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>50/50 Winner Announcement</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @php
        $banner = $event->event_screen
            ? asset('storage/' . $event->event_screen)
            : asset('storage/' . $event->banners->first()->banner);
    @endphp
    <style>
        body {
            color: #fff;
            font-family: "Jost";
        }

        div#winnerNumber {
            color: #743289;
            font-weight: 600;
            font-size: 28px;
        }

        .container-fluid {
            padding: 0px;
            width: 100%;
            margin: auto;
        }

        p.text-light {
            color: #000 !important;
            font-size: 22px;
        }

        .right-content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 30px;
            position: relative;
            background: #fff;
            overflow: hidden;
        }

        .winner-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            width: 88%;
            position: relative;
            overflow: hidden;
            color: #000;
        }

        button#drawBtn {
            background: #000;
            color: #fff;
        }

        .winner-card::before {
            content: "";
            position: absolute;
            top: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(40px);
        }

        .trophy {
            font-size: 70px;
            color: #ffc107;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .winner-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 15px;
        }

        .winner-number {
            font-size: 1rem;
            color: #9ca3af;
            margin-top: 5px;
        }


        .winner-amount {
            color: #000;
            font-size: 30px;
            font-weight: 800;
            margin: 0px 0;
        }

        .collect-amount {
            color: #f9037a;
            font-weight: 600;
            font-size: 28px;
            margin: 15px 0;
        }

        .confetti {
            position: fixed;
            top: -10px;
            border-radius: 50%;
            animation: fall linear forwards;
            z-index: 9999;
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

        .fade-out {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }

        .fade-in {
            opacity: 1;
            transform: scale(1);
            transition: all 0.3s ease;
        }

        #winnerName,
        #winnerNumber,
        #winnerAmount {
            transition: all 0.4s ease;
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.6);
        }

        .btn-primary {
            background-color: #ffc107;
            border: none;
            color: #000;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #ffca2c;
        }

        .btn-outline-light {
            font-weight: 600;
        }

        .winner-section {
            display: flex;
            align-items: center;
            background: #fff;
        }

        .winner-card.text-center h2 span {
            color: #f9037a;
        }

        /* .raffle-banner {
            background: url("{{ asset('storage/' . $event->banners->first()->banner) }}");
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            background-position: center;
        } */


        .raffle-banner {
            background: url("{{ $banner }}");
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            background-position: center;
        }

        .winner-card::before {
            content: "";
            position: absolute;
            top: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(40px);
        }

        .raffle-banner::after {
            content: "";
            position: absolute;
            inset: 0;
        }

        .trophy {
            font-size: 70px;
            color: #ffc107;
            animation: bounce 2s infinite;
        }

        .right-content::after {
            content: "";
            position: absolute;
            top: -80px;
            left: -80px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: #f9037a61;
            filter: blur(80px);
            z-index: 0;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .winner-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 15px;
        }

        .winner-number {
            font-size: 1rem;
            color: #9ca3af;
            margin-top: 5px;
        }


        .btn-primary {
            background-color: #ffc107;
            border: none;
            color: #000;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #ffca2c;
        }

        .btn-outline-light {
            font-weight: 600;
        }

        /* Confetti animation */
        .confetti {
            position: absolute;
            top: -10px;
            width: 10px;
            height: 10px;
            opacity: 0.9;
            animation:
                fall var(--duration, 4s) linear forwards,
                spin var(--spin, 2s) ease-in-out infinite;
            transform-origin: center;
            z-index: 10;
        }

        /* Shape variations */
        .confetti[data-shape="circle"] {
            border-radius: 50%;
        }

        .confetti[data-shape="square"] {
            border-radius: 2px;
        }

        .confetti[data-shape="triangle"] {
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 10px solid var(--color, #fff);
        }

        .confetti[data-shape="ribbon"] {
            width: 14px;
            height: 4px;
            border-radius: 50px;
        }

        .confetti[data-shape="star"]::before {
            content: "★";
            color: var(--color, gold);
            font-size: 14px;
            display: inline-block;
        }

        .raffle-banner h4 {
            width: 67%;
            position: absolute;
            top: 50%;
            left: 50%;
            /* text-shadow: 1px 1px 3px #f9037a; */
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 55px;
            font-weight: 500;
            margin: 0;
            padding: 50px 20px;
            display: inline-block;
            background: rgb(0 0 0 / 48%);
            border-radius: 8px;
            letter-spacing: 1px;
            line-height: 1.4;
            /* width: 560px; */
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            opacity: 1;
            min-width: 500px;
        }


        /* ================================
   📱 Medium–Large Screens (1024px–1382px)
================================ */
        @media screen and (min-width: 1024px) and (max-width: 1382px) {
            .winner-card h2 {
                font-size: 180%;
            }

            p.text-light {
                color: #000 !important;
                font-size: 140%;
            }

            .raffle-banner h4 {
                font-size: 350%;
            }

            #winnerNumber {
                font-size: 150%;
            }

            .collect-amount {
                font-size: 130%;
            }
        }

        /* ================================
   💻 Large Screens (1383px–1599px)
================================ */
        @media screen and (min-width: 1383px) and (max-width: 1599px) {
            .winner-card h2 {
                font-size: 250%;
            }

            p.text-light {
                color: #000 !important;
                font-size: 160%;
            }

            .raffle-banner h4 {
                font-size: 400%;
            }

            #winnerNumber {
                font-size: 180%;
            }

            .collect-amount {
                font-size: 150%;
            }
        }

        /* ================================
   🖥️ Extra-Large Screens (1600px–2560px)
================================ */
        @media screen and (min-width: 1600px) and (max-width: 2560px) {
            .winner-card h2 {
                font-size: 290%;
            }

            p.text-light {
                color: #000 !important;
                font-size: 180%;
            }

            .raffle-banner h4 {
                font-size: 450%;
            }

            #winnerNumber {
                font-size: 200%;
            }

            .collect-amount {
                font-size: 180%;
            }
        }

        /* ================================
   🧠 2K–2.9K Ultra Screens (2561px–2999px)
================================ */
        @media screen and (min-width: 2561px) and (max-width: 2999px) {
            .winner-card h2 {
                font-size: 320%;
            }

            p.text-light {
                color: #000 !important;
                font-size: 200%;
            }

            .raffle-banner h4 {
                font-size: 500%;
            }

            #winnerNumber {
                font-size: 240%;
            }

            .collect-amount {
                font-size: 190%;
            }
        }

        /* ================================
   🧭 Ultra-Wide Screens (3000px–8090px)
================================ */
        @media screen and (min-width: 3000px) and (max-width: 8090px) {
            .winner-card h2 {
                font-size: 350%;
            }

            p.text-light {
                color: #000 !important;
                font-size: 220%;
            }

            .raffle-banner h4 {
                font-size: 700%;
            }

            #winnerNumber {
                font-size: 280%;
            }

            .collect-amount {
                font-size: 200%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0 raffle-wrapper">
        <div class="row winner-section g-0">
            <!-- Left Banner -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 position-relative raffle-banner">
                {{-- <img class="card-img-top" alt="Raffle Banner"
                    src="{{ asset('storage/' . $event->banners->first()->banner) }}" /> --}}

            </div>

            <!-- Right Content -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 right-content">
                <div class="winner-card text-center">
                    <div class="trophy mb-3">🏆</div>
                    <h2 class="fw-bold mb-3">🎉<span>50/50</span> Raffle Winner🎉</h2>
                    <h4>{{ $event->title }}</h4>
                    <p class="text-light">Congratulations to our lucky winner!</p>

                    {{-- <div class="winner-name" id="winnerName">Sushil Kumar</div> --}}
                    <div class="winner-number" id="winnerNumber">Donate: {{ $ticketNumber }}</div>
                    <div class="collect-amount" id="">Total Amt: {{ format_price($collectAmt) }}</div>
                    <div class="winner-amount" id="winnerAmount">{{ format_price($winningPrice) }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createConfetti() {
            const colors = ["#ff6b6b", "#ffd93d", "#6bcBef", "#a78bfa", "#4ade80", "#ff7eb9", "#ff65a3"];
            const shapes = ["circle", "triangle", "square", "ribbon", "star"];
            const confettiCount = 80;
            const container = document.querySelector(".right-content");

            for (let i = 0; i < confettiCount; i++) {
                const conf = document.createElement("div");
                conf.classList.add("confetti");

                const shape = shapes[Math.floor(Math.random() * shapes.length)];
                conf.dataset.shape = shape;

                conf.style.left = Math.random() * 100 + "%";
                conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                conf.style.width = Math.random() * 10 + 6 + "px";
                conf.style.height = conf.style.width;
                conf.style.opacity = Math.random();
                conf.style.animationDuration = 3 + Math.random() * 2 + "s";
                conf.style.transform = `rotate(${Math.random() * 360}deg)`;
                container.appendChild(conf);

                setTimeout(() => conf.remove(), 6000);
            }
        }



        function animateWinner(name, number, amount) {
            const nameEl = document.getElementById("winnerName");
            const numEl = document.getElementById("winnerNumber");
            const amtEl = document.getElementById("winnerAmount");

            [nameEl, numEl, amtEl].forEach((el) => el.classList.add("fade-out"));

            setTimeout(() => {
                nameEl.textContent = name;
                numEl.textContent = "Ticket #" + number;
                amtEl.textContent = amount;

                [nameEl, numEl, amtEl].forEach((el) => {
                    el.classList.remove("fade-out");
                    el.classList.add("fade-in");
                });
            }, 300);

            setTimeout(() => {
                [nameEl, numEl, amtEl].forEach((el) => el.classList.remove("fade-in"));
            }, 1000);
        }

        createConfetti();
    </script>
</body>

</html>
