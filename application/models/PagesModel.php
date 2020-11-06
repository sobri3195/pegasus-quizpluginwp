<?php defined('BASEPATH') OR exit('No direct script access allowed');
class PagesModel extends CI_Model 
{
    var $table = 'pages';
    var $column_order = array(null, 'title', 'slug', NULL );
    var $column_search = array('title', 'slug');
    var $order = array('id' => 'DESC');

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

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function insert_pages($data) 
    {
        $this->db->insert('pages', $data);
        return $this->db->insert_id();
    }

    function pages_name_like_this($title) 
    {
        $this->db->like('title', $title);
        return $this->db->count_all_results('pages');
    }

    function page_slug_like_this($slug,$id) 
    {
        $this->db->like('slug', $slug);
        if($id)
        {
            $this->db->where('id !=', $id);
        }
        $count = $this->db->count_all_results('pages');
        return $count > 0 ? "-$count" : '';
    }
    
    function get_pages() {
        $this->_get_datatables_query();
        if ($_POST['length'] != - 1) 
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->select('pages.*')
        ->get();
        return $query->result();
    }

    function get_pages_by_id($page_id)
    {
        return $this->db->where('id',$page_id)->get('pages')->row();
    }

    function get_pages_by_slug($page_slug)
    {
        return $this->db->where('slug',$page_slug)->get('pages')->row();
    }

    function update_pages($pages_id, $data) 
    {
        $this->db->set($data)->where('id', $pages_id)->update('pages');
        return $this->db->affected_rows();
    }

    function delete_page($page_id) 
    {
        $this->db->where('id', $page_id)->delete('pages');
        return $this->db->affected_rows();
    }

}
