{{-- Shared asesor-surface motion language: feedback, state, continuity.
     Purposeful only — no page-load choreography. Loaded once in <head>. --}}
<style>
    :root {
        --ksg-ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        --ksg-accent: #605DFF;
        --ksg-success-soft: #D8FFC8;
    }

    /* Feedback: success/error alert settles in after an action */
    .ksg-feedback {
        animation: ksg-feedback 260ms var(--ksg-ease-out);
    }
    @keyframes ksg-feedback {
        from { opacity: 0; transform: translateY(-5px) scale(0.985); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* State: conditional field reveal (Ya/Tidak relationship) */
    .ksg-reveal {
        animation: ksg-reveal 220ms var(--ksg-ease-out);
    }
    @keyframes ksg-reveal {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Continuity: single confident "saved" settle on the card */
    .ksg-pulse {
        animation: ksg-pulse 420ms var(--ksg-ease-out);
    }
    @keyframes ksg-pulse {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.006); }
        100% { transform: scale(1); }
    }

    /* Feedback: Bootstrap toast slides in from the edge */
    .toast.ksg-toast-in {
        animation: ksg-toast-in 300ms var(--ksg-ease-out);
    }
    @keyframes ksg-toast-in {
        from { opacity: 0; transform: translateX(16px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Feedback: every asesor button acknowledges press/hover with a confident,
       transform-only settle. Preserves Bootstrap's color/border/box-shadow
       transitions so hover tint still fades naturally. */
    body[data-route-group="asesor"] .btn,
    body[data-route-group="asesor"] .nav-link,
    body[data-route-group="asesor"] button:not(.btn-close) {
        transition: transform 140ms var(--ksg-ease-out),
                    color .15s ease-in-out,
                    background-color .15s ease-in-out,
                    border-color .15s ease-in-out,
                    box-shadow .15s ease-in-out;
    }
    body[data-route-group="asesor"] .btn:not(:disabled):hover,
    body[data-route-group="asesor"] .nav-link:not(:disabled):hover,
    body[data-route-group="asesor"] button:not(.btn-close):not(:disabled):hover {
        transform: translateY(-1px);
    }
    body[data-route-group="asesor"] .btn:not(:disabled):active,
    body[data-route-group="asesor"] .nav-link:not(:disabled):active,
    body[data-route-group="asesor"] button:not(.btn-close):not(:disabled):active {
        transform: translateY(1px) scale(0.97);
        transition-duration: 70ms;
    }

    @media (prefers-reduced-motion: reduce) {
        .ksg-feedback,
        .ksg-reveal,
        .ksg-pulse,
        .toast.ksg-toast-in {
            animation: none !important;
        }
        body[data-route-group="asesor"] .btn,
        body[data-route-group="asesor"] .nav-link,
        body[data-route-group="asesor"] button:not(.btn-close) {
            transform: none !important;
        }
    }
</style>
<script>
    window.KsgMotion = (function () {
        function node(v) { return v && v.jquery ? v[0] : v; }
        function play(v, cls) {
            var n = node(v);
            if (!n) return;
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            n.classList.remove(cls);
            void n.offsetWidth; /* restart animation */
            n.classList.add(cls);
        }
        return {
            feedback: function (v) { play(v, 'ksg-feedback'); },
            reveal: function (v) { play(v, 'ksg-reveal'); },
            pulse: function (v) { play(v, 'ksg-pulse'); },
            toast: function (v) { play(v, 'ksg-toast-in'); }
        };
    })();
</script>
