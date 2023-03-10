<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>


<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Data Supplier</h3>
        </div>
    </div>

    <hr class="mt-0 mb-4">


    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered" id="tabel">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="15%">Origin</th>
                    <th class="text-center" width="20%">Nama</th>
                    <th class="text-center" width="15%">Telp</th>
                    <th class="text-center" width="15%">Status</th>
                    <th class="text-center" width="15%">Admin</th>
                    <th class="text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 ?>
                <?php foreach ($supplier as $sp) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $sp['origin'] ?></td>
                        <td><?= $sp['nama'] ?></td>
                        <td><?= $sp['no_telp'] ?></td>
                        <td><?= $sp['status'] ?></td>
                        <td><?= $sp['admin'] ?></td>
                        <td class="text-center">
                            <a title="Detail" class="px-2 py-0 btn btn-sm btn-outline-dark" onclick="showModalDetail(<?= $sp['id'] ?>)">
                                <i class="fa-fw fa-solid fa-magnifying-glass"></i>
                            </a>

                            <?php
                            $rule = has_permission('Admin Supplier') && (strpos($sp['id_admin'], user()->id) !== false);
                            $in_group = in_groups('Grup Super admin') || in_groups('Grup Owner');
                            if ($rule || $in_group) { ?>
                                <a title="Edit" class="px-2 py-0 btn btn-sm btn-outline-primary" href="<?= site_url() ?>supplier/<?= $sp['id'] ?>/edit">
                                    <i class="fa-fw fa-solid fa-pen"></i>
                                </a>

                                <form id="form_delete" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                                <button onclick="confirm_delete(<?= $sp['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                            <?php }  ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>

</main>

<!-- Modal -->
<div class="modal fade" id="my-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulModal"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="isiModal">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->

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
        $('#tabel').DataTable();

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
            text: "Apakah yakin menghapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete').attr('action', '<?= site_url() ?>supplier/' + id);
                $('#form_delete').submit();
            }
        })
    }


    function showModalDetail(id) {
        $.ajax({
            type: 'GET',
            url: '<?= site_url() ?>supplier/' + id,
            dataType: 'json',
            success: function(res) {
                if (res.data) {
                    $('#isiModal').html(res.data)
                    $('#my-modal').modal('toggle')
                    $('#judulModal').html('Detail Supplier')
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        })
    }
</script>

<?= $this->endSection() ?>