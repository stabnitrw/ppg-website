(function ($) {
    'use strict';

    // Navbar shadow & tombol kembali ke atas
    var $nav = $('.site-nav'), $top = $('.back-to-top');
    $(window).on('scroll', function () {
        var y = window.scrollY;
        $nav.toggleClass('scrolled', y > 10);
        $top.toggleClass('show', y > 500);
    }).trigger('scroll');
    $('.back-to-top, .to-top-link').on('click', function (e) { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // Animasi muncul saat discroll
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { threshold: 0.12 });
        $('.reveal').each(function (i) { this.style.transitionDelay = (i % 4) * 80 + 'ms'; io.observe(this); });
    } else {
        $('.reveal').addClass('in');
    }

    // Salin tautan berita
    $('.copy-link').on('click', function () {
        var $b = $(this);
        if (navigator.clipboard) {
            navigator.clipboard.writeText($b.data('url')).then(function () {
                $b.html('<i class="bi bi-check2"></i>');
                setTimeout(function () { $b.html('<i class="bi bi-link-45deg"></i>'); }, 1500);
            });
        }
    });

    // Formulir lapor diri bertahap
    var $form = $('#lapor-form');
    if (!$form.length) { return; }
    var $steps = $form.find('.form-step'), total = $steps.length, current = 1;

    function setError($el, msg) {
        $el.addClass('is-invalid');
        var $wrap = $el.closest('[class*="col-"], .form-check');
        $wrap.find('.invalid-feedback.js').remove();
        if (msg) { $wrap.append('<div class="invalid-feedback d-block js">' + msg + '</div>'); }
    }

    function clearError($el) {
        $el.removeClass('is-invalid');
        $el.closest('[class*="col-"], .form-check').find('.invalid-feedback').remove();
    }

    function checkFile(input) {
        var f = input.files && input.files[0];
        if (!f) { return input.required ? 'Dokumen wajib diunggah.' : ''; }
        var ext = f.name.split('.').pop().toLowerCase(), allowed = String($(input).data('ext')).split(',');
        if (allowed.indexOf(ext) === -1) { return 'Format file harus: ' + allowed.join(', ').toUpperCase() + '.'; }
        if (f.size > $(input).data('max-kb') * 1024) { return 'Ukuran file melebihi ' + $(input).data('max-kb') + ' KB.'; }
        return '';
    }

    function validateStep(n) {
        var ok = true, $first = null;
        $steps.filter('[data-step="' + n + '"]').find('input, select, textarea').each(function () {
            var $el = $(this), msg = '';
            if (this.type === 'file') {
                msg = checkFile(this);
            } else if (this.type === 'radio') {
                if (this.required && !$form.find('[name="' + this.name + '"]:checked').length) { msg = 'Pilih salah satu.'; }
            } else if (!this.checkValidity()) {
                msg = this.validity.valueMissing ? 'Kolom ini wajib diisi.' : 'Format isian tidak valid.';
            }
            if (msg) { ok = false; setError($el, msg); $first = $first || $el; } else if (this.type !== 'radio') { clearError($el); }
        });
        if ($first) { $first.trigger('focus'); }
        return ok;
    }

    function show(n) {
        current = n;
        $steps.removeClass('active').filter('[data-step="' + n + '"]').addClass('active');
        $('.stepper li').each(function () {
            var s = +$(this).data('step');
            $(this).toggleClass('active', s === n).toggleClass('done', s < n);
        });
        $('#btn-prev').prop('hidden', n === 1);
        $('#btn-next').prop('hidden', n === total);
        $('#btn-submit').prop('hidden', n !== total);
        $('html, body').animate({ scrollTop: $('.stepper').offset().top - 100 }, 250);
    }

    function toggleSchool() {
        var inService = $('#ppg_category').val() === 'dalam_jabatan';
        $('#school_name').prop('required', inService);
        $('.school-req').toggle(inService);
    }

    $('#ppg_category').on('change', toggleSchool);
    toggleSchool();
    $('#btn-next').on('click', function () { if (validateStep(current)) { show(current + 1); } });
    $('#btn-prev').on('click', function () { show(current - 1); });
    $form.on('input change', 'input, select, textarea', function () {
        if ($(this).hasClass('is-invalid')) {
            var msg = this.type === 'file' ? checkFile(this) : (this.checkValidity() ? '' : 'x');
            if (!msg) { clearError($(this)); }
        }
    });
    $form.on('submit', function (e) {
        for (var i = 1; i <= total; i++) {
            if (!validateStep(i)) { e.preventDefault(); show(i); return; }
        }
        $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim…');
    });

    // Jika server mengembalikan error, buka langkah pertama yang bermasalah
    var $bad = $form.find('.is-invalid').first();
    if ($bad.length) { show(+$bad.closest('.form-step').data('step')); }
})(jQuery);
