<?php

namespace Dipsycat\FbSocialBundle\Classes;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Dipsycat\FbSocialBundle\Entity\Claim;

class MarkerAdapter extends Marker {
    
    private $claim;
    
    public function __construct(Claim $claim) {
        $this->claim = $claim;
        
        $this->setPosition(new Coordinate($claim->getLatitude(), $claim->getLongitude()));
        $comment = $claim->getComment();
        $content = "<div>"
                . "<h1>$comment</h1>"
                . "<button id='open-claim'>Open</button>"
                . "<div class='claim-body' style='display:none;'>"
                . "<img src=''>"
                . "<select>"
                . "<option>1</option>"
                . "<option>2</option>"
                . "</select>"
                . "</div>"
                . "</div>";
        /*$content = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#exampleModal'>
  Launch demo modal
</button>";*/
        //$this->container->get('twig')->render('DipsycatFbSocialBundle:Main:index.html.twig', []);
        $this->setInfoWindow(new InfoWindow($content));
    }
    
    
    
}
