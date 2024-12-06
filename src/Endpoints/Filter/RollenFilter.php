<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Attributes\SubjectType;

class RollenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak): self
    {
        return $this->add('zaak', $zaak->url);
    }

    public function bySubject(string $subjectUri): self
    {
        return $this->add('betrokkene', $subjectUri);
    }

    public function bySubjectType(SubjectType $subjectType): self
    {
        return $this->add('betrokkeneType', $subjectType->get());
    }

    public function bySubjectBsn(string $bsn): self
    {
        return $this->add(
            'betrokkeneIdentificatie__natuurlijkPersoon__inpBsn',
            $bsn
        );
    }
}
