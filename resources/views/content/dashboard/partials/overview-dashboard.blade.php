{{--
    Storytelling overview dashboard.
    The layout intentionally walks the reader through scale, conversion,
    trend, campus contribution, and key takeaways instead of showing
    unrelated widgets with equal weight.
--}}

<div class="ra-story-block">
    {{-- <div class="ra-story-label"><span class="ra-story-index">1</span> Start With The Big Picture</div> --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 3.5h7l3 3v14H7v-17Z" stroke-width="1.7" stroke-linejoin="round" />
                        <path d="M14 3.5v3h3M9.5 11h5M9.5 14.5h5" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Proposals <span
                            id="overviewProposalYearLabel">(2017-present)</span></div>
                    <div class="ra-story-kpi-value" id="overviewHeroProposals"><span class="ra-skel"></span></div>

                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon ra-story-kpi-icon-solid" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="8.5" stroke-width="1.7" />
                        <path d="m8.8 12.2 2.2 2.2 4.6-5.1" stroke-width="1.7" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Completed Research <span
                            id="overviewCompletedYearLabel">(2017-present)</span></div>
                    <div class="ra-story-kpi-value" id="overviewHeroCompletedPapers"><span class="ra-skel"></span></div>

                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon ra-story-kpi-icon-solid" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M5.5 5.5h5a2.5 2.5 0 0 1 2 1 2.5 2.5 0 0 1 2-1h4v13h-4a2.5 2.5 0 0 0-2 1 2.5 2.5 0 0 0-2-1h-5v-13Z"
                            stroke-width="1.7" stroke-linejoin="round" />
                        <path d="M12.5 7v12" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Published Papers <span
                            id="overviewPublishedYearLabel">(2017-present)</span></div>
                    <div class="ra-story-kpi-value" id="overviewHeroPublishedPapers"><span class="ra-skel"></span></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ra-story-kpi">
                <div class="ra-story-kpi-icon ra-story-kpi-icon-solid" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 3.5v17M7.5 7.5h6.25a3.25 3.25 0 0 1 0 6.5H9.5" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6 20.5h12" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="ra-story-kpi-body">
                    <div class="ra-story-kpi-title">Total Fund Allocation <span
                            id="overviewFundingYearLabel">(2017-present)</span></div>
                    <div class="ra-story-kpi-value" id="overviewHeroFundAllocation"><span class="ra-skel"></span></div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="ra-story-block">
    {{-- <div class="ra-story-label"><span class="ra-story-index">2</span> Trend Of Performance</div> --}}
    <div class="ra-card ra-overview-story-panel">
        <div class="ra-card-head ra-overview-trend-head">
            <div>
                <h2 class="ra-card-title" id="overviewTrendTitle">Total Research Activity Over Time</h2>
            </div>
        </div>
        <div class="px-3 pb-3">
            <div id="overviewTrendChart" style="min-height:340px;"></div>
        </div>
    </div>
</div>

<div class="ra-story-block">
    {{-- <div class="ra-story-label"><span class="ra-story-index">3</span> Who Drives The Results</div> --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="ra-card ra-overview-story-panel h-100">
                <div class="ra-card-head">
                    <div>
                        <h2 class="ra-card-title">Leading Campus in Outputs</h2>

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
                    </div>
                </div>
                <div class="px-3 pb-3">
                    {{-- Keep the donut and its callout labels side-by-side so the
                         category names live beside the chart instead of on top of it. --}}
                    <div class="ra-overview-donut-wrap">
                        <div id="overviewFundingShareChart" style="min-height:230px;"></div>
                        <div class="ra-overview-legend" id="overviewFundingLegend"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Storytelling Overview.
     * The overview intentionally reuses the three dashboard payloads and
     * computes narrative metrics client-side so the landing page stays aligned
     * with the detailed panels while speaking in executive language.
     */
    (function() {
        const API_BASE = window.RESEARCH_API_BASE ||
            '{{ rtrim(config('services.research_api.url', 'http://127.0.0.1:8001'), '/') }}';

        let overviewTrendChart = null;
        let overviewFundingShareChart = null;
        let overviewTrendRows = [];
        let overviewLoaded = false;
        const OVERVIEW_MIN_YEAR = 2017;
        // Keep the publication story anchored to the first meaningful
        // publication period requested by the user.
        const OVERVIEW_PUBLICATION_START_YEAR = 2020;

        const fmtInt = (n) => Number(n ?? 0).toLocaleString('en-US');
        const fmtPct = (n) => (n === null || n === undefined || Number.isNaN(Number(n))) ? '—' :
            `${Number(n).toFixed(1)}%`;
        const fmtCurrency = (n) => `₱${Number(n ?? 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');
        }

        function buildOverviewYearLabel(selectedYear, trendRows) {
            // Show a bounded year range for the overview cards when the shared
            // filter is on "all years", and a single parenthesized year when a
            // specific filter value is active.
            if (selectedYear) return `(${selectedYear})`;
            const years = getOverviewDisplayRows(selectedYear, trendRows)
                .map((row) => Number(row.year))
                .filter(Boolean);
            if (!years.length) return `(${OVERVIEW_MIN_YEAR}-${new Date().getFullYear()})`;
            return `(${Math.min(...years)}-${Math.max(...years)})`;
        }

        function buildOverviewRangeText(selectedYear, trendRows) {
            if (selectedYear) return String(selectedYear);
            const years = getOverviewDisplayRows(selectedYear, trendRows)
                .map((row) => Number(row.year))
                .filter(Boolean);
            if (!years.length) return `${OVERVIEW_MIN_YEAR}-${new Date().getFullYear()}`;
            return `${Math.min(...years)}-${Math.max(...years)}`;
        }

        function getOverviewDisplayRows(selectedYear, trendRows) {
            if (selectedYear) {
                return (trendRows || []).filter((row) => String(row.year) === String(selectedYear));
            }

            const latestPublishedYear = (trendRows || [])
                .filter((row) => Number(row.published_papers || 0) > 0)
                .map((row) => Number(row.year))
                .filter(Boolean)
                .pop();

            // Default the overview trend to the publication reporting period
            // beginning in 2020 and ending at the latest year with actual
            // published-paper activity, so empty future years do not stretch
            // the publication story.
            return (trendRows || []).filter((row) => {
                const year = Number(row.year);
                if (!year || year < OVERVIEW_PUBLICATION_START_YEAR) return false;
                if (!latestPublishedYear) return true;
                return year <= latestPublishedYear;
            });
        }

        function buildOverviewTrendRange(rows) {
            const years = (rows || []).map((row) => Number(row.year)).filter(Boolean);
            if (!years.length) return `${OVERVIEW_MIN_YEAR}-${new Date().getFullYear()}`;
            const minYear = Math.min(...years);
            const maxYear = Math.max(...years);
            // When both filters collapse to one year, show a single year label
            // instead of a redundant range like 2025-2025.
            return minYear === maxYear ? String(minYear) : `${minYear}-${maxYear}`;
        }

        function buildProposalsUrl(year) {
            const url = new URL(`${API_BASE}/api/proposals/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildPublicationsSummaryUrl() {
            return new URL(`${API_BASE}/api/publications/summary`).toString();
        }

        function buildPublicationsByYearUrl() {
            return new URL(`${API_BASE}/api/publications/by-year`).toString();
        }

        function buildPublicationsByCampusUrl(year) {
            // there's no dedicated by-campus endpoint — reuse campus-contribution
            return new URL(`${API_BASE}/api/publications/campus-contribution`).toString();
        }

        function buildPublicationsCampusContributionUrl(year) {
            const url = new URL(`${API_BASE}/api/publications/campus-contribution`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function buildFundingUrl(year) {
            const url = new URL(`${API_BASE}/api/funding/dashboard`);
            if (year) url.searchParams.set('year', year);
            return url.toString();
        }

        function destroyTrendChart() {
            if (overviewTrendChart) {
                overviewTrendChart.destroy();
                overviewTrendChart = null;
            }
        }

        function destroyFundingShareChart() {
            if (overviewFundingShareChart) {
                overviewFundingShareChart.destroy();
                overviewFundingShareChart = null;
            }
        }

        function mergeOverviewTrendData(proposals, publicationYears) {
            const proposalMap = new Map(
                (proposals.proposals_by_year || []).map((row) => [Number(row.year), Number(row
                    .total_proposals || 0)])
            );
            const completedMap = new Map(
                (proposals.completed_outputs_by_year || []).map((row) => [Number(row.year), Number(row
                    .completed_outputs || 0)])
            );
            const publicationMap = new Map(
                (publicationYears || []).map((row) => [
                    Number(row.year_published),
                    {
                        // Published outputs come from the publications
                        // database and stay separate from proposal-side
                        // completed outputs.
                        published_papers: Number(row.total_publications || 0),
                    }
                ])
            );

            const yearSet = new Set([...proposalMap.keys(), ...completedMap.keys(), ...publicationMap.keys()]);

            return Array.from(yearSet)
                // The overview story starts at the institutional reporting
                // window, so ignore stray historical years from dirty source
                // publication data such as 1905.
                .filter((year) => year && year >= OVERVIEW_MIN_YEAR)
                .sort((a, b) => a - b)
                .map((year) => {
                    const pub = publicationMap.get(year) || {
                        published_papers: 0,
                    };
                    const proposalCount = proposalMap.get(year) || 0;
                    const completedCount = completedMap.get(year) || 0;
                    return {
                        year,
                        proposals: proposalCount,
                        completed_papers: completedCount,
                        published_papers: pub.published_papers,
                    };
                });
        }

        function renderTrendChart(selectedYear = '') {
            const chartEl = document.getElementById('overviewTrendChart');
            if (!chartEl) return;

            const sharedYear = document.getElementById('yearFilter')?.value || '';
            const effectiveYear = selectedYear || sharedYear;
            const rows = getOverviewDisplayRows(effectiveYear, overviewTrendRows);

            destroyTrendChart();

            if (!rows.length) {
                chartEl.innerHTML = '<div class="ra-empty">no trend data yet</div>';
                return;
            }

            chartEl.innerHTML = '';
            setText(
                'overviewTrendTitle',
                `Total Research Activity Over Time (${buildOverviewTrendRange(rows)})`
            );

            overviewTrendChart = new ApexCharts(chartEl, {
                chart: {
                    type: 'line',
                    height: 340,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Proposals',
                    data: rows.map((row) => row.proposals),
                }, {
                    name: 'Completed Papers',
                    data: rows.map((row) => row.completed_papers),
                }, {
                    name: 'Published Papers',
                    data: rows.map((row) => row.published_papers),
                }],
                colors: ['#173A9B', '#2D6AF6', '#8AB5FF'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    hover: {
                        size: 6
                    }
                },
                dataLabels: {
                    enabled: false,
                    offsetY: -10,
                    background: {
                        enabled: false
                    },
                    style: {
                        /* Enlarge point labels so yearly values are easier to read. */
                        fontSize: '16px',
                        fontWeight: '700',
                        colors: ['#1E4ED8']
                    },
                    formatter: (value) => fmtInt(value),
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    /* Enlarge legend labels for clearer series identification. */
                    fontSize: '18px',
                    labels: {
                        colors: '#3552A3'
                    }
                },
                grid: {
                    borderColor: '#E5EDFF',
                    strokeDashArray: 4,
                },
                xaxis: {
                    categories: rows.map((row) => String(row.year)),
                    labels: {
                        style: {
                            colors: '#3552A3',
                            /* Enlarge year labels along the horizontal axis. */
                            fontSize: '16px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => fmtInt(value),
                        style: {
                            colors: '#3552A3',
                            /* Enlarge scale labels on the vertical axis. */
                            fontSize: '16px'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (value) => fmtInt(value),
                    }
                }
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

            const maxValue = Math.max(...rows.map((row) => Number(row[valueKey] || 0)), 1);
            container.innerHTML = rows.map((row) => {
                const value = Number(row[valueKey] || 0);
                const width = (value / maxValue) * 100;
                return `
                    <div class="ra-story-bar-row">
                        <div class="ra-story-bar-meta">
                            <div class="ra-story-bar-name">${escapeHtml(row.campus_name)}</div>
                            <div class="ra-story-bar-value">${formatter(value)}</div>
                        </div>
                        <div class="ra-story-bar-track">
                            <div class="ra-story-bar-fill ${toneClass}" style="width:${width}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderFundingShare(rows) {
            const chartEl = document.getElementById('overviewFundingShareChart');
            const legendEl = document.getElementById('overviewFundingLegend');
            if (!chartEl || !legendEl) return;

            if (!rows.length) {
                chartEl.innerHTML = '<div class="ra-empty">no funding data yet</div>';
                legendEl.innerHTML = '';
                return;
            }

            const total = rows.reduce((sum, row) => sum + Number(row.total_allocated_fund || 0), 0);
            const palette = ['#173A9B', '#1E4ED8', '#4E7CF0', '#7F9FF8', '#4BB7C3', '#8B5CF6', '#F59E0B'];
            const topRows = rows.slice(0, 6);

            chartEl.innerHTML = '';
            destroyFundingShareChart();
            overviewFundingShareChart = new ApexCharts(chartEl, {
                chart: {
                    type: 'donut',
                    height: 220,
                    toolbar: {
                        show: false
                    }
                },
                series: topRows.map((row) => Number(row.total_allocated_fund || 0)),
                labels: topRows.map((row) => row.campus_name),
                colors: palette,
                // Move callouts into the side legend block so long campus names
                // stay readable and do not collide inside the donut slices.
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false
                },
                stroke: {
                    colors: ['#FFFDF9'],
                    width: 2
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Funds',
                                    color: '#6C7DB0'
                                },
                                value: {
                                    color: '#173A9B',
                                    formatter: () => fmtCurrency(total)
                                }
                            }
                        }
                    }
                }
            });
            overviewFundingShareChart.render();

            legendEl.innerHTML = topRows.map((row, index) => {
                const amount = Number(row.total_allocated_fund || 0);
                const share = total > 0 ? (amount / total) * 100 : 0;
                return `
                    <div class="ra-overview-legend-row">
                        <span class="ra-overview-legend-dot" style="background:${palette[index]}"></span>
                        <span class="ra-overview-legend-name">${escapeHtml(row.campus_name)}</span>
                        <span class="ra-overview-legend-share">${fmtPct(share)}</span>
                    </div>
                `;
            }).join('');
        }

        async function loadOverview(year = '') {
            try {
                const [proposalRes, pubSummaryRes, pubYearRes, pubCampusRes, pubContributionRes, fundRes] =
                await Promise.all([
                    fetch(buildProposalsUrl(year), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                    fetch(buildPublicationsSummaryUrl(), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                    fetch(buildPublicationsByYearUrl(), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                    fetch(buildPublicationsByCampusUrl(year), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                    fetch(buildPublicationsCampusContributionUrl(year), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                    fetch(buildFundingUrl(year), {
                        headers: {
                            Accept: 'application/json'
                        }
                    }),
                ]);

                if (!proposalRes.ok) throw new Error(`Proposal service responded with ${proposalRes.status}`);
                if (!pubSummaryRes.ok) throw new Error(
                    `Publications summary responded with ${pubSummaryRes.status}`);
                if (!pubYearRes.ok) throw new Error(
                    `Publications yearly trend responded with ${pubYearRes.status}`);
                if (!pubCampusRes.ok) throw new Error(
                    `Publications by-campus responded with ${pubCampusRes.status}`);
                if (!pubContributionRes.ok) throw new Error(
                    `Publications campus contribution responded with ${pubContributionRes.status}`);
                if (!fundRes.ok) throw new Error(`Funding service responded with ${fundRes.status}`);

                const proposals = await proposalRes.json();
                const publicationsSummary = await pubSummaryRes.json();
                const publicationsByYear = await pubYearRes.json();
                const publicationsByCampus = await pubCampusRes.json();
                const publicationsCampusContribution = await pubContributionRes.json();
                const funding = await fundRes.json();

                const proposalTotal = (proposals.status_distribution || [])
                    .reduce((sum, row) => sum + Number(row.total_proposals || 0), 0);
                // Completed outputs come from the proposal warehouse, not the
                // publications table, because not every completed paper is published.
                const completedPapers = (proposals.completed_outputs_by_year || [])
                    .reduce((sum, row) => sum + Number(row.completed_outputs || 0), 0);
                // Published outputs remain sourced from the publications database.
                const publishedPapers = Number(publicationsSummary.total_publications || 0);
                const totalFund = Number(funding.total_allocated_fund?.[0]?.total_allocated_fund || 0);
                const proposalToPublicationRate = proposalTotal > 0 ? (completedPapers / proposalTotal) * 100 :
                    0;

                // The overview now skips the old conversion strip, so only the
                // hero cards and lower storytelling sections are updated here.
                setText('overviewHeroProposals', fmtInt(proposalTotal));
                setText('overviewHeroCompletedPapers', fmtInt(completedPapers));
                setText('overviewHeroPublishedPapers', fmtInt(publishedPapers));
                setText('overviewHeroFundAllocation', fmtCurrency(totalFund));
                setText('overviewHeroConversionRate', fmtPct(proposalToPublicationRate));
                // Keep the hero-card captions synchronized with the shared
                // year filter so the narrative text matches the numbers shown.
                overviewTrendRows = mergeOverviewTrendData(proposals, publicationsByYear);
                const overviewYearLabel = buildOverviewYearLabel(year, overviewTrendRows);
                const overviewRangeText = buildOverviewRangeText(year, overviewTrendRows);
                setText('overviewProposalYearLabel', overviewYearLabel);
                setText('overviewCompletedYearLabel', overviewYearLabel);
                setText('overviewPublishedYearLabel', overviewYearLabel);
                setText('overviewFundingYearLabel', overviewYearLabel);
                setText('overviewProposalCopy', year ? `Total research proposals submitted for ${year}.` :
                    `Total research proposals submitted from ${overviewRangeText}.`);
                setText('overviewCompletedCopy', year ?
                    `Research papers completed through to full output for ${year}.` :
                    `Research papers completed through to full output from ${overviewRangeText}.`);
                setText('overviewFundingCopy', year ?
                    `Total funds allocated to support research and innovation for ${year}.` :
                    `Total funds allocated to support research and innovation from ${overviewRangeText}.`);

                const visibleTrendRows = getOverviewDisplayRows(year, overviewTrendRows);
                // The trend now follows the shared overview year filter only,
                // so there is no separate local range selector to maintain.
                renderTrendChart(year);

                const outputCampusRows = (publicationsByCampus || [])
                    .map((row) => ({
                        campus_name: row.campus,
                        completed_outputs: Number(row.publications || 0), // ✅ matches SQL alias
                    }))
                    .sort((a, b) => b.completed_outputs - a.completed_outputs);

                const campusContributionRows = (publicationsCampusContribution || [])
                    .map((row) => ({
                        campus_name: row.campus,
                        completion_rate: Number(row.contribution_percentage || 0),
                    }))
                    .sort((a, b) => b.completion_rate - a.completion_rate);

                renderCampusBars(
                    'overviewTopCampusOutputs',
                    outputCampusRows.slice(0, 6),
                    'completed_outputs',
                    (value) => fmtInt(value)
                );
                renderCampusBars(
                    'overviewCampusCompletion',
                    campusContributionRows.slice(0, 6),
                    'completion_rate',
                    (value) => fmtPct(value),
                    'is-soft'
                );
                renderFundingShare(funding.funding_by_campus || []);

                overviewLoaded = true;
            } catch (error) {
                console.error(error);
                destroyTrendChart();
                destroyFundingShareChart();
                [
                    'overviewHeroProposals',
                    'overviewHeroCompletedPapers',
                    'overviewHeroPublishedPapers',
                    'overviewHeroFundAllocation',
                    'overviewHeroConversionRate',
                ].forEach((id) => setText(id, '—'));
                document.getElementById('overviewTrendChart').innerHTML =
                    '<div class="ra-empty">could not load trend data</div>';
                document.getElementById('overviewTopCampusOutputs').innerHTML =
                    '<div class="ra-empty">could not load campus output data</div>';
                document.getElementById('overviewCampusCompletion').innerHTML =
                    '<div class="ra-empty">could not load campus completion data</div>';
                document.getElementById('overviewFundingShareChart').innerHTML =
                    '<div class="ra-empty">could not load funding share data</div>';
                document.getElementById('overviewFundingLegend').innerHTML = '';
            }
        }

        document.getElementById('yearFilter')?.addEventListener('change', (e) => {
            if (overviewLoaded) {
                loadOverview(e.target.value);
            }
        });

        document.querySelectorAll('.ra-tab').forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'overview') {
                    const year = document.getElementById('yearFilter')?.value || '';
                    loadOverview(year);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('overviewDashboard');
            if (panel && panel.classList.contains('is-active')) {
                loadOverview('');
            }
        });
    })();
</script>
