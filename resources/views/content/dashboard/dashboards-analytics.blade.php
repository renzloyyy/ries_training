@extends('layouts/blankLayout')

@section('title', 'Research Analytics')

{{--
    Confirmed working against this project's actual layout file:
    resources/views/layouts/blankLayout.blade.php (no sidebar/navbar
    partials included, so this page renders with zero menu chrome).
--}}

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('page-style')
    <style>
        /* =================================================================
                                                                   Research Analytics — console design tokens
                                                                   ------------------------------------------------------------
                                                                   Direction: dark, instrument-panel feel for a research office's
                                                                   internal pipeline tool. True near-black (not navy-tinted), warm
                                                                   off-white text (not pure #fff), and color spent on exactly two
                                                                   things that mean something in this data: completed (sage) and
                                                                   pending (amber). A monospace face is reserved for every
                                                                   label/readout/axis — the one typographic move that signals
                                                                   "instrument" rather than "dark-mode admin template".
                                                                ================================================================= */
        :root {
            --primary: #0B1F5C;
            --secondary: #1E3FA8;
            --success: #2E7D32;
            --info: #AFC8F3;
            --warning: #ffc107;
            --danger: #e74c3c;
            --light: #F4F6FA;
            --dark: #071240;
            --gray: #000000;
            --white: #ffffff;
            --muted: #5a6478;

            --forest: #0B1F5C;
            --emerald: #2E7D32;
            --sea: #1E3FA8;
            --gold: #ffc107;
            --coral: #AFC8F3;
            --cream: #F4F6FA;
            --ink: #071240;

            /* Dashboard tokens are mapped to the shared palette so the
                                           existing overview, proposal, publication, and funding styles
                                           inherit the new theme without rewriting every component rule. */
            --ra-bg: var(--light);
            --ra-panel: var(--white);
            --ra-panel-2: #f8faff;
            --ra-line: #dbe4fb;
            --ra-line-soft: #edf2ff;
            --ra-text: var(--dark);
            --ra-text-dim: var(--primary);
            --ra-text-faint: var(--muted);
            --ra-approved: var(--secondary);
            --ra-approved-dim: rgba(30, 63, 168, 0.12);
            --ra-pending: var(--info);
            --ra-pending-dim: rgba(175, 200, 243, 0.26);
            --ra-danger: var(--danger);
            --ra-serif: 'Lora', 'Georgia', serif;
            --ra-mono: 'JetBrains Mono', 'IBM Plex Mono', ui-monospace, 'SF Mono', Menlo, Consolas, monospace;
        }

        .ra-page[data-theme="light"] {
            --ra-bg: var(--light);
            --ra-panel: var(--white);
            --ra-panel-2: #f8faff;
            --ra-line: #dbe4fb;
            --ra-line-soft: #edf2ff;
            --ra-text: var(--dark);
            --ra-text-dim: var(--primary);
            --ra-text-faint: var(--muted);
            --ra-approved: var(--secondary);
            --ra-approved-dim: rgba(30, 63, 168, 0.12);
            --ra-pending: var(--info);
            --ra-pending-dim: rgba(175, 200, 243, 0.26);
            --ra-danger: var(--danger);
        }

        @font-face {
            font-family: 'Lora';
            src: local('Lora');
        }

        @font-face {
            font-family: 'JetBrains Mono';
            src: local('JetBrains Mono');
        }

        .ra-page {
            background-color: var(--ra-bg);
            background-image:
                radial-gradient(circle at 12% 0%, rgba(30, 63, 168, 0.08), transparent 38%),
                radial-gradient(circle at 88% 12%, rgba(175, 200, 243, 0.18), transparent 42%);
            color: var(--ra-text);
            min-height: 100vh;
            padding: .9rem clamp(1rem, 3vw, 2.5rem) 3rem;
        }

        .ra-page,
        .ra-page * {
            box-sizing: border-box;
        }

        .ra-dashboard-panel {
            display: none;
        }

        .ra-dashboard-panel.is-active {
            display: block;
        }

        .ra-topnav {
            position: sticky;
            top: 0;
            z-index: 24;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .55rem .95rem;
            margin-bottom: 1.15rem;
            border: 1px solid var(--ra-line);
            border-radius: .95rem;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(14px);
            box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .05);
        }

        .ra-topnav-brand {
            display: flex;
            align-items: center;
            gap: .55rem;
            min-width: 0;
        }

        .ra-topnav-mark {
            width: 18rem;
            height: 7rem;
            display: grid;
            place-items: center;
            border-radius: 1rem;
            background: #FFF;
            overflow: hidden;
            flex: 0 0 auto;
        }

        .ra-topnav-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .ra-topnav-copy {
            min-width: 0;
        }

        .ra-topnav-title {
            margin: 0;
            font-family: var(--ra-serif);
            font-size: 1rem;
            font-weight: 600;
            color: var(--ra-text);
            line-height: 1.1;
        }

        .ra-topnav-subtitle {
            margin-top: .18rem;
            font-family: var(--ra-mono);
            font-size: .68rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ra-text-faint);
        }

        .ra-topnav-links {
            display: flex;
            align-items: center;
            gap: .55rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .ra-topnav-link {
            padding: .7rem 1rem;
            border: 1px solid transparent;
            border-radius: 999px;
            background: transparent;
            color: var(--ra-text-dim);
            font-family: var(--ra-mono);
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: .02em;
            cursor: pointer;
            transition: color .2s ease, background-color .2s ease, border-color .2s ease;
        }

        .ra-topnav-link:hover,
        .ra-topnav-link:focus,
        .ra-topnav-link.is-active {
            color: var(--ra-text);
            background: rgba(11, 31, 92, .08);
            border-color: rgba(11, 31, 92, .12);
            outline: none;
        }

        /* ---------- Header ---------- */
        .ra-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding-bottom: 1.4rem;
            margin-bottom: 1.6rem;
            border-bottom: 1px solid var(--ra-line);
        }

        .ra-eyebrow {
            font-family: var(--ra-mono);
            /* Increase the executive header metadata so it remains readable
                   alongside the larger dashboard title. */
            font-size: .82rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--ra-text-faint);
            margin-bottom: .55rem;
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .ra-eyebrow::before {
            content: '';
            width: 14px;
            height: 1px;
            background: var(--ra-text-faint);
            display: inline-block;
        }

        .ra-title {
            font-family: var(--ra-serif);
            /* Scale the main dashboard heading up without changing the layout
                   container widths. */
            font-size: clamp(2.2rem, 3.2vw, 2.85rem);
            font-weight: 600;
            color: var(--ra-text);
            margin: 0;
            line-height: 1.1;
            letter-spacing: -.01em;
        }

        .ra-subtitle {
            color: var(--ra-text-dim);
            /* Match the subtitle to the larger hero header treatment. */
            font-size: clamp(1.15rem, 1.9vw, 1.45rem);
            margin-top: .5rem;
            max-width: 46ch;
        }

        .ra-asof {
            font-family: var(--ra-mono);
            /* Slightly enlarge the update-status text for better legibility. */
            font-size: .9rem;
            color: var(--ra-text-dim);
            display: flex;
            align-items: center;
            gap: .55rem;
            letter-spacing: .02em;
        }

        .ra-toolbar {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        @media (max-width: 767.98px) {
            .ra-topnav {
                align-items: flex-start;
                flex-direction: column;
            }

            .ra-topnav-links {
                width: 100%;
                justify-content: flex-start;
            }

            .ra-toolbar {
                width: 100%;
                justify-content: flex-start;
            }
        }

        .ra-filter {
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }

        .ra-filter-label {
            font-family: var(--ra-mono);
            /* Slightly enlarge filter labels so controls are easier to scan. */
            font-size: .92rem;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--ra-text-faint);
        }

        .ra-select {
            min-width: 140px;
            background: var(--ra-panel-2);
            border: 1px solid var(--ra-line);
            color: var(--ra-text);
            border-radius: .35rem;
            padding: .55rem .75rem;
            font-family: var(--ra-mono);
            /* Enlarge dropdown text to match the updated dashboard scale. */
            font-size: 1.05rem;
        }

        .ra-select:focus {
            outline: none;
            border-color: var(--ra-approved);
            box-shadow: 0 0 0 2px rgba(127, 179, 154, .25);
        }

        .ra-theme-toggle {
            width: 2.35rem;
            height: 2.35rem;
            background: var(--ra-panel-2);
            border: 1px solid var(--ra-line);
            color: var(--ra-text);
            border-radius: 999px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--ra-mono);
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color .2s ease, color .2s ease, background-color .2s ease;
        }

        .ra-theme-toggle svg {
            width: 1rem;
            height: 1rem;
            stroke: currentColor;
        }

        .ra-theme-toggle:hover {
            border-color: var(--ra-approved);
            color: var(--ra-approved);
        }

        .ra-theme-toggle:focus {
            outline: none;
            border-color: var(--ra-approved);
            box-shadow: 0 0 0 2px rgba(127, 179, 154, .25);
        }

        .ra-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--ra-approved);
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(127, 179, 154, .55), 0 0 6px 1px rgba(127, 179, 154, .55);
            animation: ra-pulse 2.6s infinite;
        }

        @keyframes ra-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(127, 179, 154, .45), 0 0 6px 1px rgba(127, 179, 154, .45);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(127, 179, 154, 0), 0 0 6px 1px rgba(127, 179, 154, .3);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(127, 179, 154, 0), 0 0 6px 1px rgba(127, 179, 154, 0);
            }
        }

        /* ---------- KPI strip ---------- */
        .ra-kpi-card {
            border: 1px solid var(--ra-line);
            border-radius: .35rem;
            background: linear-gradient(160deg, var(--ra-panel-2), var(--ra-panel));
            padding: 1.1rem 1.25rem;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .ra-kpi-card::after {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(127, 179, 154, .5), transparent 60%);
        }

        /* Status-specific top-border accents */
        .ra-kpi-card.is-completed::after {
            background: linear-gradient(90deg, var(--ra-approved), transparent 60%);
        }

        .ra-kpi-card.is-pending::after {
            background: linear-gradient(90deg, var(--ra-pending), transparent 60%);
        }

        .ra-kpi-card.is-inprogress::after {
            background: linear-gradient(90deg, #6E84A8, transparent 60%);
        }

        .ra-kpi-label {
            font-family: var(--ra-serif);
            /* Match proposal KPI headings to the overview hero-card title treatment. */
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0;
            text-transform: none;
            /* Keep the proposal KPI heading color aligned with overview titles. */
            color: var(--ra-text);
            margin-bottom: .55rem;
        }

        .ra-kpi-value {
            font-family: var(--ra-serif);
            /* Match proposal KPI numbers to the overview hero-card value style. */
            font-size: 2.9rem;
            font-weight: 700;
            color: var(--ra-approved);
            line-height: 1;
            letter-spacing: -.03em;
        }

        .ra-kpi-value.is-pending-val {
            /* Keep pending totals on the same primary value color as the other KPI cards. */
            color: var(--ra-approved);
        }

        .ra-kpi-value.is-inprogress-val {
            /* Keep in-progress totals on the same primary value color as the other KPI cards. */
            color: var(--ra-approved);
        }

        .ra-kpi-foot {
            font-family: var(--ra-sans, 'Instrument Sans', 'Segoe UI', sans-serif);
            /* Match proposal KPI support text to the overview card support copy. */
            font-size: 1.2rem;
            line-height: 1.4;
            /* Keep support text color aligned with the overview cards. */
            color: var(--ra-text-dim);
            margin-top: .6rem;
        }

        .ra-overview-card {
            position: relative;
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--ra-line);
            border-radius: .8rem;
            background: #FFFDF9;
            padding: 1.3rem 1.4rem 1.25rem;
            box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .06);
        }

        .ra-overview-icon {
            width: 4.1rem;
            height: 4.1rem;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: linear-gradient(180deg, #1E4ED8, #153CA9);
            color: #FFFFFF;
            margin-bottom: 1rem;
        }

        .ra-overview-icon svg,
        .ra-overview-watermark svg {
            width: 2rem;
            height: 2rem;
            stroke: currentColor;
        }

        .ra-overview-label {
            position: relative;
            z-index: 1;
            font-family: var(--ra-sans, 'Instrument Sans', 'Segoe UI', sans-serif);
            /* Align overview KPI labels with the shared dashboard label scale. */
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.35;
            color: var(--ra-text);
            margin-bottom: .9rem;
        }

        .ra-overview-value {
            position: relative;
            z-index: 1;
            font-family: var(--ra-serif);
            /* Keep overview KPI values aligned with the shared KPI number scale. */
            font-size: 2.9rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -.03em;
            color: var(--ra-approved);
        }

        .ra-overview-foot {
            position: relative;
            z-index: 1;
            margin-top: .85rem;
            font-family: var(--ra-sans, 'Instrument Sans', 'Segoe UI', sans-serif);
            /* Use the same readable support size across all overview KPI cards. */
            font-size: 1.5rem;
            color: var(--ra-text-dim);
        }

        .ra-overview-watermark {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            color: rgba(30, 78, 216, .16);
        }

        .ra-overview-watermark svg {
            width: 4.3rem;
            height: 4.3rem;
        }

        .ra-overview-trend-card {
            border-radius: .95rem;
            background: #FFFDF9;
            box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .05);
        }

        .ra-overview-trend-head {
            align-items: center;
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: .75rem;
        }

        .ra-story-block {
            margin-bottom: 1rem;
        }

        .ra-story-label {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: .7rem;
            font-family: var(--ra-mono);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ra-text);
        }

        .ra-story-index {
            width: 1.25rem;
            height: 1.25rem;
            display: inline-grid;
            place-items: center;
            border-radius: 999px;
            background: #173A9B;
            color: #FFF;
            font-size: .68rem;
        }

        .ra-story-kpi {
            display: grid;
            grid-template-columns: 68px 1fr;
            gap: 1rem;
            height: 100%;
            padding: 1.15rem 1.2rem;
            border: 1px solid var(--ra-line);
            border-radius: .95rem;
            background: #FFFDF9;
            box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .05);
        }

        .ra-story-kpi-icon,
        .ra-story-mini-icon,
        .ra-overview-insight-icon {
            display: grid;
            place-items: center;
            border-radius: 999px;
            color: #1E4ED8;
            background: rgba(30, 78, 216, .1);
        }

        .ra-story-kpi-icon {
            width: 3.9rem;
            height: 3.9rem;
        }

        .ra-story-kpi-icon-solid {
            background: linear-gradient(180deg, #1E4ED8, #153CA9);
            color: #FFF;
        }

        .ra-story-kpi-icon svg,
        .ra-story-mini-icon svg,
        .ra-overview-insight-icon svg {
            width: 1.9rem;
            height: 1.9rem;
            stroke: currentColor;
        }

        .ra-story-kpi-title,
        .ra-story-conversion-title,
        .ra-overview-insight-label {
            font-family: var(--ra-serif);
            /* Apply the requested KPI label size without affecting non-KPI headings. */
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--ra-text);
        }

        .ra-story-kpi-value,
        .ra-story-conversion-value,
        .ra-overview-insight-value {
            font-family: var(--ra-serif);
            /* Keep hero KPI values consistent with the main dashboard KPI values. */
            font-size: 2.9rem;
            font-weight: 700;
            letter-spacing: -.03em;
            line-height: 1;
            color: var(--ra-approved);
            margin: .55rem 0;
        }

        .ra-story-kpi-copy,
        .ra-story-conversion-copy,
        .ra-overview-insight-copy {
            /* Apply the requested KPI foot text size anywhere the shared KPI copy class is used. */
            font-size: 1.2rem;
            line-height: 1.4;
            color: var(--ra-text-dim);
        }

        .ra-story-link {
            margin-top: auto;
            padding: .95rem 0 0;
            border: none;
            background: transparent;
            color: var(--ra-approved);
            font-family: var(--ra-mono);
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .ra-story-conversion-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            min-height: 100%;
        }

        .ra-story-conversion-item+.ra-story-conversion-item {
            border-left: 1px dashed var(--ra-line);
        }

        .ra-story-mini-icon,
        .ra-overview-insight-icon {
            width: 3.4rem;
            height: 3.4rem;
            flex: 0 0 auto;
        }

        .ra-overview-story-panel,
        .ra-overview-insight {
            border-radius: .95rem;
            background: #FFFDF9;
            box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .04);
        }

        .ra-story-bar-row {
            margin-bottom: .85rem;
        }

        .ra-story-bar-row:last-child {
            margin-bottom: 0;
        }

        .ra-story-bar-meta {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .3rem;
        }

        .ra-story-bar-name,
        .ra-overview-legend-name {
            color: var(--ra-text-dim);
            font-size: 1.1rem;
        }

        .ra-story-bar-value,
        .ra-overview-legend-share {
            color: var(--ra-text);
            font-family: var(--ra-mono);
            font-size: 1.1rem;
        }

        .ra-story-bar-track {
            height: 8px;
            overflow: hidden;
            border-radius: 999px;
            background: #E6EEFF;
        }

        .ra-story-bar-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #173A9B, #2D6AF6);
        }

        .ra-story-bar-fill.is-soft {
            background: linear-gradient(90deg, #4E7CF0, #9ABBFF);
        }

        .ra-overview-legend {
            display: grid;
            gap: .45rem;
        }

        .ra-overview-donut-wrap {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 180px;
            gap: 1rem;
            align-items: center;
        }

        .ra-overview-legend-row {
            display: grid;
            grid-template-columns: 10px 1fr auto;
            align-items: center;
            gap: .55rem;
        }

        .ra-overview-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
        }

        @media (max-width: 991.98px) {
            .ra-overview-donut-wrap {
                grid-template-columns: 1fr;
            }

            .ra-overview-legend {
                margin-top: .8rem;
            }
        }

        .ra-overview-insight {
            padding: 1rem 1.1rem;
            border: 1px solid var(--ra-line);
            height: 100%;
        }

        .ra-overview-insight-value {
            font-size: 1.55rem;
        }

        .ra-overview-insight-alert .ra-overview-insight-value {
            color: #D14343;
        }

        .ra-overview-insight-icon-green {
            color: #2D8B57;
            background: rgba(45, 139, 87, .12);
        }

        .ra-overview-insight-icon-amber {
            color: #BA7A11;
            background: rgba(186, 122, 17, .12);
        }

        @media (max-width: 991.98px) {
            .ra-story-conversion {
                grid-template-columns: 1fr;
            }

            .ra-story-conversion-item+.ra-story-conversion-item {
                border-left: none;
                border-top: 1px dashed var(--ra-line);
            }
        }

        .ra-skel {
            display: inline-block;
            width: 64px;
            height: 1.5rem;
            border-radius: .25rem;
            background: linear-gradient(90deg, #1a2029 25%, #232b37 37%, #1a2029 63%);
            background-size: 400% 100%;
            animation: ra-shimmer 1.4s ease infinite;
        }

        @keyframes ra-shimmer {
            0% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0 50%;
            }
        }

        /* ---------- Panels ---------- */
        .ra-card {
            border: 1px solid var(--ra-line);
            border-radius: .35rem;
            background: var(--ra-panel);
        }

        .ra-card-head {
            padding: 1.15rem 1.3rem .9rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            border-bottom: 1px solid var(--ra-line-soft);
            margin-bottom: .15rem;
        }

        .ra-card-title {
            font-family: var(--ra-serif);
            /* Reduce shared panel titles so they fit cleanly above charts across all blades. */
            font-size: 1.8rem;
            line-height: 1.15;
            font-weight: 600;
            color: var(--ra-text);
            margin: 0;
        }

        .ra-card-sub {
            font-family: var(--ra-mono);
            /* Keep shared panel subtitles readable while leaving more room for chart content. */
            font-size: 1rem;
            line-height: 1.35;
            color: var(--ra-text-faint);
            margin-top: .3rem;
            letter-spacing: .01em;
        }

        /* ---------- Signature element: signal bars ---------- */
        .ra-funnel-row {
            display: grid;
            /* Give the campus-name column more room so labels can show in full. */
            grid-template-columns: 240px 1fr 72px;
            align-items: center;
            gap: .9rem;
            padding: .62rem 0;
            border-bottom: 1px solid var(--ra-line-soft);
        }

        .ra-funnel-row:last-child {
            border-bottom: none;
        }

        .ra-funnel-name {
            font-family: var(--ra-mono);
            /* Enlarge campus labels so each row is easier to scan. */
            font-size: 1rem;
            letter-spacing: .01em;
            color: var(--ra-text-dim);
            /* Allow full campus names to display instead of collapsing to ellipses. */
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            line-height: 1.25;
        }

        .ra-funnel-track {
            position: relative;
            height: 6px;
            border-radius: 1px;
            background: var(--ra-line);
            overflow: hidden;
        }

        .ra-funnel-fill {
            position: absolute;
            inset: 0;
            width: 0%;
            background: linear-gradient(90deg, var(--ra-approved), #98c4ad);
            box-shadow: 0 0 8px 0 rgba(127, 179, 154, .45);
            transition: width 1s cubic-bezier(.22, .9, .32, 1);
        }

        .ra-funnel-pct {
            font-family: var(--ra-mono);
            /* Enlarge approval-rate values to match the campus label emphasis. */
            font-size: 1rem;
            font-weight: 500;
            color: var(--ra-approved);
            text-align: right;
        }

        .ra-legend {
            display: flex;
            gap: 1.2rem;
            font-family: var(--ra-mono);
            /* Enlarge legend text so the approved/pending key stays readable. */
            font-size: .95rem;
            color: var(--ra-text-faint);
            padding: .9rem 1.3rem 1.2rem;
        }

        .ra-legend span {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .ra-legend i {
            width: 7px;
            height: 7px;
            border-radius: 1px;
            display: inline-block;
        }

        /* ---------- Tables ---------- */
        .ra-table thead th {
            font-family: var(--ra-mono);
            font-size: .66rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ra-text-faint);
            font-weight: 500;
            border-bottom: 1px solid var(--ra-line);
            padding-bottom: .7rem;
        }

        .ra-table tbody td {
            font-size: .84rem;
            color: var(--ra-text-dim);
            padding: .6rem 0;
            border-bottom: 1px solid var(--ra-line-soft);
        }

        .ra-table tbody tr:hover td {
            color: var(--ra-text);
        }

        .ra-table thead th.ra-metric-head {
            white-space: nowrap;
        }

        .ra-table thead th.ra-metric-head .ra-head-label {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .ra-table thead th.ra-metric-head .ra-head-dot {
            width: .75rem;
            height: .75rem;
            border-radius: 999px;
            border: 1px solid currentColor;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .5rem;
            line-height: 1;
            opacity: .9;
        }

        .ra-table thead th.ra-head-total {
            color: var(--ra-approved);
        }

        .ra-table thead th.ra-head-completed {
            color: #8EBB99;
        }

        .ra-table .ra-campus-summary td {
            padding-top: 1rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid rgba(127, 179, 154, .14);
            background: rgba(127, 179, 154, .04);
        }

        .ra-table .ra-campus-header td {
            padding-top: 1rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid rgba(127, 179, 154, .14);
            background: rgba(127, 179, 154, .04);
        }

        .ra-campus-name {
            font-family: var(--ra-serif);
            font-size: 1rem;
            font-weight: 600;
            color: var(--ra-text);
        }

        .ra-campus-meta {
            font-family: var(--ra-mono);
            font-size: .68rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--ra-text-faint);
        }

        .ra-campus-total {
            font-family: var(--ra-mono);
            font-size: .78rem;
            color: var(--ra-approved);
            text-align: right;
        }

        .ra-program-row td:first-child {
            color: var(--ra-text-faint);
            font-family: var(--ra-mono);
            font-size: .72rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        #chartAgenda .apexcharts-toolbar {
            display: none !important;
        }

        .ra-program-name {
            color: var(--ra-text);
            font-weight: 500;
            text-align: left;
        }

        .ra-metric {
            font-family: var(--ra-mono);
            text-align: right;
            white-space: nowrap;
        }

        .ra-metric-total {
            color: var(--ra-text);
        }

        .ra-metric-completed {
            color: #9BC8AB;
        }

        .ra-empty {
            text-align: center;
            padding: 2.4rem 1rem;
            color: var(--ra-text-faint);
            font-family: var(--ra-mono);
            font-size: .78rem;
        }

        .ra-error {
            background: rgba(194, 112, 94, .08);
            border: 1px solid rgba(194, 112, 94, .3);
            color: var(--ra-danger);
            border-radius: .35rem;
            padding: .7rem 1rem;
            font-family: var(--ra-mono);
            font-size: .78rem;
            margin-bottom: 1.3rem;
        }

        .ra-search {
            background: var(--ra-panel-2) !important;
            border: 1px solid var(--ra-line) !important;
            color: var(--ra-text) !important;
            font-family: var(--ra-mono);
            font-size: .78rem !important;
        }

        .ra-search::placeholder {
            color: var(--ra-text-faint);
        }

        .ra-search:focus {
            box-shadow: 0 0 0 2px rgba(127, 179, 154, .25) !important;
            border-color: var(--ra-approved) !important;
        }

        .ra-grid {
            display: grid;
            gap: .9rem;
        }

        .ra-tabs {
            display: flex;
            flex-direction: column;
            gap: .4rem;
            position: fixed;
            top: 1.25rem;
            left: 1rem;
            bottom: 1.25rem;
            width: 13.5rem;
            z-index: 30;
            padding: 1.35rem 1rem;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: .45rem;
            background:
                linear-gradient(180deg, rgba(5, 38, 68, .98), rgba(1, 18, 37, .98)),
                radial-gradient(circle at 50% 100%, rgba(39, 129, 191, .22), transparent 45%);
            box-shadow: 0 1rem 2.2rem rgba(0, 0, 0, .18);
            color: #EAF4FF;
            overflow: hidden;
        }

        .ra-tabs::after {
            content: '';
            position: absolute;
            inset: auto -.5rem -2rem -.5rem;
            height: 11rem;
            background:
                linear-gradient(135deg, transparent 49%, rgba(65, 155, 220, .26) 50%, transparent 51%),
                linear-gradient(45deg, transparent 49%, rgba(65, 155, 220, .16) 50%, transparent 51%);
            background-size: 2.2rem 2.2rem;
            opacity: .55;
            pointer-events: none;
        }

        .ra-tab {
            width: 100%;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: .7rem;
            background: transparent;
            border: 1px solid transparent;
            color: rgba(234, 244, 255, .78);
            border-radius: .35rem;
            padding: .72rem .75rem;
            font-family: var(--ra-mono);
            font-size: .74rem;
            text-align: left;
            cursor: pointer;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .ra-tab-icon {
            width: 1.05rem;
            height: 1.05rem;
            flex: 0 0 auto;
            stroke: currentColor;
        }

        .ra-tab:hover,
        .ra-tab.is-active {
            background: rgba(255, 255, 255, .13);
            border-color: rgba(255, 255, 255, .08);
            color: #FFFFFF;
        }

        @media (max-width: 991.98px) {
            .ra-topnav-mark {
                width: 10rem;
                height: 4.4rem;
            }

            .ra-funnel-row {
                grid-template-columns: 96px 1fr 46px;
            }

            .ra-tabs {
                position: static;
                width: 100%;
                min-height: auto;
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ra-page" id="raPage" data-theme="light">

        <nav class="ra-topnav" aria-label="Dashboard sections">
            <div class="ra-topnav-brand">
                <div class="ra-topnav-mark" aria-hidden="true">
                    {{-- Replace the placeholder dashboard icon with the local SLSU
                         logo asset so the header branding matches the university. --}}
                    <img src="{{ asset('images/slsu-logo.jpeg') }}" alt="SLSU logo">
                </div>
                <div class="ra-topnav-copy">
                    {{-- <p class="ra-topnav-title">Executive Dashboard</p>
                    <div class="ra-topnav-subtitle">Research Program</div> --}}
                </div>
            </div>

            <div class="ra-topnav-links">
                <button type="button" class="ra-topnav-link is-active" data-tab="overview">Overview</button>
                <button type="button" class="ra-topnav-link" data-tab="proposals">Proposals</button>
                <button type="button" class="ra-topnav-link" data-tab="publications">Publications</button>
                <button type="button" class="ra-topnav-link" data-tab="fundings">Fundings</button>
            </div>
        </nav>

        <div class="ra-header">
            <div>
                {{-- <div class="ra-eyebrow">Research &amp; Development Office</div> --}}
                <h1 class="ra-title">Executive Dashboard</h1>
                <div class="ra-subtitle">Research Program</div>
            </div>
            <div class="ra-toolbar">
                <div class="ra-filter">
                    <label class="ra-filter-label" for="yearFilter">Filter by year</label>
                    <select id="yearFilter" class="ra-select">
                        <option value="">All years</option>
                    </select>
                </div>
                <div class="ra-asof" style="display: hidden !important">
                    <span class="ra-dot"></span>
                    <span id="raLastUpdated">connecting&hellip;</span>
                </div>
                <button type="button" id="themeToggle" class="ra-theme-toggle" aria-label="Switch to dark mode"
                    title="Switch to dark mode">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12Z"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="2.75" stroke-width="1.8" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="raGlobalError" style="display:none" class="ra-error"></div>

        <section id="overviewDashboard" class="ra-dashboard-panel is-active">
            @include('content.dashboard.partials.overview-dashboard')
        </section>

        <section id="proposalsDashboard" class="ra-dashboard-panel">

            {{-- KPI strip: Total + status breakdown (replaces redundant status chart) --}}
            <div class="row g-3 mb-3">
                {{-- Keep all proposal summary KPIs in a single balanced strip so
                     the header reads as one unit instead of a split two-row block. --}}
                <div class="col-sm-6 col-lg">
                    <div class="ra-kpi-card">
                        <div class="ra-kpi-label">Total proposals</div>
                        <div class="ra-kpi-value" id="proposalKpiTotal"><span class="ra-skel"></span></div>

                    </div>
                </div>
                <div class="col-sm-6 col-lg">
                    <div class="ra-kpi-card is-completed">
                        <div class="ra-kpi-label">Completed</div>
                        <div class="ra-kpi-value" id="proposalKpiCompleted"><span class="ra-skel"></span></div>
                        {{-- Hide raw status codes; the card title already names the metric. --}}
                    </div>
                </div>
                <div class="col-sm-6 col-lg">
                    <div class="ra-kpi-card is-pending">
                        <div class="ra-kpi-label">Pending</div>
                        <div class="ra-kpi-value is-pending-val" id="proposalKpiPending"><span class="ra-skel"></span></div>
                        {{-- Hide raw status codes; the card title already names the metric. --}}
                    </div>
                </div>
                <div class="col-sm-6 col-lg">
                    <div class="ra-kpi-card is-inprogress">
                        <div class="ra-kpi-label">In Progress</div>
                        <div class="ra-kpi-value is-inprogress-val" id="proposalKpiInProgress"><span class="ra-skel"></span>
                        </div>
                        {{-- Hide raw status codes; the card title already names the metric. --}}
                    </div>
                </div>
                <div class="col-sm-6 col-lg">
                    <div class="ra-kpi-card">
                        <div class="ra-kpi-label">Campuses reporting</div>
                        <div class="ra-kpi-value" id="proposalKpiCampuses"><span class="ra-skel"></span></div>
                       
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                {{-- Promote the research-type card to a full-width lead panel so
                     all study types can stay visible before the agenda section. --}}
                <div class="col-12">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Research Type</h2>
                                <div class="ra-card-sub">mix of study types</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartFormat" style="min-height:280px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                {{-- Place the agenda panel under the research-type lead card so
                     the section reads top-to-bottom instead of competing side-by-side. --}}
                <div class="col-12">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Research agenda alignment</h2>
                                <div class="ra-card-sub">proposal counts by institutional agenda</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartAgenda" style="min-height:230px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                {{-- Quarterly trend --}}
                <div class="col-lg-12">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Submissions over time</h2>
                                <div class="ra-card-sub" id="quarterChartRangeLabel">2020-2026 by year and quarter</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartQuarter" style="min-height:260px;"></div>
                        </div>
                    </div>
                </div>
                {{-- Status distribution chart removed — data now surfaced in KPI strip above --}}
            </div>

            <div class="row g-3 mb-3">
                {{-- Put SDG above the campus approval panel so the section reads
                     top-to-bottom instead of competing side-by-side. --}}
                <div class="col-12">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Sustainable Development Goals</h2>
                                <div class="ra-card-sub">proposal alignment by SDG</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartSdg" style="min-height:300px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Put approval below SDG so users read thematic alignment before campus performance. --}}
                <div class="col-12">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Approval rate by campus</h2>
                                <div class="ra-card-sub"></div>
                            </div>
                        </div>
                        <div class="px-3" id="funnelList">
                            <div class="ra-empty">loading campus data&hellip;</div>
                        </div>
                        <div class="ra-legend">
                            <span><i style="background:var(--ra-approved)"></i> Approved</span>
                            <span><i style="background:var(--ra-line); border:1px solid var(--ra-text-faint)"></i>
                                Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Campus x Program detail table --}}
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <div class="ra-card">
                        <div class="ra-card-head pb-2" style="align-items:center;">
                            <div>
                                <h2 class="ra-card-title">Campus &amp; program breakdown</h2>
                                <div class="ra-card-sub">every program, grouped by campus</div>
                            </div>
                            <input type="search" id="programFilter" class="form-control form-control-sm ra-search"
                                style="max-width:220px;" placeholder="filter campus or program&hellip;" />
                        </div>
                        <div class="px-3 pb-3" style="max-height:420px; overflow-y:auto;">
                            <table class="table ra-table mb-0" id="programTable">
                                <thead>
                                    <tr>
                                        <th>Campus</th>
                                        <th>Program</th>
                                        <th class="text-end ra-metric-head ra-head-total">
                                            <span class="ra-head-label"><span class="ra-head-dot">+</span>Total
                                                proposals</span>
                                        </th>
                                        <th class="text-end ra-metric-head ra-head-completed">
                                            <span class="ra-head-label"><span class="ra-head-dot">+</span>Approved
                                                proposals</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="programTableBody">
                                    <tr>
                                        <td colspan="4" class="ra-empty">loading&hellip;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section id="publicationsDashboard" class="ra-dashboard-panel">
            @include('content.dashboard.partials.publications-dashboard')
        </section>

        <section id="fundingsDashboard" class="ra-dashboard-panel">
            @include('content.dashboard.partials.fundings-dashboard')
        </section>

    </div>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
    <script>
        (function() {
            const API_BASE = window.RESEARCH_API_BASE ||
                '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

            const pageEl = document.getElementById('raPage');
            const themeToggleEl = document.getElementById('themeToggle');
            const MONO = "'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace";
            const THEME_STORAGE_KEY = 'research-analytics-theme';
            // Remember the last active dashboard tab so page refreshes reopen
            // the same section instead of resetting the user to Overview.
            const ACTIVE_TAB_STORAGE_KEY = 'research-analytics-active-tab';
            const chartInstances = {};
            let availableYears = [];
            // Limit the shared year dropdown to the latest five reporting years
            // so the top-level filter stays aligned with the current dashboard window.
            const DASHBOARD_YEAR_SELECTION_SPAN = 5;
            let activeYear = '';

            const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
            const fmtPct = (n) => (n === null || n === undefined) ? '—' : `${Number(n).toFixed(1)}%`;
            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            } [c]));

            function showGlobalError(message) {
                const el = document.getElementById('raGlobalError');
                el.textContent = message;
                el.style.display = 'block';
            }

            function cssVar(name) {
                return getComputedStyle(pageEl).getPropertyValue(name).trim();
            }

            function palette() {
                return [
                    cssVar('--ra-approved'),
                    cssVar('--ra-pending'),
                    '#6E84A8',
                    '#A98FC4',
                    cssVar('--ra-approved'),
                    cssVar('--ra-pending')
                ];
            }

            function currentTheme() {
                return pageEl.dataset.theme === 'light' ? 'light' : 'dark';
            }

            function applyTheme(theme) {
                pageEl.dataset.theme = theme;
                localStorage.setItem(THEME_STORAGE_KEY, theme);
                const nextTheme = theme === 'light' ? 'dark' : 'light';
                themeToggleEl.setAttribute('aria-label', `Switch to ${nextTheme} mode`);
                themeToggleEl.setAttribute('title', `Switch to ${nextTheme} mode`);
            }

            function readSavedDashboardTab(panelTargets) {
                // Prefer the URL hash so a copied/refreshed link keeps the same
                // panel, then fall back to the last tab saved in localStorage.
                const hashTab = window.location.hash.replace(/^#/, '');
                if (panelTargets[hashTab]) return hashTab;
                const savedTab = localStorage.getItem(ACTIVE_TAB_STORAGE_KEY);
                return panelTargets[savedTab] ? savedTab : 'overview';
            }

            function baseChartOptions(overrides) {
                const textDim = cssVar('--ra-text-dim');
                const line = cssVar('--ra-line');
                return Object.assign({
                    chart: {
                        fontFamily: 'Public Sans, sans-serif',
                        background: 'transparent',
                        toolbar: {
                            show: false
                        },
                        animations: {
                            easing: 'easeinout',
                            speed: 500
                        },
                    },
                    theme: {
                        mode: currentTheme()
                    },
                    colors: palette(),
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        borderColor: line,
                        strokeDashArray: 3
                    },
                    tooltip: {
                        theme: currentTheme()
                    },
                    legend: {
                        labels: {
                            colors: textDim
                        }
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'none'
                            }
                        },
                        active: {
                            filter: {
                                type: 'none'
                            }
                        },
                    }
                }, overrides);
            }

            function buildDashboardUrl(year) {
                const url = new URL(`${API_BASE}/api/proposals/dashboard`);
                if (year) url.searchParams.set('year', year);
                return url.toString();
            }

            async function fetchDashboard(year = '') {
                const res = await fetch(buildDashboardUrl(year), {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                if (!res.ok) throw new Error(`Analytics service responded with ${res.status}`);
                return res.json();
            }

            function destroyChart(chartKey) {
                if (chartInstances[chartKey]) {
                    chartInstances[chartKey].destroy();
                    delete chartInstances[chartKey];
                }
            }

            function renderChart(chartKey, elementId, options) {
                destroyChart(chartKey);
                const chart = new ApexCharts(document.getElementById(elementId), options);
                chartInstances[chartKey] = chart;
                chart.render();
            }

            function setYearOptions(yearRows) {
                if (availableYears.length) return;
                availableYears = (yearRows || []).map((row) => Number(row.year)).filter(Boolean);
                const visibleYears = availableYears.slice(-DASHBOARD_YEAR_SELECTION_SPAN);
                const select = document.getElementById('yearFilter');
                // Rename the shared default option so the filter language matches
                // the overview reporting window that starts from 2020.
                select.innerHTML = `<option value="">Since 2020</option>`;
                visibleYears.forEach((year) => {
                    const option = document.createElement('option');
                    option.value = String(year);
                    option.textContent = String(year);
                    select.appendChild(option);
                });
            }

            function setTextIfExists(id, value) {
                const element = document.getElementById(id);
                if (element) element.textContent = value;
            }

            // ---------------------------------------------------------------------------
            // KPI strip — Total + Completed / Pending / In Progress from status_distribution
            // ---------------------------------------------------------------------------
            function renderKpis(data) {
                const statusRows = data.status_distribution || [];
                const statusMap = {};
                let total = 0;
                statusRows.forEach((r) => {
                    const count = Number(r.total_proposals || 0);
                    statusMap[r.status_code] = count;
                    total += count;
                });

                setTextIfExists('proposalKpiTotal', fmtInt(total));
                setTextIfExists('proposalKpiCompleted', fmtInt(statusMap['C'] ?? 0));
                setTextIfExists('proposalKpiPending', fmtInt(statusMap['P'] ?? 0));
                setTextIfExists('proposalKpiInProgress', fmtInt(statusMap['PG'] ?? 0));

                const campusRows = data.proposals_by_campus || [];
                setTextIfExists('proposalKpiCampuses', fmtInt(campusRows.length));
            }

            function renderQuarterChart(rows) {
                destroyChart('quarter');
                // Keep the default "all years" quarter chart focused on the
                // current storytelling window while preserving explicit
                // single-year filtering when the user selects a year.
                const displayRows = String(activeYear || '').trim() ?
                    rows :
                    rows.filter((row) => Number(row.year) >= 2020 && Number(row.year) <= 2026);
                const rangeLabel = document.getElementById('quarterChartRangeLabel');
                if (rangeLabel) {
                    rangeLabel.textContent = activeYear ?
                        `${activeYear} by year and quarter` :
                        '2020-2026 by year and quarter';
                }
                if (!displayRows.length) {
                    document.getElementById('chartQuarter').innerHTML =
                        '<div class="ra-empty">no submission history yet</div>';
                    return;
                }
                document.getElementById('chartQuarter').innerHTML = '';
                renderChart('quarter', 'chartQuarter', baseChartOptions({
                    chart: {
                        type: 'area',
                        height: 260
                    },
                    series: [{
                        name: 'Proposals',
                        data: displayRows.map((r) => Number(r.total_proposals || 0))
                    }],
                    xaxis: {
                        categories: displayRows.map((r) => `${r.year} Q${r.quarter}`),
                        tickAmount: 8,
                        labels: {
                            style: {
                                /* Enlarge quarter labels so the timeline reads more clearly. */
                                fontSize: '15px',
                                colors: cssVar('--ra-text-faint'),
                                fontFamily: MONO
                            }
                        },
                        axisBorder: {
                            color: cssVar('--ra-line')
                        },
                        axisTicks: {
                            color: cssVar('--ra-line')
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-faint'),
                                fontFamily: MONO,
                                /* Enlarge the vertical scale labels to match the x-axis text. */
                                fontSize: '15px'
                            }
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: .35,
                            opacityTo: 0,
                            stops: [0, 95, 100]
                        },
                    },
                    colors: [cssVar('--ra-approved')],
                }));
            }

            function renderSdgChart(rows) {
                destroyChart('sdg');
                if (!rows.length) {
                    document.getElementById('chartSdg').innerHTML = '<div class="ra-empty">no SDG data yet</div>';
                    return;
                }
                document.getElementById('chartSdg').innerHTML = '';
                const top = rows.slice(0, 17);
                renderChart('sdg', 'chartSdg', baseChartOptions({
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 2,
                            barHeight: '52%'
                        }
                    },
                    series: [{
                        name: 'Proposals',
                        data: top.map((r) => Number(r.total_proposals || 0))
                    }],
                    xaxis: {
                        categories: top.map((r) => r.sdg_code || r.sdg_name),
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-faint'),
                                fontFamily: MONO,
                                /* Enlarge the horizontal SDG scale labels for better readability. */
                                fontSize: '16px'
                            }
                        },
                        axisBorder: {
                            color: cssVar('--ra-line')
                        },
                        axisTicks: {
                            color: cssVar('--ra-line')
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-dim'),
                                /* Enlarge the SDG category labels on the left side. */
                                fontSize: '16px'
                            }
                        }
                    },
                    colors: [cssVar('--ra-approved')],
                }));
            }

            function renderFormatChart(rows) {
                destroyChart('format');
                if (!rows.length) {
                    document.getElementById('chartFormat').innerHTML = '<div class="ra-empty">no format data yet</div>';
                    return;
                }
                document.getElementById('chartFormat').innerHTML = '';
                // Switch the research-type panel to a ranked horizontal bar so
                // category names stay readable and the user can compare sizes directly.
                renderChart('format', 'chartFormat', baseChartOptions({
                    chart: {
                        type: 'bar',
                        height: Math.max(230, rows.length * 44)
                    },
                    series: [{
                        name: 'Proposals',
                        data: rows.map((r) => Number(r.total_proposals || 0)),
                    }],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 8,
                            barHeight: '55%',
                            distributed: true,
                        }
                    },
                    xaxis: {
                        categories: rows.map((r) => r.research_format_name),
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-dim'),
                                /* Enlarge the horizontal scale labels for better readability. */
                                fontSize: '16px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-dim'),
                                /* Enlarge the research-type category labels on the left. */
                                fontSize: '16px'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            /* Enlarge the proposal totals shown inside each bar. */
                            fontSize: '16px',
                            fontWeight: 700,
                            // Use the existing dashboard text token so value labels
                            // stay visible without relying on an undefined color var.
                            colors: [cssVar('--ra-text')],
                        },
                        offsetX: 8,
                    },
                    legend: {
                        show: false,
                    },
                    // Reuse only theme tokens that actually exist in the dashboard
                    // palette; this avoids invisible bars caused by missing vars.
                    colors: [
                        cssVar('--ra-approved'),
                        cssVar('--ra-pending'),
                        cssVar('--ra-text-dim'),
                        cssVar('--ra-approved-dim'),
                        cssVar('--ra-line'),
                    ],
                }));
            }

            function renderAgendaChart(rows) {
                destroyChart('agenda');
                if (!rows.length) {
                    document.getElementById('chartAgenda').innerHTML = '<div class="ra-empty">no agenda data yet</div>';
                    return;
                }
                document.getElementById('chartAgenda').innerHTML = '';
                // Wrap long agenda labels into short line groups so they stay readable
                // without forcing the chart into extreme diagonal text.
                const wrapAgendaLabel = (label) => {
                    const words = String(label || '').split(/\s+/).filter(Boolean);
                    const lines = [];
                    for (let i = 0; i < words.length; i += 2) {
                        lines.push(words.slice(i, i + 2).join(' '));
                    }
                    return lines;
                };
                renderChart('agenda', 'chartAgenda', baseChartOptions({
                    chart: {
                        type: 'bar',
                        // Increase chart height so wrapped agenda labels have room to breathe.
                        height: 360,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 2,
                            columnWidth: '50%'
                        }
                    },
                    series: [{
                        name: 'Proposals',
                        data: rows.map((r) => Number(r.total_proposals || 0))
                    }],
                    xaxis: {
                        categories: rows.map((r) => r.agenda_label),
                        labels: {
                            rotate: 0,
                            trim: false,
                            hideOverlappingLabels: false,
                            style: {
                                /* Enlarge agenda category labels for easier reading. */
                                fontSize: '14px',
                                colors: cssVar('--ra-text-faint'),
                                fontFamily: MONO
                            },
                            formatter: (value) => wrapAgendaLabel(value),
                        },
                        axisBorder: {
                            color: cssVar('--ra-line')
                        },
                        axisTicks: {
                            color: cssVar('--ra-line')
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: cssVar('--ra-text-faint'),
                                fontFamily: MONO,
                                /* Enlarge the vertical scale labels to match the agenda labels. */
                                fontSize: '15px'
                            }
                        }
                    },
                    colors: ['#6E84A8'],
                }));
            }

            function renderFunnel(rows) {
                const container = document.getElementById('funnelList');
                if (!rows.length) {
                    container.innerHTML = '<div class="ra-empty">no campus data yet</div>';
                    return;
                }
                container.innerHTML = rows.map((r) => `
                    <div class="ra-funnel-row">
                        <div class="ra-funnel-name" title="${escapeHtml(r.campus_name)}">${escapeHtml(r.campus_name)}</div>
                        <div class="ra-funnel-track">
                            <div class="ra-funnel-fill" data-pct="${Number(r.approval_rate || 0)}"></div>
                        </div>
                        <div class="ra-funnel-pct">${fmtPct(r.approval_rate)}</div>
                    </div>
                `).join('');
                requestAnimationFrame(() => {
                    container.querySelectorAll('.ra-funnel-fill').forEach((el) => {
                        el.style.width = `${el.dataset.pct}%`;
                    });
                });
            }

            let programRowsCache = [];

            function renderProgramTable(rows) {
                programRowsCache = rows;
                paintProgramTable(rows);
            }

            function groupProgramsByCampus(rows) {
                const campusMap = new Map();
                rows.forEach((row) => {
                    const campusName = row.campus_name || 'Unknown campus';
                    if (!campusMap.has(campusName)) {
                        campusMap.set(campusName, {
                            campus_name: campusName,
                            total_proposals: 0,
                            completed_proposals: 0,
                            programs: []
                        });
                    }
                    const group = campusMap.get(campusName);
                    group.total_proposals += Number(row.total_proposals || 0);
                    group.completed_proposals += Number(row.completed_proposals || 0);
                    group.programs.push(row);
                });
                return Array.from(campusMap.values()).sort((a, b) => {
                    if (a.campus_name === 'MAIN CAMPUS') return -1;
                    if (b.campus_name === 'MAIN CAMPUS') return 1;
                    return 0;
                });
            }

            function paintProgramTable(rows) {
                const tbody = document.getElementById('programTableBody');
                if (!rows.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="ra-empty">no matching programs</td></tr>';
                    return;
                }
                tbody.innerHTML = groupProgramsByCampus(rows).map((group) => `
                    <tr class="ra-campus-header">
                        <td>
                            <div class="ra-campus-name">${escapeHtml(group.campus_name)}</div>
                            <div class="ra-campus-meta">${fmtInt(group.programs.length)} program${group.programs.length === 1 ? '' : 's'}</div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ${group.programs.map((program) => `
                                                    <tr class="ra-program-row">
                                                        <td></td>
                                                        <td class="ra-program-name">${escapeHtml(program.program_name)}</td>
                                                        <td class="ra-metric ra-metric-total">${fmtInt(program.total_proposals)}</td>
                                                        <td class="ra-metric ra-metric-completed">${fmtInt(program.completed_proposals)}</td>
                                                    </tr>
                                                `).join('')}
                    <tr class="ra-campus-summary">
                        <td class="ra-campus-meta">Campus total submissions</td>
                        <td></td>
                        <td class="ra-campus-total">${fmtInt(group.total_proposals)}</td>
                        <td class="ra-campus-total">${fmtInt(group.completed_proposals)}</td>
                    </tr>
                `).join('');
            }

            document.getElementById('programFilter').addEventListener('input', (e) => {
                const q = e.target.value.trim().toLowerCase();
                paintProgramTable(!q ? programRowsCache : programRowsCache.filter((r) =>
                    r.campus_name.toLowerCase().includes(q) || r.program_name.toLowerCase().includes(q)
                ));
            });

            async function loadDashboard(year = '') {
                try {
                    activeYear = year;
                    document.getElementById('raGlobalError').style.display = 'none';
                    document.getElementById('raLastUpdated').textContent = year ? `loading ${year}...` :
                        'loading all years...';

                    const data = await fetchDashboard(year);
                    setYearOptions(data.proposals_by_year || []);
                    document.getElementById('yearFilter').value = year;

                    renderKpis(data);
                    renderQuarterChart(data.proposals_by_quarter || []);
                    renderSdgChart(data.proposals_by_sdg || []);
                    renderFormatChart(data.proposals_by_format || []);
                    renderAgendaChart(data.proposals_by_agenda || []);
                    renderFunnel(data.approval_rate_by_campus || []);
                    renderProgramTable(data.campus_program_breakdown || []);

                    document.getElementById('raLastUpdated').textContent =
                        `${year ? `year ${year}` : 'all years'} · updated ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
                    document.querySelector('.ra-dot').style.background = '#7FB39A';
                } catch (err) {
                    console.error(err);
                    showGlobalError(
                        `Couldn't load analytics data for ${year || 'all years'} from ${API_BASE}. Confirm the FastAPI app is running and the selected year has data.`
                    );
                    document.getElementById('raLastUpdated').textContent = 'offline';
                    document.querySelector('.ra-dot').style.background = '#C2705E';
                    ['proposalKpiTotal', 'proposalKpiCompleted', 'proposalKpiPending',
                        'proposalKpiInProgress', 'proposalKpiCampuses'
                    ].forEach((id) => setTextIfExists(id, '—'));
                }
            }

            document.getElementById('yearFilter').addEventListener('change', (e) => {
                loadDashboard(e.target.value);
            });

            themeToggleEl.addEventListener('click', () => {
                applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
                loadDashboard(activeYear);
            });

            function setupRailMenu() {
                const panelTargets = {
                    overview: '#overviewDashboard',
                    proposals: '#proposalsDashboard',
                    fundings: '#fundingsDashboard',
                    publications: '#publicationsDashboard',
                };

                function showDashboardPanel(tabName) {
                    // Default invalid or missing tab names back to Overview so
                    // the dashboard still opens in a safe, predictable state.
                    const activeSel = panelTargets[tabName] || '#overviewDashboard';
                    document.querySelectorAll('.ra-dashboard-panel').forEach((panel) => {
                        panel.classList.toggle('is-active', `#${panel.id}` === activeSel);
                    });
                }

                function activateDashboardTab(tabName) {
                    const safeTabName = panelTargets[tabName] ? tabName : 'overview';
                    document.querySelectorAll('.ra-tab').forEach((item) => {
                        item.classList.toggle('is-active', item.dataset.tab === safeTabName);
                    });
                    document.querySelectorAll('.ra-topnav-link').forEach((item) => {
                        item.classList.toggle('is-active', item.dataset.tab === safeTabName);
                    });
                    // Persist the selected tab immediately so a reload restores
                    // the user's current place in the dashboard.
                    localStorage.setItem(ACTIVE_TAB_STORAGE_KEY, safeTabName);
                    // Mirror the active tab into the URL so browser refreshes and
                    // shared links can reopen the same panel deterministically.
                    window.history.replaceState(null, '', `#${safeTabName}`);
                    showDashboardPanel(safeTabName);
                    if (safeTabName === 'proposals') loadDashboard(activeYear);
                }

                document.querySelectorAll('.ra-tab, .ra-topnav-link').forEach((tab) => {
                    tab.addEventListener('click', () => activateDashboardTab(tab.dataset.tab));
                });
            }

            async function init() {
                applyTheme(localStorage.getItem(THEME_STORAGE_KEY) || 'light');
                setupRailMenu();
                // Restore the saved tab before any async data loads so the
                // refreshed page paints the correct panel immediately.
                const panelTargets = {
                    overview: '#overviewDashboard',
                    proposals: '#proposalsDashboard',
                    fundings: '#fundingsDashboard',
                    publications: '#publicationsDashboard',
                };
                const initialTab = readSavedDashboardTab(panelTargets);
                document.querySelectorAll('.ra-tab').forEach((item) => {
                    item.classList.toggle('is-active', item.dataset.tab === initialTab);
                });
                document.querySelectorAll('.ra-topnav-link').forEach((item) => {
                    item.classList.toggle('is-active', item.dataset.tab === initialTab);
                });
                document.querySelectorAll('.ra-dashboard-panel').forEach((panel) => {
                    panel.classList.toggle('is-active', `#${panel.id}` === panelTargets[initialTab]);
                });
                await loadDashboard(activeYear);
                // Reapply through the shared tab activator so storage, hash, and
                // panel-specific loaders all stay in sync after initialization.
                document.querySelector(
                        `.ra-topnav-link[data-tab="${initialTab}"], .ra-tab[data-tab="${initialTab}"]`)
                    ?.dispatchEvent(new Event('click'));
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection
