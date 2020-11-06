<?php defined('BASEPATH') OR exit('No direct script access allowed');
class QuizQuestionModel extends CI_Model 
{
    var $table = 'questions';
    var $column_order = array(null, 'quiz_title', 'title', NULL);
    var $column_search = array('title', 'quiz_title');
    var $order = array('questions.id' => 'DESC');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            // if datatable send POST for search
            if ($_POST['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                // last loop
                if (count($this->column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        // here order processing
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order) ]);
        }
    }

    function get_quiz_question($quiz_id) 
    {
        $this->_get_datatables_query();
        $this->db->where('questions.quiz_id', $quiz_id);

        if ($_POST['length'] != - 1) 
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->select('questions.*')
        ->order_by('questions.id', 'asc')
        ->get();
        return $query->result();
    }

    function count_filtered($quiz_id) 
    {
        $this->_get_datatables_query();
        $this->db->where('questions.quiz_id', $quiz_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function count_all($quiz_id) 
    {
        $this->db->from($this->table);
        $this->db->where('quiz_id',$quiz_id);
        return $this->db->count_all_results();
    }


    function delete_questions($quiz_id) 
    {
        $this->db->where('quiz_id', $quiz_id)->delete('questions');
        return $this->db->affected_rows();
    }

}
