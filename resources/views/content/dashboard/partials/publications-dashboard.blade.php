{{--
    publications-dashboard.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Published Papers Dashboard — clean_publications dataset.
    Executive-first layout: headline KPIs → growth trend → campus reach →
    indexing quality profile → journal venues → monthly cadence → audit.

    New queries (no year filter — all data is unfiltered per request):
      • pub_by_year_clean         — yearly totals (0/null years excluded)
      • pub_by_campus_clean       — campus totals
      • pub_monthly_clean         — monthly totals (all years)
      • pub_campus_indexing_clean — campus × tier cross-tab
      • pub_top_journals_clean    — top 10 journals
      • pub_by_indexing_clean     — tier donut + table
      • pub_summary_clean         — headline KPI row
      • pub_yoy_growth            — year-over-year growth table
      • pub_year_campus_clean     — year × campus stacked bar
      • pub_quarterly_clean       — quarterly breakdown (2020-2026)
      • pub_campus_contribution   — campus % contribution

    Color system — ALL known indexing tiers get a distinct color:
      International       → #2dd4bf  teal
      Scopus              → #635bff  violet
      ACI                 → #f97316  orange
      Thomson Reuters/WOS → #06b6d4  cyan
      Local/Regional      → #eab308  yellow
      Unknown             → #94a3b8  slate
      (fallback)          → #9b9db0
--}}

{{-- ── Section 1: Executive KPI strip ─────────────────────────────────── --}}
<div class="pub-section-label">01 — Overview</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="pub-kpi">
            <div class="pub-kpi-label">Total publications</div>
            <div class="pub-kpi-num" id="pubKpiTotal"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot">all time · primary records</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="pub-kpi">
            <div class="pub-kpi-label">Campuses represented</div>
            <div class="pub-kpi-num" id="pubKpiCampuses"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot">with at least one paper</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="pub-kpi pub-kpi--scopus">
            <div class="pub-kpi-label">Scopus-indexed</div>
            <div class="pub-kpi-num" id="pubKpiScopus"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot" id="pubKpiScopusPct">— of total</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="pub-kpi pub-kpi--intl">
            <div class="pub-kpi-label">International</div>
            <div class="pub-kpi-num" id="pubKpiInternational"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot" id="pubKpiIntlPct">— of total</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="pub-kpi pub-kpi--growth" id="pubKpiGrowthCard">
            <div class="pub-kpi-label">Year-over-Year growth</div>
            <div class="pub-kpi-num" id="pubKpiGrowth"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot" id="pubKpiGrowthYear">vs. prior year</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="pub-kpi">
            <div class="pub-kpi-label">Papers this year</div>
            <div class="pub-kpi-num" id="pubKpiLatestYear"><span class="ra-skel"></span></div>
            <div class="pub-kpi-foot" id="pubKpiLatestYearLabel">most recent year on record</div>
        </div>
    </div>
</div>

{{-- ── Section 2: Publication trend + YoY growth ───────────────────────── --}}
<div class="pub-section-label">02 — Publication Trend</div>
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Annual publication output</h2>
                <div class="pub-card-sub" id="pubTrendSub">Total papers published per year — full historical range</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubTrend" style="min-height:280px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Year-over-year growth</h2>
                <div class="pub-card-sub">% change from previous year</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubYoY" style="min-height:280px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Section 3: Campus reach ─────────────────────────────────────────── --}}
<div class="pub-section-label">03 — Campus Reach</div>
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Publications by campus</h2>
                <div class="pub-card-sub">contribution share of each campus unit</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubCampus" style="min-height:300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Campus output over time</h2>
                <div class="pub-card-sub">year × campus stacked view — track each campus's growth trajectory</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubYearCampus" style="min-height:300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Section 4: Indexing quality profile ────────────────────────────── --}}
<div class="pub-section-label">04 — Indexing Quality Profile</div>
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Indexing tier distribution</h2>
                <div class="pub-card-sub">share of papers per indexing level</div>
            </div>
            <div class="px-3 pb-1">
                <div id="chartPubIndexing" style="min-height:220px;"></div>
            </div>
            <div class="px-3 pb-3">
                <table class="pub-table mb-0" id="pubIndexingTable">
                    <thead>
                        <tr>
                            <th>Tier</th>
                            <th class="text-end">Count</th>
                            <th class="text-end">Share</th>
                        </tr>
                    </thead>
                    <tbody id="pubIndexingTableBody">
                        <tr><td colspan="3" class="pub-empty">loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Campus output by indexing tier</h2>
                <div class="pub-card-sub">each campus's quality profile — Scopus, International, and other tiers side by side</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubCampusIndexing" style="min-height:300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Section 5: Quarterly cadence ────────────────────────────────────── --}}
<div class="pub-section-label">05 — Publishing Cadence</div>
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Quarterly output (2020–2026)</h2>
                <div class="pub-card-sub">papers submitted per quarter — recent 6-year window</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubQuarterly" style="min-height:260px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="pub-card h-100">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Monthly publication activity</h2>
                <div class="pub-card-sub">papers published per month — all years combined</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubMonthly" style="min-height:260px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Section 6: Top journals ──────────────────────────────────────────── --}}
<div class="pub-section-label">06 — Publication Venues</div>
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="pub-card">
            <div class="pub-card-head">
                <h2 class="pub-card-title">Top 10 publication venues</h2>
                <div class="pub-card-sub">journals and conferences with the highest paper counts across all years</div>
            </div>
            <div class="px-3 pb-3">
                <div id="chartPubTopJournals" style="min-height:320px;"></div>
            </div>
        </div>
    </div>
</div>



<style>
/* ── Tier color tokens — mapped to the shared dashboard theme ── */
:root {
    --tier-international:       var(--secondary, #1E3FA8);
    --tier-scopus:              var(--primary, #0B1F5C);
    --tier-aci:                 #4E7CF0;
    --tier-thomson:             var(--info, #AFC8F3);
    --tier-local:               #7F9FF8;
    --tier-unknown:             var(--muted, #5a6478);
    --tier-fallback:            var(--ink, #071240);
}

/* ── Section label ── */
.pub-section-label {
    font-family: var(--ra-mono, ui-monospace, monospace);
    font-size: .68rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--ra-text-dim, #0B1F5C);
    margin: 0 0 .75rem;
    padding-left: .1rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}
.pub-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--ra-line, #dbe4fb);
}

/* ── Page header ── */
.pub-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}
.pub-eyebrow {
    font-family: var(--ra-mono, ui-monospace, monospace);
    font-size: .68rem;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--tier-scopus);
    margin-bottom: .4rem;
}
.pub-title {
    font-family: var(--ra-serif, Georgia, serif);
    font-size: clamp(1.6rem, 2.5vw, 2rem);
    font-weight: 700;
    color: var(--ra-text, #1a1c2e);
    letter-spacing: -.02em;
    margin: 0 0 .3rem;
    line-height: 1.1;
}
.pub-sub {
    font-size: .85rem;
    color: var(--ra-text-dim, #64748b);
    margin: 0;
}
.pub-range-badge {
    display: inline-block;
    background: color-mix(in srgb, var(--tier-scopus) 12%, transparent);
    color: var(--tier-scopus);
    border: 1px solid color-mix(in srgb, var(--tier-scopus) 30%, transparent);
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 600;
    padding: .1rem .55rem;
    vertical-align: middle;
    margin-left: .3rem;
}

/* ── KPI cards ── */
.pub-kpi {
    position: relative;
    /* Mirror the overview KPI shell so the publication strip feels like the same dashboard family. */
    border: 1px solid var(--ra-line, #e2e8f0);
    border-radius: .8rem;
    background: #FFFDF9;
    padding: 1.3rem 1.4rem 1.25rem;
    height: 100%;
    overflow: hidden;
    box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .06);
    transition: box-shadow .18s, border-color .18s;
}
.pub-kpi::before {
    /* Remove the accent strip so the KPI cards match the cleaner overview card format. */
    display: none;
}
.pub-kpi:hover {
    /* Keep the hover effect subtle like the overview cards. */
    box-shadow: 0 .45rem 1.1rem rgba(26, 66, 160, .08);
    border-color: color-mix(in srgb, var(--ra-approved, #1E3FA8) 18%, transparent);
}
.pub-kpi--scopus::before        { background: var(--tier-scopus); }
.pub-kpi--scopus .pub-kpi-num   { color: var(--tier-scopus); }
.pub-kpi--scopus                { border-color: color-mix(in srgb, var(--tier-scopus) 25%, transparent); }

.pub-kpi--intl::before          { background: var(--tier-international); }
.pub-kpi--intl .pub-kpi-num     { color: var(--tier-international); }
.pub-kpi--intl                  { border-color: color-mix(in srgb, var(--tier-international) 25%, transparent); }

.pub-kpi--growth::before        { background: var(--tier-aci); }
.pub-kpi--growth .pub-kpi-num   { color: var(--tier-aci); }
.pub-kpi--growth.is-negative .pub-kpi-num { color: var(--danger, #e74c3c); }
.pub-kpi--growth.is-negative::before      { background: var(--danger, #e74c3c); }

.pub-kpi-num {
    font-family: var(--ra-serif, Georgia, serif);
    /* Match publication KPI values to the overview KPI number treatment. */
    font-size: 2.9rem;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -.03em;
    color: var(--ra-approved, #1E3FA8);
    margin: 0;
}
.pub-kpi-label {
    font-family: var(--ra-sans, 'Instrument Sans', 'Segoe UI', sans-serif);
    /* Match publication KPI labels to the overview card label treatment. */
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--ra-text, #071240);
    margin-bottom: .9rem;
}
.pub-kpi-foot {
    font-family: var(--ra-sans, 'Instrument Sans', 'Segoe UI', sans-serif);
    /* Match publication KPI support text to the overview footnote style. */
    font-size: 1.5rem;
    color: var(--ra-text-dim, #5a6478);
    margin-top: .85rem;
}

/* ── Cards ── */
.pub-card {
    border: 1px solid var(--ra-line, #e2e8f0);
    border-radius: 16px;
    background: var(--ra-panel, #fff);
    box-shadow: 0 .35rem 1rem rgba(26, 66, 160, .04);
    transition: box-shadow .2s, border-color .2s;
    overflow: hidden;
}
.pub-card:hover {
    box-shadow: 0 .55rem 1.4rem rgba(26, 66, 160, .08);
    border-color: color-mix(in srgb, var(--ra-approved, #1E3FA8) 18%, transparent);
}
.pub-card-head {
    padding: 1.1rem 1.2rem .65rem;
    border-bottom: 1px solid var(--ra-line-soft, #edf2ff);
}
.pub-card-title {
    font-family: var(--ra-serif, Georgia, serif);
    /* Reduce publication panel titles so they align with the shared dashboard cards. */
    font-size: 1.8rem;
    line-height: 1.15;
    font-weight: 700;
    color: var(--ra-text, #071240);
    margin: 0 0 .2rem;
}
.pub-card-sub {
    /* Reduce publication panel subtitles to keep the chart area balanced. */
    font-size: 1rem;
    line-height: 1.35;
    color: var(--ra-text-dim, #0B1F5C);
}

/* ── Indexing table ── */
.pub-table {
    width: 100%;
    border-collapse: collapse;
}
.pub-table thead th {
    font-family: var(--ra-mono, ui-monospace, monospace);
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--ra-text-faint, #5a6478);
    padding: .4rem .5rem;
    border-bottom: 1px solid var(--ra-line, #dbe4fb);
    font-weight: 500;
}
.pub-table tbody td {
    font-size: .8rem;
    color: var(--ra-text-dim, #0B1F5C);
    padding: .4rem .5rem;
    border-bottom: 1px solid var(--ra-line-soft, #edf2ff);
}
.pub-table tbody tr:hover td {
    background: color-mix(in srgb, var(--ra-approved, #1E3FA8) 5%, transparent);
}
.pub-tier-dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
    margin-right: 5px;
    vertical-align: middle;
    flex-shrink: 0;
}

/* ── Quality flag chips ── */
.pub-flag-chip {
    display: flex;
    align-items: center;
    gap: .6rem;
    border: 1px solid var(--ra-line, #e2e8f0);
    border-radius: 12px;
    padding: .75rem 1rem;
    background: var(--ra-panel, #fff);
    box-shadow: 0 .25rem .85rem rgba(26, 66, 160, .035);
    transition: transform .15s, border-color .15s, box-shadow .15s;
}
.pub-flag-chip:hover {
    transform: translateY(-1px);
    border-color: color-mix(in srgb, var(--ra-approved, #1E3FA8) 18%, transparent);
    box-shadow: 0 .45rem 1rem rgba(26, 66, 160, .06);
}
.pub-flag-icon { font-size: 1.2rem; line-height: 1; }
.pub-flag-count {
    font-family: var(--ra-mono, ui-monospace, monospace);
    font-size: 1.3rem;
    font-weight: 700;
    line-height: 1;
    color: var(--ra-text, #1a1c2e);
}
.pub-flag-label { font-size: .72rem; color: var(--ra-text-dim, #64748b); }
.pub-flag-chip--warn { border-color: color-mix(in srgb, var(--tier-aci) 35%, transparent); }
.pub-flag-chip--warn .pub-flag-count { color: var(--tier-aci); }
.pub-flag-chip--ok   { border-color: color-mix(in srgb, var(--ra-approved, #1E3FA8) 28%, transparent); }
.pub-flag-chip--ok   .pub-flag-count { color: var(--ra-approved, #1E3FA8); }

/* ── Skeleton ── */
.ra-skel {
    display: inline-block;
    height: 1em; width: 3em;
    border-radius: 6px;
    background: linear-gradient(90deg, var(--ra-line,#e2e8f0) 25%, #f8fafc 50%, var(--ra-line,#e2e8f0) 75%);
    background-size: 200% 100%;
    animation: pub-shimmer 1.3s ease-in-out infinite;
}
@keyframes pub-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.pub-empty {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--ra-text-faint, #94a3b8);
    font-size: .8rem;
}
</style>

<script>
(function () {
    'use strict';

    const API_BASE = window.RESEARCH_API_BASE ||
        '{{ rtrim(config("services.research_api.url", "http://127.0.0.1:8001"), "/") }}';

    const pageEl = document.getElementById('raPage') || document.body;
    const MONO   = "'JetBrains Mono','IBM Plex Mono',ui-monospace,monospace";
    // Keep the publications page aligned with the dashboard reporting window.
    const PUB_MIN_YEAR = 2017;

    // ── ALL known indexing tiers — single source of truth ───────────────
    // These colors now read from the shared theme tokens so every publication
    // chart stays aligned with the dashboard palette.
    const TIER_COLORS = {
        'International':       cssVar('--tier-international') || '#1E3FA8',
        'Scopus':              cssVar('--tier-scopus') || '#0B1F5C',
        'ACI':                 cssVar('--tier-aci') || '#ffc107',
        'Thomson Reuters/WOS': cssVar('--tier-thomson') || '#AFC8F3',
        'Local/Regional':      cssVar('--tier-local') || '#2E7D32',
        'Local':               cssVar('--tier-local') || '#2E7D32',
        'Unknown':             cssVar('--tier-unknown') || '#5a6478',
        'Unspecified':         cssVar('--tier-fallback') || '#071240',
    };
    const TIER_FALLBACK = cssVar('--tier-fallback') || '#071240';
    const tierColor = (t) => TIER_COLORS[t] || TIER_FALLBACK;

    // ── State ────────────────────────────────────────────────────────────
    const charts   = {};
    let   pubLoaded = false;

    // ── Utilities ────────────────────────────────────────────────────────
    const fmtInt  = n => Number(n ?? 0).toLocaleString('en-US');
    const fmtPct  = n => n == null ? '—' : `${Number(n).toFixed(1)}%`;
    const escHtml = s => String(s ?? '').replace(/[&<>"']/g, c =>
        ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const setText = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };

    function cssVar(name) {
        return getComputedStyle(pageEl).getPropertyValue(name).trim();
    }
    function theme() {
        return pageEl.dataset.theme === 'light' ? 'light' : 'dark';
    }

    // ── Base chart options ────────────────────────────────────────────────
    function base(overrides = {}) {
        const defaults = {
            chart: {
                fontFamily: 'Public Sans, sans-serif',
                background: 'transparent',
                toolbar: { show: false },
                animations: { easing: 'easeinout', speed: 420 },
            },
            theme:      { mode: theme() },
            colors:     Object.values(TIER_COLORS),
            dataLabels: { enabled: false },
            grid:       { borderColor: cssVar('--ra-line') || '#e2e8f0', strokeDashArray: 3 },
            tooltip:    { theme: theme() },
            legend:     { labels: { colors: cssVar('--ra-text-dim') || '#64748b' } },
            states:     { hover: { filter: { type: 'darken', value: .9 } }, active: { filter: { type: 'none' } } },
        };
        // Deep-merge chart sub-key so callers don't accidentally clobber toolbar:false
        if (overrides.chart) {
            overrides.chart = Object.assign({}, defaults.chart, overrides.chart);
        }
        return Object.assign({}, defaults, overrides);
    }

    // ── Chart lifecycle ───────────────────────────────────────────────────
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
        if (el) el.innerHTML = `<div class="pub-empty">${msg}</div>`;
    }

    // ── API helpers ───────────────────────────────────────────────────────
    async function apiFetch(path) {
        const res = await fetch(`${API_BASE}${path}`, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error(`${path} → HTTP ${res.status}`);
        return res.json();
    }

    // ── KPI strip ─────────────────────────────────────────────────────────
    function renderKpis(summary, yoyRows, yearlyRows) {
        setText('pubKpiTotal',         fmtInt(summary.total_publications));
        setText('pubKpiCampuses',      fmtInt(summary.total_campuses));
        setText('pubKpiScopus',        fmtInt(summary.scopus_publications));
        setText('pubKpiScopusPct',     fmtPct(summary.scopus_percentage) + ' of total');
        setText('pubKpiInternational', fmtInt(summary.international_publications));
        setText('pubKpiIntlPct',       fmtPct(summary.international_percentage) + ' of total');

        // Latest year count
        const validYears = (yearlyRows || []).filter((r) => {
            const year = Number(r.year_published);
            return year >= PUB_MIN_YEAR;
        });
        if (validYears.length) {
            const latest = validYears[validYears.length - 1];
            setText('pubKpiLatestYear',      fmtInt(latest.total_publications));
            setText('pubKpiLatestYearLabel', `in ${latest.year_published}`);
        }

        // Most recent YoY growth
        if (yoyRows && yoyRows.length) {
            const last = yoyRows[yoyRows.length - 1];
            const pct  = Number(last.growth_percentage || 0);
            const sign = pct >= 0 ? '+' : '';
            setText('pubKpiGrowth',     `${sign}${pct.toFixed(1)}%`);
            setText('pubKpiGrowthYear', `vs. ${last.year_published - 1}`);
            const card = document.getElementById('pubKpiGrowthCard');
            if (card) card.classList.toggle('is-negative', pct < 0);
        }
    }

    // ── Annual trend ──────────────────────────────────────────────────────
    function renderTrend(rows) {
        // Ignore stray historical records so the publication story starts in 2017.
        const clean = (rows || []).filter((r) => {
            const year = Number(r.year_published);
            return year >= PUB_MIN_YEAR;
        });
        if (!clean.length) { empty('chartPubTrend', 'no yearly data yet'); return; }

        const years  = clean.map(r => Number(r.year_published));
        const counts = clean.map(r => Number(r.total_publications || 0));
        const minY   = Math.min(...years);
        const maxY   = Math.max(...years);

        const sub = document.getElementById('pubTrendSub');
        if (sub) sub.textContent = `Total papers published per year — ${minY} to ${maxY}`;

        // Year-range badge
        const badge = document.getElementById('pubYearRangeBadge');
        if (badge) badge.textContent = `${minY} – ${maxY}`;

        const peakIdx = counts.indexOf(Math.max(...counts));

        draw('trend', 'chartPubTrend', base({
            chart:  { type: 'area', height: 280 },
            series: [{ name: 'Publications', data: counts }],
            colors: [TIER_COLORS.Scopus],
            xaxis:  {
                categories: years,
                // Show every year as integer — no decimals, no year-0
                labels: {
                    formatter: v => String(v),
                    // Enlarge the year labels so the annual trend is easier to read.
                    style: { fontSize: '16px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -35,
                },
                tickAmount: Math.min(years.length, 12),
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
                title: {
                    text: `Year (${minY}–${maxY})`,
                    // Enlarge the x-axis title to match the larger chart typography.
                    style: { fontSize: '16px', color: cssVar('--ra-text-faint') || '#94a3b8' },
                },
            },
            yaxis:  {
                min: 0,
                labels: {
                    formatter: v => fmtInt(v),
                    // Enlarge the vertical scale labels for readability.
                    style: { fontSize: '16px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                },
                title: {
                    text: 'Papers',
                    // Enlarge the y-axis title so it stays proportionate to the chart.
                    style: { fontSize: '16px', color: cssVar('--ra-text-faint') || '#94a3b8' }
                },
            },
            stroke: { curve: 'smooth', width: 2.5 },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: .3, opacityTo: 0, stops: [0, 95, 100] },
            },
            annotations: {
                points: [{
                    x: years[peakIdx],
                    y: counts[peakIdx],
                    marker: { size: 5, fillColor: TIER_COLORS.Scopus, strokeColor: '#fff', radius: 2 },
                    label: {
                        text: `Peak ${fmtInt(counts[peakIdx])}`,
                        style: {
                            background: TIER_COLORS.Scopus, color: '#fff',
                            fontSize: '11px',
                            padding: { left:6, right:6, top:3, bottom:3 },
                        },
                        offsetY: -10,
                    },
                }],
            },
        }));
    }

    // ── YoY growth bar ────────────────────────────────────────────────────
    function renderYoY(rows) {
        const clean = (rows || []).filter((r) => {
            const year = Number(r.year_published);
            return year >= PUB_MIN_YEAR;
        });
        if (!clean.length) { empty('chartPubYoY', 'no growth data yet'); return; }

        const years  = clean.map(r => Number(r.year_published));
        const values = clean.map(r => Number(r.growth_percentage || 0));
        // Match the growth chart to the overview palette: deep blue for growth
        // and a softer coral-red for decline.
        const growthPositive = cssVar('--ra-approved') || '#1E3FA8';
        const growthNegative = '#D36B5E';
        const colors = values.map(v => v >= 0 ? growthPositive : growthNegative);

        draw('yoy', 'chartPubYoY', base({
            chart: { type: 'bar', height: 280 },
            plotOptions: { bar: { borderRadius: 3, columnWidth: '55%', colors: { ranges: [] } } },
            series: [{ name: 'Growth %', data: values }],
            colors: [growthPositive],  // overridden per-bar below
            fill:   { colors },
            xaxis: {
                categories: years,
                labels: {
                    formatter: v => String(v),
                    style: { fontSize: '11px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -35,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    formatter: v => `${v.toFixed(1)}%`,
                    style: { colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                },
            },
            tooltip: {
                theme: theme(),
                y: { formatter: v => `${v.toFixed(1)}%` },
            },
            // Color bars individually based on positive/negative
            dataLabels: {
                enabled: true,
                formatter: v => `${v > 0 ? '+' : ''}${v.toFixed(1)}%`,
                style: { fontSize: '10px', fontFamily: MONO, colors: [cssVar('--ra-text') || '#1a1c2e'] },
            },
        }));

        // ApexCharts doesn't support per-bar colors natively in bar type;
        // use a workaround with individual series of 1 point each.
        // Destroy and redraw with multi-series trick:
        if (charts['yoy']) { charts['yoy'].destroy(); delete charts['yoy']; }
        const el = document.getElementById('chartPubYoY');
        if (!el) return;
        el.innerHTML = '';

        const posSeries = values.map((v, i) => ({ x: years[i], y: v >= 0 ? v : null }));
        const negSeries = values.map((v, i) => ({ x: years[i], y: v < 0  ? v : null }));

        charts['yoy'] = new ApexCharts(el, base({
            chart: { type: 'bar', height: 280, stacked: false },
            plotOptions: { bar: { borderRadius: 3, columnWidth: '55%' } },
            series: [
                { name: 'Growth', data: posSeries, color: growthPositive },
                { name: 'Decline', data: negSeries, color: growthNegative },
            ],
            colors: [growthPositive, growthNegative],
            xaxis: {
                type: 'numeric',
                tickAmount: Math.min(years.length, 12),
                labels: {
                    formatter: v => String(Math.round(v)),
                    // Enlarge the year labels so the growth chart is easier to scan.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -35,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    formatter: v => `${v > 0 ? '+' : ''}${v.toFixed(1)}%`,
                    // Enlarge the percent scale labels on the vertical axis.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                },
            },
            tooltip: { theme: theme(), y: { formatter: v => v == null ? '—' : `${v > 0 ? '+' : ''}${v.toFixed(1)}%` } },
            legend: { show: false },
            dataLabels: {
                enabled: true,
                formatter: v => v == null ? '' : `${v > 0 ? '+' : ''}${v.toFixed(1)}%`,
                // Enlarge the percentage labels shown on the bars.
                style: { fontSize: '15px', fontFamily: MONO, colors: [cssVar('--ra-text') || '#1a1c2e'] },
                background: { enabled: false },
            },
        }));
        charts['yoy'].render();
    }

    // ── Campus bar ────────────────────────────────────────────────────────
    function renderCampus(rows) {
        if (!rows.length) { empty('chartPubCampus', 'no campus data yet'); return; }
        draw('campus', 'chartPubCampus', base({
            chart: { type: 'bar', height: Math.max(300, rows.length * 40) },
            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '52%' } },
            series: [{ name: 'Publications', data: rows.map(r => Number(r.publications || r.total_publications || 0)) }],
            colors: [TIER_COLORS.Scopus],
            xaxis: {
                categories: rows.map(r => r.campus),
                // Enlarge the numeric scale labels below the campus bars.
                labels: { style: { colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO, fontSize: '15px' } },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    // Enlarge the campus labels on the left side of the chart.
                    style: { colors: cssVar('--ra-text-dim') || '#64748b', fontSize: '15px' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (v, { dataPointIndex }) => {
                    const pct = rows[dataPointIndex]?.contribution_percentage;
                    return pct != null ? `${fmtInt(v)} (${Number(pct).toFixed(1)}%)` : fmtInt(v);
                },
                    // Enlarge the value labels shown at the end of each bar.
                    style: { fontSize: '15px', fontFamily: MONO, colors: [cssVar('--ra-text') || '#1a1c2e'] },
            },
            tooltip: {
                theme: theme(),
                y: {
                    formatter: (v, { dataPointIndex }) => {
                        const pct = rows[dataPointIndex]?.contribution_percentage;
                        return `${fmtInt(v)} papers${pct != null ? ` · ${Number(pct).toFixed(1)}% of total` : ''}`;
                    },
                },
            },
        }));
    }

    // ── Year × campus stacked bar ──────────────────────────────────────────
    function renderYearCampus(rows) {
        const clean = (rows || []).filter((r) => {
            const year = Number(r.year_published);
            return year >= PUB_MIN_YEAR;
        });
        if (!clean.length) { empty('chartPubYearCampus', 'no year × campus data yet'); return; }

        const years     = [...new Set(clean.map(r => Number(r.year_published)))].sort((a,b) => a-b);
        const campuses  = [...new Set(clean.map(r => r.campus))];

        const pivot = {};
        clean.forEach(r => {
            const y = Number(r.year_published);
            const c = r.campus;
            if (!pivot[c]) pivot[c] = {};
            pivot[c][y] = Number(r.publications || r.total || 0);
        });

        // Use the overview-style blue family so the campus trend chart matches
        // the rest of the dashboard instead of mixing unrelated accent colors.
        const campusPalette = [
            '#0B1F5C',
            '#1E3FA8',
            '#4E7CF0',
            '#7EA4F7',
            '#AFC8F3',
            '#071240',
            '#2B56C2',
            '#5F8AF4',
            '#8FB1FF',
            '#3552A3',
        ];
        const series = campuses.map((campus, i) => ({
            name: campus,
            data: years.map(y => pivot[campus]?.[y] || 0),
            color: campusPalette[i % campusPalette.length],
        }));

        draw('yearCampus', 'chartPubYearCampus', base({
            chart: { type: 'bar', height: 300, stacked: true },
            plotOptions: { bar: { borderRadius: 0, columnWidth: '65%' } },
            series,
            colors: campusPalette,
            xaxis: {
                categories: years,
                labels: {
                    formatter: v => String(v),
                    // Enlarge the year labels in the stacked campus timeline.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -35,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    // Enlarge the publication scale labels for the stacked campus chart.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO }
                },
                title: {
                    text: 'Papers',
                    // Enlarge the y-axis title to match the larger graph typography.
                    style: { fontSize: '15px', color: cssVar('--ra-text-faint') || '#94a3b8' }
                },
            },
            legend: {
                position: 'bottom',
                // Enlarge the campus legend so the color key is easier to read.
                fontSize: '14px',
                labels: { colors: cssVar('--ra-text-dim') || '#64748b' },
            },
            tooltip: { theme: theme() },
        }));
    }

    // ── Indexing-tier donut ────────────────────────────────────────────────
    function renderIndexing(rows) {
        if (!rows.length) { empty('chartPubIndexing', 'no indexing data yet'); return; }

        const colors = rows.map(r => tierColor(r.indexing_tier || 'Unspecified'));

        draw('indexing', 'chartPubIndexing', base({
            chart: { type: 'donut', height: 220 },
            labels: rows.map(r => r.indexing_tier || 'Unspecified'),
            series: rows.map(r => Number(r.total || 0)),
            colors,
            // Keep the tier callouts in the side legend so the donut stays clean
            // while the label + percentage remain readable beside the chart.
            dataLabels: {
                enabled: false,
            },
            stroke: { colors: [cssVar('--ra-panel') || '#fff'], width: 2 },
            legend: {
                position: 'right',
                // Enlarge the side legend so the tier names and percentages are readable.
                fontSize: '14px',
                labels: { colors: cssVar('--ra-text-dim') || '#64748b' },
                formatter: (seriesName, opts) => {
                    const pct = Number(opts.w.globals.seriesPercent[opts.seriesIndex] || 0);
                    return `${seriesName} (${Math.round(pct)}%)`;
                },
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                color: cssVar('--ra-text-dim') || '#64748b',
                                fontSize: '16px',
                            },
                            value: {
                                color: cssVar('--ra-text') || '#1a1c2e',
                                fontFamily: MONO,
                                fontSize: '18px',
                            },
                        },
                    },
                },
            },
        }));

        const tbody = document.getElementById('pubIndexingTableBody');
        if (!tbody) return;
        tbody.innerHTML = rows.map(r => {
            const color = tierColor(r.indexing_tier || 'Unspecified');
            return `<tr>
                <td style="display:flex;align-items:center;gap:4px;">
                    <span class="pub-tier-dot" style="background:${color}"></span>
                    ${escHtml(r.indexing_tier || 'Unspecified')}
                </td>
                <td class="text-end" style="font-family:${MONO};font-size:.78rem;">${fmtInt(r.total)}</td>
                <td class="text-end" style="font-family:${MONO};font-size:.78rem;color:${color};font-weight:700;">${fmtPct(r.percentage)}</td>
            </tr>`;
        }).join('');
    }

    // ── Campus × indexing tier grouped bar ─────────────────────────────────
    function renderCampusIndexing(rows) {
        if (!rows.length) { empty('chartPubCampusIndexing', 'no cross-tab data yet'); return; }

        const campusSet = [...new Set(rows.map(r => r.campus))];
        const tierSet   = [...new Set(rows.map(r => r.indexing_tier || 'Unspecified'))];

        // Sort tiers so highest-prestige (Scopus, International) come first
        const tierOrder = ['Scopus','International','ACI','Thomson Reuters/WOS','Local/Regional','Local','Unknown','Unspecified'];
        tierSet.sort((a, b) => {
            const ai = tierOrder.indexOf(a), bi = tierOrder.indexOf(b);
            return (ai === -1 ? 99 : ai) - (bi === -1 ? 99 : bi);
        });

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
            color: tierColor(tier),
        }));

        draw('campusIndexing', 'chartPubCampusIndexing', base({
            chart: { type: 'bar', height: 300, stacked: false },
            plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
            series,
            colors: tierSet.map(t => tierColor(t)),
            xaxis: {
                categories: campusSet,
                labels: {
                    // Enlarge the campus labels for the grouped indexing chart.
                    style: { fontSize: '14px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -40,
                    trim: true,
                    maxHeight: 80,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    // Enlarge the publication scale labels on the grouped indexing chart.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO }
                },
                title: {
                    text: 'Papers',
                    // Enlarge the axis title to keep it proportionate to the chart labels.
                    style: { fontSize: '15px', color: cssVar('--ra-text-faint') || '#94a3b8' }
                },
            },
            legend: {
                position: 'top',
                // Enlarge the indexing-tier legend text for easier comparison.
                fontSize: '14px',
                labels: { colors: cssVar('--ra-text-dim') || '#64748b' },
            },
        }));
    }

    // ── Quarterly breakdown ────────────────────────────────────────────────
    function renderQuarterly(rows) {
        if (!rows.length) { empty('chartPubQuarterly', 'no quarterly data yet'); return; }

        const categories = rows.map(r => `${r.year_published} ${r.quarter}`);
        const values     = rows.map(r => Number(r.publications || 0));

        draw('quarterly', 'chartPubQuarterly', base({
            chart: { type: 'bar', height: 260 },
            plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
            series: [{ name: 'Publications', data: values }],
            colors: [TIER_COLORS.International],
            xaxis: {
                categories,
                labels: {
                    // Enlarge the quarterly labels so the recent-window timeline is easier to read.
                    style: { fontSize: '14px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -45,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    formatter: v => fmtInt(v),
                    // Enlarge the quarterly scale labels on the vertical axis.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                },
            },
        }));
    }

    // ── Monthly trend ─────────────────────────────────────────────────────
    function renderMonthly(rows) {
        if (!rows.length) { empty('chartPubMonthly', 'no monthly data yet'); return; }

        const MONTHS = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const labels = rows.map(r => `${MONTHS[r.month_published] || r.month_published} ${r.year_published}`);
        const values = rows.map(r => Number(r.total || 0));

        draw('monthly', 'chartPubMonthly', base({
            chart: { type: 'area', height: 260 },
            series: [{ name: 'Publications', data: values }],
            colors: [cssVar('--info') || '#AFC8F3'],
            xaxis: {
                categories: labels,
                tickAmount: 8,
                labels: {
                    // Enlarge the month labels so the monthly activity chart reads clearly.
                    style: { fontSize: '14px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                    rotate: -35,
                },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    formatter: v => fmtInt(v),
                    // Enlarge the monthly scale labels on the vertical axis.
                    style: { fontSize: '15px', colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO },
                },
            },
            stroke: { curve: 'smooth', width: 2.5 },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: .28, opacityTo: 0, stops: [0, 90, 100] },
            },
        }));
    }

    // ── Top journals ──────────────────────────────────────────────────────
    function renderTopJournals(rows) {
        if (!rows.length) { empty('chartPubTopJournals', 'no journal data yet'); return; }

        const truncate = (s, n = 50) => s && s.length > n ? s.slice(0, n) + '…' : (s || 'Unknown');
        const labels   = rows.map(r => truncate(r.publication_name));
        const values   = rows.map(r => Number(r.total_publications || 0));

        draw('journals', 'chartPubTopJournals', base({
            chart: { type: 'bar', height: 320 },
            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '52%' } },
            series: [{ name: 'Papers', data: values }],
            colors: [TIER_COLORS.International],
            xaxis: {
                categories: labels,
                // Enlarge the journal-count scale labels for better readability.
                labels: { style: { colors: cssVar('--ra-text-faint') || '#94a3b8', fontFamily: MONO, fontSize: '14px' } },
                axisBorder: { color: cssVar('--ra-line') || '#e2e8f0' },
                axisTicks:  { color: cssVar('--ra-line') || '#e2e8f0' },
            },
            yaxis: {
                labels: {
                    // Enlarge the journal names on the left side of the ranking.
                    style: { colors: cssVar('--ra-text-dim') || '#64748b', fontSize: '14px' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: v => fmtInt(v),
                // Enlarge the journal totals shown at the end of each bar.
                style: { fontSize: '14px', fontFamily: MONO, colors: [cssVar('--ra-text') || '#1a1c2e'] },
            },
            tooltip: {
                theme: theme(),
                custom: ({ dataPointIndex }) => `
                    <div style="padding:8px 12px;font-size:12px;max-width:300px;word-break:break-word;">
                        <strong>${escHtml(rows[dataPointIndex]?.publication_name || '')}</strong><br>
                        ${fmtInt(values[dataPointIndex])} papers
                    </div>`,
            },
        }));
    }

    // ── Data quality flags ─────────────────────────────────────────────────
    function renderQualityFlags(flags) {
        const container = document.getElementById('pubQualityFlags');
        if (!container) return;

        const items = [
            { key: 'duplicate_titles', label: 'Duplicate titles', icon: '⚠️' },
            { key: 'bad_links',        label: 'Broken links',     icon: '🔗' },
            { key: 'bad_dates',        label: 'Invalid dates',    icon: '📅' },
            { key: 'missing_dates',    label: 'Missing dates',    icon: '📅' },
            { key: 'missing_indexing', label: 'Missing indexing', icon: '🏷️' },
        ];

        container.innerHTML = items.map(item => {
            const count = Number(flags[item.key] ?? 0);
            const mod   = count > 0 ? 'pub-flag-chip--warn' : 'pub-flag-chip--ok';
            return `
            <div class="col-6 col-sm-4 col-lg-2">
                <div class="pub-flag-chip ${mod}">
                    <span class="pub-flag-icon">${item.icon}</span>
                    <div>
                        <div class="pub-flag-count">${fmtInt(count)}</div>
                        <div class="pub-flag-label">${item.label}</div>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    // ── Main init ─────────────────────────────────────────────────────────
    async function init() {
        try {
            // Fetch all data in parallel — no year filter on any endpoint
            const [
                summary,
                yearlyRows,
                yoyRows,
                campusRows,
                monthlyRows,
                campusIndexingRows,
                topJournalRows,
                indexingRows,
                qualityFlags,
                yearCampusRows,
                quarterlyRows,
            ] = await Promise.all([
                apiFetch('/api/publications/summary'),
                apiFetch('/api/publications/by-year'),
                apiFetch('/api/publications/yoy-growth'),
                apiFetch('/api/publications/campus-contribution'),
                apiFetch('/api/publications/monthly-all'),
                apiFetch('/api/publications/campus-indexing'),
                apiFetch('/api/publications/top-journals'),
                apiFetch('/api/publications/by-indexing-tier'),
                apiFetch('/api/publications/data-quality'),
                apiFetch('/api/publications/year-campus'),
                apiFetch('/api/publications/quarterly'),
            ]);

            renderKpis(summary, yoyRows, yearlyRows);
            renderTrend(yearlyRows);
            renderYoY(yoyRows);
            renderCampus(campusRows);
            renderYearCampus(yearCampusRows);
            renderIndexing(indexingRows);
            renderCampusIndexing(campusIndexingRows);
            renderQuarterly(quarterlyRows);
            renderMonthly(monthlyRows);
            renderTopJournals(topJournalRows);
            renderQualityFlags(qualityFlags);

            pubLoaded = true;
        } catch (err) {
            console.error('Publications init error:', err);
            const errEl = document.getElementById('raGlobalError');
            if (errEl) {
                errEl.textContent = `Could not load publications data from ${API_BASE}. Make sure the FastAPI service is running. (${err.message})`;
                errEl.style.display = 'block';
            }
        }
    }

    // ── Tab activation ────────────────────────────────────────────────────
    document.querySelectorAll('.ra-tab, .ra-topnav-link').forEach(tab => {
        tab.addEventListener('click', () => {
            if (tab.dataset.tab === 'publications' && !pubLoaded) {
                requestAnimationFrame(() => requestAnimationFrame(init));
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const panel = document.getElementById('publicationsDashboard');
        if (panel?.classList.contains('is-active')) init();
    });

    if (document.readyState !== 'loading') {
        const panel = document.getElementById('publicationsDashboard');
        if (panel?.classList.contains('is-active')) init();
    }

})();
</script>
