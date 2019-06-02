<?php

namespace App\Controller;

use App\Enum\BetError;
use App\Exception\DataModelException;
use App\Model\AddBet;
use App\Service\BetManager;
use App\Service\ErrorFactory;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController
{
    private const INPUT_FORMAT = 'json';

    /** @var SerializerInterface */
    private $serializer;

    /** @var ErrorFactory */
    private $errorFactory;

    /** @var BetManager */
    private $betManager;

    public function __construct(
        SerializerInterface $serializer,
        ErrorFactory $errorFactory,
        BetManager $betManager
    ) {
        $this->serializer = $serializer;
        $this->errorFactory = $errorFactory;
        $this->betManager = $betManager;
    }

    /**
     * @Route("/api/bet", name="api-bet", methods={"POST"})
     */
    public function addBetAction(Request $request): JsonResponse
    {
        try {
            try {
                $data = $this->serializer->deserialize($request->getContent(), AddBet::class, self::INPUT_FORMAT);
            } catch (RuntimeException $e) {
                return $this->getErrorResponse(BetError::STRUCTURE);
            }

            try {
                $this->betManager->process($data);
            } catch (DataModelException $e) {
                return new JsonResponse($e->getModel(), Response::HTTP_BAD_REQUEST);
            }

            return new JsonResponse([], Response::HTTP_CREATED);
        } catch (\Exception|\Error $e) {
            return $this->getErrorResponse(BetError::UNKNOWN);
        }
    }

    private function getErrorResponse(int $code): JsonResponse {
        $error = $this->errorFactory->createError(BetError::class, $code);

        return new JsonResponse($error, Response::HTTP_BAD_REQUEST);
    }
}
