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
            --ra-bg: #0A0D12;
            --ra-panel: #11151D;
            --ra-panel-2: #161B25;
            --ra-line: #232A36;
            --ra-line-soft: #1A202B;
            --ra-text: #E7E5DE;
            --ra-text-dim: #8B92A1;
            --ra-text-faint: #586272;
            --ra-approved: #7FB39A;
            --ra-approved-dim: #3D5448;
            --ra-pending: #D9A35C;
            --ra-pending-dim: #4A3D29;
            --ra-danger: #C2705E;
            --ra-serif: 'Lora', 'Georgia', serif;
            --ra-mono: 'JetBrains Mono', 'IBM Plex Mono', ui-monospace, 'SF Mono', Menlo, Consolas, monospace;
        }

        .ra-page[data-theme="light"] {
            --ra-bg: #F3EFE6;
            --ra-panel: #FCFAF5;
            --ra-panel-2: #F5F0E5;
            --ra-line: #D8CFBF;
            --ra-line-soft: #E7DED0;
            --ra-text: #1C1E21;
            --ra-text-dim: #5E645E;
            --ra-text-faint: #7B827B;
            --ra-approved: #4F8B68;
            --ra-approved-dim: #CFE2D4;
            --ra-pending: #B68139;
            --ra-pending-dim: #E9D8C0;
            --ra-danger: #B45844;
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
                radial-gradient(circle at 12% 0%, rgba(127, 179, 154, 0.05), transparent 38%),
                radial-gradient(circle at 88% 12%, rgba(217, 163, 92, 0.04), transparent 42%);
            color: var(--ra-text);
            min-height: 100vh;
            padding: 1.75rem clamp(1rem, 3vw, 2.5rem) 3rem;
        }

        @media (min-width: 992px) {
            .ra-page {
                /* Reserve room for the fixed left-side menu so dashboard cards
                                   do not sit underneath the navigation rail. */
                padding-left: 15.5rem;
            }
        }

        .ra-page,
        .ra-page * {
            box-sizing: border-box;
        }

        .ra-dashboard-panel {
            /* Dashboard panels let the left menu switch between full Blade
                       sections instead of only scrolling to cards on one long page. */
            display: none;
        }

        .ra-dashboard-panel.is-active {
            display: block;
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
            font-size: .68rem;
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
            font-size: clamp(1.7rem, 2.4vw, 2.15rem);
            font-weight: 600;
            color: var(--ra-text);
            margin: 0;
            line-height: 1.1;
            letter-spacing: -.01em;
        }

        .ra-subtitle {
            color: var(--ra-text-dim);
            font-size: .87rem;
            margin-top: .5rem;
            max-width: 46ch;
        }

        .ra-asof {
            font-family: var(--ra-mono);
            font-size: .72rem;
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
            font-size: .66rem;
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
            font-size: .78rem;
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

        .ra-kpi-label {
            font-family: var(--ra-mono);
            font-size: .68rem;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--ra-text-faint);
            margin-bottom: .55rem;
        }

        .ra-kpi-value {
            font-family: var(--ra-serif);
            font-size: 1.95rem;
            font-weight: 600;
            color: var(--ra-text);
            line-height: 1;
            letter-spacing: -.01em;
        }

        .ra-kpi-foot {
            font-family: var(--ra-mono);
            font-size: .7rem;
            color: var(--ra-text-faint);
            margin-top: .6rem;
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
            font-size: 1.02rem;
            font-weight: 600;
            color: var(--ra-text);
            margin: 0;
        }

        .ra-card-sub {
            font-family: var(--ra-mono);
            font-size: .72rem;
            color: var(--ra-text-faint);
            margin-top: .3rem;
            letter-spacing: .01em;
        }

        /* ---------- Signature element: signal bars ---------- */
        .ra-funnel-row {
            display: grid;
            grid-template-columns: 142px 1fr 58px;
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
            font-size: .74rem;
            letter-spacing: .01em;
            color: var(--ra-text-dim);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            font-size: .76rem;
            font-weight: 500;
            color: var(--ra-approved);
            text-align: right;
        }

        .ra-legend {
            display: flex;
            gap: 1.2rem;
            font-family: var(--ra-mono);
            font-size: .7rem;
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
            /* Hide the chart menu at the display layer because ApexCharts can
                                       still inject the toolbar node even when show:false is set. */
            display: none !important;
        }

        .ra-program-name {
            color: var(--ra-text);
            font-weight: 500;
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
            /* Fixed left rail follows the reference menu while keeping the
                               existing data-tab buttons available for dashboard section logic. */
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

        .ra-tabs-brand {
            position: relative;
            z-index: 1;
            text-align: center;
            padding-bottom: 1rem;
            margin-bottom: .35rem;
        }

        .ra-tabs-mark {
            width: 2.7rem;
            height: 2.7rem;
            margin: 0 auto .65rem;
            display: grid;
            place-items: center;
            color: #FFFFFF;
        }

        .ra-tabs-title {
            font-family: var(--ra-mono);
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .ra-tabs-subtitle {
            margin-top: .55rem;
            font-family: var(--ra-mono);
            font-size: .63rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: rgba(234, 244, 255, .72);
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

        .ra-tabs-quote {
            position: relative;
            z-index: 1;
            margin-top: auto;
            padding: 1rem .65rem .5rem;
            color: rgba(234, 244, 255, .78);
            font-family: var(--ra-serif);
            font-size: .78rem;
            font-style: italic;
            line-height: 1.65;
        }

        .ra-tabs-quote::before {
            content: '"';
            display: block;
            margin-bottom: .45rem;
            color: rgba(255, 255, 255, .9);
            font-family: var(--ra-serif);
            font-size: 2rem;
            line-height: .8;
        }

        @media (max-width: 991.98px) {
            .ra-funnel-row {
                grid-template-columns: 96px 1fr 46px;
            }

            .ra-tabs {
                /* On tablet/mobile widths the fixed left rail becomes a normal
                                   top block so it cannot cover the dashboard cards. */
                position: static;
                width: 100%;
                min-height: auto;
                margin-bottom: 1rem;
            }

            .ra-tabs-quote {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ra-page" id="raPage" data-theme="light">

        <div class="ra-header">
            <div>
                <div class="ra-eyebrow">Research &amp; Development Office</div>
                <h1 class="ra-title">Research Progam Dashboard</h1>
                <div class="ra-subtitle">Submission volume, approval velocity, and reach across campuses, programs, and SDGs.
                </div>
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
                    {{-- Eye icon marks the visual display mode control while keeping the button compact. --}}
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12s-3.5 6.25-9.75 6.25S2.25 12 2.25 12Z"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="2.75" stroke-width="1.8" />
                    </svg>
                </button>
            </div>

        </div>

        <div id="raGlobalError" style="display:none" class="ra-error"></div>

        {{-- Keep the left navigation in a partial so menu items can be managed separately. --}}
        @include('content.dashboard.partials.dashboard-menu')


        <section id="overviewDashboard" class="ra-dashboard-panel is-active">
            {{-- Overview collects only lead indicators from each fact table. --}}
            @include('content.dashboard.partials.overview-dashboard')
        </section>

        <section id="proposalsDashboard" class="ra-dashboard-panel">


            {{-- Proposal lead indicators are repeated here so the proposal
                 detail page has its own quick summary before tail indicators. --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-lg-4">
                    <div class="ra-kpi-card">
                        <div class="ra-kpi-label">Total proposals</div>
                        <div class="ra-kpi-value" id="proposalKpiTotal"><span class="ra-skel"></span></div>
                        <div class="ra-kpi-foot">across all campuses</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="ra-kpi-card">
                        <div class="ra-kpi-label">Approval rate</div>
                        <div class="ra-kpi-value" id="proposalKpiApprovalRate"><span class="ra-skel"></span></div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="ra-kpi-card">
                        <div class="ra-kpi-label">Campuses reporting</div>
                        <div class="ra-kpi-value" id="proposalKpiCampuses"><span class="ra-skel"></span></div>
                        <div class="ra-kpi-foot">with at least one proposal</div>
                    </div>
                </div>
            </div>


            <div class="row g-3 mb-3">
                {{-- Research format --}}
                <div class="col-lg-4">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Research format</h2>
                                <div class="ra-card-sub">mix of study types</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartFormat" style="min-height:230px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Research agenda --}}
                <div class="col-lg-8">
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
                <div class="col-lg-6">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Submissions over time</h2>
                                <div class="ra-card-sub">by year and quarter</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartQuarter" style="min-height:260px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Status distribution --}}
                <div class="col-lg-6">
                    <div class="ra-card h-100">
                        <div class="ra-card-head">
                            <div>
                                <h2 class="ra-card-title">Status distribution</h2>
                                <div class="ra-card-sub">where proposals sit in the pipeline</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div id="chartStatus" style="min-height:260px;"></div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="row g-3 mb-3">
                {{-- SDG breakdown --}}
                <div class="col-lg-5">
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

                {{-- Signature element: approval signal bars by campus --}}
                <div class="col-lg-7">
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
            {{-- Publications is a full Blade panel, separate from the proposal dashboard. --}}
            @include('content.dashboard.partials.publications-dashboard')
        </section>

        <section id="fundingsDashboard" class="ra-dashboard-panel">
            {{-- Fundings is a full Blade panel, separate from the proposal dashboard. --}}
            @include('content.dashboard.partials.fundings-dashboard')
        </section>

    </div>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
    <script>
        /**
         * Research Analytics dashboard.
         * Pulls data client-side from the FastAPI analytics service
         * (see main.py / queries.py) via the /api/proposals/dashboard
         * endpoint, which bundles all 13 named queries into one response.
         *
         * Configure the base URL via Laravel: pass it from a controller as
         * config('services.research_api.url') or set window.RESEARCH_API_BASE
         * in a small inline script before this view renders.
         */
        (function() {
            const API_BASE = window.RESEARCH_API_BASE ||
                '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

            const pageEl = document.getElementById('raPage');
            const themeToggleEl = document.getElementById('themeToggle');
            const MONO = "'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace";
            const THEME_STORAGE_KEY = 'research-analytics-theme';
            const chartInstances = {};
            let availableYears = [];
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
                // Read colors from CSS variables so charts follow the same theme
                // toggle as the surrounding cards, tables, and labels.
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

            function baseChartOptions(overrides) {
                const text = cssVar('--ra-text');
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
                        }
                    }
                }, overrides);
            }

            function buildDashboardUrl(year) {
                const url = new URL(`${API_BASE}/api/proposals/dashboard`);
                if (year) {
                    url.searchParams.set('year', year);
                }
                return url.toString();
            }

            async function fetchDashboard(year = '') {
                const res = await fetch(buildDashboardUrl(year), {
                    headers: {
                        Accept: 'application/json'
                    },
                });
                if (!res.ok) {
                    throw new Error(`Analytics service responded with ${res.status}`);
                }
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
                // Recreating each chart instance keeps ApexCharts from stacking
                // old canvases when the dashboard refetches for a new year.
                const chart = new ApexCharts(document.getElementById(elementId), options);
                chartInstances[chartKey] = chart;
                chart.render();
            }

            function setYearOptions(yearRows) {
                if (availableYears.length) {
                    return;
                }
                availableYears = (yearRows || []).map((row) => Number(row.year)).filter(Boolean);
                const select = document.getElementById('yearFilter');
                select.innerHTML = '<option value="">All years</option>';
                availableYears.forEach((year) => {
                    const option = document.createElement('option');
                    option.value = String(year);
                    option.textContent = String(year);
                    select.appendChild(option);
                });
            }

            function setTextIfExists(id, value) {
                // Some KPI values appear in both Overview and detail panels; this
                // guard prevents errors if a card is removed from one panel later.
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                }
            }

            function renderKpis(data) {
                const statusRows = data.status_distribution || [];
                const totalProposals = statusRows.reduce((sum, r) => sum + Number(r.total_proposals || 0), 0);
                setTextIfExists('kpiTotal', fmtInt(totalProposals));
                setTextIfExists('proposalKpiTotal', fmtInt(totalProposals));

                const approvalRow = data.approval_rate_overall?.[0] ?? data.approval_rate_overall;
                const approvalPct = Array.isArray(data.approval_rate_overall) ?
                    data.approval_rate_overall[0]?.approval_rate_percentage :
                    approvalRow?.approval_rate_percentage;
                setTextIfExists('kpiApprovalRate', fmtPct(approvalPct));
                setTextIfExists('proposalKpiApprovalRate', fmtPct(approvalPct));

                const campusRows = data.proposals_by_campus || [];
                setTextIfExists('kpiCampuses', fmtInt(campusRows.length));
                setTextIfExists('proposalKpiCampuses', fmtInt(campusRows.length));

            }

            function renderStatusChart(rows) {
                destroyChart('status');
                if (!rows.length) {
                    document.getElementById('chartStatus').innerHTML = '<div class="ra-empty">no status data yet</div>';
                    return;
                }
                document.getElementById('chartStatus').innerHTML = '';
                // Translate raw status codes into reader-friendly labels so
                // the donut legend matches how the office describes statuses.
                const statusLabelMap = {
                    C: 'Complete',
                    P: 'Pending',
                    PG: 'In Progress',
                };
                const labels = rows.map((r) => statusLabelMap[r.status_code] || r.status_code || 'Unknown');
                const series = rows.map((r) => Number(r.total_proposals || 0));
                renderChart('status', 'chartStatus', baseChartOptions({
                    chart: {
                        type: 'donut',
                        height: 260
                    },
                    labels,
                    series,
                    stroke: {
                        colors: [cssVar('--ra-panel')],
                        width: 2
                    },
                    legend: {
                        position: 'bottom',
                        fontSize: '12px',
                        labels: {
                            colors: cssVar('--ra-text-dim')
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        color: cssVar('--ra-text-dim')
                                    },
                                    value: {
                                        color: cssVar('--ra-text'),
                                        fontFamily: MONO
                                    },
                                },
                            },
                        },
                    },
                }));
            }

            function renderQuarterChart(rows) {
                destroyChart('quarter');
                if (!rows.length) {
                    document.getElementById('chartQuarter').innerHTML =
                        '<div class="ra-empty">no submission history yet</div>';
                    return;
                }
                document.getElementById('chartQuarter').innerHTML = '';
                const categories = rows.map((r) => `${r.year} Q${r.quarter}`);
                const series = [{
                    name: 'Proposals',
                    data: rows.map((r) => Number(r.total_proposals || 0))
                }];
                renderChart('quarter', 'chartQuarter', baseChartOptions({
                    chart: {
                        type: 'area',
                        height: 260
                    },
                    series,
                    xaxis: {
                        categories,
                        tickAmount: 8,
                        labels: {
                            style: {
                                fontSize: '10px',
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
                                fontFamily: MONO
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
                                fontSize: '10px'
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
                                fontSize: '11px'
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
                renderChart('format', 'chartFormat', baseChartOptions({
                    chart: {
                        type: 'donut',
                        height: 230
                    },
                    labels: rows.map((r) => r.research_format_name),
                    series: rows.map((r) => Number(r.total_proposals || 0)),
                    stroke: {
                        colors: [cssVar('--ra-panel')],
                        width: 2
                    },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        labels: {
                            colors: cssVar('--ra-text-dim')
                        }
                    },
                }));
            }

            function renderAgendaChart(rows) {
                destroyChart('agenda');
                if (!rows.length) {
                    document.getElementById('chartAgenda').innerHTML = '<div class="ra-empty">no agenda data yet</div>';
                    return;
                }
                document.getElementById('chartAgenda').innerHTML = '';
                renderChart('agenda', 'chartAgenda', baseChartOptions({
                    chart: {
                        type: 'bar',
                        height: 230,
                        // Hide the ApexCharts toolbar/menu on this panel even
                        // if a later override changes the shared chart config.
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
                            style: {
                                fontSize: '9px',
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
                                fontFamily: MONO
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
                // Grouping in the view layer lets the search box keep working on
                // the filtered subset while campus totals update automatically.
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
                // Pin MAIN CAMPUS first so the primary campus stays at the top even
                // when the API returns other campuses ahead of it.
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
                const groupedRows = groupProgramsByCampus(rows);
                // Render one summary row per campus, followed by its programs, so
                // the table reads like a real breakdown instead of flat duplicates.
                tbody.innerHTML = groupedRows.map((group) => `
                    <tr class="ra-campus-summary">
                        <td>
                            <div class="ra-campus-name">${escapeHtml(group.campus_name)}</div>
                            <div class="ra-campus-meta">${fmtInt(group.programs.length)} program${group.programs.length === 1 ? '' : 's'}</div>
                        </td>
                        <td class="ra-campus-meta">Campus total submissions</td>
                        <td class="ra-campus-total">${fmtInt(group.total_proposals)}</td>
                        <td class="ra-campus-total">${fmtInt(group.completed_proposals)}</td>
                    </tr>
                    ${group.programs.map((program) => `
                                    <tr class="ra-program-row">
                                        <td>Program</td>
                                        <td class="ra-program-name">${escapeHtml(program.program_name)}</td>
                                        <td class="ra-metric ra-metric-total">${fmtInt(program.total_proposals)}</td>
                                        <td class="ra-metric ra-metric-completed">${fmtInt(program.completed_proposals)}</td>
                                    </tr>
                                `).join('')}
                `).join('');
            }

            document.getElementById('programFilter').addEventListener('input', (e) => {
                const q = e.target.value.trim().toLowerCase();
                if (!q) {
                    paintProgramTable(programRowsCache);
                    return;
                }
                paintProgramTable(programRowsCache.filter((r) =>
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
                    renderStatusChart(data.status_distribution || []);
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
                    [
                        'kpiTotal', 'kpiApprovalRate', 'kpiCampuses',
                    ].forEach((id) => {
                        document.getElementById(id).textContent = '—';
                    });
                }
            }

            document.getElementById('yearFilter').addEventListener('change', (e) => {
                // The dropdown simply reuses the same dashboard endpoint with an
                // optional ?year=... parameter, so the frontend stays thin.
                loadDashboard(e.target.value);
            });

            themeToggleEl.addEventListener('click', () => {
                const nextTheme = currentTheme() === 'dark' ? 'light' : 'dark';
                // Re-fetching is unnecessary here; we just redraw against the
                // existing state so the charts pick up the new CSS-variable palette.
                applyTheme(nextTheme);
                loadDashboard(activeYear);
            });

            function setupRailMenu() {
                const panelTargets = {
                    overview: '#overviewDashboard',
                    proposals: '#proposalsDashboard',
                    fundings: '#fundingsDashboard',
                    publications: '#publicationsDashboard',
                };
                const tabTargets = {
                    overview: '.ra-header',
                    proposals: '#chartStatus',
                };

                function showDashboardPanel(tabName) {
                    // Switch the full dashboard Blade panel first, then optional
                    // scrolling can happen inside the active proposal panel.
                    const activePanelSelector = panelTargets[tabName] || '#proposalsDashboard';
                    document.querySelectorAll('.ra-dashboard-panel').forEach((panel) => {
                        panel.classList.toggle('is-active', `#${panel.id}` === activePanelSelector);
                    });
                }

                document.querySelectorAll('.ra-tab').forEach((tab) => {
                    tab.addEventListener('click', () => {
                        // Selecting a left-menu item marks it active and decides
                        // which full Blade dashboard panel should be visible.
                        document.querySelectorAll('.ra-tab').forEach((item) => {
                            item.classList.toggle('is-active', item === tab);
                        });

                        showDashboardPanel(tab.dataset.tab);

                        if (tab.dataset.tab === 'proposals') {
                            // Proposal charts are inside a hidden panel on first
                            // load, so redraw them after the panel becomes visible.
                            loadDashboard(activeYear);
                        }

                        const target = document.querySelector(tabTargets[tab.dataset.tab]);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            }

            async function init() {
                // Persist the user's theme choice across refreshes so the dashboard
                // behaves like an application setting rather than a one-off demo.
                applyTheme(localStorage.getItem(THEME_STORAGE_KEY) || 'light');
                setupRailMenu();
                await loadDashboard(activeYear);
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection
