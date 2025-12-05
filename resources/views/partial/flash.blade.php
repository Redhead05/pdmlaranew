@if(session('success'))
    <div class="alert alert-primary bg-primary text-white position-relative flash-progress p-3" role="alert" data-duration="1500" style="overflow:hidden;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="flex-grow-1 pe-2">{{ session('success') }}</div>
            <div class="text-white fw-semibold ms-2" aria-hidden="true"><span class="flash-percent">0%</span></div>
        </div>

        <div class="progress mb-0" role="progressbar" aria-label="Flash progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height:6px;">
            <div class="progress-bar progress-bar-striped bg-danger" style="width:0%"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.flash-progress').forEach(function (flash) {
                const bar = flash.querySelector('.progress-bar');
                const pctEl = flash.querySelector('.flash-percent');

                // safer parsing: preserve 0 and only fallback on invalid values
                const raw = flash.dataset.duration;
                const parsed = Number(raw);
                const duration = Number.isFinite(parsed) ? parsed : 5000; // default fallback

                const start = performance.now();

                bar.style.width = '0%';
                bar.style.transition = 'width linear ' + duration + 'ms';

                // Trigger grow to 100%
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    bar.style.width = '100%';
                    flash.querySelector('[role="progressbar"]').setAttribute('aria-valuenow', '100');
                }));

                function tick(now) {
                    const elapsed = now - start;
                    const progress = Math.min(1, elapsed / duration);
                    const pct = Math.round(progress * 100);
                    if (pctEl) pctEl.textContent = pct + '%';

                    if (elapsed < duration) {
                        requestAnimationFrame(tick);
                    } else {
                        flash.style.transition = 'opacity .25s';
                        flash.style.opacity = '0';
                        setTimeout(() => flash.remove(), 300);
                    }
                }

                requestAnimationFrame(tick);
                flash.addEventListener('click', () => flash.remove());
            });
        });
    </script>
@endif
