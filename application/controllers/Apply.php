<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apply extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ApplyModel');
    }

    public function index($idVacancy = NULL, $statusApplicant = 'qualified')
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':

                $id_loker = $this->input->post('id_loker');

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

                    $min_pengalaman = 3;
                    $jenjang = intval($this->input->post('jenjang'));

                    //insert pengalaman
                    $dataPengalaman = [];
                    $selisih = [];
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

                        $yearSelesai = date('Y', strtotime($pecahData[3]));
                        $yearMulai = date('Y', strtotime($pecahData[2]));
                        $monthSelesai = date('n', strtotime($pecahData[3]));
                        $monthMulai = date('n', strtotime($pecahData[2]));

                        if ($yearSelesai == $yearMulai) {
                            if ($monthSelesai == $monthMulai) {
                                $selisih[$i] = 0;
                            } else {
                                $selisih[$i] = intval($monthSelesai) - intval($monthMulai);
                            }
                        } else {
                            if ($monthSelesai == $monthMulai) {
                                $selisih[$i] = (intval($yearSelesai) - intval($yearMulai)) * 12;
                            } else if ($monthMulai > $monthSelesai) {
                                $selisih[$i] = ((intval($yearSelesai) - intval($yearMulai)) * 12) - (intval($monthMulai) - intval($monthSelesai));
                            } else if ($monthMulai < $monthSelesai) {
                                $selisih[$i] = ((intval($yearSelesai) - intval($yearMulai)) * 12) + (intval($monthSelesai) - intval($monthMulai));
                            }
                        }

                        $insertPengalaman = $this->ApplyModel->insert_data('tbl_pengalaman', $dataPengalaman[$i]);
                    }

                    $akumulasi = 0;
                    foreach ($selisih as $s) {
                        if ($s <= 0) {
                            $akumulasi = 0;
                        } else {
                            $akumulasi = $akumulasi + $s;
                        }
                    }

                    $rpc_get_vacancy_by_jabatan = [];
                    $poin = 0;
                    for ($i = 0; $i < count($dataPengalaman); $i++) {
                        $jabatan = str_replace(' ', '%', $dataPengalaman[$i]['jabatan_kerja']);
                        $url = 'http://localhost/careers-admin-server/filter/' . intval($id_loker) . '?jabatan=' . str_replace('"', '', $jabatan);
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        $get_rpc_content = curl_exec($ch);
                        array_push($rpc_get_vacancy_by_jabatan, json_decode($get_rpc_content));
                        if ($rpc_get_vacancy_by_jabatan[$i] != NULL) {
                            if ($rpc_get_vacancy_by_jabatan[$i]->response->status == 200) {
                                $poin++;
                            }
                        }
                    }
                    curl_close($ch);

                    $get_rpc_pendidikan = file_get_contents('http://localhost/careers-admin-server/edupoint/' . intval($id_loker) . '');
                    $get_pendidikan_point_vacancy = json_decode($get_rpc_pendidikan);

                    // if ($jenjang >= $get_pendidikan_point_vacancy->response->value) {
                    //     $poin++;
                    // }

                    if ($poin > 0) {
                        $sisaBagi = $akumulasi % 12;
                        $hasilBagi = ($akumulasi - $sisaBagi) / 12;

                        if ($hasilBagi >= $min_pengalaman || $jenjang >= $get_pendidikan_point_vacancy->response->value) {
                            $status = 'qualified';
                        } else {
                            $status = 'disqualified';
                        }
                    } else {
                        $status = 'disqualified';
                    }

                    $insertStatus = $this->ApplyModel->insert_data('tbl_status', ['id_personal' => $id_personal, 'status' => $status]);

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

                    if ($insertPendidikan && $insertPengalaman && $insertStatus && $insertKeahlian && $insertReferensi) {
                        $data['response'] = [
                            'status' => 201,
                            'message' => 'Input Applicant Success',
                        ];
                    } else {
                        $data['response'] = [
                            'status' => 500,
                            'message' => 'Internal Server Error',
                            'data_pengalaman' => $dataPengalaman
                        ];
                    }
                } else {
                    $data['response'] = [
                        'status' => 500,
                        'message' => 'Internal Server Error',
                    ];
                }

                break;

            case 'GET':

                $list = $this->ApplyModel->get_datatables($idVacancy, $statusApplicant);
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
                    $row[] = '<div class="d-grid gap-1"><button class="btn btn-danger btn-sm rounded-pill btn-delete-personal" data-id="' . $data_applicant->id_personal . '" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button></div>';
                    $dataRow[] = $row;
                }
                $data = [
                    'draw' => $this->input->get('draw'),
                    'recordsTotal' => $this->ApplyModel->count_all(),
                    'recordsFiltered' => $this->ApplyModel->count_filtered($idVacancy, $statusApplicant),
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

    public function applicantDelete($id_personal)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

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

    public function getSession()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $data['session'] = [
                    $this->session->userdata()
                ];
                break;

            case 'DELETE':
                $this->session->unset_userdata('id_loker[]');

                $data = [
                    'message' => 'id_loker session removed'
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

    public function testing($id_personal = 1)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'POST':

                $min_pengalaman = 3;

                $jenjang = intval($this->input->post('jenjang'));

                //insert pengalaman
                $dataPengalaman = [];
                $selisih = [];
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

                    $yearSelesai = date('Y', strtotime($pecahData[3]));
                    $yearMulai = date('Y', strtotime($pecahData[2]));
                    $monthSelesai = date('n', strtotime($pecahData[3]));
                    $monthMulai = date('n', strtotime($pecahData[2]));

                    if ($yearSelesai == $yearMulai) {
                        if ($monthSelesai == $monthMulai) {
                            $selisih[$i] = 0;
                        } else {
                            $selisih[$i] = intval($monthSelesai) - intval($monthMulai);
                        }
                    } else {
                        if ($monthSelesai == $monthMulai) {
                            $selisih[$i] = (intval($yearSelesai) - intval($yearMulai)) * 12;
                        } else if ($monthMulai > $monthSelesai) {
                            $selisih[$i] = ((intval($yearSelesai) - intval($yearMulai)) * 12) - (intval($monthMulai) - intval($monthSelesai));
                        } else if ($monthMulai < $monthSelesai) {
                            $selisih[$i] = ((intval($yearSelesai) - intval($yearMulai)) * 12) + (intval($monthSelesai) - intval($monthMulai));
                        }
                    }
                }

                $akumulasi = 0;
                foreach ($selisih as $s) {
                    if ($s <= 0) {
                        $akumulasi = 0;
                    } else {
                        $akumulasi = $akumulasi + $s;
                    }
                }

                $rpc_get_vacancy_by_jabatan = [];
                $poin = 0;
                for ($i = 0; $i < count($dataPengalaman); $i++) {
                    $jabatan = str_replace(' ', '%', $dataPengalaman[$i]['jabatan_kerja']);
                    $url = 'http://localhost/careers-admin-server/filter/3300123130207?jabatan=' . str_replace('"', '', $jabatan);
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    $get_rpc_content = curl_exec($ch);
                    array_push($rpc_get_vacancy_by_jabatan, json_decode($get_rpc_content));
                    if ($rpc_get_vacancy_by_jabatan[$i] != NULL) {
                        if ($rpc_get_vacancy_by_jabatan[$i]->response->status == 200) {
                            $poin++;
                        }
                    }
                }
                curl_close($ch);

                $get_rpc_pendidikan = file_get_contents('http://localhost/careers-admin-server/edupoint/3300123130207');
                $get_pendidikan_point_vacancy = json_decode($get_rpc_pendidikan);

                // if ($jenjang >= $get_pendidikan_point_vacancy->response->value) {
                //     $poin++;
                // }

                if ($poin > 0) {
                    $sisaBagi = $akumulasi % 12;
                    $hasilBagi = ($akumulasi - $sisaBagi) / 12;

                    if ($hasilBagi >= $min_pengalaman && $jenjang >= $get_pendidikan_point_vacancy->response->value) {
                        $status = 'qualified';
                    } else {
                        $status = 'disqualified';
                    }
                } else {
                    $status = 'disqualified';
                }

                $data['response'] = [
                    'status' => 200,
                    'message' => 'Success',
                    // 'data_pengalaman' => $dataPengalaman,
                    // 'data_kualifikasi' => $rpc_get_vacancy_by_jabatan,
                    'akumulasi' => $hasilBagi . ' Tahun ' . $sisaBagi . ' Bulan',
                    'min_pengalaman' => $min_pengalaman,
                    // 'selisih' => $selisih,
                    'poin_pendidikan' => $get_pendidikan_point_vacancy->response->value,
                    'applicant_pendidikan' => $jenjang,
                    'status' => $status
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
}
