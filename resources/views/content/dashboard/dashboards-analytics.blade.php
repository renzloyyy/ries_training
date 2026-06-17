@extends('layouts/blankLayout')

@section('title', 'Research Analytics')

{{--
    Confirmed working against this project's actual layout file:
    resources/views/layouts/blankLayout.blade.php (no sidebar/navbar
    partials included, so this page renders with zero menu chrome).
--}}

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
  ])
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
      --ra-bg:        #0A0D12;
      --ra-panel:     #11151D;
      --ra-panel-2:   #161B25;
      --ra-line:      #232A36;
      --ra-line-soft: #1A202B;
      --ra-text:      #E7E5DE;
      --ra-text-dim:  #8B92A1;
      --ra-text-faint:#586272;
      --ra-approved:  #7FB39A;
      --ra-approved-dim: #3D5448;
      --ra-pending:   #D9A35C;
      --ra-pending-dim: #4A3D29;
      --ra-danger:    #C2705E;
      --ra-serif: 'Lora', 'Georgia', serif;
      --ra-mono: 'JetBrains Mono', 'IBM Plex Mono', ui-monospace, 'SF Mono', Menlo, Consolas, monospace;
    }

    @font-face { font-family: 'Lora'; src: local('Lora'); }
    @font-face { font-family: 'JetBrains Mono'; src: local('JetBrains Mono'); }

    .ra-page {
      background-color: var(--ra-bg);
      background-image:
        radial-gradient(circle at 12% 0%, rgba(127,179,154,0.05), transparent 38%),
        radial-gradient(circle at 88% 12%, rgba(217,163,92,0.04), transparent 42%);
      color: var(--ra-text);
      min-height: 100vh;
      padding: 1.75rem clamp(1rem, 3vw, 2.5rem) 3rem;
    }

    .ra-page, .ra-page * { box-sizing: border-box; }

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

    .ra-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--ra-approved);
      display: inline-block;
      box-shadow: 0 0 0 0 rgba(127,179,154,.55), 0 0 6px 1px rgba(127,179,154,.55);
      animation: ra-pulse 2.6s infinite;
    }

    @keyframes ra-pulse {
      0%   { box-shadow: 0 0 0 0 rgba(127,179,154,.45), 0 0 6px 1px rgba(127,179,154,.45); }
      70%  { box-shadow: 0 0 0 7px rgba(127,179,154,0), 0 0 6px 1px rgba(127,179,154,.3); }
      100% { box-shadow: 0 0 0 0 rgba(127,179,154,0), 0 0 6px 1px rgba(127,179,154,0); }
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
      background: linear-gradient(90deg, rgba(127,179,154,.5), transparent 60%);
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
      0%   { background-position: 100% 50%; }
      100% { background-position: 0 50%; }
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

    .ra-funnel-row:last-child { border-bottom: none; }

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
      box-shadow: 0 0 8px 0 rgba(127,179,154,.45);
      transition: width 1s cubic-bezier(.22,.9,.32,1);
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

    .ra-legend span { display: inline-flex; align-items: center; gap: .45rem; }
    .ra-legend i { width: 7px; height: 7px; border-radius: 1px; display: inline-block; }

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

    .ra-table tbody tr:hover td { color: var(--ra-text); }

    .ra-empty {
      text-align: center;
      padding: 2.4rem 1rem;
      color: var(--ra-text-faint);
      font-family: var(--ra-mono);
      font-size: .78rem;
    }

    .ra-error {
      background: rgba(194,112,94,.08);
      border: 1px solid rgba(194,112,94,.3);
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

    .ra-search::placeholder { color: var(--ra-text-faint); }
    .ra-search:focus { box-shadow: 0 0 0 2px rgba(127,179,154,.25) !important; border-color: var(--ra-approved) !important; }

    .ra-grid { display: grid; gap: .9rem; }

    @media (max-width: 575.98px) {
      .ra-funnel-row { grid-template-columns: 96px 1fr 46px; }
    }
  </style>
@endsection

@section('content')
<div class="ra-page">

  <div class="ra-header">
    <div>
      <div class="ra-eyebrow">Research &amp; Development Office</div>
      <h1 class="ra-title">Research Proposal Analytics</h1>
      <div class="ra-subtitle">Submission volume, approval velocity, and reach across campuses, programs, and SDGs.</div>
    </div>
    <div class="ra-asof">
      <span class="ra-dot"></span>
      <span id="raLastUpdated">connecting&hellip;</span>
    </div>
  </div>

  <div id="raGlobalError" style="display:none" class="ra-error"></div>

  {{-- KPI strip --}}
  <div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
      <div class="ra-kpi-card">
        <div class="ra-kpi-label">Total proposals</div>
        <div class="ra-kpi-value" id="kpiTotal"><span class="ra-skel"></span></div>
        <div class="ra-kpi-foot">across all campuses</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="ra-kpi-card">
        <div class="ra-kpi-label">Approval rate</div>
        <div class="ra-kpi-value" id="kpiApprovalRate"><span class="ra-skel"></span></div>
        <div class="ra-kpi-foot">completed &divide; (completed + pending)</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="ra-kpi-card">
        <div class="ra-kpi-label">Campuses reporting</div>
        <div class="ra-kpi-value" id="kpiCampuses"><span class="ra-skel"></span></div>
        <div class="ra-kpi-foot">with at least one proposal</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="ra-kpi-card">
        <div class="ra-kpi-label">Leading SDG</div>
        <div class="ra-kpi-value" id="kpiTopSdg" style="font-size:1.3rem;"><span class="ra-skel"></span></div>
        <div class="ra-kpi-foot" id="kpiTopSdgFoot">by proposal count</div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    {{-- Status distribution --}}
    <div class="col-lg-5">
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

    {{-- Quarterly trend --}}
    <div class="col-lg-7">
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
  </div>

  <div class="row g-3 mb-3">
    {{-- Signature element: approval signal bars by campus --}}
    <div class="col-lg-6">
      <div class="ra-card h-100">
        <div class="ra-card-head">
          <div>
            <h2 class="ra-card-title">Approval rate by campus</h2>
            <div class="ra-card-sub">completed vs. pending share</div>
          </div>
        </div>
        <div class="px-3" id="funnelList">
          <div class="ra-empty">loading campus data&hellip;</div>
        </div>
        <div class="ra-legend">
          <span><i style="background:var(--ra-approved)"></i> completed</span>
          <span><i style="background:var(--ra-line); border:1px solid var(--ra-text-faint)"></i> pending</span>
        </div>
      </div>
    </div>

    {{-- SDG breakdown --}}
    <div class="col-lg-6">
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

  {{-- Campus x Program detail table --}}
  <div class="row g-3 mb-3">
    <div class="col-12">
      <div class="ra-card">
        <div class="ra-card-head pb-2" style="align-items:center;">
          <div>
            <h2 class="ra-card-title">Campus &amp; program breakdown</h2>
            <div class="ra-card-sub">every program, grouped by campus</div>
          </div>
          <input
            type="search"
            id="programFilter"
            class="form-control form-control-sm ra-search"
            style="max-width:220px;"
            placeholder="filter campus or program&hellip;"
          />
        </div>
        <div class="px-3 pb-3" style="max-height:420px; overflow-y:auto;">
          <table class="table ra-table mb-0" id="programTable">
            <thead>
              <tr>
                <th>Campus</th>
                <th>Program</th>
                <th class="text-end">Total proposals</th>
              </tr>
            </thead>
            <tbody id="programTableBody">
              <tr><td colspan="3" class="ra-empty">loading&hellip;</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/apex-charts/apexcharts.js',
  ])
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
    (function () {
      const API_BASE = window.RESEARCH_API_BASE || '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

      const TEXT = '#E7E5DE';
      const TEXT_DIM = '#8B92A1';
      const TEXT_FAINT = '#586272';
      const LINE = '#232A36';
      const APPROVED = '#7FB39A';
      const PENDING = '#D9A35C';
      const PALETTE = [APPROVED, PENDING, '#6E84A8', '#A98FC4', '#7FB39A', '#D9A35C'];
      const MONO = "'JetBrains Mono', 'IBM Plex Mono', ui-monospace, monospace";

      const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
      const fmtPct = (n) => (n === null || n === undefined) ? '—' : `${Number(n).toFixed(1)}%`;
      const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
      }[c]));

      function showGlobalError(message) {
        const el = document.getElementById('raGlobalError');
        el.textContent = message;
        el.style.display = 'block';
      }

      function baseChartOptions(overrides) {
        return Object.assign({
          chart: {
            fontFamily: 'Public Sans, sans-serif',
            background: 'transparent',
            toolbar: { show: false },
            animations: { easing: 'easeinout', speed: 500 },
          },
          theme: { mode: 'dark' },
          colors: PALETTE,
          dataLabels: { enabled: false },
          grid: { borderColor: LINE, strokeDashArray: 3 },
          tooltip: { theme: 'dark' },
          legend: { labels: { colors: TEXT_DIM } },
        }, overrides);
      }

      async function fetchDashboard() {
        const res = await fetch(`${API_BASE}/api/proposals/dashboard`, {
          headers: { Accept: 'application/json' },
        });
        if (!res.ok) {
          throw new Error(`Analytics service responded with ${res.status}`);
        }
        return res.json();
      }

      function renderKpis(data) {
        const statusRows = data.status_distribution || [];
        const totalProposals = statusRows.reduce((sum, r) => sum + Number(r.total_proposals || 0), 0);
        document.getElementById('kpiTotal').textContent = fmtInt(totalProposals);

        const approvalRow = data.approval_rate_overall?.[0] ?? data.approval_rate_overall;
        const approvalPct = Array.isArray(data.approval_rate_overall)
          ? data.approval_rate_overall[0]?.approval_rate_percentage
          : approvalRow?.approval_rate_percentage;
        document.getElementById('kpiApprovalRate').textContent = fmtPct(approvalPct);

        const campusRows = data.proposals_by_campus || [];
        document.getElementById('kpiCampuses').textContent = fmtInt(campusRows.length);

        const sdgRows = data.proposals_by_sdg || [];
        if (sdgRows.length) {
          const top = sdgRows[0];
          document.getElementById('kpiTopSdg').textContent = top.sdg_code || top.sdg_name || '—';
          document.getElementById('kpiTopSdgFoot').textContent = `${escapeHtml(top.sdg_name || '')} · ${fmtInt(top.total_proposals)} proposals`;
        } else {
          document.getElementById('kpiTopSdg').textContent = '—';
        }
      }

      function renderStatusChart(rows) {
        if (!rows.length) {
          document.getElementById('chartStatus').innerHTML = '<div class="ra-empty">no status data yet</div>';
          return;
        }
        const labels = rows.map((r) => r.status_code ?? 'Unknown');
        const series = rows.map((r) => Number(r.total_proposals || 0));
        new ApexCharts(document.getElementById('chartStatus'), baseChartOptions({
          chart: { type: 'donut', height: 260 },
          labels,
          series,
          stroke: { colors: ['#11151D'], width: 2 },
          legend: { position: 'bottom', fontSize: '12px', labels: { colors: TEXT_DIM } },
          plotOptions: {
            pie: {
              donut: {
                labels: {
                  show: true,
                  total: { show: true, label: 'Total', color: TEXT_DIM },
                  value: { color: TEXT, fontFamily: MONO },
                },
              },
            },
          },
        })).render();
      }

      function renderQuarterChart(rows) {
        if (!rows.length) {
          document.getElementById('chartQuarter').innerHTML = '<div class="ra-empty">no submission history yet</div>';
          return;
        }
        const categories = rows.map((r) => `${r.year} Q${r.quarter}`);
        const series = [{ name: 'Proposals', data: rows.map((r) => Number(r.total_proposals || 0)) }];
        new ApexCharts(document.getElementById('chartQuarter'), baseChartOptions({
          chart: { type: 'area', height: 260 },
          series,
          xaxis: {
            categories,
            tickAmount: 8,
            labels: { style: { fontSize: '10px', colors: TEXT_FAINT, fontFamily: MONO } },
            axisBorder: { color: LINE },
            axisTicks: { color: LINE },
          },
          yaxis: { labels: { style: { colors: TEXT_FAINT, fontFamily: MONO } } },
          stroke: { curve: 'smooth', width: 2 },
          fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: 0, stops: [0, 95, 100] },
          },
          colors: [APPROVED],
        })).render();
      }

      function renderSdgChart(rows) {
        if (!rows.length) {
          document.getElementById('chartSdg').innerHTML = '<div class="ra-empty">no SDG data yet</div>';
          return;
        }
        const top = rows.slice(0, 10);
        new ApexCharts(document.getElementById('chartSdg'), baseChartOptions({
          chart: { type: 'bar', height: 300 },
          plotOptions: { bar: { horizontal: true, borderRadius: 2, barHeight: '52%' } },
          series: [{ name: 'Proposals', data: top.map((r) => Number(r.total_proposals || 0)) }],
          xaxis: {
            categories: top.map((r) => r.sdg_code || r.sdg_name),
            labels: { style: { colors: TEXT_FAINT, fontFamily: MONO, fontSize: '10px' } },
            axisBorder: { color: LINE },
            axisTicks: { color: LINE },
          },
          yaxis: { labels: { style: { colors: TEXT_DIM, fontSize: '11px' } } },
          colors: [APPROVED],
        })).render();
      }

      function renderFormatChart(rows) {
        if (!rows.length) {
          document.getElementById('chartFormat').innerHTML = '<div class="ra-empty">no format data yet</div>';
          return;
        }
        new ApexCharts(document.getElementById('chartFormat'), baseChartOptions({
          chart: { type: 'donut', height: 230 },
          labels: rows.map((r) => r.research_format_name),
          series: rows.map((r) => Number(r.total_proposals || 0)),
          stroke: { colors: ['#11151D'], width: 2 },
          legend: { position: 'bottom', fontSize: '11px', labels: { colors: TEXT_DIM } },
        })).render();
      }

      function renderAgendaChart(rows) {
        if (!rows.length) {
          document.getElementById('chartAgenda').innerHTML = '<div class="ra-empty">no agenda data yet</div>';
          return;
        }
        new ApexCharts(document.getElementById('chartAgenda'), baseChartOptions({
          chart: { type: 'bar', height: 230 },
          plotOptions: { bar: { borderRadius: 2, columnWidth: '50%' } },
          series: [{ name: 'Proposals', data: rows.map((r) => Number(r.total_proposals || 0)) }],
          xaxis: {
            categories: rows.map((r) => r.agenda_label),
            labels: { style: { fontSize: '9px', colors: TEXT_FAINT, fontFamily: MONO } },
            axisBorder: { color: LINE },
            axisTicks: { color: LINE },
          },
          yaxis: { labels: { style: { colors: TEXT_FAINT, fontFamily: MONO } } },
          colors: ['#6E84A8'],
        })).render();
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

      function paintProgramTable(rows) {
        const tbody = document.getElementById('programTableBody');
        if (!rows.length) {
          tbody.innerHTML = '<tr><td colspan="3" class="ra-empty">no matching programs</td></tr>';
          return;
        }
        tbody.innerHTML = rows.map((r) => `
          <tr>
            <td>${escapeHtml(r.campus_name)}</td>
            <td>${escapeHtml(r.program_name)}</td>
            <td class="text-end">${fmtInt(r.total_proposals)}</td>
          </tr>
        `).join('');
      }

      document.getElementById('programFilter').addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        if (!q) { paintProgramTable(programRowsCache); return; }
        paintProgramTable(programRowsCache.filter((r) =>
          r.campus_name.toLowerCase().includes(q) || r.program_name.toLowerCase().includes(q)
        ));
      });

      async function init() {
        try {
          const data = await fetchDashboard();

          renderKpis(data);
          renderStatusChart(data.status_distribution || []);
          renderQuarterChart(data.proposals_by_quarter || []);
          renderSdgChart(data.proposals_by_sdg || []);
          renderFormatChart(data.proposals_by_format || []);
          renderAgendaChart(data.proposals_by_agenda || []);
          renderFunnel(data.approval_rate_by_campus || []);
          renderProgramTable(data.campus_program_breakdown || []);

          document.getElementById('raLastUpdated').textContent =
            `live · updated ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
        } catch (err) {
          console.error(err);
          showGlobalError(
            `Couldn't reach the analytics service at ${API_BASE}. Confirm the FastAPI app is running (uvicorn main:app --port 8001) and reachable from the browser.`
          );
          document.getElementById('raLastUpdated').textContent = 'offline';
          document.querySelector('.ra-dot').style.background = '#C2705E';
          [
            'kpiTotal', 'kpiApprovalRate', 'kpiCampuses', 'kpiTopSdg',
          ].forEach((id) => { document.getElementById(id).textContent = '—'; });
        }
      }

      document.addEventListener('DOMContentLoaded', init);
    })();
  </script>
@endsection