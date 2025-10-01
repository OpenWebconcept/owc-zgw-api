<?php

declare(strict_types=1);

namespace OWC\ZGW\WordPress;

use OWC\ZGW\ApiCredentials;
use OWC\ZGW\ApiClientManager;
use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Support\ServiceProvider;
use OWC\ZGW\Clients\Xxllnc\Client as XXLLNC;
use OWC\ZGW\Clients\Procura\Client as Procura;
use OWC\ZGW\Clients\OpenZaak\Client as OpenZaak;
use OWC\ZGW\Clients\DecosJoin\ApiCredentials as DecosJoinApiCredentials;
use OWC\ZGW\Clients\DecosJoin\Client as DecosJoin;
use OWC\ZGW\Clients\RxMission\Client as RxMission;

class ClientProvider extends ServiceProvider
{
    protected ApiClientManager $apiManager;

    public function boot(): void
    {
        $this->apiManager = $this->container->get(ApiClientManager::class);
    }

    public function register(): void
    {
        add_action('init', [$this, 'initializeClients']);
    }

    public function initializeClients(): void
    {
        $clients = (array) get_option('zgw_api_settings');
        $clients = $clients['zgw-api-configured-clients'] ?? [];

        if (empty($clients)) {
            return;
        }

        foreach ($clients as $clientConfig) {
            $this->registerClient($clientConfig);
        }
    }

    protected function registerClient(array $config)
    {
        switch ($config['client_type'] ?? '') {
            case 'decosjoin':
                $credentials = new DecosJoinApiCredentials();
                $credentials->setClientSecretZrc($config['client_secret_zrc'] ?? '');
                break;

            default:
                $credentials = new ApiCredentials();
                break;
        }

        $credentials->setClientId($config['client_id'] ?? '');
        $credentials->setClientSecret($config['client_secret'] ?? '');

        $this->apiManager->addClient(
            $config['name'] ?? '',
            $this->getClientFqcn($config['client_type'] ?? ''),
            $credentials,
            $this->getApiUrlCollection($config)
        );
    }

    protected function getApiUrlCollection(array $config): ApiUrlCollection
    {
        $urlCollection = new ApiUrlCollection();
        $urlCollection->setZakenEndpoint(
            filter_var($config['zrc_endpoint'] ?? '', FILTER_SANITIZE_URL)
        );
        $urlCollection->setCatalogiEndpoint(
            filter_var($config['ztc_endpoint'] ?? '', FILTER_SANITIZE_URL)
        );
        $urlCollection->setDocumentenEndpoint(
            filter_var($config['drc_endpoint'] ?? '', FILTER_SANITIZE_URL)
        );

        return $urlCollection;
    }

    protected function getClientFqcn(string $clientName): string
    {
        switch ($clientName) {
            case 'openzaak':
                return OpenZaak::class;
            case 'xxllnc':
                return XXLLNC::class;
            case 'rxmission':
                return RxMission::class;
            case 'decosjoin':
                return DecosJoin::class;
            case 'procura':
                return Procura::class;
        }

        throw new \InvalidArgumentException("Unknown client name");
    }
}
