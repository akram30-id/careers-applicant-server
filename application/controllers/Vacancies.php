<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vacancies extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function latest_vacancies()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/latest'));

                $divisi = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/divisi'));

                $vacancies = [];

                foreach ($divisi->response->data as $dataDivisi) {
                    $nama_divisi = $dataDivisi->nama_divisi;
                    if (isset($content->response->data->$nama_divisi)) {
                        foreach ($content->response->data->$nama_divisi as $response) {
                            if ($response->status == "Close") {
                                continue;
                            } else {
                                $vacancies[$nama_divisi][] = $response;
                            }
                        }
                    }
                };

                $data = [
                    'response' => [
                        'status' => $content->response->status,
                        'message' => $content->response->message,
                        'count' => count($vacancies),
                        'data' => $vacancies
                        // 'data' => $content
                    ]
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

    public function synchronize()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $content = $this->curl_get_contents('http://localhost/careers-admin-server/vacancy/synchronize');
                $data = $content;

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

    public function list_divisi()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $data = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/divisi'));

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

    public function get_divisi_by_id($idDivisi)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $data = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/divisi/id/' . $idDivisi));

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

    public function sort_by_divisi($idDivisi)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/s-divisi/' . str_replace('"', "", $idDivisi)));

                $divisi = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/divisi'));

                $vacancies = [];

                foreach ($divisi->response->data as $dataDivisi) {
                    $nama_divisi = $dataDivisi->nama_divisi;
                    if (isset($content->response->data->$nama_divisi)) {
                        foreach ($content->response->data->$nama_divisi as $response) {
                            if ($response->status == "Close") {
                                continue;
                            } else {
                                $vacancies[$nama_divisi][] = $response;
                            }
                        }
                    }
                };

                if (count($vacancies) < 1) {
                    $status = 404;
                    $message = "No Vacancy Found";
                } else {
                    $status = $content->response->status;
                    $message = $content->response->message;
                }

                $data = [
                    'response' => [
                        'status' => $status,
                        'message' => $message,
                        'data' => $vacancies
                    ],
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

    public function search_vacancy()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $idDivisi = str_replace('"', '', $this->input->get('id_divisi'));
                $searchValue = str_replace('"', '', $this->input->get('search_value'));

                if ($idDivisi !== NULL || $idDivisi !== "") {
                    $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/s/' . $idDivisi . '?search_value=' . str_replace(' ', '%', $searchValue)));
                } else {
                    $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/s?search_value=' . str_replace(' ', '%', $searchValue)));
                }

                $divisi = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/divisi'));

                $vacancies = [];

                foreach ($divisi->response->data as $dataDivisi) {
                    $nama_divisi = $dataDivisi->nama_divisi;
                    if (isset($content->response->data->$nama_divisi)) {
                        foreach ($content->response->data->$nama_divisi as $response) {
                            if ($response->status == "Close") {
                                continue;
                            } else {
                                $vacancies[$nama_divisi][] = $response;
                            }
                        }
                    }
                };

                if (count($vacancies) < 1) {
                    $status = 404;
                    $message = "No Vacancy Found";
                } else {
                    $status = $content->response->status;
                    $message = $content->response->message;
                }

                $data = [
                    'response' => [
                        'status' => $status,
                        'message' => $message,
                        'data' => $vacancies
                    ],
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

    public function vacancies_per_divisi($idDivisi, $page)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/divisi/' . $idDivisi . '/' . $page));

                $vacancies = [];
                if (isset($content->response->data)) {
                    foreach ($content->response->data as $responseData) {
                        if ($responseData->status !== "Close") {
                            $vacancies[] = $responseData;
                        }
                    }
                }

                if (count($vacancies) > 0) {
                    $message = 'Vacancie(s) Found';
                    $status = 200;
                } else {
                    $message = 'No Vacancie(s) Found';
                    $status = 404;
                }

                $data = [
                    'response' => [
                        'status' => $status,
                        'message' => $message,
                        'page' => $content->response->page,
                        'page_count' => $content->response->page_count,
                        'data' => $vacancies,
                        'content' => $content
                    ]
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

    public function vacancies_sort_per_divisi($idDivisi)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $level = $this->input->get('level');

                $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/f/' . str_replace('"', "", $idDivisi) . '?level=' . str_replace('"', '', $level)));

                $data = $content;

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

    public function detail_vacancy($idVacancy)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $content = json_decode($this->curl_get_contents('http://localhost/careers-admin-server/vacancy/id/' . str_replace('"', "", $idVacancy)));

                $data = $content;

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
