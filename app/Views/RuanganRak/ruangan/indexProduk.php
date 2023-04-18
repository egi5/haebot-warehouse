<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>


<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Data Produk</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>ruangan-rak">
                <i class="fa-fw fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-danger" href="<?= site_url() ?>ruangan">
                <i class="fa-fw fa-solid fa-gear"></i> Master Ruangan
            </a>
        </div>
        <div class="mb-1">
            <a class="btn btn-sm btn-outline-secondary mb-3" id="tombolTambah">
                <i class="fa-fw fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <hr class="mt-0 mb-4">

    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered" width="100%" id="tabel">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="40%">Ruangan</th>
                    <th class="text-center" width="40%">Produk</th>
                    <th class="text-center" width="15%">Stok</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</main>

<?= $this->include('MyLayout/js') ?>



<!-- Modal -->
<div class="modal fade" id="my-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulModal"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="isiForm">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->



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
            ajax: '<?= site_url() ?>getdataruanganproduk',
            order: [],
            columns: [{
                    data: 'no',
                    orderable: false
                },
                {
                    data: 'ruangan'
                },
                {
                    data: 'produk'
                },
                {
                    data: 'stok',
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


    $('#tombolTambah').click(function(e) {
        e.preventDefault();
        showModalTambah();
    })


    function showModalTambah() {
        $.ajax({
            type: 'GET',
            url: '<?= site_url() ?>lokasiproduk/new',
            dataType: 'json',
            success: function(res) {
                if (res.data) {
                    $('#isiForm').html(res.data)
                    $('#my-modal').modal('toggle')
                    $('#judulModal').html('Tambah Produk')
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        })
    }
</script>

<?= $this->endSection() ?>