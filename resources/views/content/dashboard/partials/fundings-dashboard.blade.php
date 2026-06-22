{{--
    Fundings dashboard panel.
    These cards are layout-ready placeholders for the future funding fact table
    and keep stable IDs for later chart/table rendering.
--}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-6">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total funded projects</div>
            <div class="ra-kpi-value" id="kpiTotalFundedProjects">—</div>
            <div class="ra-kpi-foot">approved projects with funding</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-6">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total allocated fund</div>
            <div class="ra-kpi-value" id="kpiTotalAllocatedFund">—</div>
            <div class="ra-kpi-foot">sum of allocated funding</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by campus</h2>
                    <div class="ra-card-sub">allocated fund grouped by campus</div>
                </div>
            </div>
            <div class="ra-empty" id="fundingByCampus">Waiting for campus funding data.</div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding by program</h2>
                    <div class="ra-card-sub">allocated fund grouped by program</div>
                </div>
            </div>
            <div class="ra-empty" id="fundingByProgram">Waiting for program funding data.</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="ra-card">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Funding over time</h2>
                    <div class="ra-card-sub">allocated fund by year or quarter</div>
                </div>
            </div>
            <div class="ra-empty" id="fundingOverTime">Waiting for funding trend data.</div>
        </div>
    </div>
</div>
