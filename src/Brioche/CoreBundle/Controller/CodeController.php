<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Brioche\CoreBundle\Exception\BriocheException;

class CodeController extends Controller
{
    /**
     * @Route(
     *      "/appliquer-un-code-promo",
     *      name = "code_apply"
     * )
     * @Method({"POST"})
     */
    public function applyAction(Request $request)
    {
        $briocheBuilder = $this->get('brioche_core.brioche_builder');
        
        $codePromo = $request->get('code_promo');
        
        try {
            $code = $briocheBuilder->applyCode($codePromo);
            
            $brioche = $briocheBuilder->getCurrentBrioche();
            $reduction = $brioche->getCodeReduction();
            
            return new JsonResponse(array(
                'ok' => true,
                'codeTitle' => $code->getCodeType()->getTitle(),
                'codeReduction' => number_format(-$reduction, 2).' €',
                'updatedPrice' => number_format($brioche->getPrice(), 2).' €',
            ));
        } catch (BriocheException $e) {
            return new JsonResponse(array(
                'ok' => false,
                'reason' => $e->getMessage(),
            ));
        }
    }
}
