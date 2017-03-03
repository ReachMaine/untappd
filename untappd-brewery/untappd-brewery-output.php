<?php
function utb_output($id, $feedtype, $limit, $in_display){
    $output = '';
//$output .= "<p>in output with id: ".$id." feedtype: ".$feedtype.' and display:'.$in_display.'</p>';
    if ( is_string($in_display) && ($in_display !="") ){
        $display = array_map('trim',explode(",", $in_display));
    } else {
        $display = array();
    }

//echo "display: <pre style='color:black;'>"; var_dump($display); echo "</pre>";
    $id = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $id);
    $id = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $id);

    $config = utb_get_api_settings();
    //echo "config: <pre style='color:black;'>"; var_dump($config); echo "</pre>";
    $Tappd = new UTB_untappd($config);
    //echo "tappd: <pre style='color:black;'>"; var_dump($Tappd); echo "</pre>";
    $feed ='';
    $transientName = $feedtype.$id;
    try {
        if ( false === ( $feed = get_transient($transientName) ) ) {
            switch ($feedtype) {
                case 'breweryBeers':
                case 'beersFeed':
                    $feed = $Tappd->breweryBeers($id);
                    break;
                case 'beerFeed':
                    $feed = $Tappd->beerFeed($id);
                    break;
            }

            if($feedtype == 'venueFeed'){
                $feed = $Tappd->venueFeed($id);
            }
            if($feedtype == 'breweryFeed'){
                $feed = $Tappd->breweryFeed($id);
            }
            if($feedtype == 'userFeed'){
                $feed = $Tappd->userFeed($id);
            }
        	set_transient( $transientName, $feed, 60*60*0.25);
        }
    } catch (Exception $e) {
        die();
    }

    if ($limit == ''){
        $limit = 10;
    }


    $counter = 1;

    //print_r($feed);

    //echo "feed: <pre style='color:red;'>"; var_dump($feed); echo "</pre>";
    if (($id != '') && ($feed != '')) {
        switch ($feedtype) {
            case "breweryBeers" :
                $output .= '<div class="untappdbrewery brewerybeers" >';
                $output .= '<div class="brewerybeers_inner">';

                    $beer_count = $feed->response->brewery->beer_count;
                    //echo 'Beer List: <pre style="color:black;">'; var_dump($feed->response->brewery->beer_list); echo "</pre>";
                    foreach ($feed->response->brewery->beer_list->items as $i) {

                     //echo '<pre style="color:black;"> '; var_dump($i); echo "</pre>";
                    // if($counter <= $limit){
                         $output .=  '<div class="beer_wrapper">';
                            $output .= '<div class="beer_img_wrapper">';
                                $output .= '<img src="' .$i->beer->beer_label. '" alt="' . $i->beer->beer_name . '" />';
                            $output .= "</div>";
                              //echo '<pre style="color:black;"> '; var_dump($i->beer_label); echo "</pre>";
                             $output .= '<h3 class="beer_name">'.$i->beer->beer_name.'</h3>';
                             //$output .= '<div class="beer_style">'.$i->beer->beer_style.'</div>';
                             $output .= '<div class="beer_desc">'.$i->beer->beer_description.'</div>';
                             $output .= '<div class="beer_abv">'.$i->beer->beer_abv.__('% ABV', 'untappedBrewery').'</div>';
                             $output .= '<div class="beer_ibu">'.$i->beer->beer_ibu.__(' IBU ', 'untappedBrewery').'</div>';
                            // $output .= '<div>is_in_production: '.$i->beer->is_in_production.'</div>';
                         $output .= '</div>';
                    /*}
                    else{
                        break;
                    } */
                        $counter++;
                    } // end for
                $output .= '</div>'; //brewerybeers_inner
                $output .= '<div class="branding">';
                    $output .= '<span>Data provided by <a href="https://untappd.com">Untappd</a></span>';
                $output .= '</div>';
                $output .= '</div>'; //untappdbrewerybeers
                break;
            case 'beersFeed':
//echo 'display array: <pre style="color:black;">'; var_dump($in_display); echo "</pre>";
                    $output .= '<div class="untappdbrewery beersfeed" >';
                        $output .= '<div class="brewerybeers_inner">';
                        foreach ($feed->response->brewery->beer_list->items as $beer) {
//echo '<pre style="color:black;"> '; var_dump($beer); echo "</pre>";
                            $link = get_bloginfo('url').'/'.$beer->beer->beer_slug;
                            $link = get_bloginfo('url').'/'.sanitize_title($beer->beer->beer_name);
                            $output .=  '<div class="beer_wrapper">';
                            if ( in_array('label', $display) ){
                                $output .= '<div class="beer_img_wrapper">';
                                    if (in_array('link', $display)) { $output .= '<a href="'.$link.'" >';}
                                    $output .= '<img src="' .$beer->beer->beer_label. '" alt="'.$beer->beer->beer_name.'" />';
                                    if (in_array('link', $display)) { $output .= '</a>';}
                                $output .= "</div>";

                            }
                            if ( in_array('title', $display) ) {
                                if (in_array('link', $display)) { $output .= '<a href="'.$link.'" >';}
                                $output .=  '<h3 class="beer_name">'.$beer->beer->beer_name.'</h3>';
                                if (in_array('link', $display)) { $output .= '</a>';}
                            }
                            $output .= '</div>';// beer_wrapper
                            $counter++;
                        } // end for
                        $output .= '</div>';
                    $output .= '</div>'; //untappdbrewerybeers
                break;
            case 'beerFeed':
                $beer = $feed->response->beer;
                $output .= '<div class="untappdbrewery beerfeed" >';
                $output .=      '<div class="beer_inner">';
                $output .=          '<div class="beer_style">'.$beer->beer_style.'</div>';
                $output .=          '<div class="beer_abv">'.$beer->beer_abv.__('% ABV', 'untappedBrewery').'</div>';
                $output .=          '<div class="beer_ibu">'.$beer->beer_ibu.__(' IBU ', 'untappedBrewery').'</div>';
                $output .=          '<div class="beer_desc">'.$beer->beer_description.'</div>';
                $output .=       '</div>'; //brewerybeers_inner
                $output .= '</div>'; //untappdbrewerybeers
                break;

            default:
                $output .= "Error getting feed. id=".$id." and feedtype = ".$feedtype;
            }
        }

    return $output;
}
