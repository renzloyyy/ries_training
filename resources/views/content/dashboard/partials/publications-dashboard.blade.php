{{--
    Publications / Research Outputs dashboard panel.
    The cards are display-ready placeholders; the IDs give us stable hooks
    when fact_research_output API queries are added later.
--}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Total outputs</div>
            <div class="ra-kpi-value" id="kpiTotalOutputs">—</div>
            <div class="ra-kpi-foot">all research outputs</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completed outputs</div>
            <div class="ra-kpi-value" id="kpiCompletedOutputs">—</div>
            <div class="ra-kpi-foot">based on complete count</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Completion rate</div>
            <div class="ra-kpi-value" id="kpiOutputCompletionRate">—</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="ra-kpi-card">
            <div class="ra-kpi-label">Active campuses</div>
            <div class="ra-kpi-value" id="kpiOutputCampuses">—</div>
            <div class="ra-kpi-foot">with at least one output</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Outputs by campus</h2>
                    <div class="ra-card-sub">research output count per campus</div>
                </div>
            </div>
            <div class="ra-empty" id="outputsByCampus">Waiting for research output campus data.</div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Outputs by program</h2>
                    <div class="ra-card-sub">research output count per program</div>
                </div>
            </div>
            <div class="ra-empty" id="outputsByProgram">Waiting for research output program data.</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Outputs by category</h2>
                    <div class="ra-card-sub">journal, conference, book, or other output types</div>
                </div>
            </div>
            <div class="ra-empty" id="outputsByCategory">Waiting for research output category data.</div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ra-card h-100">
            <div class="ra-card-head">
                <div>
                    <h2 class="ra-card-title">Outputs by SDG</h2>
                    <div class="ra-card-sub">research output alignment by SDG</div>
                </div>
            </div>
            <div class="ra-empty" id="outputsBySdg">Waiting for research output SDG data.</div>
        </div>
    </div>
</div>
