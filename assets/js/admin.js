(function ($) {
    'use strict';

    // Sidebar mobile
    $('#sidebarToggle').on('click', function () { $('#sidebar').addClass('open'); $('#sidebarBackdrop').addClass('show'); });
    $('#sidebarBackdrop').on('click', function () { $('#sidebar').removeClass('open'); $(this).removeClass('show'); });

    // Konfirmasi sebelum hapus
    $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!window.confirm($(this).data('confirm'))) { e.preventDefault(); }
    });

    // Tampilkan field agenda sesuai jenis berita
    $('#type').on('change', function () {
        var isAgenda = this.value === 'agenda';
        $('#agenda-fields').toggleClass('d-none', !isAgenda);
        $('#event_date').prop('required', isAgenda);
    }).trigger('change');

    // Simpan tab aktif pada halaman pengaturan
    $('.settings-tabs [data-tab]').on('shown.bs.tab', function () {
        $('#current-tab').val($(this).data('tab'));
        history.replaceState(null, '', '#' + $(this).data('tab'));
    });
    if (location.hash && $('.settings-tabs [data-tab="' + location.hash.slice(1) + '"]').length) {
        bootstrap.Tab.getOrCreateInstance($('.settings-tabs [data-tab="' + location.hash.slice(1) + '"]')[0]).show();
    }

    // Pratinjau gambar sebelum upload
    $('.img-input').on('change', function () {
        var f = this.files && this.files[0], $prev = $(this).siblings('.img-preview, .logo-preview').find('img').addBack('img');
        if (f && /^image\//.test(f.type) && $prev.length) { $prev.attr('src', URL.createObjectURL(f)); }
    });

    // Editor teks kaya (Summernote) dengan upload gambar ke server
    if ($.fn.summernote) {
        var csrfName = $('meta[name="csrf-name"]').attr('content'), csrfHash = $('meta[name="csrf-hash"]').attr('content');
        $('.rich-editor').summernote({
            lang: 'id-ID',
            height: 380,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'table', 'hr']],
                ['view', ['codeview', 'fullscreen']]
            ],
            styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
            callbacks: {
                onImageUpload: function (files) {
                    var $ed = $(this);
                    $.each(files, function (_, file) {
                        var fd = new FormData();
                        fd.append('image', file);
                        fd.append(csrfName, csrfHash);
                        $.ajax({ url: window.ADMIN.uploadUrl, method: 'POST', data: fd, processData: false, contentType: false })
                            .done(function (res) { $ed.summernote('insertImage', res.url); })
                            .fail(function (xhr) { alert((xhr.responseJSON && xhr.responseJSON.error) || 'Gagal mengunggah gambar.'); });
                    });
                }
            }
        });
        $('form').on('submit', function () {
            $(this).find('.rich-editor').each(function () {
                if ($(this).summernote('isEmpty')) { $(this).val(''); }
            });
        });
    }
})(jQuery);
