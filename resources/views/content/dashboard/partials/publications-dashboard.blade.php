{{--
    publications-dashboard.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Published Papers Dashboard — clean_publications dataset.
    Modernized: glass-panel cards, tier-coded color system, micro-interactions.

    Layout (top → bottom):
      1. Page header  — title, year-range badge, year-filter dropdown
      2. KPI strip    — 6 headline numbers, tier-colored accents
      3. Trend row    — Annual publications line chart (full history, no filter)
                      + Indexing-tier donut with % table
      4. Campus row   — Campus bar chart + Campus × Indexing grouped bar
      5. Detail row   — Top-10 journals horizontal bar
                      + Monthly area chart (filtered)
      6. Data quality — Flag summary strip (audit trail for the president)

    Data source: FastAPI /api/publications/dashboard  +  /summary  +  /by-year
    Chart library: ApexCharts (already loaded by the parent layout)

    Color system — indexing tiers (used consistently across KPIs, donut,
    legend, table dots, and the campus×indexing bars):
      Scopus        → #635bff  (violet)
      International → #2dd4bf  (teal)
      Local         → #ffb400  (amber)
      Unspecified   → #9b9db0  (slate)
--}}

{{-- ── Page header ──────────────────────────────────────────────────────── --}}
<div class="ra-pub-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h1 class="ra-page-title mb-0">Published Research Papers</h1>
        <p class="ra-page-sub mb-0">
            Peer-reviewed and indexed publications from across all campuses
            <span class="ra-year-badge" id="pubYearRangeBadge">
                <span class="ra-skel" style="width:90px;display:inline-block;"></span>
            </span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <label for="pubYearFilter" class="ra-filter-label">Filter by year</label>
        <select id="pubYearFilter" class="form-select form-select-sm ra-year-select" style="width:auto;">
            <option value="">All years</option>
        </select>
    </div>
</div>

{{-- ── KPI strip ────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3" id="pubKpiRow">
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card" data-tier="neutral">
            <div class="ra-kpi-icon">📄</div>
            <div class="ra-kpi-label">Total publications</div>
            <div class="ra-kpi-value" id="pubKpiTotal"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">primary records only</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card" data-tier="neutral">
            <div class="ra-kpi-icon">🏫</div>
            <div class="ra-kpi-label">Campuses represented</div>
            <div class="ra-kpi-value" id="pubKpiCampuses"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">with at least one paper</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card" data-tier="neutral">
            <div class="ra-kpi-icon">📚</div>
            <div class="ra-kpi-label">Unique journals</div>
            <div class="ra-kpi-value" id="pubKpiJournals"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">distinct publication venues</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card ra-kpi-card--accent" data-tier="scopus">
            <div class="ra-kpi-icon">⭐</div>
            <div class="ra-kpi-label">Scopus-indexed</div>
            <div class="ra-kpi-value" id="pubKpiScopus"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">highest-tier indexing</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card ra-kpi-card--accent" data-tier="international">
            <div class="ra-kpi-icon">🌐</div>
            <div class="ra-kpi-label">International</div>
            <div class="ra-kpi-value" id="pubKpiInternational"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">internationally indexed</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="ra-kpi-card" data-tier="neutral">
            <div class="ra-kpi-icon">📏</div>
            <div class="ra-kpi-label">Avg. pages / paper</div>
            <div class="ra-kpi-value" id="pubKpiAvgPages"><span class="ra-skel"></span></div>
            <div class="ra-kpi-foot">across papers with page data</div>
        </div>
    </div>
</div>

{{-- ── Row 2: Trend + Indexing tier ───────────────────────────────────────── --}}
<div class="row g-3 mb-3">

    {{-- Annual publications trend (full history, no year filter applied) --}}
    <div class="col-lg-7">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Annual publication output</h2>
                    <div class="ra-card-sub" id="pubTrendSubtitle">
                        Total peer-reviewed papers published per year — full historical range
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubTrend" style="min-height:260px;"></div>
            </div>
        </div>
    </div>

    {{-- Indexing-tier donut + percentage table --}}
    <div class="col-lg-5">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Indexing tier distribution</h2>
                    <div class="ra-card-sub">share of publications by indexing level</div>
                </div>
            </div>
            <div class="px-3 pb-1">
                <div id="chartPubIndexing" style="min-height:200px;"></div>
            </div>
            <div class="px-3 pb-3">
                <table class="table ra-table ra-table-sm mb-0" id="pubIndexingTable">
                    <thead>
                        <tr>
                            <th>Tier</th>
                            <th class="text-end">Count</th>
                            <th class="text-end">Share</th>
                        </tr>
                    </thead>
                    <tbody id="pubIndexingTableBody">
                        <tr><td colspan="3" class="ra-empty">loading&hellip;</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ── Row 3: Campus bar + Campus × Indexing grouped bar ─────────────────── --}}
<div class="row g-3 mb-3">

    {{-- Publications per campus --}}
    <div class="col-lg-5">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Publications by campus</h2>
                    <div class="ra-card-sub">total published papers per campus unit</div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubCampus" style="min-height:280px;"></div>
            </div>
        </div>
    </div>

    {{-- Campus × indexing tier grouped bar --}}
    <div class="col-lg-7">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Campus output by indexing tier</h2>
                    <div class="ra-card-sub">
                        Scopus, International, and other tiers broken down per campus —
                        shows each campus&rsquo;s research quality profile
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubCampusIndexing" style="min-height:280px;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ── Row 4: Top journals + Monthly trend ────────────────────────────────── --}}
<div class="row g-3 mb-3">

    {{-- Top 10 journals --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Top 10 publication venues</h2>
                    <div class="ra-card-sub">
                        journals and conferences with the highest paper counts
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubTopJournals" style="min-height:300px;"></div>
            </div>
        </div>
    </div>

    {{-- Monthly trend (filterable) --}}
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Monthly publication activity</h2>
                    <div class="ra-card-sub" id="pubMonthlySubtitle">
                        papers published each month — select a year above to zoom in
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubMonthly" style="min-height:300px;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ── Data quality strip ───────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head pb-1">
                <div>
                    <h2 class="ra-card-title">Data quality audit</h2>
                    <div class="ra-card-sub">
                        Flag counts from the cleaning process — lower is better.
                        These records are <strong>excluded</strong> from all counts above.
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div class="row g-3" id="pubQualityFlags">
                    {{-- Filled by JS --}}
                    <div class="col-12 ra-empty">loading quality flags&hellip;</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ── Tier color tokens (single source of truth, mirrors JS TIER_COLORS) ── */
    :root {
        --tier-scopus: #635bff;
        --tier-international: #2dd4bf;
        --tier-local: #ffb400;
        --tier-unspecified: #9b9db0;
    }

    /* ── Page header ── */
    .ra-pub-header .ra-page-title {
        font-weight: 700;
        letter-spacing: -.01em;
        background: linear-gradient(90deg, var(--ra-text, #1a1c2e) 0%, var(--tier-scopus) 140%);
        -webkit-background-clip: text;
        background-clip: text;
    }
    .ra-page-sub {
        color: var(--ra-text-dim);
        font-size: .88rem;
    }

    /* ── Year-range badge ── */
    .ra-year-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: linear-gradient(135deg, var(--tier-scopus)22, var(--tier-scopus)10);
        color: var(--tier-scopus);
        border: 1px solid var(--tier-scopus)55;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        padding: .15rem .65rem;
        margin-left: .5rem;
        vertical-align: middle;
        letter-spacing: .02em;
    }

    .ra-filter-label {
        font-size: .8rem;
        color: var(--ra-text-dim);
        white-space: nowrap;
    }
    .ra-year-select {
        border-radius: 999px;
        font-size: .8rem;
        transition: box-shadow .15s ease, border-color .15s ease;
    }
    .ra-year-select:focus {
        box-shadow: 0 0 0 3px var(--tier-scopus)33;
        border-color: var(--tier-scopus);
    }

    /* ── Cards: soft elevation, gentle hover lift ── */
    .ra-card {
        border-radius: 18px;
        border: 1px solid var(--ra-line);
        background: var(--ra-panel);
        box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        transition: box-shadow .2s ease, transform .2s ease;
        overflow: hidden;
    }
    .ra-card:hover {
        box-shadow: 0 8px 24px rgba(16, 24, 40, .08);
    }
    .ra-card-head {
        padding: 1rem 1.1rem .25rem;
    }
    .ra-card-title {
        font-size: .95rem;
        font-weight: 650;
        margin-bottom: .15rem;
    }
    .ra-card-sub {
        font-size: .76rem;
        color: var(--ra-text-dim);
        line-height: 1.4;
    }

    /* ── KPI cards ── */
    .ra-kpi-card {
        position: relative;
        border-radius: 16px;
        border: 1px solid var(--ra-line);
        background: var(--ra-panel);
        padding: .9rem 1rem .85rem;
        height: 100%;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        overflow: hidden;
    }
    .ra-kpi-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 3px;
        background: var(--ra-line);
    }
    .ra-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 26px rgba(16, 24, 40, .09);
    }
    .ra-kpi-icon {
        font-size: 1rem;
        opacity: .8;
        margin-bottom: .15rem;
    }
    .ra-kpi-label {
        font-size: .72rem;
        font-weight: 600;
        color: var(--ra-text-dim);
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .ra-kpi-value {
        font-family: 'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace;
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.25;
        margin-top: .1rem;
        color: var(--ra-text);
    }
    .ra-kpi-foot {
        font-size: .68rem;
        color: var(--ra-text-faint, var(--ra-text-dim));
    }

    /* Tier-coded KPI accents — color matches the chart/legend for that tier */
    .ra-kpi-card[data-tier="scopus"]::before        { background: var(--tier-scopus); }
    .ra-kpi-card[data-tier="scopus"] .ra-kpi-value  { color: var(--tier-scopus); }
    .ra-kpi-card[data-tier="scopus"]              { border-color: var(--tier-scopus)33; }

    .ra-kpi-card[data-tier="international"]::before        { background: var(--tier-international); }
    .ra-kpi-card[data-tier="international"] .ra-kpi-value  { color: var(--tier-international); }
    .ra-kpi-card[data-tier="international"]              { border-color: var(--tier-international)33; }

    .ra-kpi-card--accent {
        background: linear-gradient(180deg, var(--ra-panel) 70%, var(--ra-panel-alt, var(--ra-panel)) 100%);
    }

    /* ── Quality flag chips ── */
    .ra-flag-chip {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: var(--ra-panel);
        border: 1px solid var(--ra-line);
        border-radius: 12px;
        padding: .6rem .9rem;
        transition: border-color .15s ease, transform .15s ease;
    }
    .ra-flag-chip:hover { transform: translateY(-1px); }
    .ra-flag-chip .ra-flag-icon {
        font-size: 1.1rem;
        line-height: 1;
    }
    .ra-flag-chip .ra-flag-count {
        font-size: 1.25rem;
        font-weight: 700;
        font-family: 'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace;
        color: var(--ra-text);
        line-height: 1;
    }
    .ra-flag-chip .ra-flag-label {
        font-size: .72rem;
        color: var(--ra-text-dim);
        line-height: 1.2;
    }
    .ra-flag-chip--warn { border-color: var(--ra-pending, #ffb400)55; }
    .ra-flag-chip--warn .ra-flag-count { color: var(--ra-pending, #ffb400); }
    .ra-flag-chip--ok   { border-color: var(--ra-approved, #71dd37)40; }
    .ra-flag-chip--ok   .ra-flag-count { color: var(--ra-approved, #71dd37); }

    /* ── Indexing table ── */
    .ra-table-sm th, .ra-table-sm td {
        font-size: .78rem;
        padding: .35rem .5rem;
    }
    .ra-table-sm thead th {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--ra-text-faint, var(--ra-text-dim));
        border-bottom: 1px solid var(--ra-line);
    }
    .ra-table-sm tbody tr {
        transition: background-color .15s ease;
    }
    .ra-table-sm tbody tr:hover {
        background-color: rgba(99, 91, 255, .05);
    }
    .ra-tier-dot {
        display: inline-block;
        width: 9px; height: 9px;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
        box-shadow: 0 0 0 3px currentColor11;
    }

    /* ── Skeleton loaders ── */
    .ra-skel {
        display: inline-block;
        height: 1em;
        width: 3.2em;
        border-radius: 6px;
        background: linear-gradient(90deg, var(--ra-line) 25%, var(--ra-panel-alt, var(--ra-line)) 50%, var(--ra-line) 75%);
        background-size: 200% 100%;
        animation: ra-shimmer 1.3s ease-in-out infinite;
    }
    @keyframes ra-shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>

<script>
    /**
     * Published Papers Dashboard — clean_publications panel.
     *
     * API endpoints consumed:
     *   GET /api/publications/summary          → KPI strip (all-years)
     *   GET /api/publications/years            → year-filter dropdown
     *   GET /api/publications/by-year          → annual trend (all-years)
     *   GET /api/publications/data-quality     → flag audit strip
     *   GET /api/publications/dashboard?year=  → everything else (filterable)
     *       keys: pub_by_campus, pub_by_indexing_tier, pub_campus_indexing,
     *             pub_top_journals, pub_monthly_trend
     *
     * Pattern: static data (summary, by-year, data-quality) is fetched once
     * on load and never re-fetched. Filterable data is re-fetched whenever the
     * year dropdown changes.
     *
     * Color system: TIER_COLORS below is the single source of truth for
     * indexing-tier colors across every chart (donut, table dots, campus×tier
     * bars). It mirrors the CSS custom properties (--tier-scopus etc.) used
     * for the KPI card accents, so the same tier always reads the same color
     * everywhere on the page.
     */
    (function () {
        'use strict';

        // ── Config ──────────────────────────────────────────────────────
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config("services.research_api.url", "http://127.0.0.1:8001"), "/") }}';

        const pageEl  = document.getElementById('raPage') || document.body;
        const MONO    = "'JetBrains Mono','IBM Plex Mono',ui-monospace,monospace";

        // Tier colours — consistent across all charts and the KPI accents.
        const TIER_COLORS = {
            'Scopus':        '#635bff',
            'International': '#2dd4bf',
            'Local':         '#ffb400',
            'Unspecified':   '#9b9db0',
        };
        const DEFAULT_TIER_COLOR = '#9b9db0';

        // ── State ───────────────────────────────────────────────────────
        const charts   = {};
        let   pubLoaded = false;

        // ── Utilities ───────────────────────────────────────────────────
        const fmtInt   = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtFloat = (n) => n == null ? '—' : Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const fmtPct   = (n) => n == null ? '—' : `${Number(n).toFixed(1)}%`;
        const escHtml  = (s) => String(s ?? '').replace(/[&<>"']/g, c =>
            ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));
        const setText  = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
        const tierColor = (tier) => TIER_COLORS[tier] || DEFAULT_TIER_COLOR;

        function cssVar(name) {
            return getComputedStyle(pageEl).getPropertyValue(name).trim();
        }
        function theme() {
            return pageEl.dataset.theme === 'light' ? 'light' : 'dark';
        }

        // ── Base chart options ───────────────────────────────────────────
        function base(overrides = {}) {
            return Object.assign({
                chart: {
                    fontFamily: 'Public Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: { easing: 'easeinout', speed: 420 },
                    dropShadow: { enabled: false },
                },
                theme:       { mode: theme() },
                colors:      [
                    '#635bff', '#2dd4bf', '#ffb400', '#ff3e1d',
                    '#03c3ec', '#ff6f91', '#6E84A8',
                ],
                dataLabels:  { enabled: false },
                grid:        { borderColor: cssVar('--ra-line'), strokeDashArray: 3 },
                tooltip:     { theme: theme() },
                legend:      { labels: { colors: cssVar('--ra-text-dim') } },
                states:      {
                    hover:  { filter: { type: 'darken', value: .92 } },
                    active: { filter: { type: 'none' } },
                },
            }, overrides);
        }

        // ── Chart lifecycle ──────────────────────────────────────────────
        function draw(key, elId, opts) {
            if (charts[key]) { charts[key].destroy(); delete charts[key]; }
            const el = document.getElementById(elId);
            if (!el) return;
            el.innerHTML = '';
            charts[key] = new ApexCharts(el, opts);
            charts[key].render();
        }
        function empty(elId, msg = 'no data yet') {
            const el = document.getElementById(elId);
            if (el) el.innerHTML = `<div class="ra-empty">${msg}</div>`;
        }

        // ── API helpers ──────────────────────────────────────────────────
        async function apiFetch(path) {
            const res = await fetch(`${API_BASE}${path}`, { headers: { Accept: 'application/json' } });
            if (!res.ok) throw new Error(`${path} → HTTP ${res.status}`);
            return res.json();
        }

        // ── Year filter ──────────────────────────────────────────────────
        async function populateYearFilter() {
            try {
                const years = await apiFetch('/api/publications/years');
                const sel = document.getElementById('pubYearFilter');
                years.forEach(row => {
                    const opt = document.createElement('option');
                    opt.value = row.publication_year;
                    opt.textContent = row.publication_year;
                    sel.appendChild(opt);
                });
            } catch (e) {
                console.warn('Could not load year options:', e);
            }
        }

        // ── Year-range badge ─────────────────────────────────────────────
        function setYearRangeBadge(yearRows) {
            const badge = document.getElementById('pubYearRangeBadge');
            if (!badge || !yearRows.length) return;
            const years = yearRows.map(r => Number(r.publication_year)).filter(Boolean).sort((a,b)=>a-b);
            const min = years[0], max = years[years.length - 1];
            badge.textContent = min === max ? `${min}` : `${min} – ${max}`;
        }

        // ── KPI strip ────────────────────────────────────────────────────
        function renderKpis(summary) {
            setText('pubKpiTotal',         fmtInt(summary.total_publications));
            setText('pubKpiCampuses',      fmtInt(summary.total_campuses));
            setText('pubKpiJournals',      fmtInt(summary.unique_journals));
            setText('pubKpiScopus',        fmtInt(summary.scopus_publications));
            setText('pubKpiInternational', fmtInt(summary.international_publications));
            setText('pubKpiAvgPages',      fmtFloat(summary.average_pages));
        }

        // ── Annual trend (full history) ──────────────────────────────────
        function renderTrend(rows) {
            if (!rows.length) { empty('chartPubTrend', 'no yearly data yet'); return; }

            const years   = rows.map(r => r.year_published);
            const counts  = rows.map(r => Number(r.total_publications || 0));
            const minYear = Math.min(...years);
            const maxYear = Math.max(...years);

            // Update subtitle dynamically
            const sub = document.getElementById('pubTrendSubtitle');
            if (sub) sub.textContent =
                `Total peer-reviewed papers published per year — ${minYear} to ${maxYear}`;

            draw('trend', 'chartPubTrend', base({
                chart:  { type: 'area', height: 260 },
                series: [{ name: 'Publications', data: counts }],
                xaxis:  {
                    categories: years,
                    labels: {
                        style: { fontSize: '11px', colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks:  { color: cssVar('--ra-line') },
                    title: {
                        text: `Year (${minYear}–${maxYear})`,
                        style: { fontSize: '11px', color: cssVar('--ra-text-faint') },
                    },
                },
                yaxis: {
                    title: { text: 'Publications', style: { fontSize: '11px', color: cssVar('--ra-text-faint') } },
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO } },
                },
                stroke: { curve: 'smooth', width: 2.5 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: 0, stops: [0, 95, 100] },
                },
                colors: [TIER_COLORS.Scopus],
                annotations: {
                    // Peak-year annotation
                    points: (() => {
                        const peak = counts.indexOf(Math.max(...counts));
                        return [{
                            x: years[peak],
                            y: counts[peak],
                            marker: { size: 5, fillColor: TIER_COLORS.Scopus, strokeColor: '#fff', radius: 3 },
                            label: {
                                text: `Peak: ${fmtInt(counts[peak])}`,
                                style: { background: TIER_COLORS.Scopus, color: '#fff', fontSize: '10px', padding: { left:6,right:6,top:3,bottom:3 } },
                                offsetY: -8,
                            },
                        }];
                    })(),
                },
            }));
        }

        // ── Indexing-tier donut ──────────────────────────────────────────
        function renderIndexing(rows) {
            if (!rows.length) { empty('chartPubIndexing', 'no indexing data yet'); return; }

            const colors = rows.map(r => tierColor(r.indexing_tier || 'Unspecified'));

            draw('indexing', 'chartPubIndexing', base({
                chart: { type: 'donut', height: 200 },
                labels: rows.map(r => r.indexing_tier || 'Unspecified'),
                series: rows.map(r => Number(r.total || 0)),
                colors,
                stroke: { colors: [cssVar('--ra-panel')], width: 2 },
                legend: { position: 'right', fontSize: '11px', labels: { colors: cssVar('--ra-text-dim') } },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true, label: 'Total',
                                    color: cssVar('--ra-text-dim'),
                                },
                                value: { color: cssVar('--ra-text'), fontFamily: MONO },
                            },
                        },
                    },
                },
            }));

            // Tier percentage table — dot color matches the donut slice exactly
            const tbody = document.getElementById('pubIndexingTableBody');
            if (!tbody) return;
            tbody.innerHTML = rows.map(r => {
                const color = tierColor(r.indexing_tier || 'Unspecified');
                return `<tr>
                    <td><span class="ra-tier-dot" style="background:${color};color:${color}"></span>${escHtml(r.indexing_tier || 'Unspecified')}</td>
                    <td class="text-end" style="font-family:${MONO};font-size:.8rem">${fmtInt(r.total)}</td>
                    <td class="text-end" style="font-family:${MONO};font-size:.8rem;color:${color};font-weight:600">${fmtPct(r.percentage)}</td>
                </tr>`;
            }).join('');
        }

        // ── Campus bar chart ─────────────────────────────────────────────
        function renderCampus(rows) {
            if (!rows.length) { empty('chartPubCampus', 'no campus data yet'); return; }

            // Horizontal bar — easier to read long campus names
            draw('campus', 'chartPubCampus', base({
                chart: { type: 'bar', height: Math.max(280, rows.length * 38) },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '55%' } },
                series: [{ name: 'Publications', data: rows.map(r => Number(r.total_publications || 0)) }],
                xaxis: {
                    categories: rows.map(r => r.campus),
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO, fontSize: '10px' } },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks:  { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: { style: { colors: cssVar('--ra-text-dim'), fontSize: '11px' } },
                },
                colors: [TIER_COLORS.Scopus],
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '10px', fontFamily: MONO, colors: [cssVar('--ra-text')] },
                    formatter: v => fmtInt(v),
                },
            }));
        }

        // ── Campus × Indexing grouped bar ────────────────────────────────
        function renderCampusIndexing(rows) {
            if (!rows.length) { empty('chartPubCampusIndexing', 'no cross-tab data yet'); return; }

            // Pivot: campus → { tier → count }
            const campusSet = [...new Set(rows.map(r => r.campus))];
            const tierSet   = [...new Set(rows.map(r => r.indexing_tier || 'Unspecified'))];

            const pivot = {};
            rows.forEach(r => {
                const c = r.campus;
                const t = r.indexing_tier || 'Unspecified';
                if (!pivot[c]) pivot[c] = {};
                pivot[c][t] = Number(r.total || 0);
            });

            const series = tierSet.map(tier => ({
                name: tier,
                data: campusSet.map(campus => pivot[campus]?.[tier] || 0),
            }));

            const colors = tierSet.map(t => tierColor(t));

            draw('campusIndexing', 'chartPubCampusIndexing', base({
                chart: { type: 'bar', height: 280, stacked: false },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
                series,
                colors,
                xaxis: {
                    categories: campusSet,
                    labels: {
                        style: { fontSize: '9px', colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        rotate: -40,
                        trim: true,
                        maxHeight: 80,
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks:  { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO } },
                    title: { text: 'Papers', style: { fontSize: '11px', color: cssVar('--ra-text-faint') } },
                },
                legend: {
                    position: 'top',
                    fontSize: '11px',
                    labels: { colors: cssVar('--ra-text-dim') },
                },
            }));
        }

        // ── Top 10 journals ──────────────────────────────────────────────
        function renderTopJournals(rows) {
            if (!rows.length) { empty('chartPubTopJournals', 'no journal data yet'); return; }

            // Truncate long names for display
            const truncate = (s, n = 40) => s.length > n ? s.slice(0, n) + '…' : s;
            const labels   = rows.map(r => truncate(r.publication_name || 'Unknown'));
            const values   = rows.map(r => Number(r.total_publications || 0));

            draw('journals', 'chartPubTopJournals', base({
                chart: { type: 'bar', height: 300 },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '55%' } },
                series: [{ name: 'Papers', data: values }],
                xaxis: {
                    categories: labels,
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO, fontSize: '10px' } },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks:  { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: { style: { colors: cssVar('--ra-text-dim'), fontSize: '10px' } },
                },
                colors: [TIER_COLORS.International],
                tooltip: {
                    theme: theme(),
                    y: { formatter: v => `${fmtInt(v)} papers` },
                    // Show full name in tooltip
                    custom: ({ dataPointIndex }) => `
                        <div style="padding:8px 12px;font-size:12px;max-width:280px;word-break:break-word;">
                            <strong>${escHtml(rows[dataPointIndex]?.publication_name || '')}</strong><br>
                            ${fmtInt(values[dataPointIndex])} papers
                        </div>`,
                },
            }));
        }

        // ── Monthly trend ────────────────────────────────────────────────
        function renderMonthly(rows, selectedYear) {
            const sub = document.getElementById('pubMonthlySubtitle');
            if (!rows.length) {
                empty('chartPubMonthly', 'no monthly data for this period');
                if (sub) sub.textContent = 'papers published each month — select a year above to zoom in';
                return;
            }

            const MONTHS = ['', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const labels = rows.map(r => `${MONTHS[r.month_published] || r.month_published} ${r.year_published}`);
            const values = rows.map(r => Number(r.total || 0));

            if (sub) {
                sub.textContent = selectedYear
                    ? `Monthly breakdown for ${selectedYear}`
                    : 'Papers published each month — all years combined';
            }

            draw('monthly', 'chartPubMonthly', base({
                chart: { type: 'area', height: 300 },
                series: [{ name: 'Publications', data: values }],
                xaxis: {
                    categories: labels,
                    tickAmount: 6,
                    labels: {
                        style: { fontSize: '10px', colors: cssVar('--ra-text-faint'), fontFamily: MONO },
                        rotate: -30,
                    },
                    axisBorder: { color: cssVar('--ra-line') },
                    axisTicks:  { color: cssVar('--ra-line') },
                },
                yaxis: {
                    labels: { style: { colors: cssVar('--ra-text-faint'), fontFamily: MONO } },
                },
                stroke: { curve: 'smooth', width: 2.5 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: .3, opacityTo: 0, stops: [0, 90, 100] },
                },
                colors: ['#03c3ec'],
            }));
        }

        // ── Data-quality flags ───────────────────────────────────────────
        function renderQualityFlags(flags) {
            const container = document.getElementById('pubQualityFlags');
            if (!container) return;

            const items = [
                { key: 'duplicate_titles', label: 'Duplicate titles',    icon: '⚠️' },
                { key: 'bad_links',         label: 'Broken links',        icon: '🔗' },
                { key: 'bad_dates',         label: 'Invalid dates',       icon: '📅' },
                { key: 'missing_dates',     label: 'Missing dates',       icon: '📅' },
                { key: 'missing_indexing',  label: 'Missing indexing',    icon: '🏷️' },
            ];

            container.innerHTML = items.map(item => {
                const count = Number(flags[item.key] ?? 0);
                const mod   = count > 0 ? 'ra-flag-chip--warn' : 'ra-flag-chip--ok';
                return `
                <div class="col-6 col-sm-4 col-lg-2">
                    <div class="ra-flag-chip ${mod}">
                        <span class="ra-flag-icon">${item.icon}</span>
                        <div>
                            <div class="ra-flag-count">${fmtInt(count)}</div>
                            <div class="ra-flag-label">${item.label}</div>
                        </div>
                    </div>
                </div>`;
            }).join('');
        }

        // ── Main load function (filterable data) ─────────────────────────
        async function loadFiltered(year = '') {
            try {
                const params   = year ? `?year=${year}` : '';
                const dashboard = await apiFetch(`/api/publications/dashboard${params}`);

                renderIndexing(dashboard.pub_by_indexing_tier || []);
                renderCampus(dashboard.pub_by_campus || []);
                renderCampusIndexing(dashboard.pub_campus_indexing || []);
                renderTopJournals(dashboard.pub_top_journals || []);
                renderMonthly(dashboard.pub_monthly_trend || [], year ? Number(year) : null);

            } catch (err) {
                console.error('Publications dashboard error:', err);
                const errEl = document.getElementById('raGlobalError');
                if (errEl) {
                    errEl.textContent =
                        `Could not load publications data (${year || 'all years'}) from ${API_BASE}. ` +
                        `Make sure the FastAPI service is running.`;
                    errEl.style.display = 'block';
                }
            }
        }

        // ── Static (once-only) loads ─────────────────────────────────────
        async function loadStatic() {
            try {
                const [summary, yearRows, qualityFlags] = await Promise.all([
                    apiFetch('/api/publications/summary'),
                    apiFetch('/api/publications/years'),
                    apiFetch('/api/publications/data-quality'),
                ]);
                renderKpis(summary);
                setYearRangeBadge(yearRows);
                renderQualityFlags(qualityFlags);
            } catch (err) {
                console.error('Publications static load error:', err);
            }

            try {
                const yearlyRows = await apiFetch('/api/publications/by-year');
                renderTrend(yearlyRows);
            } catch (err) {
                console.error('Publications trend error:', err);
                empty('chartPubTrend', 'could not load trend data');
            }
        }

        // ── Bootstrap ────────────────────────────────────────────────────
        async function init() {
            await populateYearFilter();
            await loadStatic();
            await loadFiltered('');
            pubLoaded = true;
        }

        // ── Year filter change handler ────────────────────────────────────
        document.getElementById('pubYearFilter')?.addEventListener('change', (e) => {
            loadFiltered(e.target.value);
        });

        // ── Tab activation (charts need visible container) ────────────────
        document.querySelectorAll('.ra-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'publications' && !pubLoaded) {
                    init();
                }
            });
        });

        // ── Run on DOMContentLoaded if this panel is already active ───────
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('publicationsDashboard');
            if (panel && panel.classList.contains('is-active')) {
                init();
            }
        });

        // Fallback: run now if DOM is already ready
        if (document.readyState !== 'loading') {
            const panel = document.getElementById('publicationsDashboard');
            if (panel && panel.classList.contains('is-active')) {
                init();
            }
        }

    })();
</script>