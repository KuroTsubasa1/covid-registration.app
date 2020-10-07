<?php


namespace App\LHarm\RegistrationAppBundle\Controller;

use App\Repository\GuestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FrontendController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * Displays the apps landing page
     */
    public function index(Request $request)
    {
        return $this->render(
            '@LHarmRegistrationApp/landing.html.twig',
            [
                'title' => 'RegistrationApp',
                'local' => $request->getLocale(),
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * Displays the apps guest form
     */
    public function guestForm(
        Request $request,
        GuestRepository $guestRepository,
        TranslatorInterface $translator
    ) {
        return $this->render(
            '@LHarmRegistrationApp/form.html.twig',
            [
                'title' => 'RegistrationApp',
            ]
        );
    }

    public function checkIn(
        Request $request,
        GuestRepository $guestRepository,
        TranslatorInterface $translator
    ) {
        if (mb_strtolower($request->getMethod()) == 'post')
        {
            $formData = $request->request->all();

            $validationResult = $guestRepository->validation(
                $translator,
                $formData
            );

            if ($validationResult === true)
            {
                $guestRepository->save($translator, $formData);
                $this->session->set('sessionID', $this->session->getId());

                return $this->render(
                    '@LHarmRegistrationApp/checkedIn.html.twig',
                    [
                        'title'        => 'RegistrationApp',
                        'name'         => $formData['form_name'],
                        'street'       => $formData['form_street_number'],
                        'zip_code'     => $formData['form_zip_city'],
                        'phone_number' => $formData['form_phone'],
                        'email'        => $formData['form_email'],
                        'note'         => $formData['form_note'],
                    ]
                );
            }
            else
            {
                return $this->render(
                    '@LHarmRegistrationApp/form.html.twig',
                    [
                        'title' => 'RegistrationApp',
                        'error' => $validationResult,
                    ]
                );
            }
        }
        else
        {
            return $this->render(
                '@LHarmRegistrationApp/form.html.twig',
                [
                    'title' => 'RegistrationApp',
                ]
            );
        }
    }

    public function checkOut(Request $request)
    {
        $this->session->invalidate();
        return $this->redirectToRoute('guest_form');
    }
}
