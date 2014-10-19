<?php 
global $wpdb;
$table_name = $wpdb->prefix . "ilenvideolock";
//$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE option_id = 1", OBJECT );
//SELECT id,creation2,post_id, count(*) total FROM `wp_ilenvideolock` WHERE `creation2` between '2014-09-03' and '2014-10-03' group by creation2  order by id asc

//echo "SELECT  id, DATE_FORMAT(creation2,'%d-%m-%Y') creation2, post_id, COUNT( * ) total, type_social social FROM $table_name WHERE creation2 between '$date_from' and '$date_to' GROUP BY creation2, type_social order by id asc";
if( !isset($_POST["dt_from"]) || !isset($_POST["dt_to"]) || !IF_isDateFormat($_POST["dt_from"]) || !IF_isDateFormat($_POST["dt_to"]) ){
  $date_current           = date("Y-m-d",current_time('timestamp'));
  $date_timestamp_1_month = strtotime($date_current .' -7 days');
  $date_from              = date("Y-m-d",$date_timestamp_1_month);
  $date_to                = $date_current;
}else{
  $date_from = $_POST["dt_from"];
  $date_to   = $_POST["dt_to"];
}

?>
<div class="ilen-page-content wrap">

  <div class="row">

    <div class="col-md-6">

      <div class="btn-group class_type_graph" data-toggle="buttons">
        <label class="btn btn-default active">
          <i class="fa fa-search-minus"></i>
          <input type="radio" name="type_chart" id="type_chart1" value="1" checked> Total
        </label>
        <label class="btn btn-default">
        <i class="fa fa-search-plus"></i>
          <input type="radio" name="type_chart" id="type_chart2" value="2" > Detailed
        </label>
      </div>


      <div class="btn-group class_type_social" data-toggle="buttons-checkbox" style="display:none">
        <label class="btn btn-default active">
          <input type="checkbox" value="1" id="bcheck_facebook" checked /> <i class="fa fa-facebook"></i>
        </label>
        <label class="btn btn-default active">
          <input type="checkbox" value="1" id="bcheck_twitter"  checked /> <i class="fa fa-twitter"></i>
        </label>
        <label class="btn btn-default active">
          <input type="checkbox" value="1" id="bcheck_googeplus" checked /> <i class="fa fa-google-plus"></i>
        </label>
      </div>

    </div>
    <form method="post" action="<?php // echo admin_url('options-general.php?page='.$this->parameter['id_menu'])."&tabs=stats"; ?>" name="frmfiltergraph" id="frmfiltergraph">

    <div class="col-md-6 pull-right">

      <div class="area-admin-picker pull-right">
      
      
        <div class="col-md-5 pull-left"  style="padding: 0 7px;">
          <div class='input-group date' id='dt-1' style="width:145px;" data-date="<?php echo $date_from; ?>" >
            <input type='text' name="dt_from" class="form-control" value="<?php echo $date_from; ?>" data-date-format="YYYY-MM-DD" />
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>

        <div class="col-md-5 pull-left" style="padding: 0 2px;">
          <div class='input-group date' id='dt-2' style="width:145px;" data-date="<?php echo $date_to; ?>" data-date-format="YYYY-MM-DD">
            <input type='text' name="dt_to" class="form-control" value="<?php echo $date_to; ?>"  />
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>

        <!-- <a href="#" class="button btn_range_filter" >Filter</a>-->
        <input type="submit" value="Filter" class="button"  />
     
      </div>

    </div>
   </form>

  </div>

  <div id="chart_div" style="background:;height:225px;width:100%;border-top:0px solid #F1F1F1;border-bottom:0px solid #F1F1F1;margin:10px 0;margin-top: 42px;"></div>

  <!--<button type="button" id="hideSales"  >Hide facebook</button>
  <button type="button" id="hideExpenses"  >Hide twitter</button>-->

  <input type="hidden" value="" name="hidden_combination_colors" id="hidden_combination_colors" />

<?php

$array_data_date = array();
$results = $wpdb->get_results( "SELECT  id, DATE_FORMAT(creation2,'%d-%m-%Y') creation2, post_id, COUNT( * ) total, type_social social FROM $table_name WHERE creation2 between '$date_from 00:00:00' and '$date_to 23:59:59' GROUP BY creation2, type_social order by id asc" );

// get range data
/*$days_diff = IF_dateDifference( $date_from, $date_to );
$from_time = strtotime($date_current);
$to_time = strtotime($date_current) + ($days_diff*86400) ;
$array_data_date = array();*/

/*echo "dias $days_diff | tiempo desde: $from_time | tiempo hasta: $to_time | suma:".($from_time + 86400);
for( $i=$from_time; $i<=$to_time; $i + 86400 ){
  echo "$from_time <br />";
  $array_data_date[] = date("Y-m-d",$i) ;
}*/
for ($date = strtotime($date_from); $date < strtotime($date_to)+86400; $date = strtotime("+1 day", $date)) {
    $array_data_date[] =  date("d-m-Y", $date);
}

if( isset($results) && $results ){

  $date_change      = "";
  $array_data_count = array();
  $array_fb_data    = array();
  $array_tw_data    = array();
  $array_go_data    = array();

  $bit_fb = 0;
  $bit_tw = 0;
  $bit_go = 0;
  foreach ($results as $value) {


    /*if( $date_change != $value->creation2 ){
      $array_data_date[] = $value->creation2;
      $date_change = $value->creation2;
    }*/
 

    /*$array_fb_data[$value->creation2] = 0;
    $array_tw_data[$value->creation2] = 0;
    $array_go_data[$value->creation2] = 0;*/
    $bit_fb = isset($array_fb_data[$value->creation2])? (int)$array_fb_data[$value->creation2] + (int)$value->total: (int)$value->total;
    $bit_tw = isset($array_tw_data[$value->creation2])? (int)$array_tw_data[$value->creation2] + (int)$value->total: (int)$value->total;
    $bit_go = isset($array_go_data[$value->creation2])? (int)$array_go_data[$value->creation2] + (int)$value->total: (int)$value->total;
    if( isset($value->social) && $value->social == 'facebook' ){
      $array_fb_data[$value->creation2] = $bit_fb;
    }
    if( isset($value->social) && $value->social == 'twitter' ){
      $array_tw_data[$value->creation2] = $bit_tw;
    }
    if( isset($value->social) && $value->social == 'googleplus' ){
      $array_go_data[$value->creation2] = $bit_go;
    }


  }

  if( is_array($array_data_date) ){
    $array_data_all[]      = "['Date', 'Total']";
    $array_data_fb_tw_go[] ="['Date', 'Facebook','Twitter','Google plus']";
    $array_data_fb_tw[]    ="['Date', 'Facebook','Twitter']";
    $array_data_fb[]       ="['Date', 'Facebook']";
    $array_data_tw[]       ="['Date', 'Twitter']";
    $array_data_go[]       ="['Date', 'Google Plus']";
    $array_data_go_tw[]    ="['Date', 'Google Plus','Twitter']";
    $array_data_go_fb[]    ="['Date', 'Google Plus','Facebook']";
    $array_data_tw_go[]    ="['Date', 'Twitter','Google Plus']";
    foreach ($array_data_date as $datte) {

      $fb_count = 0;
      $tw_count = 0;
      $go_count = 0;
      $sum      = 0;

      $fb_count = isset($array_fb_data[$datte])?(int)$array_fb_data[$datte]:0;
      $tw_count = isset($array_tw_data[$datte])?(int)$array_tw_data[$datte]:0;
      $go_count = isset($array_go_data[$datte])?(int)$array_go_data[$datte]:0;
      //echo "$datte - {$array_tw_data[$datte]}<br />";
      $sum = $fb_count + $tw_count + $go_count;

      // get total for date
      $array_data_count[$datte] = $sum;

      $array_data_all[]      = "['$datte',$sum]";
      $array_data_fb_tw_go[] = "['$datte', $fb_count,$tw_count,$go_count]";
      $array_data_fb_tw[]    = "['$datte', $fb_count,$tw_count]";
      $array_data_fb[]       = "['$datte', $fb_count]";
      $array_data_tw[]       = "['$datte', $tw_count]";
      $array_data_go[]       = "['$datte', $go_count]";
      $array_data_go_tw[]    = "['$datte', $go_count,$tw_count]";
      $array_data_go_fb[]    = "['$datte', $go_count,$fb_count]";
      $array_data_tw_go[]    = "['$datte', $tw_count,$go_count]";


    }

  }


}

 
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);


      function getDataChart_ilen( rt ){
        var data_js_chart_all;
        var data_js_chart_fb_tw_go;
        var data_js_chart_fb_tw;
        var data_js_chart_fb;
        var data_js_chart_tw;
        var data_js_chart_go;
        var data_js_chart_go_tw;
        var data_js_chart_go_fb;
        var data_js_chart_tw_go;


        data_js_chart_all      = [ <?php if( is_array($array_data_all) ){ echo implode( ",",$array_data_all ) ; } ?> ];
        data_js_chart_fb_tw_go = [ <?php if( is_array($array_data_fb_tw_go) ){ echo implode( ",",$array_data_fb_tw_go ) ; } ?> ];
        data_js_chart_fb_tw    = [ <?php if( is_array($array_data_fb_tw) ){ echo implode( ",",$array_data_fb_tw ) ; } ?> ];
        data_js_chart_fb       = [ <?php if( is_array($array_data_fb) ){ echo implode( ",",$array_data_fb ) ; } ?> ];
        data_js_chart_tw       = [ <?php if( is_array($array_data_tw) ){ echo implode( ",",$array_data_tw ) ; } ?> ];
        data_js_chart_go       = [ <?php if( is_array($array_data_go) ){ echo implode( ",",$array_data_go ) ; } ?> ];
        data_js_chart_go_tw    = [ <?php if( is_array($array_data_go_tw) ){ echo implode( ",",$array_data_go_tw ) ; } ?> ];
        data_js_chart_go_fb    = [ <?php if( is_array($array_data_go_fb) ){ echo implode( ",",$array_data_go_fb ) ; } ?> ];
        data_js_chart_tw_go    = [ <?php if( is_array($array_data_tw_go) ){ echo implode( ",",$array_data_tw_go ) ; } ?> ];

       if( rt == 'all' ){
        return data_js_chart_all;
       }else if( rt != 'all' ){
 

          //alert( jQuery("#bcheck_facebook").prop('checked')  );
          //alert( jQuery("#bcheck_twitter").prop('checked')  );
          //alert( jQuery("#bcheck_googeplus").prop('checked')  );
          if( jQuery("#bcheck_facebook").prop('checked') && jQuery("#bcheck_twitter").prop('checked') && jQuery("#bcheck_googeplus").prop('checked') ){
            jQuery("#hidden_combination_colors").val(1);
            return data_js_chart_fb_tw_go;    
          }else if( jQuery("#bcheck_facebook").prop('checked')  && jQuery("#bcheck_twitter").prop('checked')  ){
            jQuery("#hidden_combination_colors").val(2);
            return data_js_chart_fb_tw;
          }else if( jQuery("#bcheck_googeplus").prop('checked')  && jQuery("#bcheck_twitter").prop('checked')   ){
            jQuery("#hidden_combination_colors").val(3);
            return data_js_chart_go_tw;
          }else if( jQuery("#bcheck_googeplus").prop('checked')  && jQuery("#bcheck_facebook").prop('checked')   ){
            jQuery("#hidden_combination_colors").val(4);
            return data_js_chart_go_fb;
          }else if( jQuery("#bcheck_googeplus").prop('checked')  && jQuery("#bcheck_twitter").prop('checked')   ){
            jQuery("#hidden_combination_colors").val(5);
            return data_js_chart_tw_go;
          }else if( jQuery("#bcheck_twitter").prop('checked')   ){
            jQuery("#hidden_combination_colors").val(6);
            return data_js_chart_tw;
          }else if( jQuery("#bcheck_googeplus").prop('checked')   ){
            jQuery("#hidden_combination_colors").val(7);
            return data_js_chart_go;
          }else if( jQuery("#bcheck_facebook").prop('checked')  ){
            jQuery("#hidden_combination_colors").val(8);
            return data_js_chart_fb;
          }else{
            return ['',''];
          }

       }
        

      }

      function colorslines( cc ){

        if( !cc || cc == 1 ){
          return ['#7089BE', '#3AB9E9', '#E26F61'];
        }

        if( cc == 2 ){
          return ['#7089BE', '#3AB9E9'];
        }

        if( cc == 3 ){
          return ['#E26F61','#3AB9E9'];
        }

        if( cc == 4 ){
          return ['#E26F61','#7089BE'];
        }

        if( cc == 5 ){
          return ['#E26F61','#3AB9E9'];
        }

        if( cc == 6 ){
          return ['#3AB9E9'];
        }

        if( cc == 7 ){
          return ['#E26F61'];
        }

        if( cc == 8 ){
          return ['#7089BE'];
        }




        if( cc == 'all' ){
          return ['#5588C6'];
        }

      }

      function chart_options_ilen( dd ){
        var options = {
          title: '', /*HEADING OF GRAPH*/
          legendTextStyle: {color:'#5c5c5c'}, /*FOR RIGHT TEXT*/
          colors: colorslines(jQuery("#hidden_combination_colors").val()), /*FOR GRAPH COLORS*/
          titleTextStyle: {color: '#5c5c5c', fontName:'arial', fontSize:'9'}, /*FOR TITLE COLOR , FONT AND FONT SIZE*/
          titlePosition: 'out', /*YOU CAN USE "IN" , "OUT" , "RIGHT" , "BOTTOM" */
          backgroundColor: 'none', /*FOR BACKGROUND COLOR*/
          lineWidth: '3', /*WIDTH OF LINE*/
          pointSize: '5', /*SIZE OF POINT*/
          pointSize: 7,
          lineWidth: 3,
          height:'225',
          chartArea:{left:40,top:25,width:"100%",height:"200"},
          legend : 'none',
          tooltip: {textStyle: {color: '#5c5c5c', fontSize:'10'}, showColorCode: true}, /*TOOL TIP OPTIONS*/
          /*is3D: true*/ /*FOR 3D Effect*/
          vAxis: {
            title: '',
            titleTextStyle: {
                color: 'red'
            },
            fontSize: '7px',
            baseline: { baselineColor:'#919191' },
            gridlines: { color:'#F0F0F0' },
          },
          hAxis: {
            textPosition: 'none'
          },

          animation:{
            easing: 'inAndOut',
          },
        };

        return options;
      }

      function chart_options_all_ilen( dd ){
        var options = {
          title: '', /*HEADING OF GRAPH*/
          legendTextStyle: {color:'#5c5c5c'}, /*FOR RIGHT TEXT*/
          colors: ['#5588C6'], /*FOR GRAPH COLORS*/
          titleTextStyle: {color: '#5c5c5c', fontName:'arial', fontSize:'9'}, /*FOR TITLE COLOR , FONT AND FONT SIZE*/
          titlePosition: 'out', /*YOU CAN USE "IN" , "OUT" , "RIGHT" , "BOTTOM" */
          backgroundColor: 'none', /*FOR BACKGROUND COLOR*/
          lineWidth: '3', /*WIDTH OF LINE*/
          pointSize: '5', /*SIZE OF POINT*/
          pointSize: 7,
          lineWidth: 3,
          height:'225',
          chartArea:{left:40,top:25,width:"100%",height:"200"},
          legend : 'none',
          tooltip: {textStyle: {color: '#5c5c5c', fontSize:'10'}, showColorCode: true}, /*TOOL TIP OPTIONS*/
          /*is3D: true*/ /*FOR 3D Effect*/
          vAxis: {
            title: '',
            titleTextStyle: {
                color: 'red'
            },
            fontSize: '7px',
            gridlines: { color:'#F0F0F0' },
          },
          hAxis: {
            textPosition: 'none'
          },

          animation:{
            easing: 'inAndOut',
          },
        };

        return options;
      }

      function drawChart() {
        var data_all = google.visualization.arrayToDataTable(getDataChart_ilen('all'));

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data_all, chart_options_all_ilen(0));

        /**
        * @see http://stackoverflow.com/questions/17444586/show-hide-lines-data-in-google-chart
        */
        /*var hideSal = document.getElementById("hideSales");
        hideSal.onclick = function()
        {

          view = new google.visualization.DataView( google.visualization.arrayToDataTable( getDataChart_ilen(1) ) );
          //view.hideColumns([1]); 
          chart.draw(view, chart_options_ilen(0));
        }

        var hideExp = document.getElementById("hideExpenses");
        hideExp.onclick = function()
        {
          view = new google.visualization.DataView( google.visualization.arrayToDataTable( getDataChart_ilen(2) ) );
          //view.hideColumns([2]); 
          chart.draw(view, chart_options_ilen(0));
        } */

      }


      var data_chart;
      var chart;
      jQuery('.class_type_graph input[type=radio]').on("change", function() {

         if( this.value == 1 ){
            data_chart = google.visualization.arrayToDataTable(getDataChart_ilen('all'));
            chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data_chart, chart_options_all_ilen(0));
            jQuery(".class_type_social > label").addClass("active");
            jQuery(".class_type_social > label > input").prop('checked', true);
            jQuery(".class_type_social").css("display","none");
         }else if( this.value == 2 ){
            data_chart = google.visualization.arrayToDataTable(getDataChart_ilen(0));
            chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data_chart, chart_options_ilen(0));
            jQuery(".class_type_social").css("display","inline-block");
         }

      });


      jQuery(".class_type_social > label").on("click", function(){

        if( jQuery(this).hasClass('active') ){
          jQuery(this).children().prop('checked', false);
          jQuery(this).children().removeAttr('checked');
        }else{
          jQuery(this).children().prop('checked', true);
          jQuery(this).children().attr('checked','');
        }

      });

      jQuery('.class_type_social > label').on('click', function () {
            data_chart = google.visualization.arrayToDataTable(getDataChart_ilen(0));
            chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data_chart, chart_options_ilen(0));
      });


    jQuery(document).ready(function(){
      jQuery('#dt-1,#dt-2').datetimepicker({ 
        pickTime: false,
        todayHighlight: true,
        format:'YYYY-MM-DD' 
      });
    });

    </script>



<?php

  require_once "stats-list.php";

?>