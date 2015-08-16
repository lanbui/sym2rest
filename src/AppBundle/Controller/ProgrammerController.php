<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProgrammerController extends Controller
{
    /**
     * @Route("/api/programmers", name="api_programmers_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('AppBundle:User');

        $programmer = new Programmer();
        $form = $this->createForm(new ProgrammerType(), $programmer);
        $this->processForm($request, $form);

        $programmer->setUser($userRepo->findOneByUsername('weaverryan'));

        $em->persist($programmer);
        $em->flush();

        return new JsonResponse($this->serializeProgrammer($programmer), 201,
                            array('Location' => $this->generateUrl('api_programmer_show', array('nickname' => $programmer->getNickname()))
                                  ));
    }

    /**
     * @Route("/api/programmers/{nickname}", name="api_programmers_edit")
     * @Method({"PUT", "PATCH"})
     * @param Request $request
     * @param $nickname
     * @return JsonResponse
     */
    public function updateAction(Request $request, $nickname)
    {
        $em = $this->getDoctrine()->getManager();
        $programmer = $em->getRepository('AppBundle:Programmer')->findOneByNickname($nickname);
        if (!$programmer) {
            throw $this->createNotFoundException(sprintf(
                'No programmer found with nickname "%s"',
                    $nickname
                ));
        }
        $form = $this->createForm(new ProgrammerType(), $programmer, ['is_edit' => true]);
        $this->processForm($request, $form);

        $em->persist($programmer);
        $em->flush();
        return new JsonResponse($this->serializeProgrammer($programmer), 200);
    }

    /**
     * @Route("/api/programmers/{nickname}", name="api_programmers_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param $nickname
     * @return Response
     */
    public function deleteAction(Request $request, $nickname)
    {
        $em = $this->getDoctrine()->getManager();
        $programmer = $em->getRepository('AppBundle:Programmer')->findOneByNickname($nickname);
        if (!$programmer) {
            throw $this->createNotFoundException(sprintf(
                'No programmer found with nickname "%s"',
                $nickname
            ));
        } else {
            $em->remove($programmer);
            $em->flush();
        }
        return new Response(null, 204);
    }

    /**
     * @Route("/api/programmers/{nickname}", name="api_programmer_show")
     * @Method("GET")
     * @param $nickname
     * @return JsonResponse
     */
    public function showAction($nickname)
    {
        /**
         * @var $programmer \AppBundle\Entity\Programmer
         */
        $programmer = $this->getDoctrine()->getRepository('AppBundle:Programmer')->findOneByNickname($nickname);

        if ( !$programmer ) {
            throw $this->createNotFoundException(sprintf('No programmer found with nickname "%s"', $nickname));
        }
        $data = $this->serializeProgrammer($programmer);
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/programmers", name="api_programmer_list")
     * @Method("GET")
     * @return JsonResponse
     */
    public function listAction()
    {
        $programmers = $this->getDoctrine()->getRepository('AppBundle:Programmer')->findAll();
        $data = array('programmers' => array());
        foreach($programmers as $programmer) {
            $data['programmers'][] = $this->serializeProgrammer($programmer);
        }
        return new JsonResponse($data);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    private function serializeProgrammer(Programmer $programmer)
    {
        $data = array(
            'nickname' => $programmer->getNickname(),
            'avatarNumber' => $programmer->getAvatarNumber(),
            'powerLevel' => $programmer->getPowerLevel(),
            'tagLine' => $programmer->getTagLine()
        );
        return $data;
    }
}
