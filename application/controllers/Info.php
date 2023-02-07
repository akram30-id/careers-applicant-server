<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Info extends CI_Controller
{
    public function vacancy($idVacancy)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                
                $rpc = file_get_contents('http://localhost/careers-admin-server/vacancy/id/' . $idVacancy);
                $dataVacancy = json_decode($rpc, true);

                $data['response'] = [
                    'status' => 200,
                    'message' => 'Vacancy Found',
                    'data' => [
                        'posisi' => $dataVacancy["response"]["data"]["vacancy"][0]["posisi"],
                        'id_divisi' => $dataVacancy["response"]["data"]["divisi"][0]["id_divisi"]
                    ]
                ];

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request',
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
