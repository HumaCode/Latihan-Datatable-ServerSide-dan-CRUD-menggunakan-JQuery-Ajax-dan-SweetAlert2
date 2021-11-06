<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title " id="exampleModalLabel">Edit Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open('mahasiswa/updatedata', ['class' => 'formsimpan']) ?>
            <div class="pesan " style="display: none;"></div>

            <div class="modal-body">

                <div class="form-group row">
                    <label for="nobp" class="col-sm-3 col-form-label">No.BP</label>
                    <div class="col-sm">
                        <input type="text" name="nobp" id="nobp" class="form-control" readonly value="<?= $nobp ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama Mahasiswa</label>
                    <div class="col-sm">
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $nama ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tmplahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                    <div class="col-sm">
                        <input type="text" name="tmplahir" id="tmplahir" class="form-control" value="<?= $tmplahir ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tgllahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm">
                        <input type="date" name="tgllahir" id="tgllahir" class="form-control" value="<?= $tgllahir ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="jenkel" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm">
                        <select name="jenkel" id="jenkel" class="form-control">
                            <option value="L" <?php if($jenkel == 'L') {echo 'selected';} ?>>Laki-laki</option>
                            <option value="P" <?php if($jenkel == 'P') {echo 'selected';} ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="sibmit" class="btn btn-primary">Simpan</button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>


<!-- tampung datanya yang diinputkan user -->
<script>
    $(document).ready(function() {
        $('.formsimpan').submit(function(e) {
            $.ajax({
                type: "POST",
                url: $(this).attr('action'), // atribut yang namanya action 
                data: $(this).serialize(), // semua data yang ada di form
                dataType: "JSON",
                success: function(response) {
                    if (response.error) {
                        $('.pesan').html(response.error).show();
                    }

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.success
                        });

                        tampildatamahasiswa();
                        $('#modal-edit').modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, trownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + trownError);
                }
            });
            return false;
        });
    });
</script>