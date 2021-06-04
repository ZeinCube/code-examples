<?php

use App\Exception\API\APIException;
use App\Exception\API\IncorrectDataException;
use App\Form\Type\RegistrationType;
use App\Mail\Manager\RegistrationCodeMailManager;
use App\Manager\Security\UserManager;
use App\Model\Registration;
use App\Util\API\Payload;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractAPIController
{
    private const PHONE = 'phone';
    private const EMAIL = 'email';

    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * @var RegistrationCodeMailManager
     */
    private RegistrationCodeMailManager $registrationCodeMailManager;

    /**
     * @var TokenGeneratorInterface
     */
    private TokenGeneratorInterface $tokenGenerator;

    /**
     * @param UserManager                 $userManager
     * @param RegistrationCodeMailManager $registrationCodeMailManager
     * @param TokenGeneratorInterface     $tokenGenerator
     */
    public function __construct(
        UserManager $userManager,
        RegistrationCodeMailManager $registrationCodeMailManager,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->userManager                 = $userManager;
        $this->registrationCodeMailManager = $registrationCodeMailManager;
        $this->tokenGenerator              = $tokenGenerator;
    }

    /**
     * @param Request $request
     *
     * @throws IncorrectDataException
     * @throws APIException
     *
     * @return Response
     */
    public function postRegister(Request $request)
    {
        /** @var Registration $registration */
        $registration = $this->handleForm($this->createForm(RegistrationType::class), $request->request->all());

        $identity = $registration->getIdentity();
        $field    = $this->validateEmailOrPhone($identity);

        $user = $this->userManager->findOneBy([
            $field => $identity,
        ]);

        if ($user !== null) {
            $code = substr($this->tokenGenerator->generateToken(), 0, UserManager::GENERATOR_PASSWORD_LENGTH);

            $user->setPlainPassword($code);
        } else {
            $user = $this->userManager->registerUser($identity, $field);
            $code = $user->getPlainPassword();
        }

        $this->userManager->save($user);

        switch ($field) {
            case self::PHONE:
                //TODO:send to sms
                break;
            case self::EMAIL:
                $this->registrationCodeMailManager->sendCode($code, $user->getEmail());

                break;
        }

        return $this->response(Payload::create([
            'type' => $field,
        ]));
    }

    /**
     * @param string $value
     *
     * @throws APIException
     *
     * @return string
     */
    private function validateEmailOrPhone(string $value)
    {
        $phoneMatches = [];
        preg_match('/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', $value, $phoneMatches);
        $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);

        if (!$isEmail && count($phoneMatches) === 0) {
            throw $this->createAPIException('Current value is invalid');
        }

        return $isEmail ? self::EMAIL : self::PHONE;
    }
}