<?php

require_once(dirname(__FILE__).'/CampCache.php');

/**
 * CampCacheList Class
 *
 * Simple class providing a standard interface to fetch and store
 * list of objects (articles, issues, sections, attachments, etc)
 * generated by static methods in several classes (e.g. Articles::GetList(),
 * ArticleTypeField::FetchFields()).
 */
class CampCacheList
{
    /**
     * Cache key for the given list.
     *
     * @var string
     */
    private $m_cacheKey = null;

    /**
     * Class name where the list belongs to.
     *
     * @var string
     */
    private $m_className = null;

    /**
     * Parameters passed to build the list with.
     *
     * @var array
     */
    private $m_parameters = array();

    /**
     * Default time to live in cache.
     *
     * @var int
     */
    protected $m_defaultTTL = 600;


    /**
     * Class constructor.
     *
     * @param array $p_parameters
     * @param string $p_className
     */
    public function __construct(array $p_parameters, $p_className)
    {
        $this->m_className = $p_className;
	$this->m_parameters = $p_parameters;
    }


    /**
     * Fetch list with the given parameters from cache.
     *
     * @return mixed
     *    array $list List of items found in cache
     *    null List does not exist in cache
     */
    public function fetchFromCache()
    {
        if (CampCache::IsEnabled()) {
	    $list = CampCache::singleton()->fetch($this->getCacheKey());
	    if ($list !== false && is_array($list)) {
	        return $list;
	    }
	}

	return null;
    }


    /**
     * Store the list of items in cache.
     *
     * @param array $p_list
     *
     * @return void
     */
    public function storeInCache(array $p_list)
    {
        if (CampCache::IsEnabled()) {
	    CampCache::singleton()->store($this->getCacheKey(), $p_list, $this->m_defaultTTL);
	}
    }


    /**
     * Generate the cache key for the list based on parameters.
     *
     * @return string m_cacheKey
     */
    private function getCacheKey()
    {
        if (is_null($this->m_cacheKey)) {
	    foreach($this->m_parameters as $paramName => $paramValue) {
	        if (is_array($paramValue)) {
		    $this->m_parameters[$paramName] = serialize($paramValue);
		}
	    }
	    $paramString = implode('_', $this->m_parameters);
	    $this->m_cacheKey = $this->m_className . '_List_' . $paramString;
	}

	return $this->m_cacheKey;
    }

} // class CampCacheList

?>