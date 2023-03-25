<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>


<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Fixing Pemesanan dan Buat Pembelian</h3>
        </div>
    </div>

    <hr class="mt-0 mb-4">

    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered" width="100%" id="tabel">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="13%">No Pemesanan</th>
                    <th class="text-center" width="12%">Tanggal</th>
                    <th class="text-center" width="30%">Supplier</th>
                    <th class="text-center" width="15%">Admin</th>
                    <th class="text-center" width="15%">Status</th>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</main>

<?= $this->include('MyLayout/js') ?>

<script>
    // Bahan Alert
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: '#EC7063',
        color: '#fff',
        iconColor: '#fff',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


    $(document).ready(function() {
        $('#tabel').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= site_url() ?>get_pemesanan_ordered',
            order: [],
            columns: [{
                    data: 'no',
                    orderable: false
                },
                {
                    data: 'no_pemesanan'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'supplier'
                },
                {
                    data: 'admin'
                },
                {
                    data: 'status'
                },
                {
                    data: 'aksi',
                    orderable: false,
                    className: 'text-center'
                },
            ]
        });


        // Alert
        var op = <?= (!empty(session()->getFlashdata('pesan')) ? json_encode(session()->getFlashdata('pesan')) : '""'); ?>;
        if (op != '') {
            Toast.fire({
                icon: 'success',
                title: op
            })
        }
    });


    function confirm_delete(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus data pembelian ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const {
                    value: text
                } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Apa alasan menghapus data ini?',
                    inputPlaceholder: '',
                    inputAttributes: {
                        'aria-label': ''
                    },
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                })

                if (text) {
                    $.ajax({
                        type: "post",
                        url: "<?= base_url() ?>alasan_hapus_pemesanan",
                        data: 'id=' + id + '&alasan_dihapus=' + text,
                        dataType: "json",
                        success: function(response) {
                            if (response.ok) {
                                $('#form_delete').attr('action', '<?= site_url() ?>pembelian/' + response.id_pembelian);
                                $('#form_delete').submit();
                            } else {
                                Swal.fire(
                                    'Opss.',
                                    'Terjadi kesalahan, hubungi IT Support',
                                    'error'
                                )
                            }
                        },
                        error: function(e) {
                            alert('Error \n' + e.responseText);
                        }
                    });
                }
            }
        })
    }
</script>

<?= $this->endSection() ?>