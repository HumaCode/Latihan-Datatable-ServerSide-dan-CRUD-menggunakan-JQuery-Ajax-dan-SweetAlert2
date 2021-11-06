<!-- DataTables -->
<link href="<?= base_url() ?>assets/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="<?= base_url() ?>assets/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- responsive datatable -->
<link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />

<!-- Required datatable js -->
<script src="<?= base_url() ?>assets/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>


<!-- responsive serverside -->
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<div class="col-lg">
    <div class="card m-b-30">
        <h4 class="card-header mt-0">Semua Data Mahasiswa</h4>
        <div class="card-body">

            <button type="button" class="btn btn-primary" id="tomboltambah">
                <i class="fa fa-plus-circle"></i> Tambah Mahasiswa
            </button>

            <p class="card-text">
            <table id="datamahasiswa" class="table table-bordered table-striped display nowrap" style="width: 100%;">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>NOBP</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                </tbody>
            </table>
            </p>
        </div>
    </div>
</div>

<!-- modal -->
<div class="viewmodal" style="display: none;"></div>


<!-- script datatable -->
<script>
    function tampildatamahasiswa() {
        table = $('#datamahasiswa').DataTable({
            responsive: true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "<?= site_url('mahasiswa/ambildata') ?>",
                "type": "POST"
            },


            "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            }],

        });
    }

    $(document).ready(function() {
        tampildatamahasiswa();

        // tombol tambah
        $('#tomboltambah').click(function(e) {
            $.ajax({
                url: "<?= site_url('mahasiswa/formtambah') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('.viewmodal').html(response.success).show();

                        // fuction autofokus
                        $('#modal-tambah').on('shown.bs.modal', function(e) {
                            $('#nobp').focus();
                        })

                        // menampilkan modal
                        $('#modal-tambah').modal('show');
                    }
                }
            });
        });
    });


    // membuat function edit data
    function edit(nobp) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('mahasiswa/formedit') ?>",
            data: {
                nobp: nobp
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('.viewmodal').html(response.success).show();

                    // fuction autofokus
                    $('#modal-edit').on('shown.bs.modal', function(e) {
                        $('#nama').focus();
                    })

                    // menampilkan modal
                    $('#modal-edit').modal('show');
                }
            }
        });
    }


    // function hapus data
    function hapus(nobp, nama) {
        Swal.fire({
            title: 'Hapus Data',
            text: `Apakah kamu yakin akan menghapus data = ${ nama } ..?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus.',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('mahasiswa/hapus') ?>",
                    data: {
                        nobp: nobp
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Konfirmasi',
                                text: 'Data ' + `${ nama } ` + response.success
                            });
                            tampildatamahasiswa();
                        }
                    }
                });
            }
        })
    }
</script>