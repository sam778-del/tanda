<?php


namespace App\Services;


use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink;
use Kreait\Firebase\DynamicLinks;
use Kreait\Firebase\Factory;

class Firebase
{
    private Factory $factory;
    private DynamicLinks $dynamicLinks;

    public function __construct()
    {
        $serviceAccount = base_path('eazisounds-213413-firebase-adminsdk-w207q-e0b1461c7a.json');
        $this->factory = (new Factory)->withServiceAccount($serviceAccount);
        $this->dynamicLinks = $this->factory->createDynamicLinksService('https://tandatest.page.link');
    }

    public function generateDynamicLink($url): string
    {
        $link = $this->dynamicLinks->createShortLink($url);
        $link = $this->dynamicLinks->createDynamicLink($url, CreateDynamicLink::WITH_SHORT_SUFFIX);
        return (string) $link;
    }

    public function generateDynamicLinkFromLongLink($longLink)
    {
        $link = $this->dynamicLinks->shortenLongDynamicLink($longLink);
        $link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_UNGUESSABLE_SUFFIX);
        $link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_SHORT_SUFFIX);
        return (string) $link;
    }

    public function generateLink(): string
    {
        $parameters = [
            'dynamicLinkInfo' => [
                'domainUriPrefix' => 'https://tandatest.page.link',
                'link' => 'https://164.90.165.19/api/stackers/users/1',
            ],
            'suffix' => ['option' => 'SHORT'],
        ];
        $link = $this->dynamicLinks->createDynamicLink($parameters);
        return (string) $link;
    }
}
