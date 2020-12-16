<?php
    class novel_model extends CI_model {
        public function getNovel($id = null) {
            if($id === null) {
                return $this->db->get('tb_novel')->result_array(); 
            } else {
                return $this->db->get_where('tb_novel', ['id' => $id])->result_array();
            }
        }
        public function deleteNovel($id) {
            $this->db->delete('tb_novel', ['id' => $id]);
            return $this->db->affected_rows();
        }
        public function createNovel($data) {
            $this->db->insert('tb_novel', $data);
            return $this->db->affected_rows();
        } 
        public function updateNovel($data, $id) {
            $this->db->update('tb_novel', $data, ['id' => $id]);
            return $this->db->affected_rows();
        }
    }
?>