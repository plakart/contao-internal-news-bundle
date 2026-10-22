<?php

declare(strict_types=1);

namespace Plakart\InternalNewsBundle\Security;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\CoreBundle\Security\DataContainer\CreateAction;
use Contao\CoreBundle\Security\DataContainer\DeleteAction;
use Contao\CoreBundle\Security\DataContainer\UpdateAction;
use Plakart\InternalNewsBundle\Category;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Pflege-Modul und Schreibzugriffe auf die News-Tabelle nur für Admins.
 *
 * Der Voter liefert nie "granted", sondern nur "denied" oder "abstain". Damit kann
 * keine Benutzergruppen-Freischaltung das Modul für Nicht-Admins öffnen.
 */
#[AutoconfigureTag('security.voter', ['priority' => 200])]
class AdminOnlyVoter extends Voter
{
    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (ContaoCorePermissions::USER_CAN_ACCESS_MODULE === $attribute) {
            return \in_array(Category::MODULE_EDIT, (array) $subject, true);
        }

        return ContaoCorePermissions::DC_PREFIX.Category::TABLE === $attribute
            && ($subject instanceof CreateAction || $subject instanceof UpdateAction || $subject instanceof DeleteAction);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // Voter::vote() macht aus true "granted"; wir wollen aber nur ablehnen.
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes, \Symfony\Component\Security\Core\Authorization\Voter\Vote|null $vote = null): int
    {
        $result = parent::vote($token, $subject, $attributes, $vote);

        return self::ACCESS_GRANTED === $result ? self::ACCESS_ABSTAIN : $result;
    }
}
