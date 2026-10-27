<?php

namespace App\Validator;

use App\Entity\AreaPage;
use App\Repository\AreaPageRepository;
use Symfony\Component\Routing\Exception\ExceptionInterface as RoutingException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AreaPageSlugsValidator extends ConstraintValidator
{
    public function __construct(
        private UrlMatcherInterface $matcher,
        private AreaPageRepository $repository,
    ) {
    }

    public function validate(mixed $page, Constraint $constraint): void
    {
        if (!$constraint instanceof AreaPageSlugs) {
            throw new UnexpectedTypeException($constraint, AreaPageSlugs::class);
        }
        if (!$page instanceof AreaPage) {
            return;
        }

        foreach (['slug' => $page->getSlug(), 'slugEn' => $page->getSlugEn()] as $field => $slug) {
            // format invalide : déjà signalé par la contrainte Regex
            if (!$slug || !preg_match('/^'.AreaPage::SLUG_REQUIREMENT.'$/', $slug)) {
                continue;
            }

            $path = ('slugEn' === $field ? '/en/' : '/').$slug;
            try {
                $route = $this->matcher->match($path)['_route'] ?? null;
            } catch (RoutingException) {
                $route = null;
            }
            if ('area_page' !== $route) {
                $this->context->buildViolation($constraint->routeMessage)->atPath($field)->addViolation();
                continue;
            }

            $other = $this->repository->findOneByAnySlug($slug, false);
            if ($other && $other !== $page) {
                $this->context->buildViolation($constraint->areaMessage)
                    ->setParameter('{{ name }}', (string) $other->getName())
                    ->atPath($field)
                    ->addViolation();
            }
        }
    }
}
