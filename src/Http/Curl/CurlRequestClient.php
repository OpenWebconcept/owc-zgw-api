<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Curl;

use OWC\ZGW\Http\Response;
use InvalidArgumentException;
use OWC\ZGW\Http\RequestOptions;
use OWC\ZGW\Http\SslCertificatesStore;
use OWC\ZGW\Http\RequestClientInterface;

class CurlRequestClient implements RequestClientInterface
{
    protected RequestOptions $options;
    protected SslCertificatesStore $certificates;

    public function __construct(?RequestOptions $options = null)
    {
        $this->options = $options ?: new RequestOptions([]);
    }

    public function get(string $uri, ?RequestOptions $options = null): Response
    {
        $handle = $this->buildHandle($uri, $options);

        $response = curl_exec($handle);

        return $this->handleResponse($response, $handle);
    }

    public function post(string $uri, mixed $body, ?RequestOptions $options = null): Response
    {
        $handle = $this->buildHandle($uri, $options);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($handle);

        return $this->handleResponse($response, $handle);
    }

    public function delete(string $uri, ?RequestOptions $options = null): Response
    {
        $handle = $this->buildHandle($uri, $options);
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, ' DELETE');

        $response = curl_exec($handle);

        return $this->handleResponse($response, $handle);
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
            throw new InvalidArgumentException('Missing SSL certificates: both public and private certificates are required for WordPressRequestClient configuration.');
        }

        $this->certificates = $store;

        return $this;
    }

    /** @return resource */
    protected function buildHandle(string $uri, RequestOptions $options)
    {
        $options = $this->mergeRequestOptions($options);

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->buildUri($uri));
        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->buildHeaders($options));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        return $this->applyCertificates($handle);
    }

    /**
     * @param resource $handle
     */
    protected function handleResponse(string $response, $handle): Response
    {
        $error = curl_error($handle);

        if ($error !== '') {
            throw new CurlRequestError($error);
        }

        return CurlClientResponse::fromResponse($response, $handle);
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

    /** @return string[] */
    protected function buildHeaders(RequestOptions $options): array
    {
        $formatted = [];
        $headers = $options->getHeaders();

        foreach ($headers as $header => $value) {
            $formatted[] = $header . ': ' . $value;
        }

        return $formatted;
    }

    /**
     * @param resource $handle
     *
     * @return resource
     */
    protected function applyCertificates($handle)
    {
        if (isset($this->certificates)) {
            curl_setopt($handle, CURLOPT_SSLCERT, $this->certificates->getPublicCertificatePath());
            curl_setopt($handle, CURLOPT_SSLKEY, $this->certificates->getPrivateCertificatePath());
        }

        return $handle;
    }
}
