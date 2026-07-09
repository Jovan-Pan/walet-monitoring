$(document).ready(function () {
    // Sidebar toggle
    $('#sidebarToggle').on('click', function () {
        $('#sidebar').toggleClass('show');
    });

    // Select2 init
    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }

    // Toast auto-show on page load
    $('.toast').toast('show');

    // Confirm delete
    $('.btn-delete').on('click', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
            window.location.href = url;
        }
    });

    // Auto fill harga per kg berdasarkan grade (form hasil panen)
    $('#grade').on('change', function () {
        var grade = $(this).val();
        var hargaField = $('#harga_per_kg');
        if (hargaField.val() === '' || confirm('Update harga per kg sesuai grade ' + grade + '?')) {
            var harga = { 'A': 15000000, 'B': 10000000, 'C': 5000000 };
            if (harga[grade]) {
                hargaField.val(harga[grade]);
            }
        }
    });

    // Format angka ribuan saat input
    $('.format-rupiah').on('input', function () {
        var val = $(this).val().replace(/[^\d]/g, '');
        if (val) {
            $(this).val(parseInt(val).toLocaleString('id-ID'));
        }
    });

    // Hitung total nilai otomatis (form hasil panen)
    $('#berat_kg, #harga_per_kg').on('input', function () {
        var berat = parseFloat($('#berat_kg').val()) || 0;
        var harga = parseFloat($('#harga_per_kg').val().replace(/[^\d]/g, '')) || 0;
        var total = berat * harga;
        $('#total_nilai_preview').text('Rp ' + total.toLocaleString('id-ID'));
    });

    // Filter form auto submit on change
    $('.auto-submit').on('change', function () {
        $(this).closest('form').submit();
    });
});
