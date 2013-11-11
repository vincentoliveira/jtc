<?php

namespace Jtc\AnnonceBundle\Controller;

use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{
    /**
     * Homepage
     * 
     * @Template
     */
    public function indexAction()
    {
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $lastAnnonce = $repo->getLastAnnonce();
        $todayAnnonce = $repo->getLeavingToday();
        
        return array(
            'lastannonce' => $lastAnnonce,
            'todayannonce' => $todayAnnonce,
        );
    }


    /**
     * Create annonce
     * 
     * @Template
     */
    public function createAction()
    {
        $errors = array();

        $request = $this->getRequest();
        $postData = $request->request->all();
        if ($request->getMethod() == "POST") {
            $formHandler = $this->get('jtc_annonce.annonce_form_handler');

            $isValid = $formHandler->isValid($postData);
            if ($isValid === true) {
                $annonceId = $formHandler->hydrateEntity($postData);
                if ($annonceId === false) {
                    $errors['internal'][] = 'internal';
                }
            } else {
                $errors = $isValid;
            }
        }

        return array(
            'errors' => $errors,
            'old_data' => $postData,
        );
    }


}
