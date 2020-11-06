<?php defined('BASEPATH') OR exit('No direct script access allowed');
class UsersModel extends CI_Model {
    /**
     * @vars
     */
    private $_db;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        // define primary table
        $this->_db = 'users';
    }

    /**
     * Get list of non-deleted users
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'last_name', $dir = 'ASC') {
        $sql = "
        SELECT SQL_CALC_FOUND_ROWS *
        FROM {$this->_db}
        WHERE deleted = '0'
        ";
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $value = $this->db->escape('%' . $value . '%');
                $sql.= " AND {$key} LIKE {$value}";
            }
        }
        $sql.= " ORDER BY {$sort} {$dir}";
        if ($limit) {
            $sql.= " LIMIT {$offset}, {$limit}";
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $results['results'] = $query->result_array();
        } else {
            $results['results'] = NULL;
        }
        $sql = "SELECT FOUND_ROWS() AS total";
        $query = $this->db->query($sql);
        $results['total'] = $query->row()->total;
        return $results;
    }

    /**
     * Get specific user
     *
     * @param  int $id
     * @return array|boolean
     */
    function get_user($id = NULL) {
        if ($id) {
            $sql = "
            SELECT *
            FROM {$this->_db}
            WHERE id = " . $this->db->escape($id) . "
            AND deleted = '0'
            ";
            $query = $this->db->query($sql);
            if ($query->num_rows()) {
                return $query->row_array();
            }
        }
        return FALSE;
    }

    /**
     * Add a new user
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function add_user($data = array()) {
        if ($data) {
            // secure password
            $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['password'] . $salt);
            $config['upload_path'] = "./assets/images/user_image";
            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('user_image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
            }
            $file = $this->upload->data();
            $image = $file['file_name'];

            $status = $data['status']=='Active' ? 1 : 0; 
            $is_admin = $data['is_admin']=='Admin' ? 1 :($data['is_admin']=='Visitor' ? 2 : 0); 

            $sql = "
            INSERT INTO {$this->_db} (
            username,
            password,
            salt,
            first_name,
            last_name,
            email,
            image,
            language,
            is_admin,
            status,
            deleted,
            created,
            updated
            ) VALUES (
            " . $this->db->escape($data['username']) . ",
            " . $this->db->escape($password) . ",
            " . $this->db->escape($salt) . ",
            " . $this->db->escape($data['first_name']) . ",
            " . $this->db->escape($data['last_name']) . ",
            " . $this->db->escape($data['email']) . ",
            " . $this->db->escape($image) . ",
            " . $this->db->escape($this->config->item('language')) . ",
            " . $this->db->escape($is_admin) . ",
            " . $this->db->escape($status) . ",
            '0',
            '" . date('Y-m-d H:i:s') . "',
            '" . date('Y-m-d H:i:s') . "'
            )
            ";
            $this->db->query($sql);
            if ($id = $this->db->insert_id()) {
                return $id;
            }
        }
        return FALSE;
    }

    /**
     * User creates their own profile
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function create_profile($data = array()) {
        if ($data) {
            // secure password and create validation code
            $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['password'] . $salt);
            $validation_code = sha1(microtime(TRUE) . mt_rand(10000, 90000));
            $config['upload_path'] = "./assets/images/user_image";
            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('user_image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
                
                
            }
            $file = $this->upload->data();
            $profileimg = $file['file_name'];
            $sql = "
            INSERT INTO {$this->_db} (
            username,
            password,
            salt,
            first_name,
            last_name,
            email,
            image,
            language,
            is_admin,
            status,
            deleted,
            validation_code,
            created,
            updated
            ) VALUES (
            " . $this->db->escape($data['username']) . ",
            " . $this->db->escape($password) . ",
            " . $this->db->escape($salt) . ",
            " . $this->db->escape($data['first_name']) . ",
            " . $this->db->escape($data['last_name']) . ",
            " . $this->db->escape($data['email']) . ",
            " . $this->db->escape($profileimg) . ",
            " . $this->db->escape($data['language']) . ",
            '0',
            '0',
            '0',
            " . $this->db->escape($validation_code) . ",
            '" . date('Y-m-d H:i:s') . "',
            '" . date('Y-m-d H:i:s') . "'
            )
            ";
            $this->db->query($sql);
            if ($this->db->insert_id()) {
                return $validation_code;
            }
        }
        return FALSE;
    }

    /**
     * Edit an existing user
     *
     * @param  array $data
     * @return boolean
     */
    function edit_user($data = array()) {
        if ($data) {
            $sql = "
            UPDATE {$this->_db}
            SET
            username = " . $this->db->escape($data['username']) . ",
            ";
            if ($data['password'] != '') {
                // secure password
                $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $data['password'] . $salt);
                $sql.= "
                password = " . $this->db->escape($password) . ",
                salt = " . $this->db->escape($salt) . ",
                ";
            }
            $selectImage = $this->db->where('id', $data['id'])->get('users')->row('image');
            $editimg = $selectImage;
            if ($_FILES['user_image']['name']) {
                if (!empty($selectImage)) {
                    $path = "./assets/images/user_image/$selectImage";
                    unlink($path);
                }
                $config['upload_path'] = "./assets/images/user_image";
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('user_image')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error', $error['error']);
                    
                }
                $file = $this->upload->data();
                $editimg = $file['file_name'];
            }
            $status = $data['status']=='Active' ? 1 : 0; 
            $is_admin = $data['is_admin']=='Admin' ? 1 :($data['is_admin']=='Visitor' ? 2 : 0); 
            $sql.= "
            first_name = " . $this->db->escape($data['first_name']) . ",
            last_name = " . $this->db->escape($data['last_name']) . ",
            email = " . $this->db->escape($data['email']) . ",
            image = " . $this->db->escape($editimg) . ",
            language = " . $this->db->escape($data['language']) . ",
            is_admin = " . $this->db->escape($is_admin) . ",
            status = " . $this->db->escape($status) . ",
            updated = '" . date('Y-m-d H:i:s') . "'
            WHERE id = " . $this->db->escape($data['id']) . "
            AND deleted = '0'
            ";
            $this->db->query($sql);
            if ($this->db->affected_rows()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * User edits their own profile
     *
     * @param  array $data
     * @param  int $user_id
     * @return boolean
     */
    function edit_profile($data = array(), $user_id = NULL) {
        if ($data && $user_id) {
            $sql = "
            UPDATE {$this->_db}
            SET
            username = " . $this->db->escape($data['username']) . ",
            ";
            if ($data['password'] != '') {
                // secure password
                $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $data['password'] . $salt);
                $sql.= "
                password = " . $this->db->escape($password) . ",
                salt = " . $this->db->escape($salt) . ",
                ";
            }
            $status = $data['status']=='Active' ? 1 : 0; 
            $is_admin = $data['is_admin']=='Admin' ? 1 :($data['is_admin']=='Authors' ? 2 : 0); 
            $sql.= "
            first_name = " . $this->db->escape($data['first_name']) . ",
            last_name = " . $this->db->escape($data['last_name']) . ",
            email = " . $this->db->escape($data['email']) . ",
            language = " . $this->db->escape($data['language']) . ",
            updated = '" . date('Y-m-d H:i:s') . "'
            WHERE id = " . $this->db->escape($user_id) . "
            AND deleted = '0'
            ";
            $this->db->query($sql);
            if ($this->db->affected_rows()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Soft delete an existing user
     *
     * @param  int $id
     * @return boolean
     */
    function delete_user($id = NULL) {
        if ($id) {
            $sql = "
            UPDATE {$this->_db}
            SET
            is_admin = '0',
            status = '0',
            deleted = '1',
            updated = '" . date('Y-m-d H:i:s') . "'
            WHERE id = " . $this->db->escape($id) . "
            AND id > 1
            ";
            $this->db->query($sql);
            if ($this->db->affected_rows()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Check for valid login credentials
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    function login($username = NULL, $password = NULL) {
        if ($username && $password) {
            $sql = "
            SELECT
            id,
            username,
            password,
            salt,
            first_name,
            last_name,
            email,
            image,
            language,
            is_admin,
            status,
            created,
            updated
            FROM {$this->_db}
            WHERE (username = " . $this->db->escape($username) . "
            OR email = " . $this->db->escape($username) . ")
            
            AND deleted = '0'
            LIMIT 1
            ";
            // AND status = '1'
            $query = $this->db->query($sql);

            if ($query->num_rows()) 
            {
                $results = $query->row_array();
                $salted_password = hash('sha512', $password . $results['salt']);
                if ($results['password'] == $salted_password) {
                    unset($results['password']);
                    unset($results['salt']);
                    if($results['status']==1)
                    { 
                        return $results; 
                    }
                    else
                    {
                        return 'not-active';
                    }
                }
            }
        }
        return FALSE;
    }

    /**
     * Handle user login attempts
     *
     * @return boolean
     */
    function login_attempts() {
        // delete older attempts
        $older_time = date('Y-m-d H:i:s', strtotime('-' . $this->config->item('login_max_time') . ' seconds'));
        $sql = "
        DELETE FROM login_attempts
        WHERE attempt < '{$older_time}'
        ";
        $query = $this->db->query($sql);
        // insert the new attempt
        $sql = "
        INSERT INTO login_attempts (
        ip,
        attempt
        ) VALUES (
        " . $this->db->escape($_SERVER['REMOTE_ADDR']) . ",
        '" . date("Y-m-d H:i:s") . "'
        )
        ";
        $query = $this->db->query($sql);
        // get count of attempts from this IP
        $sql = "
        SELECT
        COUNT(*) AS attempts
        FROM login_attempts
        WHERE ip = " . $this->db->escape($_SERVER['REMOTE_ADDR']);
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $results = $query->row_array();
            $login_attempts = $results['attempts'];
            if ($login_attempts > $this->config->item('login_max_attempts')) {
                // too many attempts
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Validate a user-created account
     *
     * @param  string $encrypted_email
     * @param  string $validation_code
     * @return boolean
     */
    function validate_account($encrypted_email = NULL, $validation_code = NULL) {
        if ($encrypted_email && $validation_code) {
            $sql = "
            SELECT id
            FROM {$this->_db}
            WHERE SHA1(email) = " . $this->db->escape($encrypted_email) . "
            AND validation_code = " . $this->db->escape($validation_code) . "
            AND status = '0'
            AND deleted = '0'
            LIMIT 1
            ";
            $query = $this->db->query($sql);
            if ($query->num_rows()) {
                $results = $query->row_array();
                $sql = "
                UPDATE {$this->_db}
                SET status = '1',
                validation_code = NULL
                WHERE id = '" . $results['id'] . "'
                ";
                $this->db->query($sql);
                if ($this->db->affected_rows()) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Reset password
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function reset_password($data = array()) {
        if ($data) {
            $sql = "
            SELECT
            id,
            first_name
            FROM {$this->_db}
            WHERE email = " . $this->db->escape($data['email']) . "
            AND status = '1'
            AND deleted = '0'
            LIMIT 1
            ";
            $query = $this->db->query($sql);
            if ($query->num_rows()) {
                // get user info
                $user = $query->row_array();
                // create new random password
                $user_data['new_password'] = generate_random_password();
                $user_data['first_name'] = $user['first_name'];
                // create new salt and stored password
                $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $user_data['new_password'] . $salt);
                $sql = "
                UPDATE {$this->_db} SET
                password = " . $this->db->escape($password) . ",
                salt = " . $this->db->escape($salt) . "
                WHERE id = " . $this->db->escape($user['id']) . "
                ";
                $this->db->query($sql);
                if ($this->db->affected_rows()) {
                    return $user_data;
                }
            }
        }
        return FALSE;
    }

    function reset_password_by_token($email) 
    {
        return $this->db->where('email',$email)->get('users')->row();
    }

    /**
     * Check to see if a username already exists
     *
     * @param  string $username
     * @return boolean
     */
    function username_exists($username) {
        $sql = "
        SELECT id
        FROM {$this->_db}
        WHERE username = " . $this->db->escape($username) . "
        LIMIT 1
        ";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check to see if an email already exists
     *
     * @param  string $email
     * @return boolean
     */
    function email_exists($email) {
        $sql = "
        SELECT id
        FROM {$this->_db}
        WHERE email = " . $this->db->escape($email) . "
        LIMIT 1
        ";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return TRUE;
        }
        return FALSE;
    }

    function fav_product_data($user_id) {
        $this->db->select('product.id as product_id, product_title,  product_category_id, product_brand_id, product_meta_keyword,product_description, product_meta_description, product_short_detail, product_full_detail, product_lowest_marcket, product_slug, category_slug, product_varient.id as product_varient_id,  product_varient.sku as product_sku, product_image, category_slug,favourite_products.id as is_fav');
        $this->db->join('category', 'category.id = product.product_category_id', 'left');
        $this->db->join('product_varient', 'product_varient.product_id = product.id', 'left');
        $this->db->join('product_variant_market', 'product_variant_market.product_variant_id = product_varient.id', 'left');
        $this->db->join('brand', 'brand.id = product.product_brand_id', 'left');
        $this->db->join('market', 'market.id = product_variant_market.market_id', 'left');
        $this->db->join('favourite_products', 'favourite_products.products_id = product.id', 'left');
        $this->db->join('product_variant_custom_field_values', 'product_variant_custom_field_values.product_variant_id = product_varient.id', 'left');
        $this->db->where('is_primary', 1);
        $this->db->where('favourite_products.user_id', $user_id);
        $this->db->group_by('favourite_products.products_id');
        return $this->db->get('product')->result();
    }

    function remove_from_fav_product($user_id, $product_id) 
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('products_id', $product_id);
        $this->db->delete('favourite_products');
        return $this->db->affected_rows();
    }

    function check_is_valid_user($token)
    {
        return $this->db->where('token',$token)->get('users')->row();
    }

    function update_user_token_by_email($email,$data)
    {
        $this->db->set($data)->where('email', $email)->update('users');
        return $this->db->affected_rows();
    }

    function get_quiz_by_userid($user_id)
    {  
        return $this->db->select('quiz_like.quiz_id,quiz_like.user_id,quizes.*,(SELECT count(id) FROM quiz_count WHERE quiz_count.quiz_id = quizes.id )as total_view,(SELECT id FROM quiz_like WHERE quiz_like.user_id = "'.$user_id.'" LIMIT 1) as like_id,(SELECT count(id) FROM quiz_like WHERE quiz_like.quiz_id = quizes.id)as total_like')
        ->join('quizes','quizes.id = quiz_like.quiz_id')
        ->where('quiz_like.user_id',$user_id)
        ->get('quiz_like')
        ->result();
    }

    function get_question_count_by_quiz_id($quiz_id)
    {
        $result = $this->db->select('count(*) as count')->where('quiz_id',$quiz_id)->get('questions')->row();
        return $result->count ? $result->count : 0;
    }
}
