{{-- Soft UI 3 — admin table treatment (DataTables + plain tables). Scoped to
     the admin route-group body marker so every admin table shares one soft
     visual language with no per-view opt-in. Loaded once in <head>, after
     Bootstrap and DataTables CSS, so these overrides win. Uses DataTables 2.x
     selectors (`.dt-container`, `.dt-search`, `.dt-length`, `.dt-info`,
     `.dt-paging`). --}}
<style>
    /* Re-theme DataTables' native row states onto the soft-ui ramp. */
    body[data-route-group="admin"] {
        --dt-row-selected: 96, 93, 255;        /* #605DFF indigo */
        --dt-row-selected-text: 255, 255, 255;
        --dt-row-stripe: 246, 247, 249;        /* #F6F7F9 page-bg */
        --dt-row-stripe-alpha: 0.06;
        --dt-row-hover: 236, 240, 255;         /* #ECF0FF border-tint */
        --dt-row-hover-alpha: 0.09;
        --dt-column-ordering: 221, 228, 255;   /* #DDE4FF tint */
    }

    /* ---------- Table surface ---------- */
    body[data-route-group="admin"] table.dataTable {
        color: #3A4252;
        font-size: .8125rem;
        border-spacing: 0;
    }
    body[data-route-group="admin"] table.dataTable > thead > tr > th,
    body[data-route-group="admin"] table.dataTable > thead > tr > td,
    body[data-route-group="admin"] table.table:not(.dataTable) > thead > tr > th,
    body[data-route-group="admin"] table.table:not(.dataTable) > thead > tr > td {
        background: linear-gradient(180deg, #F7F8FF 0%, #EEF1FF 100%);
        color: #445164;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        padding: .75rem .875rem;
        border-bottom: 1px solid #E2E7F6;
        white-space: nowrap;
    }
    body[data-route-group="admin"] table.dataTable > tbody > tr > th,
    body[data-route-group="admin"] table.dataTable > tbody > tr > td {
        padding: .75rem .875rem;
        color: #3A4252;
        vertical-align: middle;
    }
    body[data-route-group="admin"] table.dataTable.display > tbody > tr > *,
    body[data-route-group="admin"] table.dataTable.row-border > tbody > tr > * {
        border-top: 1px solid #ECEEF2;
    }

    /* ---------- Sort indicators ---------- */
    body[data-route-group="admin"] table.dataTable thead > tr > th span.dt-column-order {
        color: #605DFF;
    }

    /* ---------- Search & length controls ---------- */
    body[data-route-group="admin"] .dt-container .dt-search input,
    body[data-route-group="admin"] .dt-container select.dt-input {
        background-color: #ffffff;
        border: 1px solid #E4E7F1;
        border-radius: 10px;
        padding: .45rem .75rem;
        color: #3A4252;
        transition: border-color .18s ease, box-shadow .18s ease;
    }
    body[data-route-group="admin"] .dt-container .dt-search input:focus,
    body[data-route-group="admin"] .dt-container select.dt-input:focus {
        outline: none;
        border-color: #605DFF;
        box-shadow: 0 0 0 3px rgba(96, 93, 255, 0.14);
    }
    body[data-route-group="admin"] .dt-container .dt-info {
        color: #64748B;
        font-size: .8125rem;
    }

    /* ---------- Pagination: soft rounded pills ---------- */
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button {
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 .55rem;
        margin-left: 4px;
        border: 1px solid transparent;
        border-radius: 9px;
        color: #526077 !important;
        font-size: .8125rem;
        transition: background-color .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease;
    }
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button:hover:not(.current):not(.disabled) {
        background-color: #ECF0FF;
        border-color: #DDE4FF;
        color: #4936F5 !important;
    }
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button.current,
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button.current:hover {
        background: linear-gradient(180deg, #7B79FF 0%, #6A68FF 100%);
        border-color: transparent;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(96, 93, 255, 0.32);
    }
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button.disabled,
    body[data-route-group="admin"] .dt-container .dt-paging .dt-paging-button.disabled:hover {
        color: #B7BECB !important;
        background: transparent;
        box-shadow: none;
    }

    /* ---------- Processing spinner & empty state ---------- */
    body[data-route-group="admin"] div.dt-processing > div:last-child > div {
        background: #605DFF;
    }
    body[data-route-group="admin"] table.dataTable td.dataTables_empty {
        color: #8695AA;
        padding: 3rem 1rem;
        text-align: center;
    }
</style>
