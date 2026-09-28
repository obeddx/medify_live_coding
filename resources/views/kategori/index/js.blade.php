<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [4, 5] }
            ]
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    function esc(text) {
        return $('<div>').text(text == null ? '' : text).html();
    }

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url("kategori/search") }}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: {
                kode: $('#filter-kode').val(),
                nama: $('#filter-nama').val()
            },
            success: function(results) {
                var rows = [];

                $.each(results.data, function(index, item) {
                    var btnView = '<a href="{{ url("kategori/view") }}/' + encodeURIComponent(item.kode) + '" class="btn btn-primary">View</a>';
                    var btnEdit = '<a href="{{ url("kategori/form/edit") }}/' + item.id + '" class="btn btn-primary">Edit</a>';

                    rows.push([
                        item.id,
                        esc(item.kode),
                        esc(item.nama),
                        item.jumlah_item,
                        btnView,
                        btnEdit
                    ]);
                });

                dataTableObj.rows.add(rows).draw();
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>