<div class="container">
  <div class="row">
    <div class="col-12 text-center py-3"><h1><?php echo lang('quiz_history'); ?></h1></div>
    <?php 

      $gradients = array();
      $gradients[] = 'mask gradient-vue';
      $gradients[] = 'mask gradient-angular';
      $gradients[] = 'mask gradient-react';
      $gradients[] = 'mask gradient-material';
      $gradients[] = 'mask gradient-html';
      $gradients[] = 'mask gradient-laravel';
      $gradients[] = 'mask gradient-react-native';
      $gradients[] = 'mask gradient-nuxtjs';

      if($my_quiz_history)
      {
    ?>
        <div class="col-12 my-5">
          <div class="table100 ver1  m-b-110">
            <div class="table100-head ">
              <table>
                <thead>
                  <tr class="row100 head">
                    <th class="cell100 his-column1"><?php echo lang('title'); ?></th>
                    <th class="cell100 his-column2"><?php echo lang('questions'); ?></th>
                    <th class="cell100 his-column3"><?php echo lang('attended'); ?></th>
                    <th class="cell100 his-column4"><?php echo lang('correct'); ?></th>
                    <th class="cell100 his-column5"><?php echo lang('wrong'); ?></th>
                    <th class="cell100 his-column6"><?php echo lang('quiz_date'); ?></th>
                    <th class="cell100 his-column7"><i class="fas fa-info-circle"></i></th>
                  </tr>
                </thead>
              </table>
            </div>
          
            <div class="table100-body js-pscroll ps ps--active-y">
              <table>
                <tbody>
                  <?php
                    foreach ($my_quiz_history as  $quiz_array) 
                    {
                      $quiz_id = $quiz_array->quiz_id; ;
                      $started = date( "d M Y , h:i A", strtotime($quiz_array->started));
                      $date_of_quiz = date( "d M Y", strtotime($quiz_array->started));
                      $duration_min = $quiz_array->duration_min;
                      $completed_time = $quiz_array->completed;
                      if($completed_time)
                      {          
                        $completed = date("d M Y , h:i A", strtotime($completed_time));
                      }
                      else
                      {
                        $complete_count = strtotime("+$duration_min minutes", strtotime($started));
                        $completed = date("d M Y , h:i A", $complete_count);
                      }
                      $total_attemp = $quiz_array->total_attemp - $quiz_array->correct;
                      $total_attemp = $total_attemp ? $total_attemp : 0;
                  ?>
                    <tr class="row100 body">
                      <td class="cell100 his-column1"><?php echo xss_clean($quiz_array->quiz_title); ?></td>
                      <td class="cell100 his-column2"><?php echo xss_clean($quiz_array->questions); ?></td>
                      <td class="cell100 his-column3"><?php echo xss_clean($total_attemp); ?></td>
                      <td class="cell100 his-column4"><?php echo xss_clean($quiz_array->correct); ?></td>
                      <td class="cell100 his-column5"><?php echo xss_clean($total_attemp) - xss_clean($quiz_array->correct); ?></td>
                      <td class="cell100 his-column6"><?php echo xss_clean($date_of_quiz); ?></td>
                      <td class="cell100 his-column7"><a href="<?php echo base_url("my/test/summary/$quiz_array->id") ?>"><i class="fas fa-eye"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-12 text-right history_page"><?php echo xss_clean($pagination);  ?></div>
        <?php
          }
          else
          {
        ?>
          <div class="col-12 text-center text-danger"><?php echo lang('no_quiz_given'); ?></div>
        <?php
          }
        ?>
  </div>
</div>
