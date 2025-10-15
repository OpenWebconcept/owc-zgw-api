<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Support\Collection;

class ZaakSteps extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): mixed
    {
        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?Collection
    {
        if (! $model instanceof Zaak) {
            return null;
        }

        $statussen = $this->getSortedStatussen($model);

        if ($statussen->isEmpty()) {
            return $statussen;
        }

        $statusToelichting = (string) $model?->status?->statustype?->omschrijving;

        // Not possible to match with a status connected to a 'Zaak', set the first status as current.
        if (empty($statusToelichting)) {
            $currentVolgnummer = $statussen->first()->volgnummer();

            return $this->addProcessStatusses($statussen, (int) $currentVolgnummer);
        }

        // Get the current status which matches with the status connected to a 'Zaak'.
        $filtered = $statussen->filter(function ($status) use ($statusToelichting) {
            return strtolower((string) $status->omschrijving) === strtolower($statusToelichting);
        });

        if ($filtered->isEmpty()) {
            return $statussen;
        }

        return $this->addProcessStatusses($statussen, (int) $filtered->first()->volgnummer());
    }

    protected function getSortedStatussen(Entity $model): Collection
    {
        $statusTypen = $model?->zaaktype?->statustypen;

        if (! $statusTypen) {
            return Collection::collect([]);
        }

        return $statusTypen->sortByAttribute('volgnummer')->mapWithKeys(function ($key, $statusType) {
            /**
             * Ensures uniform usage of 'volgnummers' across different clients.
             * Set the 'volgnummer' attribute of the statustype to its position in the collection (1-based index).
             */
            $statusType->setValue('volgnummer', $key + 1);

            return $statusType;
        });
    }

    protected function addProcessStatusses(Collection $statussen, int $currentVolgnummer)
    {
        return $statussen->map(function ($status) use ($currentVolgnummer) {
            $volgnummer = (int) $status->volgnummer();

            if ($volgnummer < $currentVolgnummer) {
                $status->setValue('processStatus', 'past');
            } elseif ($volgnummer === $currentVolgnummer) {
                $status->setValue('processStatus', 'current');
            } else {
                $status->setValue('processStatus', 'future');
            }

            return $status;
        });
    }
}
