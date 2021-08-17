<?php

namespace texnixe\Related;

use Kirby\Cache\Cache;
use Kirby\Cms\Files;
use Kirby\Cms\Pages;

class Related
{

    protected static $indexname = null;

    protected static $cache = null;

    /**
     * Returns the cache object
     *
     * @return Cache
     */
    protected static function cache(): Cache
    {
        if (!static::$cache) {
            static::$cache = kirby()->cache('texnixe.related');
        }
        // create new index table on new version of plugin
        if (!static::$indexname) {
            static::$indexname = 'index' . str_replace('.', '', kirby()->plugin('texnixe/related')->version()[0]);
        }
        return static::$cache;
    }

    /**
     * Flushes the cache
     *
     * @return bool
     */
    public static function flush(): bool
    {
        if (static::$cache) {
            return static::cache()->flush();
        }
    }

    /**
     * Fetches related pages
     *
     * @param File|Page   $base File or page object.
     * @param File|Page   $collection Files or pages object.
     * @param array       $options Search options.
     */
    protected static function data($base, $collection, $options = [])
    {
        // initialize new collection based on type
        $related = $collection;

        $defaults = option('texnixe.related.defaults');
        // add the default search collection to defaults
        $defaults['searchCollection'] = $base->siblings(false);

        // Merge default and user options
        $options = array_merge($defaults, $options);

        // define variables
        $searchCollection = $options['searchCollection'];
        $matches          = $options['matches'];
        $searchField      = strtolower($options['searchField']);
        $delimiter        = $options['delimiter'];
        $languageFilter   = $options['languageFilter'];

        // get search items from active basis
        $searchItems      = $base->{$searchField}()->split($delimiter);
        $itemCount        = count($searchItems);

        if ($itemCount > 0) {
            dump($itemCount);
            // no. of matches can't be greater than no. of searchItems
            $matches > $itemCount ? $matches = $itemCount : $matches;

            while ($itemCount >= $matches) {
                $relevant[$itemCount] = $searchCollection->filter(function ($b) use ($searchItems, $searchField, $delimiter, $itemCount) {
                    return count(array_intersect($searchItems, $b->$searchField()->split($delimiter))) == $itemCount;
                });
                $related->add($relevant[$itemCount]);
                $itemCount--;
            }

            // filter collection by current language if $languageFilter set to true
            if (kirby()->multilang() === true && $languageFilter === true) {
                $related = $related->filter(function ($p) {
                    return $p->translation(kirby()->language()->code())->exists();
                });
            }
        }
        return $related;
    }

    /**
     * Returns related pages
     *
     * @param File|Page   $base File or page object.
     * @param File|Page   $collection Files or pages object.
     * @param array       $options Search options.
     *
     * @return Files|Pages
     */
    public static function getRelated($base, $collection, $options = [])
    {
        // try to get data from the cache, else create new
        if ($response = static::cache()->get(md5($base->id() . implode(',', $options)))) {
            $data    = $response['data'];
            $related = $collection->data($data ?? []);
        } else {
            $related = static::data($base, $collection, $options);
        }

        static::cache()->set(
            md5($base->id() . implode(',', $options)),
            $related,
            option('texnixe.related.expires')
        );

        return $related;
    }
}
