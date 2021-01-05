<?php

namespace texnixe\Related;


class Related
{

    private static $indexname = null;

    private static $cache = null;

    private static function cache(): \Kirby\Cache\Cache
    {
        if (!static::$cache) {
            static::$cache = kirby()->cache('texnixe.related');
        }
        // create new index table on new version of plugin
        if (!static::$indexname) {
            static::$indexname = 'index'.str_replace('.', '', kirby()->plugin('texnixe/related')->version()[0]);
        }
        return static::$cache;
    }

    public static function flush()
    {
        if (static::$cache) {
            return static::cache()->flush();
        }
    }

    public static function data($basis, $options = [])
    {
        // new empty collection
        $related = static::getClassName($basis, []);

        $defaults = option('texnixe.related.defaults');
        // add the default search collection to defaults
        $defaults['searchCollection'] = $basis->siblings(false);

        // Merge default and user options
        $options = array_merge($defaults, $options);

        // define variables
        $searchCollection = $options['searchCollection'];
        $matches          = $options['matches'];
        $searchField      = strtolower($options['searchField']);
        $delimiter        = $options['delimiter'];
        $languageFilter   = $options['languageFilter'];

         // get search items from active basis
         $searchItems     = $basis->{$searchField}()->split($delimiter);
         $noOfSearchItems = count($searchItems);

        if($noOfSearchItems > 0) {
            // no. of matches can't be greater than no. of searchItems
            $matches > $noOfSearchItems? $matches = $noOfSearchItems: $matches;

            for($i = $noOfSearchItems; $i >= $matches; $i--) {
            $relevant[$i] = $searchCollection->filter(function($b) use($searchItems, $searchField, $delimiter, $i) {
                return count(array_intersect($searchItems, $b->$searchField()->split($delimiter))) == $i;
            });

            $related->add($relevant[$i]);
        }

        // filter collection by current language if $languageFilter set to true
        if(kirby()->multilang() === true && $languageFilter === true) {
            $related = $related->filter(function($p) {
                return $p->translation(kirby()->language()->code())->exists();
            });
        }

        }
        return $related;

    }

    public static function getClassName($basis, $items = '')
    {
        if(is_a($basis, '\Kirby\Cms\Page')) {
            return pages($items);
        }
        if(is_a($basis, '\Kirby\Cms\File')) {
            return new \Kirby\Cms\Files($items);
        }
    }

    public static function getRelated($basis, $options = [])
    {
        $collection = $options['searchCollection']?? $basis->siblings(false);

        // try to get data from the cache, else create new
        if($response = static::cache()->get(md5($basis->id().implode(',',$options)))) {
            $data = $response['data'];
            $related = static::getClassName($basis, array_keys($data));
        } else {
            $related = static::data($basis, $options);
        }

        static::cache()->set(
            md5($basis->id() . implode(',', $options)),
            $related,
            option('texnixe.related.expires')

        );

        return $related;
    }
}
