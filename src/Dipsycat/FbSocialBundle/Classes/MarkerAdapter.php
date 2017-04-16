<?php

namespace Dipsycat\FbSocialBundle\Classes;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Overlay\Icon;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Dipsycat\FbSocialBundle\Entity\Claim;

class MarkerAdapter extends Marker {

    private $claim;

    public function __construct(Claim $claim) {
        $this->claim = $claim;

        $this->setPosition(new Coordinate($claim->getLatitude(), $claim->getLongitude()));
        $this->putIcon();
        $comment = $claim->getComment();
        $content = "<div>"
            . "<h2>$comment</h2>"
            . "<a href='/claim/" . $claim->getId() . "' target='_blank'>Go to claim</a>"
            . "</div>";
        $this->setInfoWindow(new InfoWindow($content));
    }

    public function putIcon() {
        $icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';

        $status = $this->claim->getStatus();
        if ($status == 'in progress') {
            $icon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
        } else if ($status == 'closed') {
            $icon = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
        }
        parent::setIcon(new Icon($icon));
    }

}
