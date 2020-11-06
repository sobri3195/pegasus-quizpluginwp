<?php


class resize_image
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('image_lib');
    }

    public function resize_to_thumb($image_full_path, $image_resize_to) 
    {
       $configer = array(
                            'image_library'   => 'gd2',
                            'source_image'    => $image_full_path,
                            'create_thumb'    => FALSE,
                            'maintain_ratio'  => FALSE,
                            'width'           => 231,
                            'height'          => 130,
                            'new_image'       => $image_resize_to,
                        );
        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($configer);
        if($this->CI->image_lib->resize())
        {
             return TRUE;
        }
        else
        {
            
            return FALSE;
        }

    }



    public function resize_to_small($image_full_path, $image_resize_to) 
    {
       $configer = array(
                            'image_library'   => 'gd2',
                            'source_image'    => $image_full_path,
                            'create_thumb'    => FALSE,
                            'maintain_ratio'  => TRUE,
                            'width'           => 670,
                            'height'          => 400,
                            'new_image'       => $image_resize_to,
                        );
        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($configer);
        if($this->CI->image_lib->resize())
        {
             return TRUE;
        }
        else
        {
            
            return FALSE;
        }

    }



    public function resize_to_medium($image_full_path, $image_resize_to) 
    {
       $configer = array(
                            'image_library'   => 'gd2',
                            'source_image'    => $image_full_path,
                            'create_thumb'    => FALSE,
                            'maintain_ratio'  => TRUE,
                            'width'           => 750,
                            'height'          => 466,
                            'new_image'       => $image_resize_to,
                        );
        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($configer);
        if($this->CI->image_lib->resize())
        {
             return TRUE;
        }
        else
        {
            
            return FALSE;
        }

    }


    

}
