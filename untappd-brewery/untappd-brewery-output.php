<?php
function utb_output($id, $feedtype, $limit){

    $id = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $id);
    $id = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $id);

    $config = utb_get_api_settings();
    $Tappd = new UTB_untappd($config);
    $feed ='';
    $transientName = $feedtype.$id;
    try {
        if ( false === ( $feed = get_transient($transientName) ) ) {
            if($feedtype == 'beerFeed') {
                $feed = $Tappd->beerFeed($id);
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
    if($feedtype == 'breweryBeers' && $id != ''){
        $output .= '<div class="untappdbreweryfeed" >';
            $output .= '<div class="untappdbreweryheading">';
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
            $output .= '</div>';
            $output .= '<div class="checkincontainer">';
            //echo "<pre>"; var_dump($feed); echo "</pre>";
            $beer_count = $feed->response->brewery->beer_count;
            //echo 'Beer List: <pre style="color:black;">'; var_dump($feed->response->brewery->beer_list); echo "</pre>";
         foreach ($feed->response->brewery->beer_list->items as $i) {
             $output .=  '<div class="beer_wrapper">';
             $output .= '<img src="' .$i->beer->beer_label. '" alt="' . $i->beer->beer_name . '" />';
              //echo '<pre style="color:black;"> '; var_dump($i->beer_label); echo "</pre>";
             $output .= '<h3 class="beer_name">'.$i->beer->beer_name.'</h3>';
             $output .= '<div class="beer_style">'.$i->beer->beer_style.'</div>';
             $output .= '<div class="beer_desc">'.$i->beer->beer_description.'</div>';
             $output .= '<div class="beer_abv">'.$i->beer->beer_abv.'% ABV </div>';
             $output .= '<div class="beer_ibu">'.$i->beer->beer_ibu.' IBU </div>';
             $output .= '<div>is_in_production: '.$i->beer->is_in_production.'</div>';
             $output .= '</div>';
             //echo '<pre style="color:black;"> '; var_dump($i); echo "</pre>";
            /* if($counter <= $limit){
                $output .= '<div class="brewerycheckin">';
                    $output .= '<div class="breweryuserpic" >';
                        $output .= '<a href="https://untappd.com/user/' . $i->user->user_name . '" >';
                           $output .= '<img src="' . htmlentities($i->user->user_avatar) . '" alt="' . $i->user->user_name . '" />';
                        $output .= '</a>';
                    $output .= '</div>';
                    $output .= '<div class="breweryusername" >';
                        $output .= '<a href="https://untappd.com/user/' . $i->user->user_name . '" >' . $i->user->user_name . '</a> is drinking <a href="https://untappd.com/beer/' . $i->beer->bid . '">' . $i->beer->beer_name . '</a>';
                    $output .= '</div>';
                $output .= '</div>';
            }
            else{
                break;
            }
            $counter++; */
        } // end for
            $output .= '</div>';
            $output .= '<div class="branding">';
                $output .= '<span>Data provided by <a href="https://untappd.com">Untappd</a></span>';
            $output .= '</div>';
        $output .= '</div>';
    }



    return $output;
}
