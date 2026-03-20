<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\WordPress;

use OWC\ZGW\Http\Response;
use InvalidArgumentException;
use OWC\ZGW\Http\RequestOptions;
use OWC\ZGW\Http\SslCertificatesStore;
use OWC\ZGW\Http\RequestClientInterface;

class WordPressRequestClient implements RequestClientInterface
{
    protected RequestOptions $options;

    public function __construct(?RequestOptions $options = null)
    {
        $this->options = $options ?: new RequestOptions([]);
    }

    public function get(string $uri, ?RequestOptions $options = null): Response
    {
        $response = wp_remote_get(
            $this->buildUri($uri),
            $this->mergeRequestOptions($options)->toArray()
        );

        return $this->handleResponse($response);
    }

    public function post(string $uri, mixed $body, ?RequestOptions $options = null): Response
    {
        $options = $this->mergeRequestOptions($options)->set('body', $body);
        $response = wp_remote_post($this->buildUri($uri), $options->toArray());

        return $this->handleResponse($response);
    }

    public function delete(string $uri, ?RequestOptions $options = null): Response
    {
        $options->set('method', 'DELETE');

        $response = wp_remote_request(
            $this->buildUri($uri),
            $this->mergeRequestOptions($options)->toArray()
        );

        return $this->handleResponse($response);
    }

    public function getRequestOptions(): RequestOptions
    {
        return $this->options;
    }

    public function setRequestOptions(RequestOptions $options): self
    {
        $this->options = $options;

        return $this;
    }

	public function addSslCertificates(SslCertificatesStore $store): self
	{
		if ($store->isIncomplete()) {
			throw new InvalidArgumentException(
				'Missing SSL certificates: both public and private certificates are required for WordPressRequestClient configuration.'
			);
		}

		add_filter('http_request_args', $this->getHttpRequestArgsSslCertificatesCallback($store), 10, 1);
		add_action('http_api_curl', $this->getHttpApiCurlSslCertificatesCallback($store), 10, 2);

		return $this;
	}

	/**
	 * Configure the WordPress HTTP API trust store for supplier certificate validation.
	 */
	protected function getHttpRequestArgsSslCertificatesCallback(SslCertificatesStore $store): \Closure
	{
		return function (array $parsedArgs) use ($store): array {
			// Validate that the request is initiated by this package.
			if (! isset($parsedArgs['headers']['_owc_request_logging'])) {
				return $parsedArgs;
			}

			$supplierCertificatePath = $store->getSupplierCertificatePath();

			if ('' === trim($supplierCertificatePath)) {
				return $parsedArgs;
			}

			$parsedArgs['sslverify']       = true;
			$parsedArgs['sslcertificates'] = $supplierCertificatePath;

			return $parsedArgs;
		};
	}

	/**
	 * Configure the cURL client certificate and private key for mTLS authentication.
	 */
	protected function getHttpApiCurlSslCertificatesCallback(SslCertificatesStore $store): \Closure
	{
		return function ($handle, $parsedArgs) use ($store): void {
			// Validate that the request is initiated by this package.
			if (! isset($parsedArgs['headers']['_owc_request_logging'])) {
				return;
			}

			curl_setopt($handle, CURLOPT_SSLCERT, $store->getPublicCertificatePath());
			curl_setopt($handle, CURLOPT_SSLKEY, $store->getPrivateCertificatePath());

			$supplierCertificatePath = $store->getSupplierCertificatePath();

			if ('' === trim($supplierCertificatePath)) {
				return;
			}

			curl_setopt($handle, CURLOPT_CAINFO, $supplierCertificatePath);
		};
	}

    /**
     * @param \WP_Error|array<mixed> $response
     */
    protected function handleResponse($response): Response
    {
        if (is_wp_error($response)) {
            throw WordPressRequestError::fromWpError($response);
        }

        return WordPressClientResponse::fromResponse($response);
    }

    protected function mergeRequestOptions(?RequestOptions $options = null): RequestOptions
    {
        $this->options->addHeader('_owc_request_logging', microtime(true));

        if (! $options) {
            return $this->options;
        }

        return $this->options->clone()->merge($options);
    }

    protected function buildUri(string $uri): string
    {
        if ($this->options->has('base_uri')) {
            $uri = rtrim($this->options->get('base_uri'), '/') . '/' . $uri;
        }

        return $uri;
    }
}
