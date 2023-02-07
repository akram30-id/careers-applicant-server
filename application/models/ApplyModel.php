<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApplyModel extends CI_Model
{

    function insert_data($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    function insert_personal($data)
    {
        $query = $this->db->insert('tbl_personal', $data);
        if ($query == TRUE) {
            $id = $this->db->insert_id();
        } else {
            $id = NULL;
        }

        $result = [$query, $id];
        return $result;
    }

    //set nama tabel yang akan kita tampilkan datanya
    var $table1 = 'tbl_personal';
    var $table5 = 'tbl_referensi';

    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = ['nama_lengkap', 'tempat_lahir', 'tgl_lahir', 'email', 'kontak', 'referensi', 'id_personal', null];

    var $column_search = ['nama_lengkap', 'referensi'];

    // default order
    var $order = ['id_personal', 'asc'];

    private function _get_datatables_query($idVacancy)
    {
        $this->db->from($this->table1);
        $this->db->join($this->table5, 'tbl_personal.id_personal = tbl_referensi.id_personal');
        $this->db->where('tbl_personal.id_loker', $idVacancy);
        $i = 0;

        foreach ($this->column_search as $item) { // loop kolom
            if ($this->input->get('search')['value']) { // jika datatable mengirim POST untuk search
                if ($i === 0) { // looping pertama
                    $this->db->group_start();
                    $this->db->like($item, $this->input->get('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->get('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) { // looping terakhir
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // jika datatable mengirim POST untuk order
        if ($this->input->get('order')) {
            $this->db->order_by($this->column_order[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($idVacancy)
    {
        $this->_get_datatables_query($idVacancy);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($idVacancy)
    {
        $this->_get_datatables_query($idVacancy);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {
        $this->db->from($this->table1);
        return $this->db->count_all_results();
    }

    function get_details_applicant ($table, $idApplicant)
    {
        return $this->db->get_where($table, ['id_personal' => $idApplicant]);
    }

    function get_applicant ($table, $id_applicant)
    {
        return $this->db->get_where($table, ['id_personal' => $id_applicant]);
    }
    
    function delete_applicant ($id_applicant)
    {
        return $this->db->delete('tbl_personal', ['id_personal' => $id_applicant]);
    }
}
