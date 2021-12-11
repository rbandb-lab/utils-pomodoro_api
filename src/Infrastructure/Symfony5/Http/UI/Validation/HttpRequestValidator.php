<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Validation;

use Pomodoro\SharedKernel\Error\Error;
use Symfony\Component\HttpFoundation\Request;

final class HttpRequestValidator
{
    private array $acceptedLocales;
    private array $acceptedContentTypes;
    private string $contentType;
    private string $locale;

    public function __construct(array $acceptedLocales, array $acceptedContentTypes)
    {
        $this->acceptedLocales = $acceptedLocales;
        $this->acceptedContentTypes = $acceptedContentTypes;
    }

    public function checkRequest(Request $request): bool|Error
    {
        $params = $this->requestParams($request);

        if (!$this->validateContentType($params['content-type'])) {
            return new Error('content-type', 'invalid-content-type');
        }
        if (!$this->validateLocale($params['locale'])) {
            return new Error('locale', 'invalid-locale');
        }

        return true;
    }

    public function validateContentType(string $contentType): bool
    {
        $this->contentType = $contentType;
        if (in_array($contentType, $this->acceptedContentTypes, true)) {
            return true;
        }

        return false;
    }

    public function validateLocale(string $locale): bool
    {
        $this->locale = $locale;
        return in_array($locale, $this->acceptedLocales, true);
    }


    public function requestParams(Request $request): array
    {
        if (isset($request->headers)) {
            $headers = $request->headers->getIterator()->getArrayCopy();
        }

        $headers = array_change_key_case($headers, CASE_LOWER);

        $contentType = !empty($headers['content-type'][0]) ? $headers['content-type'][0] : "application/json";
        $locale = $request->getLocale();
        $uri = $request->getRequestUri();

        return [
            'content-type' => $contentType,
            'locale' => $locale,
            'uri' => $uri
        ];
    }

    public function getResponseParams(): array
    {
        return [
            'content-type' => $this->contentType,
            'locale' => $this->locale
        ];
    }
}
