<?php
/**
 * 目录/时间线
 *
 * @package custom
 */

if (!defined("__TYPECHO_ROOT_DIR__")) {
    exit();
}
$this->need("header.php");
$this->need("sidebar.php");
$this->need("topbar.php");
?>

<main class="site-main">
    <div class="container">
        <div class="row g-3 g-xl-4">
            <div class="col-12">
				<div class="card card-xl" id="archives">         
    <div id="archives-content">   
        <?php  
            $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);   
            $year=0; $mon=0; $i=0; $j=0;   
            $all = array();   
            $output = '';   
            while($archives->next()):   
                $year_tmp = date('Y',$archives->created);   
                $mon_tmp = date('n',$archives->created);   

                $y=$year; $m=$mon;   
                if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';   
                if ($year != $year_tmp) {   
                    $year = $year_tmp;   
                    $all[$year] = array();   
                }   

                if ($mon != $mon_tmp) {   
                    $mon = $mon_tmp;   
                    array_push($all[$year], $mon);   
                    $output .= "<div class='archive-title' id='arti-$year-$mon'><h3>$year-$mon</h3><div class='archives archives-$mon' data-date='$year-$mon'>";   
                }   
                $output .= '<div class="brick"><a href="'.$archives->permalink .'"><span class="time">'.date('m-d',$archives->created).'</span>'.$archives->title .'<em>('.$archives->fields->text.')</em></a></div>';   
            endwhile;   
            $output .= '</div></div>';   
            echo $output;   

            $html = "";   
            $year_now = date("Y");   
            foreach($all as $key => $value){   
                $html .= "<li class='year' id='year-$key'><a href='#' class='year-toogle' id='yeto-$key'>$key</a><ul class='monthall'>";   
                for($i=12; $i>0; $i--){   
                    if($key == $year_now && $i > $value[0]) continue;   
                    $html .= in_array($i, $value) ? ("<li class='month monthed' id='mont-$key-$i'>$i</li>") : ("<li class='month'>$i</li>");   
                }   
                $html .= "</ul></li>";   
            }   
        ?>  
    </div>         
    <div id="archive-nav">         
        <ul class="archive-nav"><?php echo $html;?></ul>         
    </div>         
</div>
			</div>
		</div>
	</div>
</main>

<?php $this->need('footer.php'); ?>
