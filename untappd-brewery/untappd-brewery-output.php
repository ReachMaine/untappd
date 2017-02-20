<?php
function utb_output($id, $feedtype, $limit){

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

            if($feedtype == 'breweryBeers') {
                $feed = $Tappd->breweryBeers($id);
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

    $output = '';
    $counter = 1;

    //print_r($feed);

    //echo "feed: <pre style='color:black;'>"; var_dump($feed); echo "</pre>";
    if ($id != '' && $feed != '') {
        if($feedtype == 'breweryBeers' && $id != ''){
            $output .= '<div class="untappdbrewery brewerybeers" >';
                /*  not sure we need this since on the site. $output .= '<div class="untappdbreweryheading">';
                    $output .= '<div class="untappdbrewerypic">';
                        $output .= '<a href="' . $feed->response->brewery->contact->url . '">';
                            $output .= '<img src="' . $feed->response->brewery->brewery_label . '" alt="' . $feed->response->checkins->items[0]->brewery->brewery_name . '" />';
                        $output .= '</a>';
                    $output .= '</div>';
                    $output .= '<div class="untappdbreweryname">';
                        $output .= 'Beer from ';
                        $output .= '<a href="' . $feed->response->brewery->items[0]->brewery->contact->url . '">';
                            $output .= $feed->response->brewery->brewery_name;
                        $output .= '</a>';
                    $output .= '</div>';
                $output .= '</div>'; */
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
                             $output .= '<div class="beer_abv">'.$i->beer->beer_abv.'% ABV </div>';
                             $output .= '<div class="beer_ibu">'.$i->beer->beer_ibu.' IBU </div>';
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
        }
    } else {
        $output .= "Error getting feed. id=".$id." and feedtype = ".$feedtype;
    }

    return $output;
}
