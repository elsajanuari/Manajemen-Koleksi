<script>
    document.querySelectorAll('[data-scroll-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var track = document.getElementById(btn.getAttribute('data-scroll-target'));
            if (!track) return;
            var delta = track.clientWidth * 0.82;
            track.scrollBy({
                left: btn.classList.contains('scroll-btn--next') ? delta : -delta,
                behavior: 'smooth'
            });
        });
    });

    (function () {
        var dialog = document.getElementById('gallery-lightbox');
        if (!dialog) return;

        var openButtons = document.querySelectorAll('[data-lightbox-open]');
        var closeButtons = dialog.querySelectorAll('[data-lightbox-close]');
        var lightboxImg = dialog.querySelector('.lightbox__figure img');

        function openLightbox(src) {
            if (lightboxImg && src) {
                lightboxImg.src = src;
            }
            if (typeof dialog.showModal === 'function') {
                dialog.showModal();
            } else {
                dialog.setAttribute('open', '');
            }
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            if (dialog.open) {
                dialog.close();
            } else {
                dialog.removeAttribute('open');
            }
            document.body.style.overflow = '';
        }

        openButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                openLightbox(btn.getAttribute('data-lightbox-src'));
            });
        });

        closeButtons.forEach(function (btn) {
            btn.addEventListener('click', closeLightbox);
        });

        dialog.addEventListener('click', function (e) {
            if (e.target === dialog) {
                closeLightbox();
            }
        });

        dialog.addEventListener('cancel', function () {
            document.body.style.overflow = '';
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && dialog.open) {
                closeLightbox();
            }
        });
    })();
</script>
