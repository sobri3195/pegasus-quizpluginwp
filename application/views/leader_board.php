<div class="container">
  <div class="row">
    <div class="col-12 text-center"> <h2 class="heading"><?php echo ucwords($quiz_data->title); ?></h2> <hr></div>

    <?php 
      if($leader_board_quiz_history)
      {
    ?>
      <div class="col-12 my-5">
        <div class="table100 ver1  m-b-110">
          <div class="table100-head ">
            <table>
              <thead>
                <tr class="row100 head">
                  <th class="cell100 column1"><?php echo lang('name') ?></th>
                  <th class="cell100 column2"><?php echo lang('attended') ?></th>
                  <th class="cell100 column3"><?php echo lang('correct') ?></th>
                  <th class="cell100 column4"><?php echo lang('date') ?></th>
                  <th class="cell100 column5"><?php echo lang('score') ?></th>
                  <th class="cell100 column5"><?php echo lang('rank') ?></th>
                </tr>
              </thead>
            </table>
          </div>

          <div class="table100-body js-pscroll ps ps--active-y">
            <table>
              <tbody
                <?php
                  $i = 0;
                  $rank_array_data = array();
                  foreach ($leader_board_quiz_history as $ind => $quiz_array) 
                  {
                    $first_name  = $quiz_array->first_name ? : $quiz_array->guest_name;
                    $full_name_of_user = $first_name. ' '.$quiz_array->last_name;
                    $name_of_user = (strlen($full_name_of_user) > 30) ? substr($full_name_of_user, 0, 30).'...' : $full_name_of_user ;
                    $started = date( "d M Y , h:i A", strtotime($quiz_array->started));
                    $date_of_exam = date( "d M Y ", strtotime($quiz_array->started));

                    $duration_min = $quiz_data->duration_min;
                    $completed_time = $quiz_array->completed;
                    $score = 0;
                    if($quiz_array->correct > 0)
                    {
                      $score = ($quiz_array->correct/$quiz_array->questions)*100;
                      $score = round($score, 2);
                    }

                    if($completed_time)
                    {          
                      $completed = date("d M Y , h:i A", strtotime($completed_time));
                    }
                    else
                    {
                      $complete_count = strtotime("+$duration_min minutes", strtotime($started));
                      $completed = date("d M Y , h:i A", $complete_count);
                    }


                    $rank_array['name_of_user'] = $name_of_user;
                    $rank_array['total_attemp'] = $quiz_array->total_attemp ? $quiz_array->total_attemp : 0;
                    $rank_array['correct'] = $quiz_array->correct ;
                    $rank_array['date_of_exam'] = $date_of_exam;
                    $rank_array['score'] = $score;

                    $rank_array_data[$ind] = $rank_array;
                  }

                  foreach($rank_array_data as $k=>$v) {
                    $sort['score'][$k] = $v['score'];
                  }

                  array_multisort($sort['score'], SORT_DESC, $rank_array_data);          
                  $last_score = 0;

                  foreach ($rank_array_data as $key => $value_array) 
                  {
                    if($value_array['score'] != $last_score)
                    {
                      $i++;
                    }
                ?>
                  <tr class="row100 body">
                    <td class="cell100 column1"><?php echo xss_clean($value_array['name_of_user']); ?></td>
                    <td class="cell100 column2"><?php echo xss_clean($value_array['total_attemp']); ?></td>
                    <td class="cell100 column3"><?php echo xss_clean($value_array['correct']); ?></td>
                    <td class="cell100 column4"><?php echo xss_clean($value_array['date_of_exam']); ?></td>
                    <td class="cell100 column5"><?php echo xss_clean($value_array['score']); ?> %</td>
                    <td class="cell100 column5"><strong><?php echo xss_clean($i); ?></strong></td>
                  </tr>

                <?php 
                    $last_score = $value_array['score'];     
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php
        }
        else
        {
      ?>
        <div class="col-12 text-center text-danger"> <?php echo lang('no_quiz_given'); ?> </div>
      <?php
        }
      ?>
  </div>
</div>
