<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'form_validation'
        ]);

        $this->load->model('Modelmahasiswa', 'mahasiswa');
    }

    public function index()
    {
        $parser = [
            'judul' => '<i class="fa fa-users"></i> Data Mahasiswa',
            'isi' => $this->load->view('mahasiswa/tampil-data', ' ', true)
        ];

        $this->parser->parse('template/main', $parser);
    }

    // datatable
    public function ambildata()
    {
        if ($this->input->is_ajax_request() == true) {

            $list = $this->mahasiswa->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = array();

                // tombol aksi edit dan delete
                $tomboledit = "<button type=\"button\" class=\"btn btn-outline-info\" title=\"Edit Data\" onClick=\"edit('" . $field->nobp . "')\"><i class=\"fa fa-tag\"></i></button>";
                $tombolhapus = "<button type=\"button\" class=\"btn btn-outline-danger\" title=\"Hapus Data\" onClick=\"hapus('" . $field->nobp . "' , '" . $field->nama . "')\"><i class=\"fa fa-trash\"></i></button>";

                $row[] = $no;
                $row[] = $field->nobp;
                $row[] = $field->nama;
                $row[] = $field->tmplahir;
                $row[] = $field->tgllahir;
                $row[] = $field->jenkel;
                $row[] = $tomboledit . ' ' . $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->mahasiswa->count_all(),
                "recordsFiltered" => $this->mahasiswa->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function formtambah()
    {
        if ($this->input->is_ajax_request() == true) {
            $msg = [
                'success' => $this->load->view('mahasiswa/modal-tambah', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function formedit()
    {
        if ($this->input->is_ajax_request() == true) {
            $nobp = $this->input->post('nobp', true);

            $ambildata = $this->mahasiswa->ambildataById($nobp);

            if ($ambildata->num_rows() > 0) {
                $row = $ambildata->row_array();
                $data = [
                    'nobp' => $nobp,
                    'nama' => $row['nama'],
                    'tmplahir' => $row['tmplahir'],
                    'tgllahir' => $row['tgllahir'],
                    'jenkel' => $row['jenkel']
                ];
            }

            $msg = [
                'success' => $this->load->view('mahasiswa/modal-edit', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == true) {
            $nobp = $this->input->post('nobp', true);
            $nama = $this->input->post('nama', true);
            $tmplahir = $this->input->post('tmplahir', true);
            $tgllahir = $this->input->post('tgllahir', true);
            $jenkel = $this->input->post('jenkel', true);

            // set rules
            $this->form_validation->set_rules('nobp', 'No.BP', 'trim|required|is_unique[mahasiswa.nobp]', [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s sudah terdaftar'
            ]);
            $this->form_validation->set_rules('nama', 'Nama Mahasiswa', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('tmplahir', 'Tempat Lahir', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('tgllahir', 'Tanggal Lahir', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jenkel', 'Jenis Kelamin', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);

            // validasi
            if ($this->form_validation->run() == true) {
                // jika validasinya benar, simpan data
                $this->mahasiswa->simpan($nobp, $nama, $tmplahir, $tgllahir, $jenkel);

                $msg = [
                    'success' => 'Data berhasil di tambahkan'
                ];
            } else {
                // jika gagal tampilkan error
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        ' . validation_errors() . '
                    </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function updatedata()
    {
        if ($this->input->is_ajax_request() == true) {
            $nobp = $this->input->post('nobp', true);
            $nama = $this->input->post('nama', true);
            $tmplahir = $this->input->post('tmplahir', true);
            $tgllahir = $this->input->post('tgllahir', true);
            $jenkel = $this->input->post('jenkel', true);

            // set rules
            $this->form_validation->set_rules('nobp', 'No.BP', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('nama', 'Nama Mahasiswa', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('tmplahir', 'Tempat Lahir', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('tgllahir', 'Tanggal Lahir', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jenkel', 'Jenis Kelamin', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);

            // validasi
            if ($this->form_validation->run() == true) {
                // jika validasinya benar, simpan data
                $this->mahasiswa->update($nobp, $nama, $tmplahir, $tgllahir, $jenkel);

                $msg = [
                    'success' => 'Data berhasil diubah'
                ];
            } else {
                // jika gagal tampilkan error
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        ' . validation_errors() . '
                    </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == true) {
            $nobp = $this->input->post('nobp', true);

            $hapus = $this->mahasiswa->hapus($nobp);
            if ($hapus) {
                $msg = [
                    'success' => 'Berhasil dihapus'
                ];
            }

            echo json_encode($msg);
        }
    }
}
