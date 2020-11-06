<div class="container">
  <div class="row mb-4">
    <div class="col-10"></div>
    <div class="col-2">
      <form action="" method="GET" id="Quiz_filter_form">
        <div class="form-controll">
          <select class="form-control" id="Quiz_filter" name="most">
            <option  value="">Select One</option>
            <option <?php echo $this->input->get('most') == 'recent' ? 'Selected': ''; ?> value="recent"><?php echo lang('most_recent'); ?></option>
            <option <?php echo $this->input->get('most') == 'liked' ? 'Selected': ''; ?> value="liked"><?php echo lang('most_liked'); ?></option>
            <option <?php echo $this->input->get('most') == 'attended' ? 'Selected': ''; ?> value="attended"><?php echo lang('most_attended'); ?></option>
          </select>
        </div>
      </form>
    </div> 
  </div>

  <?php 
    
  ?>

  <div class="row">
    <div class="col-12 text-center"> <h2 class="heading"><?php echo ucwords($category_data->category_title); ?></h2> <hr></div>
    
    <?php
      $data['quiz_list_data'] = $quiz_data;
      $this->load->view('quiz_data_list',$data);  
    ?>
  </div>
  <?php echo xss_clean($pagination) ?>
</div>
