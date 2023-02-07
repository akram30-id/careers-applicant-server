<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apply extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ApplyModel');
    }

    public function index($idVacancy = NULL)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':

                $id_loker = $this->input->post('id_loker');

                if ($this->session->fingerprint == "" || $this->session->id_loker != $id_loker) {
                    $nama = $this->input->post('nama');
                    $tempat_lahir = $this->input->post('tempat_lahir');
                    $tgl_lahir = $this->input->post('tgl_lahir');
                    $email = $this->input->post('email');
                    $kontak = $this->input->post('kontak');

                    $dataPersonal = [
                        'id_loker' => $id_loker,
                        'nama_lengkap' => $nama,
                        'tempat_lahir' => $tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'kontak' => $kontak,
                        'email' => $email,
                    ];

                    $insertPersonal = $this->ApplyModel->insert_personal($dataPersonal);

                    if ($insertPersonal[0] == TRUE) {
                        $id_personal = $insertPersonal[1];

                        $fingerprint = bin2hex($nama);
                        $this->session->set_userdata(['fingerprint' => $fingerprint]);
                        $this->session->set_userdata(['id_loker' => $id_loker]);
                        $this->session->mark_as_temp('fingerprint', 3600);
                        $this->session->mark_as_temp('id_loker', 3600);

                        //insert pendidikan
                        $jenjang = $this->input->post('jenjang');
                        $prodi = $this->input->post('prodi');
                        $institusi = $this->input->post('institusi_pendidikan');
                        $tgl_mulai = $this->input->post('tgl_mulai_pendidikan');
                        $tgl_selesai = $this->input->post('tgl_selesai_pendidikan');
                        $achievement_pendidikan = $this->input->post('achievement_pendidikan');

                        $dataPendidikan = [
                            'id_personal' => $id_personal,
                            'jenjang_pendidikan' => $jenjang,
                            'prodi_pendidikan' => $prodi,
                            'institusi_pendidikan' => $institusi,
                            'tgl_mulai' => $tgl_mulai,
                            'tgl_selesai' => $tgl_selesai,
                            'achievement_pendidikan' => $achievement_pendidikan,
                        ];

                        $insertPendidikan = $this->ApplyModel->insert_data('tbl_pendidikan', $dataPendidikan);

                        //insert pengalaman
                        $dataPengalaman = [];
                        $pengalaman = $this->input->post('pengalaman');
                        $pengalaman = explode(";", $pengalaman);
                        for ($i = 0; $i < (count($pengalaman)); $i++) {
                            $pecahData = explode("$", $pengalaman[$i]);
                            // $dataPengalaman[] = $pecahData;
                            $dataPengalaman[$i]['id_personal'] = $id_personal;
                            $dataPengalaman[$i]['institusi_kerja'] = $pecahData[0];
                            $dataPengalaman[$i]['jabatan_kerja'] = $pecahData[1];
                            $dataPengalaman[$i]['tgl_mulai'] = $pecahData[2];
                            $dataPengalaman[$i]['tgl_selesai'] = $pecahData[3];
                            $dataPengalaman[$i]['achievement_kerja'] = $pecahData[4];

                            $insertPengalaman = $this->ApplyModel->insert_data('tbl_pengalaman', $dataPengalaman[$i]);
                        }

                        //insert keahlian
                        $jenis_sertifikat = $this->input->post('jenis_sertifikat');
                        $tgl_berlaku = $this->input->post('tgl_berlaku');
                        $tgl_expired = $this->input->post('tgl_expired');

                        $config['upload_path'] = './assets/sertifikat/';
                        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                        $config['max_size']  = '1536';
                        $config['max_width']  = '2048';
                        $config['max_height']  = '2048';

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('file')) {
                            $data['response'] = [
                                'status' => 500,
                                'message' => 'Failed',
                                'error' => $this->upload->display_errors()
                            ];
                        } else {
                            $dataupload = $this->upload->data();

                            $dataKeahlian = [
                                'id_personal' => $id_personal,
                                'jenis_sertifikat' => $jenis_sertifikat,
                                'tgl_berlaku' => $tgl_berlaku,
                                'tgl_expired' => $tgl_expired,
                                'file_sertifikat' => $dataupload['file_name'],
                            ];

                            $insertKeahlian = $this->ApplyModel->insert_data('tbl_keahlian', $dataKeahlian);
                        }

                        //insert referensi
                        $referensi = $this->input->post('referensi');

                        $dataReferensi = [
                            'id_personal' => $id_personal,
                            'referensi' => $referensi,
                        ];

                        $insertReferensi = $this->ApplyModel->insert_data('tbl_referensi', $dataReferensi);

                        if ($insertPendidikan && $insertPengalaman && $insertKeahlian && $insertReferensi) {
                            $data['response'] = [
                                'status' => 201,
                                'message' => 'Input Applicant Success',
                                'fingerprint' => $fingerprint,
                                'data_pengalaman' => $dataPengalaman,
                                'count' => count($pengalaman)
                            ];
                        }
                    } else {
                        $data['response'] = [
                            'status' => 500,
                            'message' => 'Internal Server Error',
                        ];
                    }
                } else {
                    $data['response'] = [
                        'status' => 400,
                        'message' => 'Anda telah melamar di vacancy ini. Silahkan coba lagi nanti.',
                    ];
                }

                break;

            case 'DELETE':

                $id_personal = $this->input->input_stream('id_personal');

                $cek = $this->ApplyModel->get_applicant('tbl_personal', $id_personal);
                $sertifkat = $this->ApplyModel->get_applicant('tbl_keahlian', $id_personal);

                if ($cek->num_rows() > 0) {
                    $deleteQuery = $this->ApplyModel->delete_applicant($id_personal);

                    unlink(FCPATH . 'assets/sertifikat/' . $sertifkat->result()[0]->file_sertifikat);

                    if ($deleteQuery !== FALSE) {
                        $data['response'] = [
                            'status' => 200,
                            'message' => 'Delete Applicant Success'
                        ];
                    } else {
                        $data['response'] = [
                            'status' => 500,
                            'message' => 'Internal Server Error'
                        ];
                    }
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Applicant Found'
                    ];
                }

                break;

            case 'GET':

                $list = $this->ApplyModel->get_datatables($idVacancy);
                $dataRow = [];
                $no = $this->input->get('start');

                //looping data
                foreach ($list as $data_applicant) {
                    $no++;
                    $row = [];

                    // row terakhir untuk button update dan delete
                    $row[] = $no;
                    $row[] = $data_applicant->nama_lengkap;
                    $row[] = $data_applicant->tempat_lahir . ', ' . date('d F Y', strtotime($data_applicant->tgl_lahir));
                    $row[] = $data_applicant->email;
                    $row[] = $data_applicant->kontak;
                    $row[] = $data_applicant->referensi;
                    $row[] = '<button class="btn btn-warning btn-sm btn-show-details rounded-pill" data-id="' . $data_applicant->id_personal . '" data-bs-toggle="modal" data-bs-target="#modal-details">Show</button>';
                    $row[] = '<div class="d-grid gap-1"><button class="btn btn-danger btn-sm rounded-pill btn-delete-personal" data-id="' . $data_applicant->id_personal . '">Delete</button></div>';
                    $dataRow[] = $row;
                }
                $data = [
                    'draw' => $this->input->get('draw'),
                    'recordsTotal' => $this->ApplyModel->count_all(),
                    'recordsFiltered' => $this->ApplyModel->count_filtered($idVacancy),
                    'data' => $dataRow
                ];

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request'
                ];
                break;
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function applicantDetails($idApplicant = NULL)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'GET':

                $personal = $this->ApplyModel->get_details_applicant('tbl_personal', $idApplicant);
                $pengalaman = $this->ApplyModel->get_details_applicant('tbl_pengalaman', $idApplicant);
                $pendidikan = $this->ApplyModel->get_details_applicant('tbl_pendidikan', $idApplicant);
                $keahlian = $this->ApplyModel->get_details_applicant('tbl_keahlian', $idApplicant);
                $referensi = $this->ApplyModel->get_details_applicant('tbl_referensi', $idApplicant);

                if ($personal->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Details Found',
                        'data' => [
                            'nama' => $personal->result()[0]->nama_lengkap,
                            'pengalaman' => $pengalaman->result(),
                            'pendidikan' => $pendidikan->result(),
                            'keahlian' => $keahlian->result(),
                            'referensi' => $referensi->result(),
                        ]
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Applicant Found'
                    ];
                }

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request'
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
