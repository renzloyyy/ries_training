{{--
    Storytelling overview dashboard.
    The layout intentionally walks the reader through scale, conversion,
    trend, campus contribution, and key takeaways instead of showing
    unrelated widgets with equal weight.
--}}

<div class="ra-story-block">
    <div class="ra-story-label"><span class="ra-story-index">1</span> Start With The Big Picture</div>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 3.5h7l3 3v14H7v-17Z" stroke-width="1.7" stroke-linejoin="round" />
                        <path d="M14 3.5v3h3M9.5 11h5M9.5 14.5h5" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Proposals</div>
                    <div class="ra-story-kpi-value" id="overviewHeroProposals"><span class="ra-skel"></span></div>
                    <div class="ra-story-kpi-copy" id="overviewProposalCopy">Total research proposals submitted across all years.</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon ra-story-kpi-icon-solid" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="8.5" stroke-width="1.7" />
                        <path d="m8.8 12.2 2.2 2.2 4.6-5.1" stroke-width="1.7" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Completed Papers</div>
                    <div class="ra-story-kpi-value" id="overviewHeroCompletedPapers"><span class="ra-skel"></span></div>
                    <div class="ra-story-kpi-copy">Research papers completed through to full output.</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon ra-story-kpi-icon-solid" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 3.5v17M7.5 7.5h6.25a3.25 3.25 0 0 1 0 6.5H9.5" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6 20.5h12" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Fund Allocation</div>
                    <div class="ra-story-kpi-value" id="overviewHeroFundAllocation"><span class="ra-skel"></span></div>
                    <div class="ra-story-kpi-copy">Total funds allocated to support research and innovation.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ra-story-block">
    <div class="ra-story-label"><span class="ra-story-index">2</span> Trend Of Performance</div>
    <div class="ra-card ra-overview-story-panel">
        <div class="ra-card-head ra-overview-trend-head">
            <div>
                <h2 class="ra-card-title" id="overviewTrendTitle">Proposals vs Completed Papers vs Published Papers Over Time</h2>
                <div class="ra-card-sub">yearly movement across pipeline input, completed outputs, and publication records</div>
            </div>
            <div class="ra-filter">
                <label class="ra-filter-label" for="overviewTrendYearFilter">Trend range</label>
                <select id="overviewTrendYearFilter" class="ra-select">
                    <option value="">All years</option>
                </select>
            </div>
        </div>
        <div class="px-3 pb-3">
            <div id="overviewTrendChart" style="min-height:340px;"></div>
        </div>
    </div>
</div>

<div class="ra-story-block">
    <div class="ra-story-label"><span class="ra-story-index">3</span> Who Drives The Results</div>
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="ra-card ra-overview-story-panel h-100">
                <div class="ra-card-head">
                    <div>
                        <h2 class="ra-card-title">Top Campus by Outputs</h2>
                        <div class="ra-card-sub">by total completed papers</div>
                    </div>
                </div>
                <div class="px-3 pb-3" id="overviewTopCampusOutputs"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ra-card ra-overview-story-panel h-100">
                <div class="ra-card-head">
                    <div>
                        <h2 class="ra-card-title">Completion Rate by Campus</h2>
                        <div class="ra-card-sub">completed papers as a percentage of publication records</div>
                    </div>
                </div>
                <div class="px-3 pb-3" id="overviewCampusCompletion"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ra-card ra-overview-story-panel h-100">
                <div class="ra-card-head">
                    <div>
                        <h2 class="ra-card-title">Funding Share by Campus</h2>
                        <div class="ra-card-sub">share of total fund allocation</div>
                    </div>
                </div>
                <div class="px-3 pb-3">
                    <div id="overviewFundingShareChart" style="min-height:230px;"></div>
                    <div class="ra-overview-legend" id="overviewFundingLegend"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ra-story-block">
    <div class="ra-story-label"><span class="ra-story-index">4</span> Key Insights</div>
    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="ra-overview-insight ra-overview-insight-alert">
                <div class="ra-overview-insight-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 6.5h14M5 12h9M5 17.5h6" stroke-width="1.7" stroke-linecap="round" />
                        <path d="m15 17 2.2 2.2L21 14.5" stroke-width="1.7" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ra-overview-insight-label">Lowest Completion Campus</div>
                <div class="ra-overview-insight-value" id="overviewLowestCampus">—</div>
                <div class="ra-overview-insight-copy" id="overviewLowestCampusCopy">Needs support to convert proposals into outputs.</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-overview-insight">
                <div class="ra-overview-insight-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 4.5h10v4.2a5 5 0 0 1-3.4 4.7L12 14l-1.6-.6A5 5 0 0 1 7 8.7V4.5Z"
                            stroke-width="1.7" stroke-linejoin="round" />
                        <path d="M12 14v5.5M9 20.5h6" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-overview-insight-label">Highest Output Campus</div>
                <div class="ra-overview-insight-value" id="overviewHighestCampus">—</div>
                <div class="ra-overview-insight-copy" id="overviewHighestCampusCopy">Leads in completed papers and drives overall performance.</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-overview-insight">
                <div class="ra-overview-insight-icon ra-overview-insight-icon-green" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 3.5h6M10 3.5v4l-5 8.4A3 3 0 0 0 7.6 20h8.8a3 3 0 0 0 2.6-4.1L14 7.5v-4"
                            stroke-width="1.7" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ra-overview-insight-label">Most Funded Category</div>
                <div class="ra-overview-insight-value" id="overviewTopCategory">—</div>
                <div class="ra-overview-insight-copy" id="overviewTopCategoryCopy">Receives the largest share of funding across categories.</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-overview-insight">
                <div class="ra-overview-insight-icon ra-overview-insight-icon-amber" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4.5 18.5V12M9.5 18.5V8.5M14.5 18.5v-4M19.5 18.5V5.5" stroke-width="1.7"
                            stroke-linecap="round" />
                        <path d="m4.5 7.5 5 1.5 5-3 5 1.5" stroke-width="1.7" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ra-overview-insight-label">Best Growth Year</div>
                <div class="ra-overview-insight-value" id="overviewBestGrowthYear">—</div>
                <div class="ra-overview-insight-copy" id="overviewBestGrowthYearCopy">Showed the strongest growth across proposals and outputs.</div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        let overviewTrendChart     = null;
        let overviewFundingShareChart = null;
        let overviewTrendRows      = [];
        let overviewLoaded         = false;

        const fmtInt      = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtPct      = (n) => (n === null || n === undefined || Number.isNaN(Number(n))) ? '—' : `${Number(n).toFixed(1)}%`;
        const fmtCurrency = (n) => `₱${Number(n ?? 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;').replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#39;');
        }

        // ---------------------------------------------------------------------------
        // URL builders — every endpoint verified against main.py
        // ---------------------------------------------------------------------------

        function buildProposalsUrl(year) {
            const url = new URL(`${API_BASE}/api/proposals/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildPublicationsSummaryUrl() {
            // Returns a single object: { total_publications, ... }
            return `${API_BASE}/api/publications/summary`;
        }

        function buildPublicationsByYearUrl() {
            // Returns array of { year_published, total_publications }
            return `${API_BASE}/api/publications/by-year`;
        }

        function buildPublicationsCampusContributionUrl() {
            // Returns array of { campus, contribution_percentage, total_publications }
            // NOTE: this endpoint ignores the year param — it always covers all years.
            return `${API_BASE}/api/publications/campus-contribution`;
        }

        function buildFundingTotalUrl(year) {
            // Dedicated single-row endpoint — avoids parsing the dashboard bundle.
            const url = new URL(`${API_BASE}/api/funding/total-allocated`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildFundingCampusUrl(year) {
            // Returns array of { campus_name, funded_projects, total_allocated_fund }
            const url = new URL(`${API_BASE}/api/funding/by-campus`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildFundingCategoryUrl(year) {
            // Returns array of { program_category, funded_projects, total_allocated_fund }
            const url = new URL(`${API_BASE}/api/funding/by-category`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        // ---------------------------------------------------------------------------
        // Chart helpers
        // ---------------------------------------------------------------------------

        function destroyTrendChart() {
            if (overviewTrendChart) { overviewTrendChart.destroy(); overviewTrendChart = null; }
        }

        function destroyFundingShareChart() {
            if (overviewFundingShareChart) { overviewFundingShareChart.destroy(); overviewFundingShareChart = null; }
        }

        function setTrendYearOptions(years) {
            const select = document.getElementById('overviewTrendYearFilter');
            if (!select) return;
            const current = select.value;
            select.innerHTML = '<option value="">All years</option>';
            years.forEach((year) => {
                const opt = document.createElement('option');
                opt.value = String(year);
                opt.textContent = String(year);
                select.appendChild(opt);
            });
            select.value = years.includes(Number(current)) ? current : '';
        }

        function mergeOverviewTrendData(proposals, publicationYears) {
            const proposalMap = new Map(
                (proposals.proposals_by_year || []).map((r) => [Number(r.year), Number(r.total_proposals || 0)])
            );
            // /api/publications/by-year returns { year_published, total_publications }
            const publicationMap = new Map(
                (publicationYears || []).map((r) => [
                    Number(r.year_published),
                    Number(r.total_publications || 0),
                ])
            );

            const yearSet = new Set([...proposalMap.keys(), ...publicationMap.keys()]);
            return Array.from(yearSet)
                .filter(Boolean)
                .sort((a, b) => a - b)
                .map((year) => ({
                    year,
                    proposals:       proposalMap.get(year)    || 0,
                    completed_papers: publicationMap.get(year) || 0,
                    published_papers: publicationMap.get(year) || 0,
                }));
        }

        function renderTrendChart(selectedYear = '') {
            const chartEl = document.getElementById('overviewTrendChart');
            if (!chartEl) return;

            const rows = selectedYear
                ? overviewTrendRows.filter((r) => String(r.year) === String(selectedYear))
                : overviewTrendRows;

            destroyTrendChart();

            if (!rows.length) {
                chartEl.innerHTML = '<div class="ra-empty">no trend data yet</div>';
                return;
            }

            chartEl.innerHTML = '';
            setText(
                'overviewTrendTitle',
                `Proposals vs Completed Papers vs Published Papers Over Time (${rows[0].year}–${rows[rows.length - 1].year})`
            );

            overviewTrendChart = new ApexCharts(chartEl, {
                chart: { type: 'line', height: 340, toolbar: { show: false }, zoom: { enabled: false } },
                series: [
                    { name: 'Proposals',        data: rows.map((r) => r.proposals) },
                    { name: 'Completed Papers',  data: rows.map((r) => r.completed_papers) },
                    { name: 'Published Papers',  data: rows.map((r) => r.published_papers) },
                ],
                colors: ['#173A9B', '#2D6AF6', '#8AB5FF'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 5, hover: { size: 6 } },
                dataLabels: {
                    enabled: true,
                    offsetY: -10,
                    background: { enabled: false },
                    style: { fontSize: '11px', fontWeight: '700', colors: ['#1E4ED8'] },
                    formatter: (v) => fmtInt(v),
                },
                legend: { position: 'top', horizontalAlign: 'left', fontSize: '13px', labels: { colors: '#3552A3' } },
                grid: { borderColor: '#E5EDFF', strokeDashArray: 4 },
                xaxis: {
                    categories: rows.map((r) => String(r.year)),
                    labels: { style: { colors: '#3552A3', fontSize: '12px' } },
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: { formatter: (v) => fmtInt(v), style: { colors: '#3552A3', fontSize: '12px' } },
                },
                tooltip: { shared: true, intersect: false, y: { formatter: (v) => fmtInt(v) } },
            });
            overviewTrendChart.render();
        }

        function renderCampusBars(containerId, rows, valueKey, formatter, toneClass = '') {
            const container = document.getElementById(containerId);
            if (!container) return;
            if (!rows.length) {
                container.innerHTML = '<div class="ra-empty">no campus data yet</div>';
                return;
            }
            const maxValue = Math.max(...rows.map((r) => Number(r[valueKey] || 0)), 1);
            container.innerHTML = rows.map((r) => {
                const value = Number(r[valueKey] || 0);
                const width = (value / maxValue) * 100;
                return `
                    <div class="ra-story-bar-row">
                        <div class="ra-story-bar-meta">
                            <div class="ra-story-bar-name">${escapeHtml(r.campus_name)}</div>
                            <div class="ra-story-bar-value">${formatter(value)}</div>
                        </div>
                        <div class="ra-story-bar-track">
                            <div class="ra-story-bar-fill ${toneClass}" style="width:${width}%"></div>
                        </div>
                    </div>`;
            }).join('');
        }

        function renderFundingShare(rows) {
            const chartEl  = document.getElementById('overviewFundingShareChart');
            const legendEl = document.getElementById('overviewFundingLegend');
            if (!chartEl || !legendEl) return;

            if (!rows.length) {
                chartEl.innerHTML = '<div class="ra-empty">no funding data yet</div>';
                legendEl.innerHTML = '';
                return;
            }

            const total   = rows.reduce((s, r) => s + Number(r.total_allocated_fund || 0), 0);
            const palette = ['#173A9B', '#1E4ED8', '#4E7CF0', '#7F9FF8', '#4BB7C3', '#8B5CF6', '#F59E0B'];
            const topRows = rows.slice(0, 6);

            chartEl.innerHTML = '';
            destroyFundingShareChart();
            overviewFundingShareChart = new ApexCharts(chartEl, {
                chart: { type: 'donut', height: 220, toolbar: { show: false } },
                series: topRows.map((r) => Number(r.total_allocated_fund || 0)),
                labels:  topRows.map((r) => r.campus_name),
                colors:  palette,
                legend:  { show: false },
                stroke:  { colors: ['#FFFDF9'], width: 2 },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                total: { show: true, label: 'Total Funds', color: '#6C7DB0' },
                                value: { color: '#173A9B', formatter: () => fmtCurrency(total) },
                            },
                        },
                    },
                },
            });
            overviewFundingShareChart.render();

            legendEl.innerHTML = topRows.map((r, i) => {
                const amount = Number(r.total_allocated_fund || 0);
                const share  = total > 0 ? (amount / total) * 100 : 0;
                return `
                    <div class="ra-overview-legend-row">
                        <span class="ra-overview-legend-dot" style="background:${palette[i]}"></span>
                        <span class="ra-overview-legend-name">${escapeHtml(r.campus_name)}</span>
                        <span class="ra-overview-legend-share">${fmtPct(share)}</span>
                    </div>`;
            }).join('');
        }

        function updateInsightCards(pubCampusRows, campusContributionRows, fundingCategoryRows) {
            // Lowest completion — sort ascending by contribution_percentage
            const sortedByRate = [...campusContributionRows]
                .sort((a, b) => a.completion_rate - b.completion_rate);

            // Highest output — sort descending by completed_outputs
            const sortedByOutput = [...pubCampusRows]
                .sort((a, b) => b.completed_outputs - a.completed_outputs);

            // Most funded category
            const topCategory = [...fundingCategoryRows]
                .sort((a, b) => Number(b.total_allocated_fund || 0) - Number(a.total_allocated_fund || 0))[0];

            // Best growth year (combined proposals + papers delta)
            const growthRows = overviewTrendRows
                .map((r, i, list) => {
                    if (i === 0) return null;
                    const prev = list[i - 1];
                    return {
                        year: r.year,
                        growth_score: (r.proposals - prev.proposals) + (r.completed_papers - prev.completed_papers),
                    };
                })
                .filter(Boolean)
                .sort((a, b) => b.growth_score - a.growth_score);

            const lowest    = sortedByRate[0];
            const highest   = sortedByOutput[0];
            const bestGrowth = growthRows[0];

            setText('overviewLowestCampus', lowest?.campus_name || '—');
            setText('overviewLowestCampusCopy',
                lowest ? `${fmtPct(lowest.completion_rate)} completion rate across publication records.`
                       : 'Needs support to convert proposals into outputs.');

            setText('overviewHighestCampus', highest?.campus_name || '—');
            setText('overviewHighestCampusCopy',
                highest ? `${fmtInt(highest.completed_outputs)} completed papers lead overall output.`
                        : 'Leads in completed papers and drives overall performance.');

            setText('overviewTopCategory', topCategory?.program_category || '—');
            setText('overviewTopCategoryCopy',
                topCategory ? `${fmtCurrency(topCategory.total_allocated_fund)} allocated to this category.`
                            : 'Receives the largest share of funding across categories.');

            setText('overviewBestGrowthYear', bestGrowth?.year || '—');
            setText('overviewBestGrowthYearCopy',
                bestGrowth ? 'Strongest combined rise in proposals and completed papers.'
                           : 'Showed the strongest growth across proposals and outputs.');
        }

        // ---------------------------------------------------------------------------
        // Main loader
        // ---------------------------------------------------------------------------

        async function loadOverview(year = '') {
            try {
                // Fire all requests in parallel — each targets a real, verified endpoint.
                const [
                    proposalRes,
                    pubSummaryRes,
                    pubYearRes,
                    pubContributionRes,
                    fundTotalRes,
                    fundCampusRes,
                    fundCategoryRes,
                ] = await Promise.all([
                    fetch(buildProposalsUrl(year),                    { headers: { Accept: 'application/json' } }),
                    fetch(buildPublicationsSummaryUrl(),              { headers: { Accept: 'application/json' } }),
                    fetch(buildPublicationsByYearUrl(),               { headers: { Accept: 'application/json' } }),
                    fetch(buildPublicationsCampusContributionUrl(),   { headers: { Accept: 'application/json' } }),
                    fetch(buildFundingTotalUrl(year),                 { headers: { Accept: 'application/json' } }),
                    fetch(buildFundingCampusUrl(year),                { headers: { Accept: 'application/json' } }),
                    fetch(buildFundingCategoryUrl(year),              { headers: { Accept: 'application/json' } }),
                ]);

                if (!proposalRes.ok)      throw new Error(`Proposals: ${proposalRes.status}`);
                if (!pubSummaryRes.ok)    throw new Error(`Publications summary: ${pubSummaryRes.status}`);
                if (!pubYearRes.ok)       throw new Error(`Publications by-year: ${pubYearRes.status}`);
                if (!pubContributionRes.ok) throw new Error(`Publications campus-contribution: ${pubContributionRes.status}`);
                if (!fundTotalRes.ok)     throw new Error(`Funding total: ${fundTotalRes.status}`);
                if (!fundCampusRes.ok)    throw new Error(`Funding by-campus: ${fundCampusRes.status}`);
                if (!fundCategoryRes.ok)  throw new Error(`Funding by-category: ${fundCategoryRes.status}`);

                const proposals              = await proposalRes.json();
                const publicationsSummary    = await pubSummaryRes.json();
                const publicationsByYear     = await pubYearRes.json();
                const pubContribution        = await pubContributionRes.json();
                // /api/funding/total-allocated returns { total_allocated_fund: <number> }
                const fundTotal              = await fundTotalRes.json();
                const fundCampus             = await fundCampusRes.json();
                const fundCategory           = await fundCategoryRes.json();

                // ---------------------------------------------------------------------------
                // Hero KPIs
                // ---------------------------------------------------------------------------
                const proposalTotal = (proposals.status_distribution || [])
                    .reduce((sum, r) => sum + Number(r.total_proposals || 0), 0);

                const completedPapers = Number(publicationsSummary.total_publications || 0);

                // /api/funding/total-allocated returns a single object, not an array
                const totalFund = Number(fundTotal.total_allocated_fund || 0);

                setText('overviewHeroProposals',      fmtInt(proposalTotal));
                setText('overviewHeroCompletedPapers', fmtInt(completedPapers));
                setText('overviewHeroFundAllocation',  fmtCurrency(totalFund));
                setText('overviewProposalCopy',
                    year ? `Total research proposals submitted for ${year}.`
                         : 'Total research proposals submitted across all years.');

                // ---------------------------------------------------------------------------
                // Trend chart
                // ---------------------------------------------------------------------------
                overviewTrendRows = mergeOverviewTrendData(proposals, publicationsByYear);
                setTrendYearOptions(overviewTrendRows.map((r) => r.year));
                renderTrendChart(document.getElementById('overviewTrendYearFilter')?.value || '');

                // ---------------------------------------------------------------------------
                // Campus bars
                // Section 3 — Who Drives The Results
                // pubContribution rows: { campus, contribution_percentage, total_publications }
                // ---------------------------------------------------------------------------
                const outputCampusRows = (pubContribution || [])
                    .map((r) => ({
                        campus_name:      r.campus,
                        completed_outputs: Number(r.total_publications || 0),
                    }))
                    .sort((a, b) => b.completed_outputs - a.completed_outputs);

                const campusContributionRows = (pubContribution || [])
                    .map((r) => ({
                        campus_name:    r.campus,
                        completion_rate: Number(r.contribution_percentage || 0),
                    }))
                    .sort((a, b) => b.completion_rate - a.completion_rate);

                renderCampusBars('overviewTopCampusOutputs', outputCampusRows.slice(0, 6),
                    'completed_outputs', (v) => fmtInt(v));

                renderCampusBars('overviewCampusCompletion', campusContributionRows.slice(0, 6),
                    'completion_rate', (v) => fmtPct(v), 'is-soft');

                // fundCampus rows: { campus_name, funded_projects, total_allocated_fund }
                renderFundingShare(fundCampus || []);

                // ---------------------------------------------------------------------------
                // Insight cards
                // ---------------------------------------------------------------------------
                updateInsightCards(outputCampusRows, campusContributionRows, fundCategory || []);

                overviewLoaded = true;

            } catch (error) {
                console.error('[Overview]', error);

                destroyTrendChart();
                destroyFundingShareChart();

                [
                    'overviewHeroProposals', 'overviewHeroCompletedPapers',
                    'overviewHeroFundAllocation', 'overviewLowestCampus',
                    'overviewHighestCampus', 'overviewTopCategory', 'overviewBestGrowthYear',
                ].forEach((id) => setText(id, '—'));

                const safe = (id, msg) => {
                    const el = document.getElementById(id);
                    if (el) el.innerHTML = `<div class="ra-empty">${msg}</div>`;
                };
                safe('overviewTrendChart',       'could not load trend data');
                safe('overviewTopCampusOutputs', 'could not load campus output data');
                safe('overviewCampusCompletion', 'could not load campus completion data');
                safe('overviewFundingShareChart','could not load funding share data');
                setText('overviewFundingLegend', '');
            }
        }

        // ---------------------------------------------------------------------------
        // Event listeners
        // ---------------------------------------------------------------------------

        document.getElementById('overviewTrendYearFilter')?.addEventListener('change', (e) => {
            renderTrendChart(e.target.value);
        });

        // Re-load whenever the global year filter changes while Overview is visible.
        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverview(e.target.value);
            }
        });

        // Both the left-rail tabs AND the top-nav links can activate Overview.
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-tab]');
            if (btn && btn.dataset.tab === 'overview') {
                const year = document.getElementById('yearFilter')?.value || '';
                loadOverview(year);
            }
        });

        // Initial load when the panel is already active on page load.
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverview('');
            }
        });

        // Guard for scripts that run after DOMContentLoaded has already fired.
        if (document.readyState !== 'loading') {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverview('');
            }
        }

    })();
</script>