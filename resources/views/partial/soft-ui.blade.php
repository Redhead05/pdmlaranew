{{-- Soft UI treatment for the app shell (asesor + admin): soft elevation,
     gentle gradients, pill pastel status chips, soft toasts, and a soft
     sidebar menu. Scoped via the body route-group marker so it never leaks
     onto public/landing surfaces. Loaded once in <head>, after Bootstrap,
     so these overrides win. --}}
<style>
    /* ---------- Buttons: soft, rounded, gently elevated ---------- */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn {
        border-radius: 12px;
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    /* Primary — soft indigo-violet gradient with accent glow + top sheen */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-primary {
        background-image: linear-gradient(180deg, #7B79FF 0%, #605DFF 100%);
        border: none;
        box-shadow: 0 6px 16px -6px rgba(96, 93, 255, 0.55),
                    inset 0 1px 0 rgba(255, 255, 255, 0.28);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-primary:hover {
        background-image: linear-gradient(180deg, #8A88FF 0%, #6A68FF 100%);
        box-shadow: 0 8px 20px -6px rgba(96, 93, 255, 0.62),
                    inset 0 1px 0 rgba(255, 255, 255, 0.34);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-primary:active {
        box-shadow: 0 2px 6px -2px rgba(96, 93, 255, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    /* Success — soft lime gradient */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-success {
        background-image: linear-gradient(180deg, #4FE30F 0%, #37D80A 100%);
        border: none;
        box-shadow: 0 6px 16px -6px rgba(55, 216, 10, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-success:hover {
        background-image: linear-gradient(180deg, #5CE81F 0%, #3EDB0F 100%);
        box-shadow: 0 8px 20px -6px rgba(55, 216, 10, 0.58),
                    inset 0 1px 0 rgba(255, 255, 255, 0.36);
    }

    /* Neutral & outline — soft elevation, keep their tone */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-secondary,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-secondary,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-primary,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-warning,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-info,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-success {
        box-shadow: 0 3px 8px -3px rgba(100, 100, 111, 0.25);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-outline-danger {
        box-shadow: 0 3px 8px -3px rgba(253, 88, 18, 0.22);
    }

    /* Solid semantic buttons — soft elevation, keep their tone */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-danger,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-warning,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .btn-info {
        box-shadow: 0 6px 16px -6px rgba(100, 100, 111, 0.35),
                    inset 0 1px 0 rgba(255, 255, 255, 0.28);
    }

    /* ---------- Badges: soft pastel pills with matching text ---------- */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
        box-shadow: 0 1px 2px rgba(100, 100, 111, 0.14),
                    inset 0 1px 0 rgba(255, 255, 255, 0.55);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-primary,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-primary {
        background-color: #DDE4FF !important;
        color: #4936F5 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-success,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-success {
        background-color: #D8FFC8 !important;
        color: #1E7A00 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-danger,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-danger {
        background-color: #FFE1DD !important;
        color: #C43805 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-warning,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-warning {
        background-color: #FFF4D6 !important;
        color: #8A5C00 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-secondary,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-secondary {
        background-color: #ECEFF4 !important;
        color: #526077 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-info,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-info {
        background-color: #DDF3FE !important;
        color: #0E7DB2 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.bg-light,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .badge.text-bg-light {
        background-color: #ECEFF4 !important;
        color: #526077 !important;
        border-color: #ECEEF2 !important;
    }

    /* ---------- Toasts: soft pastel, rounded, gently elevated ---------- */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast {
        border-radius: 14px;
        border: none;
        box-shadow: 0 12px 28px -10px rgba(100, 100, 111, 0.35),
                    inset 0 1px 0 rgba(255, 255, 255, 0.6);
        overflow: hidden;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-primary {
        background-color: #DDE4FF !important;
        color: #4936F5 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-success {
        background-color: #D8FFC8 !important;
        color: #1E7A00 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-danger {
        background-color: #FFE1DD !important;
        color: #C43805 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-warning {
        background-color: #FFF4D6 !important;
        color: #8A5C00 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-info {
        background-color: #DDF3FE !important;
        color: #0E7DB2 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast.text-bg-secondary {
        background-color: #ECEFF4 !important;
        color: #526077 !important;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast .btn-close-white {
        filter: none;
        opacity: 0.55;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .toast .btn-close-white:hover {
        opacity: 1;
    }

    /* ---------- Icons: soft rounded action icons in table cells ---------- */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) table td a:not(.btn):has(.material-symbols-outlined),
    body:is([data-route-group="asesor"], [data-route-group="admin"]) table td button:not(.btn):has(.material-symbols-outlined) {
        border-radius: 9px;
        transition: background-color .18s ease, box-shadow .18s ease;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) table td a:not(.btn):has(.material-symbols-outlined):hover,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) table td button:not(.btn):has(.material-symbols-outlined):hover {
        background-color: #ECF0FF;
        box-shadow: 0 3px 8px -3px rgba(96, 93, 255, 0.38);
    }

    /* ---------- Sidebar: soft menu ---------- */
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-link {
        border-radius: 12px;
        transition: background-color .2s cubic-bezier(0.16, 1, 0.3, 1),
                    background-image .2s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow .2s cubic-bezier(0.16, 1, 0.3, 1),
                    color .2s ease;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-inner > .menu-item > .menu-link:hover,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-sub .menu-link:hover {
        background-color: #F6F7F9;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-inner > .menu-item > .menu-link.active,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-sub .menu-link.active {
        background-image: linear-gradient(180deg, #ECF0FF 0%, #DDE4FF 100%);
        color: #4936F5;
        box-shadow: 0 6px 16px -8px rgba(96, 93, 255, 0.55),
                    inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-inner > .menu-item > .menu-link.active .title,
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-sub .menu-link.active .title {
        color: #4936F5;
    }
    body:is([data-route-group="asesor"], [data-route-group="admin"]) .menu-vertical .menu-sub .menu-link.active::before {
        border-color: #4936F5;
        background-color: #4936F5;
    }
</style>
