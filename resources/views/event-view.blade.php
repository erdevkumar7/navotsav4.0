<!Doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>50/50 Raffle Winners</title>
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

        span.qty {
            font-size: 24px;
            background: url("{{ url('assets/images/ticket-bg.png') }}");
            width: 120px;
            height: 55px;
            background-size: cover;
            background-position: center;
            padding-top: 8px;
            color: #fff;
        }


        span.price {
            font-size: 24px;
            background: #f9037a14;
            width: 114px;
            border-radius: 8px;
            padding: 9px;
            color: #fff;
            background: #743289;
            font-weight: 800;
        }




        .raffle-wrapper {
            display: flex;
            background: #fff;
            width: 100%;
            margin: auto;
            padding: 20px;
        }

        /* LEFT SIDE BANNER */


        .raffle-banner::after {
            content: "";
            position: absolute;
            inset: 0;
        }

        /* RIGHT SIDE CONTENT */
        .raffle-content {
            background: #fff;
            padding: 0px 40px;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .raffle-content::before {
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

        .amount {
            font-size: 60px;
            font-weight: 700;
            color: #f9037a;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            box-shadow: 0 3px 30px rgb(0 0 0 / 26%);
            padding: 4px 18px;
            border-radius: 10px;
        }

        .raffle-logo {
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            margin-top: 20px;
        }

        .raffle-logo .circle {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle, #ffd600, #ffb800);
            border-radius: 50%;
            width: 110px;
            height: 110px;
            color: #000;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: 0 0 20px #ffd60080;
        }

        .raffle-logo p {
            color: #ffd600;
            letter-spacing: 4px;
            font-weight: 600;
            margin-top: 10px;
        }

        .raffle-options .option {
            border: 1px solid #f9037a;
            border-radius: 10px;
            padding: 6px 0px;
            font-size: 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-weight: 600;
            color: #000;
            width: 480px;
            margin: auto;
        }

        /*        .raffle-options .option:hover {
            background: #743289;
            color: #fff;
            transform: translateY(-2px);
        }*/

        .winner-box {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 214, 0, 0.3);
            box-shadow: 0 0 15px rgba(255, 214, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .winner-box h3 {
            color: #ffd600;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .winner-box p {
            color: #ccc;
            font-size: 1rem;
        }

        h2.sold_tic {
            font-size: 60px;
            font-weight: 700;
            color: #743289;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            padding: 4px 18px;
            border-radius: 10px;
            box-shadow: 0 3px 30px rgb(0 0 0 / 26%);
        }

        .solid_tic {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            padding-bottom: 25px;
            width: 100%;
            margin: auto;
        }

        .raffle-content h4 {
            color: #000;
            padding-bottom: 16px;
            font-size: 23px;
            font-weight: 600;
        }

        .countdown.text-center.mb-3 {
            background: #4939c0;
            color: #fff;
            border-radius: 15px;
            padding: 10px;
            width: 480px;
            margin: auto;
        }

        .countdown h3 {
            color: #ec6624;
            display: flex;
            justify-content: space-evenly;
        }

        .countdown span.number-text-add {
            display: flex;
            flex-direction: column;
            font-weight: 600;
            padding: 10px 0;
            font-size: 30px;
        }

        small.days-text-add {
            color: #fff;
            font-size: 16px;
            font-weight: 400;
        }

        .countdown p.mb-1 {
            font-size: 30px;
            font-weight: 500;
        }

        .raffle-banner {
            background: url("{{ $banner }}");
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            background-position: center;
        }

        .event-title-new {
            color: #000;
            font-size: 25px;
            padding-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }


        .raffle-banner h4 {
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
    </style>
</head>

<body onload="collectedAmt(true)">
    <div class="container-fluid p-0 raffle-wrapper">
        <div class="row g-0 w-100">
            <!-- LEFT SIDE IMAGE -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 position-relative raffle-banner">
                {{-- <img class="card-img-top" alt="Enhanced coherent time-frame"
                    src="" /> --}}
                {{-- <h4>{{ $event->title }}</h4> --}}
            </div>

            <!-- RIGHT SIDE CONTENT -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 raffle-content">
                <div class="raffle-logo">
                    <img alt="Logo" width="120" src="{{ url('assets/images/fiftyplay-logo.png') }}" />
                </div>
                <div class="event-title-new">
                    {{ $event->title }}
                </div>
                <div class="w-100">
                    <div class="solid_tic">
                        <h2 id="event-pot" class="amount">$0</h2>
                        <h2 id="sold-ticket" class="sold_tic">0 Sold</h2>
                    </div>
                    @if ($event->draw_time && $event->winner_type == 'automatic')
                        <div class="countdown text-center mb-3">
                            <p class="mb-1">This Raffle ends in:</p>
                            <h3><span class="number-text-add">0 <small class="days-text-add">Days</small></span><span
                                    class="number-text-add">0 <small class="days-text-add">Hours</small></span><span
                                    class="number-text-add">0 <small class="days-text-add">Minutes</small></span><span
                                    class="number-text-add">0 <small class="days-text-add">Seconds</small></span></h3>
                        </div>
                    @endif

                    <div class="raffle-options d-flex flex-column gap-2 mt-1">
                        @foreach ($event->multiplePrices as $price)
                            <div class="option"><span class="qty">{{ $price->quantity }}</span> FOR <span
                                    class="price">${{ floor($price->price) }}</span></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Parse the draw time from backend (Laravel Blade variable)
            const drawTime = new Date("{{ $event->draw_time }}").getTime();

            // Get element references once (for performance)
            const numberElements = document.querySelectorAll('.number-text-add');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = drawTime - now;

                if (distance <= 0) {
                    clearInterval(timerInterval);
                    numberElements[0].innerHTML = `0 <small class="days-text-add">Days</small>`;
                    numberElements[1].innerHTML = `0 <small class="days-text-add">Hours</small>`;
                    numberElements[2].innerHTML = `0 <small class="days-text-add">Minutes</small>`;
                    numberElements[3].innerHTML = `0 <small class="days-text-add">Seconds</small>`;
                    document.querySelector(".countdown p").textContent = "🎉 This raffle has ended!";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                numberElements[0].innerHTML = `${days} <small class="days-text-add">Days</small>`;
                numberElements[1].innerHTML = `${hours} <small class="days-text-add">Hours</small>`;
                numberElements[2].innerHTML = `${minutes} <small class="days-text-add">Minutes</small>`;
                numberElements[3].innerHTML = `${seconds} <small class="days-text-add">Seconds</small>`;
            }

            // Initial update and interval refresh
            updateCountdown();
            const timerInterval = setInterval(updateCountdown, 1000);
        });



        function animateValue(id, start, end, duration, prefix = '', suffix = '') {
            const obj = document.getElementById(id);
            if (!obj) return;

            const startTime = performance.now();

            function update(timestamp) {
                const progress = Math.min((timestamp - startTime) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                obj.textContent = `${prefix}${value.toLocaleString()}${suffix}`;

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        }

        function collectedAmt(initialLoad = false) {
            $.get(`{{ url('api') }}/event/{{ $event->id }}/collected-amount`, function(response) {
                if (response && response.amount) {
                    const amount = response.amount;
                    const sold = response.sold_tickets;

                    if (initialLoad) {
                        // Animate only on first load
                        animateValue('event-pot', 0, amount, 1000, '$');
                        animateValue('sold-ticket', 0, sold, 1000, '', ' Sold');
                    } else {
                        // Update instantly on later refreshes
                        $('#event-pot').text(`$${amount.toLocaleString()}`);
                        $('#sold-ticket').text(`${sold.toLocaleString()} Sold`);
                    }

                    if (response.is_finalized === true) {
                        setTimeout(() => {
                            window.location = "{{ route('event.winner', $event->id) }}";
                        }, 3000);

                    }
                }
            }).fail(function(xhr) {
                console.error('Error fetching collected amount:', xhr.responseText);
            });
        }

        // call every few seconds to auto-refresh
        setInterval(collectedAmt, 5000);

        // Call immediately when page loads
    </script>
</body>

</html>
