<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('AuthModel');
    }

    public function isAccountExist($email)
    {
        $getAccount = $this->AuthModel->getAccount($email)->result();

        if (count($getAccount) > 0) {
            return true;
        } else {
            return false;
        }
        
        return $getAccount;
    }

    private function jsonOutput(Int $statusCode, String $message, $data = NULL)
    {
        return $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'message' => $message,
                'data' => $data
            ]));
    }

    public function register()
    {
        try {
            $email = htmlspecialchars($this->input->post('email'));
            $password = htmlspecialchars($this->input->post('password'));

            $account = $this->isAccountExist($email);
            if ($account) {
                return $this->jsonOutput(200, 'Akun dengan email ' . $email . ' sudah ada.');
            } else {
                $register = $this->AuthModel->register($email, $password);

                if ($register) {
                    return $this->jsonOutput(201, 'Pendaftaran Akun Berhasil. Silahkan Login.');
                } else {
                    return $this->jsonOutput(500, 'Internal Server Error');
                }
            }
        } catch (\Throwable $th) {
            $this->jsonOutput(500, 'Internal Server Error', strval($th));
        }
    }

    public function login()
    {
        $email = htmlspecialchars($this->input->post('email'));
        $password = htmlspecialchars($this->input->post('password'));

        $account = $this->isAccountExist($email);

        if ($account) {

            $user = $this->AuthModel->login($email, $password)->result();
            if (count($user) > 0) {
                $token = random_int(10000000, 99999999);
                $updateToken = $this->AuthModel->updateToken($token, $email);
                if ($updateToken) {
                    $this->session->set_userdata([
                        'email' => $email,
                        'token' => $token
                    ]);

                    return $this->jsonOutput(200, 'Login Success', ['email' => $email]);
                } else {
                    return $this->jsonOutput(500, 'Internal Server Error');
                }
            } else {
                return $this->jsonOutput(401, 'Password Salah');
            }
        } else {
            return $this->jsonOutput(404, 'Akun dengan email ' . $email . ' tidak ditemukan');
        }
    }

    public function getAuthSession()
    {
        return $this->jsonOutput(200, 'Show Session AUTH', [
            'email' => $this->session->email, 'token' => $this->session->token
        ]);
    }

    public function logout()
    {
        $email = htmlspecialchars($this->input->post('email'));

        $account = $this->isAccountExist($email);
        
        if ($account) {
            $updateToken = $this->AuthModel->updateToken($email, NULL);
            if ($updateToken) {
                $this->session->sess_destroy();
                return $this->jsonOutput(200, 'Logout Success');
            } else {
                return $this->jsonOutput(500, 'Internal Server Error');
            }
        } else {
            return $this->jsonOutput(401, 'Invalid Authentication');
        }
    }
}
