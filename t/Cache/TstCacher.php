<?php

require_once dirname(__FILE__).'/Interface.php';

class TstCacher implements Zend_Cache_Backend_Interface
{
	/**
	 * Set the frontend directives
	 *
	 * @param array $directives assoc of directives
	 */
	public function setDirectives($directives)
	{
	}

	/**
	 * Test if a cache is available for the given id and (if yes) return it (false else)
	 *
	 * Note : return value is always "string" (unserialization is done by the core not by the backend)
	 *
	 * @param  string  $id                     Cache id
	 * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
	 * @return string|false cached datas
	 */
	public function load($id, $doNotTest = false)
	{
		if (!array_key_exists($id,$this->c))
			return false;
		if ($this->t[$id]>0 || $this->t[$id]===false)
			return $this->c[$id];
		if ($doNotTest)
			return $this->c[$id];
		unset($this->t[$id]);
		unset($this->c[$id]);
		return false;
	}

	/*
	 * Функция для совместимости
	 */
	public function get($id)
	{
		return $this->load($id);
	}
	/**
	 * "Подождать" некоторое время
	 *
	 * @param int $sec
	 */
	public function sleep($sec)
	{
		foreach (array_keys($this->t) as $v)
			$this->t[$v]-=$sec;
	}

	/**
	 * Test if a cache is available or not (for the given id)
	 *
	 * @param  string $id cache id
	 * @return mixed|false (a cache is not available) or "last modified" timestamp (int) of the available cache record
	 */
	public function test($id)
	{
		return $this->load($id) === false;
	}

	/**
	 * Save some string datas into a cache record
	 *
	 * Note : $data is always "string" (serialization is done by the
	 * core not by the backend)
	 *
	 * @param  string $data            Datas to cache
	 * @param  string $id              Cache id
	 * @param  array $tags             Array of strings, the cache record will be tagged by each string entry
	 * @param  int   $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
	 * @return boolean true if no problem
	 */
	public function save($data, $id, $tags = array(), $specificLifetime = false)
	{
		$this->c[$id] = $data;
		if ($specificLifetime===null)
			$this->t[$id]=false;
		else
			$this->t[$id] = $specificLifetime?$specificLifetime:self::defTime;
	}

	/**
	 * Remove a cache record
	 *
	 * @param  string $id Cache id
	 * @return boolean True if no problem
	 */
	public function remove($id)
	{
		unset($this->t[$id]);
		unset($this->c[$id]);
	}

	/**
	 * Clean some cache records
	 *
	 * Available modes are :
	 * CACHE_CLR_ALL (default)    => remove all cache entries ($tags is not used)
	 * CACHE_CLR_OLD              => remove too old cache entries ($tags is not used)
	 * CACHE_CLR_TAG     => remove cache entries matching all given tags
	 *                                               ($tags can be an array of strings or a single string)
	 * CACHE_CLR_NOT_TAG => remove cache entries not {matching one of the given tags}
	 *                                               ($tags can be an array of strings or a single string)
	 *
	 * @param  string $mode Clean mode
	 * @param  array  $tags Array of tags
	 * @return boolean true if no problem
	 */
	public function clean($mode = CACHE_CLR_ALL, $tags = array())
	{
		if ($mode == CACHE_CLR_ALL)
			$this->c=$this->t=array();
		if ($mode == CACHE_CLR_OLD)
			foreach ($this->t as $k=>$v)
				if ($v<=0)
				{
					unset($this->t[$k]);
					unset($this->c[$k]);
				}
	}

	public function getAll()
	{
		return $this->c;
	}

	protected $c=array();
	protected $t=array();
	const defTime=3600;
}

?>