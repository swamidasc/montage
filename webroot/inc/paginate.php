<?php
/*--------------------------------------------------------------------------------------------
|    @desc:         pagination 
|    @author:       Aravind Buddha
|    @url:          http://www.techumber.com
|    @date:         12 August 2012
|    @email         aravind@techumber.com
|    @license:      Free!, to Share,copy, distribute and transmit , 
|                   but i'll be glad if i my name listed in the credits'
---------------------------------------------------------------------------------------------*/
function paginate($reload, $page, $tpages) {
    $adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = "";
    // previous
    if ($page == 1) {
        $out.= "<span class='nav disabled'>" . $prevlabel . "</span>\n";
    } elseif ($page == 2) {
        $out.= "<li><a class='nav' href=\"" . $reload . "#searchtop\">" . $prevlabel . "</a>\n</li>";
    } else {
        $out.= "<li><a class='nav' href=\"" . $reload . "&amp;page=" . ($page - 1) . "#searchtop\">" . $prevlabel . "</a>\n</li>";
    }
  
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li  class=\"active\"><a href=''>" . $i . "</a>\n</li>";
        } elseif ($i == 1) {
            $out.= "<li><a  href=\"" . $reload . "#searchtop\">" . $i . "</a>\n</li>";
        } else {
            $out.= "<li><a  href=\"" . $reload . "&amp;page=" . $i . "#searchtop\">" . $i . "</a>\n</li>";
        }
    }
    
    if ($page < ($tpages - $adjacents)) {
        $out.= "<li><em>&hellip;</em><a href=\"" . $reload . "&amp;page=" . $tpages . "#searchtop\">" . $tpages . "</a>\n</li>";
    }
    // next
    if ($page < $tpages) {
        $out.= "<li><a class='nav' href=\"" . $reload . "&amp;page=" . ($page + 1) . "#searchtop\">" . $nextlabel . "</a>\n</li>";
    } else {
        $out.= "<span class='nav disabled'>" . $nextlabel . "</span>\n";
    }
    $out.= "";
    return $out;
}
