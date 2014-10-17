<?php 


function getSOcialCount( $post__id, $red__social ){
  global $wpdb;

  //$table_name = $wpdb->prefix . "ilenvideolock";
  //$sql = "SELECT count(*) tt from  $table_name where type_social = '$red__social' and post_id = $post__id ";
  return $wpdb->get_row("SELECT count(*) tt from  {$wpdb->prefix}ilenvideolock where type_social = '$red__social' and post_id = $post__id ")->tt;
}


global $wpdb;

$table_name = $wpdb->prefix . "ilenvideolock";

// GET TOP POSTS SHARES
$sql = "SELECT a.post_id, b.post_title, creation2, COUNT( a.type_social ) total
        FROM  wp_ilenvideolock a
        INNER JOIN wp_posts b ON a.post_id = b.ID
        GROUP BY  post_id
        ORDER BY COUNT( * ) DESC 
        LIMIT 0 , 20";
$results = $wpdb->get_results( $sql );

// GET LAST POSTS SHARES
$sql_2 = "SELECT a.post_id, 
                 b.post_title, 
                 a.creation2,
                 a.ip,
                 a.country,
                 a.creation,
                 /*a.creation date_with_hours,*/
                 a.type_social,
                 a.id_video,
                 a.country_code,
                 a.type_video
        FROM  wp_ilenvideolock a
        INNER JOIN wp_posts b ON a.post_id = b.ID
        ORDER BY a.id DESC 
        LIMIT 0 , 50";
$results_2 = $wpdb->get_results( $sql_2 );
//var_dump( $results_2 );


$oddrow = 'aternate';
?>
<br />
<hr />
<h3>Other Stats</h3>
<div class="btn-group class_type_stats_tab" data-toggle="buttons">
  <label class="btn btn-default active">
    <i class="fa fa-star"></i>  
    <input type="radio" name="type_list" id="type_top" value="1" checked> Top
  </label>
  <label class="btn btn-default">
  
    <i class="fa fa-list-ul"></i>
    <input type="radio" name="type_list" id="type_last" value="2" > Last
  </label>
</div>

<div class="tab_other_stats_top">
  <table class="wp-list-table widefat fixed posts ilenvideolock_stats_table">
  <thead>
    <tr >
      <th width="300" class="string">Post Title</th>
      <th>Facebook share</th>
      <th>Tweets</th>
      <th>Google share</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $value):?>
    <tr class="<?php if( $oddrow == 'alternate' ){ echo 'alternate';$oddrow=''; }else{ $oddrow = 'alternate'; } ?>">
      <td class="string"><a href="<?php echo get_permalink( $value->post_id ); ?>" target="_blank"><?php echo $value->post_title ?></a></td>
      <td><?php echo getSOcialCount($value->post_id,'facebook'); ?></td>
      <td><?php echo getSOcialCount($value->post_id,'twitter'); ?></td>
      <td><?php echo getSOcialCount($value->post_id,'googleplus'); ?></td>
      <td><?php echo $value->total ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
</div>

<div class="tab_other_stats_last" style="display:none">
  <table class="wp-list-table widefat fixed posts ilenvideolock_stats_table">
  <thead>
    <tr >
      <th width="125">IP</th>
      <th>Country</th>
      <th width="300" class="string">Post Title</th>
      <th>Video</th>
      <th>Social</th>
      <th>Date/Time</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results_2 as $value):?>
    <tr class="<?php if( $oddrow == 'alternate' ){ echo 'alternate';$oddrow=''; }else{ $oddrow = 'alternate'; } ?>">
      <td ><?php echo $value->ip ?></td>
      <td><span class="flag flag-<?php echo strtolower($value->country_code); ?> flag-style"></span><?php echo $value->country ?></td>
      <td class="string"><a href="<?php echo get_permalink( $value->post_id ); ?>" target="_blank"><?php echo $value->post_title ?></a></td>
      <td><a href="<?php if( isset($value->type_video) &&  $value->type_video == 'dailymotion' ){ echo "http://www.dailymotion.com/video/$value->id_video"; }elseif(isset($value->type_video) &&  $value->type_video == 'youtube'){ echo "https://www.youtube.com/watch?v=$value->id_video"; }elseif( isset($value->type_video) &&  $value->type_video == 'other' ){ echo $value->id_video; } ?>"  target="_blank"><?php echo $value->id_video ?></a></td>
      <td><?php echo ucfirst($value->type_social); ?></td>
      <td><?php echo date('d-m-Y H:i:s',$value->creation); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
</div>



<script>
jQuery('.class_type_stats_tab input[type=radio]').on("change", function() {

 if( this.value == 1 ){
    jQuery(".tab_other_stats_top").css("display","block");
    jQuery(".tab_other_stats_last").css("display","none");
 }else if( this.value == 2 ){
    jQuery(".tab_other_stats_top").css("display","none");
    jQuery(".tab_other_stats_last").css("display","block");
 }

});
</script>

</div>