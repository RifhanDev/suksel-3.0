<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fasa Peralihan STOS 3.0 | Sistem Tender Online Selangor</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet">

    {{-- Standalone on purpose: during maintenance there is no session, so the modernLanding layout (menus, login) can't run. --}}
    <style>
        :root {
            --sg-red: #c41e3a;
            --sg-red-dark: #a01830;
            --sg-red-darker: #8b1428;
            --sg-yellow: #ffcc00;
            --sg-black: #1f1f1f;
            --sg-bg: #f3f4f6;
            --text-secondary: #4b5563;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-display: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --logo: clamp(96px, 20vh, 184px);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--sg-bg);
            background-image: radial-gradient(#dfe2e7 1px, transparent 1px);
            background-size: 20px 20px;
            color: var(--sg-black);
            line-height: 1.6;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        .closed {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(340px, 42%) 1fr;
        }

        /* The navbar's angled brand band, enlarged; the yellow layer behind it shows as a sliver along the cut. */
        .brand-panel {
            position: relative;
            isolation: isolate;
        }

        .brand-panel::before,
        .brand-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
        }

        .brand-panel::before {
            background: var(--sg-yellow);
            clip-path: polygon(0 0, 100% 0, 86% 100%, 0 100%);
        }

        .brand-panel::after {
            background: linear-gradient(150deg, var(--sg-red) 0%, var(--sg-red-dark) 55%, var(--sg-red-darker) 100%);
            clip-path: polygon(0 0, calc(100% - 16px) 0, calc(86% - 16px) 100%, 0 100%);
        }

        .brand-lockup {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 20% 2rem 11%;
        }

        .logo-box {
            width: var(--logo);
            height: var(--logo);
            background: #fff;
            border: 4px solid var(--sg-yellow);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.25);
            margin-bottom: clamp(1rem, 3.5vh, 2.25rem);
        }

        .logo-box img {
            height: calc(var(--logo) * 0.85);
            max-width: calc(var(--logo) * 0.87);
            display: block;
        }

        .brand-line-top {
            display: block;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: clamp(1rem, min(1.9vw, 3.2vh), 1.75rem);
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.95);
        }

        .brand-line-btm {
            display: block;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2.25rem, min(6vw, 9.5vh), 5.25rem);
            line-height: 1;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--sg-yellow);
            text-shadow: 0 3px 0 rgba(0, 0, 0, 0.12);
        }

        .brand-tagline {
            margin-top: clamp(0.75rem, 2.4vh, 1.5rem);
            padding-top: clamp(0.6rem, 1.8vh, 1.1rem);
            border-top: 2px solid rgba(255, 204, 0, 0.55);
            max-width: 22rem;
            font-size: clamp(0.85rem, 1.9vh, 1rem);
            font-weight: 500;
            color: rgba(255, 255, 255, 0.88);
        }

        /* Sizes below follow the screen height so the card fits without scrolling on laptops and desktops. */
        .message {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(0.75rem, 2vh, 3rem) 5% clamp(0.75rem, 2vh, 3rem) 3%;
        }

        .card {
            width: 100%;
            max-width: 660px;
            background: #fff;
            border: 1px solid #eceef2;
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(17, 24, 39, 0.10), 0 2px 6px rgba(17, 24, 39, 0.04);
            padding: clamp(1rem, 2.6vh, 2.25rem) clamp(1.25rem, 3.5vw, 3rem);
            text-align: center;
        }

        .illustration {
            display: block;
            height: clamp(90px, 19vh, 220px);
            width: auto;
            max-width: 100%;
            margin: 0 auto clamp(0.5rem, 1.6vh, 1.25rem);
        }

        .gear {
            transform-box: fill-box;
            transform-origin: center;
            animation: spin 14s linear infinite;
        }

        .gear--reverse {
            animation-direction: reverse;
            animation-duration: 9s;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .phase-tag {
            display: inline-block;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--sg-red-darker);
            background: var(--sg-yellow);
            padding: 0.3rem 1.1rem;
            border-radius: 999px;
            margin-bottom: clamp(0.5rem, 1.4vh, 1rem);
        }

        .card h1 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(1.25rem, min(2.4vw, 3.6vh), 2rem);
            line-height: 1.2;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            color: var(--sg-red-darker);
            margin-bottom: clamp(0.5rem, 1.4vh, 0.85rem);
        }

        .lead {
            font-size: clamp(0.9rem, 2vh, 1rem);
            line-height: 1.65;
            color: var(--text-secondary);
            max-width: 34rem;
            margin: 0 auto clamp(0.85rem, 2.4vh, 1.75rem);
        }

        /* The one fact users came for: when the new system opens. */
        .launch {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            text-align: left;
            background: linear-gradient(135deg, #fff9e0 0%, #fff3c4 100%);
            border: 1px solid #f6dd7a;
            border-radius: 14px;
            padding: clamp(0.7rem, 1.8vh, 1.1rem) 1.4rem;
            margin-bottom: clamp(0.85rem, 2.4vh, 1.75rem);
        }

        .calendar {
            flex-shrink: 0;
            width: clamp(56px, 9vh, 74px);
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 14px rgba(139, 20, 40, 0.18);
            text-align: center;
        }

        .calendar-month {
            display: block;
            background: var(--sg-red);
            color: #fff;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 0.15rem 0;
        }

        .calendar-day {
            display: block;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(1.3rem, 3.2vh, 1.9rem);
            line-height: 1.15;
            color: var(--sg-black);
            padding-top: 0.15rem;
        }

        .calendar-year {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #6b7280;
            padding-bottom: 0.3rem;
        }

        .launch-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--sg-red-darker);
            line-height: 1.45;
        }

        .launch-date {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(1.25rem, min(2.4vw, 3.6vh), 1.75rem);
            line-height: 1.25;
            color: var(--sg-red-darker);
        }

        .apology {
            font-size: 0.9rem;
            color: var(--text-secondary);
            max-width: 34rem;
            margin: 0 auto clamp(0.6rem, 1.8vh, 1.25rem);
        }

        .signoff {
            display: inline-block;
            padding-top: 0.6rem;
            border-top: 2px solid var(--sg-red);
        }

        .signoff strong {
            display: block;
            font-size: 0.95rem;
            color: var(--sg-black);
        }

        .signoff span {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .footer-bottom {
            background: var(--sg-red-darker);
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.75rem;
            padding: 0.8rem 1.5rem;
            text-align: center;
        }

        /* One entrance, the same slide-in the navbar brand uses. */
        @keyframes brandSlideIn {
            from {
                opacity: 0;
                transform: translateX(-24px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .logo-box,
        .brand-line-top,
        .brand-line-btm,
        .brand-tagline {
            opacity: 0;
            animation: brandSlideIn 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .brand-line-top {
            animation-delay: 0.1s;
        }

        .brand-line-btm {
            animation-delay: 0.2s;
        }

        .brand-tagline {
            animation-delay: 0.3s;
        }

        @media (prefers-reduced-motion: reduce) {
            .logo-box,
            .brand-line-top,
            .brand-line-btm,
            .brand-tagline {
                opacity: 1;
                animation: none;
            }

            .gear {
                animation: none;
            }
        }

        /* Short laptop screens (e.g. 1280x720): a smaller illustration keeps everything on one screen. */
        @media (min-width: 1001px) and (max-height: 660px) {
            .illustration {
                height: 15vh;
            }
        }

        /* Tablets and phones: brand becomes a band on top, card below. */
        @media (max-width: 1000px) {
            :root {
                --logo: clamp(64px, 10vh, 96px);
            }

            .closed {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }

            .brand-panel::before {
                clip-path: polygon(0 0, 100% 0, 100% 80%, 0 100%);
            }

            .brand-panel::after {
                clip-path: polygon(0 0, 100% 0, 100% calc(80% - 12px), 0 calc(100% - 12px));
            }

            .brand-lockup {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
                padding: 1.1rem 1.25rem 2.6rem;
            }

            .logo-box {
                border-width: 3px;
                margin-bottom: 0;
                flex-shrink: 0;
            }

            .brand-line-top {
                font-size: 0.85rem;
                letter-spacing: 3px;
            }

            .brand-line-btm {
                font-size: clamp(1.75rem, 7vw, 2.6rem);
            }

            .brand-tagline {
                display: none;
            }

            .message {
                padding: 0 0.85rem 1.25rem;
                align-items: flex-start;
            }

            .card {
                padding: 1.1rem 1.1rem 1.4rem;
                border-radius: 16px;
            }

            .illustration {
                height: clamp(80px, 14vh, 170px);
            }

            .card h1 {
                font-size: clamp(1.15rem, 4.8vw, 1.6rem);
            }

            .launch-date {
                font-size: clamp(1.2rem, 5vw, 1.6rem);
            }
        }

        /* Phones: the date text already says it all, so the calendar tile gives its room back. */
        @media (max-width: 480px) {
            .calendar {
                display: none;
            }

            .launch {
                padding: 0.75rem 1rem;
            }

            .launch-label,
            .apology {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <main class="closed">
        <div class="brand-panel">
            <div class="brand-lockup">
                <div class="logo-box">
                    <img src="{{ asset('images/Jata_Negeri_Selangor_2025.png') }}" alt="Jata Negeri Selangor">
                </div>
                <div>
                    <span class="brand-line-top">Perolehan</span>
                    <span class="brand-line-btm">Selangor</span>
                </div>
                <p class="brand-tagline">Sistem Tender Online Selangor</p>
            </div>
        </div>

        <section class="message">
            <div class="card">
                {{-- Maintenance worker fixing the system, drawn in the Selangor red and yellow. --}}
                <svg class="illustration" viewBox="0 0 440 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <pattern id="hazard" width="16" height="16" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                            <rect width="8" height="16" fill="#ffcc00" />
                            <rect x="8" width="8" height="16" fill="#1f2937" />
                        </pattern>
                    </defs>

                    <ellipse cx="225" cy="284" rx="185" ry="10" fill="#eceef2" />
                    <circle cx="235" cy="148" r="122" fill="#fdecef" />

                    <g class="gear">
                        <circle cx="96" cy="86" r="34" fill="#ffcc00" />
                        <circle cx="96" cy="86" r="41" fill="none" stroke="#ffcc00" stroke-width="12" stroke-dasharray="11 10.5" />
                        <circle cx="96" cy="86" r="12" fill="#fff" />
                    </g>
                    <g class="gear gear--reverse">
                        <circle cx="58" cy="150" r="20" fill="#9ca3af" />
                        <circle cx="58" cy="150" r="25" fill="none" stroke="#9ca3af" stroke-width="9" stroke-dasharray="7.5 7.2" />
                        <circle cx="58" cy="150" r="7" fill="#fff" />
                    </g>

                    <rect x="148" y="92" width="176" height="122" rx="12" fill="#1f2937" />
                    <rect x="159" y="103" width="154" height="88" rx="4" fill="#c41e3a" />
                    <rect x="159" y="177" width="154" height="14" fill="url(#hazard)" />
                    <g stroke="#fff" stroke-width="7" stroke-linecap="round" fill="none">
                        <path d="M218 166 l36 -36" />
                        <path d="M218 130 l36 36" />
                    </g>
                    <circle cx="258" cy="126" r="9" fill="none" stroke="#fff" stroke-width="5" />
                    <circle cx="214" cy="126" r="5" fill="#fff" />
                    <rect x="222" y="214" width="28" height="22" fill="#374151" />
                    <rect x="194" y="234" width="84" height="9" rx="4.5" fill="#4b5563" />

                    <g>
                        <polygon points="104,280 140,280 129,226 115,226" fill="#c41e3a" />
                        <rect x="111" y="242" width="22" height="7" fill="#fff" />
                        <rect x="108" y="258" width="28" height="7" fill="#fff" />
                        <rect x="96" y="276" width="52" height="8" rx="3" fill="#8b1428" />
                    </g>

                    <g>
                        <rect x="342" y="204" width="17" height="68" rx="7" fill="#1f2937" />
                        <rect x="364" y="204" width="17" height="68" rx="7" fill="#1f2937" />
                        <rect x="335" y="266" width="28" height="13" rx="6.5" fill="#111827" />
                        <rect x="360" y="266" width="28" height="13" rx="6.5" fill="#111827" />

                        <path d="M384 142 L398 194" stroke="#c41e3a" stroke-width="15" stroke-linecap="round" />
                        <circle cx="399" cy="198" r="8.5" fill="#f1c27d" />

                        <rect x="333" y="128" width="56" height="84" rx="15" fill="#c41e3a" />
                        <rect x="335" y="132" width="21" height="78" rx="9" fill="#ffcc00" />
                        <rect x="366" y="132" width="21" height="78" rx="9" fill="#ffcc00" />
                        <rect x="333" y="178" width="56" height="7" fill="#e5e7eb" />

                        <path d="M340 142 L314 112" stroke="#c41e3a" stroke-width="15" stroke-linecap="round" />
                        <circle cx="311" cy="108" r="8.5" fill="#f1c27d" />
                        <path d="M306 103 L286 83" stroke="#6b7280" stroke-width="7" stroke-linecap="round" />
                        <path d="M287 70 a11 11 0 1 0 12 13 l-7 -1 l-4 -5 z" fill="#6b7280" />

                        <rect x="354" y="114" width="14" height="16" rx="4" fill="#e8b571" />
                        <circle cx="361" cy="98" r="21" fill="#f1c27d" />
                        <circle cx="354" cy="101" r="2.3" fill="#1f2937" />
                        <circle cx="368" cy="101" r="2.3" fill="#1f2937" />
                        <path d="M355 109 q6 5 12 0" stroke="#1f2937" stroke-width="2.2" fill="none" stroke-linecap="round" />
                        <path d="M338 92 a23 23 0 0 1 46 0 z" fill="#ffcc00" />
                        <rect x="333" y="89" width="56" height="8" rx="4" fill="#f5b800" />
                        <rect x="358" y="70" width="6" height="20" rx="3" fill="#f5b800" />
                    </g>
                </svg>

                <span class="phase-tag">Fasa Peralihan STOS 3.0</span>
                <h1>Sistem sedang dalam fasa peralihan</h1>
                <p class="lead">
                    Sistem Tender Online Selangor (STOS) sedang ditutup sementara bagi memberi laluan kepada proses
                    peralihan ke sistem baharu.
                </p>

                <div class="launch">
                    <div class="calendar" aria-hidden="true">
                        <span class="calendar-month">NOV</span>
                        <span class="calendar-day">16</span>
                        <span class="calendar-year">2026</span>
                    </div>
                    <div>
                        <p class="launch-label">Sistem Tender Online Selangor yang baharu dijadualkan mula beroperasi pada</p>
                        <p class="launch-date">16 November 2026</p>
                    </div>
                </div>

                <p class="apology">
                    Kami memohon maaf atas segala kesulitan dan menghargai kerjasama serta perhatian pihak tuan/puan.
                </p>
                <div class="signoff">
                    <strong>Bahagian Pengurusan Maklumat</strong>
                    <span>Pejabat Setiausaha Kerajaan Negeri Selangor</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer-bottom">
        &copy; {{ date('Y') }} Setiausaha Kerajaan Negeri Selangor. Hak Cipta Terpelihara.
    </footer>
</body>

</html>
