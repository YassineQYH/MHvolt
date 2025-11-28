<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Promotion;

class UniqueAutoPromoValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Promotion) {
            return;
        }

        // Si autoApply n'est pas activé, rien à faire
        if (!$value->isAutoApply()) {
            return;
        }

        // --- Vérifier si la promo est expirée ---
        if ($value->isExpired()) {
            $this->context->buildViolation("Impossible d'activer une promotion expirée.")
                ->atPath('autoApply')
                ->addViolation();
            return;
        }

        // --- Vérifier qu'aucune autre promo auto n'est active ---
        $existing = $this->em->getRepository(Promotion::class)
            ->createQueryBuilder('p')
            ->where('p.autoApply = :auto')
            ->andWhere('p.id != :currentId')
            ->setParameter('auto', true)
            ->setParameter('currentId', $value->getId() ?? 0)
            ->getQuery()
            ->getResult();

        if (!empty($existing)) {
            $this->context->buildViolation($constraint->message ?? 'Une autre promotion automatique est déjà active.')
                ->atPath('autoApply')
                ->addViolation();
        }
    }
}
