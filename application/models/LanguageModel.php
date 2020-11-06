<?php defined('BASEPATH') OR exit('No direct script access allowed');
class LanguageModel extends CI_Model 
{

    var $table = 'language';
    var $column_order = array(null, 'lang', NULL );
    var $column_search = array('lang');
    var $order = array('id' => 'ASC');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() 
    {
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

    function count_filtered() 
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() 
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function insert_language($data) 
    {
        $this->db->insert('language', $data);
        return $this->db->insert_id();
    }

    function language_name_like_this($title) 
    {
        $this->db->like('title', $title);
        return $this->db->count_all_results('language');
    }

    function language_slug_like_this($slug,$id) 
    {
        $this->db->like('slug', $slug);
        if($id)
        {
            $this->db->where('id !=', $id);
        }
        $count = $this->db->count_all_results('language');
        return $count > 0 ? "-$count" : '';
    }
    
    function get_languages() 
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != - 1) 
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->select('language.*')
        ->get();
        return $query->result();
    }

    function get_languages_name() 
    {
        return $this->db->get('language')->result();
    }

    function get_language_by_id($language_id)
    {
        return $this->db->where('id',$language_id)->get('language')->row();
    }

    function get_language_by_name($language_name)
    {
        return $this->db->where('lang',$language_name)->get('language')->row();
    }

    function update_language($language_id, $data) 
    {
        $this->db->set($data)->where('id', $language_id)->update('language');
        return $this->db->affected_rows();
    }

    function delete_language($language_id) 
    {
        $this->db->where('id', $language_id)->delete('language');
        return $this->db->affected_rows();
    }

    function delete_language_token($language_id) 
    {
        $this->db->where('language_id', $language_id)->delete('lang_token');
        return $this->db->affected_rows();
    }

    function get_language_data($language_id)
    {
        $this->db->where('language_id',$language_id);
        return $this->db->get('lang_token')->result();
    }

    function chek_laguage_by_token_or_category($token, $category, $language_id)
    {
        if($language_id)
        {            
            $this->db->where('id !=',$language_id);
        }

        $this->db->where('token',$token);
        $this->db->where('category',$category);
        return $this->db->get('language')->result();
    }

    function all_langague()
    {
        $this->db->select('id,lang');
        $this->db->group_by('lang');
        return $this->db->get('language')->result();
    }

    function get_language_tokens($language_id)
    {
        $this->db->where('language_id',$language_id);
        return $this->db->get('lang_token')->result();
    }

    function update_language_tokens($token_content, $language_id, $lang_token_id)
    {
        $this->db->set($token_content)->where('language_id', $language_id)->where('id', $lang_token_id)->update('lang_token');
        return $this->db->affected_rows();

    }

    function insert_language_tokens($data)
    {
        return $this->db->insert_batch('lang_token', $data);
    }

    function get_languages_for_token()
    {
        return $this->db->select('id,lang')->get('language')->result();   
    }

    function get_token_and_languageid($token_value,$language_id)
    {
        return $this->db->select('id,language_id,token')
        ->where('language_id',$language_id)
        ->where('token',$token_value)
        ->get('lang_token')->row();
    }

    function update_token_data($id,$token_data)
    {
        $this->db->set($token_data)->where('id', $id)->update('lang_token');
        return $this->db->affected_rows();        
    }

    function insert_token_data($token_data)
    {
        $this->db->insert('lang_token', $token_data);
        return $this->db->insert_id();
    }
}
