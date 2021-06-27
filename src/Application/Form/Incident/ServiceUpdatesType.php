<?php

namespace App\Application\Form\Incident;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceRepository;
use App\Domain\Service\Repository\ServiceStatusRepository;
use LogicException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ServiceUpdatesType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var array<string, ServiceStatus>|null
     */
    private ?array $servicesMap = null;

    public function __construct(
        private ServiceRepository $serviceRepository,
        private ServiceStatusRepository $serviceStatusRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultStatus = $this->serviceStatusRepository->findDefault();

        if (!$defaultStatus) {
            throw new LogicException('Could not find a default service status');
        }

        $choices = $this->serviceStatusRepository->findAll();
        $this->servicesMap = [];

        foreach ($this->serviceRepository->findAll() as $service) {
            $this->servicesMap[$name = sprintf('serviceStatus_%s', $service->getId())] = $service;

            $builder->add(
                $name,
                ChoiceType::class,
                [
                    'label' => $service->getName(),
                    'choices' => $choices,
                    'expanded' => true,
                    'choice_label' => fn(ServiceStatus $status) => $status->getName(),
                    'data' => $service->getLastStatus($defaultStatus)
                ]
            );
        }

        $builder->addModelTransformer($this);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value): mixed
    {
        return $value;
    }

    /**
     * @psalm-param array<string, ServiceStatus> $value
     *
     * @psalm-return list<array{0: ServiceStatus, 1: ServiceStatus}>
     * @return ServiceStatus[][]
     */
    public function reverseTransform($value): array
    {
        if (null === $this->servicesMap) {
            throw new LogicException();
        }

        $updates = [];

        foreach ($value as $serviceIdentifier => $status) {
            $updates[] = [$this->servicesMap[$serviceIdentifier], $status];
        }

        return $updates;
    }
}
