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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dipsycat\FbSocialBundle\Entity\Claim;

class MapController extends Controller {

    public function indexAction(Request $request) {
        $dataStart = new \DateTime();
        $data = [
            'status' => !empty($request->get('status')) ? $request->get('status') : array_values(Claim::getAllStatuses()),
            'dateStart' => !empty($request->get('date-start')) ? $request->get('date-start') : $dataStart->format('y-m-d'),
            'dateEnd' => !empty($request->get('date-end')) ? $request->get('date-end') : $dataStart->format('y-m-d'),
        ];
        $map = $this->mapInit($data);
        return $this->render('DipsycatFbSocialBundle:Map:index.html.twig', [
                    'map' => $map,
                    'data' => $data
        ]);
    }

    private function mapInit($data) {
        $map =new Map();
        $map->setCenter(new Coordinate(59.955674, 30.2816493));
        $map->setMapOption('zoom', 10);
        $map->setStylesheetOption('width', '95%');
        $map->setStylesheetOption('height', '800px');
        $map->setStylesheetOption('position', 'absolute');
        $map->setHtmlId('map_canvas');

        $claims = $this->getClaims($data);
        foreach ($claims as $claim) {
            $marker = new MarkerAdapter($claim);
            $map->getOverlayManager()->addMarker($marker);
        }

        $fullscreenControl = new FullscreenControl(ControlPosition::TOP_LEFT);
        $map->getControlManager()->setFullscreenControl($fullscreenControl);
        return $map;
    }

    private function getClaims($data) {
        $em = $this->getDoctrine()->getManager();
        $claimRepository = $em->getRepository('DipsycatFbSocialBundle:Claim');
        $claims = $claimRepository->getClaims($data);
        return $claims;
    }

    public function addClaimAction(Request $request) {
        $data = $request->get('data');
        $data = json_decode($data);
        $Claim = new Claim();
        $em = $this->getDoctrine()->getManager();
        $em->persist($Claim);
        $em->flush();
    }

}
