<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Control\FullscreenControl;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Control\ControlPosition;
use Ivory\GoogleMap\Event\Event;
use Dipsycat\FbSocialBundle\Classes\MarkerAdapter;

class MapController extends Controller {

    public function indexAction() {
        $map = $this->mapInit();
        return $this->render('DipsycatFbSocialBundle:Map:index.html.twig', [
                    'map' => $map
        ]);
    }

    private function mapInit() {
        $map = new Map();
        $map->setCenter(new Coordinate(59.955674, 30.2816493));
        $map->setMapOption('zoom', 10);
        $map->setStylesheetOption('width', '95%');
        $map->setStylesheetOption('height', '800px');
        $map->setStylesheetOption('position', 'absolute');
        $map->setHtmlId('map_canvas');

        $claims = $this->getClaims();
        foreach ($claims as $claim) {
            $marker = new MarkerAdapter($claim);
            $map->getOverlayManager()->addMarker($marker);
            //$marker = $marker->getInfoWindow()->getVariable();
           
            $eventDOM = new Event(
                    "document.getElementById('open-claim')", 'click', "
function(){
console.log(chmok);
}
"
            );
            $domEventString = $map->getVariable().'_container.events.dom_events.event'.$eventDOM->getVariable().' = event'.$eventDOM->getVariable().' = google.maps.event.addDomListener('.$eventDOM->getInstance().', "click", ' . $eventDOM->getHandle() . ');';

            $event = new Event(
                    $marker->getVariable(), 'click', 'function(){
'.'console.log("open");
' . $domEventString . '
}', true
            );
            $map->getEventManager()->addEvent($event);
        }

        $fullscreenControl = new FullscreenControl(ControlPosition::TOP_LEFT);
        $map->getControlManager()->setFullscreenControl($fullscreenControl);
        return $map;
    }

    private function getClaims() {
        $em = $this->getDoctrine()->getManager();
        $claimRepository = $em->getRepository('DipsycatFbSocialBundle:Claim');
        return $claimRepository->findAll();
    }
    
    public function addClaimAction(\Symfony\Component\HttpFoundation\Request $request) {
        $data = $request->get('data');
        $data = json_decode($data);
        $Claim = new Claim();
        $em = $this->getDoctrine()->getManager();
        $em->persist($Claim);
        $em->flush();
    }

}
