<?php

namespace App\Support;

use Illuminate\Http\Request;

class OrigenContacto
{
    public static function resolver(Request $request): string
    {
        $origen = $request->input('origen');
        if (is_string($origen) && array_key_exists($origen, config('leads.origenes'))) {
            return $origen;
        }

        $medium = $request->query('utm_medium');
        $source = $request->query('utm_source');
        $medium = is_string($medium) ? strtolower($medium) : '';
        $source = is_string($source) ? strtolower($source) : '';
        if (in_array($medium, ['cpc', 'ppc', 'paid', 'paid_social'], true)) return 'publicidad';
        if ($medium === 'organic') return 'organico';
        if ($medium === 'email' || $source === 'newsletter') return 'email';
        if (in_array($medium, ['social', 'social-media'], true) || in_array($source, ['facebook', 'instagram', 'linkedin'], true)) return 'redes';
        if ($medium === 'referral') return 'referido';

        $host = strtolower((string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host);
        if (in_array($host, ['google.es', 'google.com', 'bing.com', 'duckduckgo.com'], true)) return 'organico';
        if (in_array($host, ['facebook.com', 'instagram.com', 'linkedin.com', 'm.facebook.com', 'l.facebook.com'], true)) return 'redes';
        $ownHost = preg_replace('/^www\./', '', strtolower((string) parse_url(config('app.url'), PHP_URL_HOST)));
        if ($host && !in_array($host, [$ownHost, preg_replace('/^www\./', '', $request->getHost())], true)) return 'referido';

        return 'directo';
    }
}
