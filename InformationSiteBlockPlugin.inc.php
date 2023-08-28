<?php

/**
 * @file plugins/blocks/informationSite/InformationSiteBlockPlugin.inc.php
 *
 * Copyright (c) 2023 SID-UNCuyo
 * Copyright (c) 2003 Horacio Degiorgi
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class InformationSiteBlockPlugin
 * @ingroup plugins_blocks_informationSite
 *
 * @brief Class for informationSite block plugin
 */
define('JOURNALS_BLOCK_MAX_ITEMS', 50);
define('JOURNALS_BLOCK_CACHE_DAYS', 2);
define('JONE_DAY_SECONDS', 60 * 60 * 24);
define('JTWO_DAYS_SECONDS', JONE_DAY_SECONDS * JOURNALS_BLOCK_CACHE_DAYS);

import('lib.pkp.classes.plugins.BlockPlugin');
import('classes.submission.SubmissionDAO');

class InformationSiteBlockPlugin extends BlockPlugin
{
	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	var $_plugin;
	function __construct()
	{
		PluginRegistry::loadCategory('block');
		$plugin = PluginRegistry::getPlugin('importexport', 'MedraExportPlugin'); /* @var $plugin MedraExportPlugin */
		$this->_plugin = $plugin;

		if (is_a($plugin, 'MedraExportPlugin')) {
			$plugin->addLocaleData();
		}

		parent::__construct();
	}

	function getContextSpecificPluginSettingsFile()
	{
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName()
	{
		//return __('plugins.block.informationSite.displayName');
		return 'Información del portal';
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription()
	{
		//return __('plugins.block.informationSite.description');
		return 'Información ampliada del portal de revistas';
	}

	/**
	 * @see BlockPlugin::getContents
	 */
	function getContents($templateMgr, $request = null)
	{


		$journal = $request->getJournal();

		if (!$journal)
			return '';
		$locale = AppLocale::getLocale();
		$cacheManager = CacheManager::getManager();
		$cache = $cacheManager->getFileCache(
			$journal->getId(),
			'journals_' . $locale,
			[$this, 'cacheDismiss']
		);
		
		$journals =& $cache->getContents();
		
		$currentCacheTime = time() - $cache->getCacheTime();

		if ($currentCacheTime > JTWO_DAYS_SECONDS) {
			$cache->flush();
			$journals = $this->_getjournals() ; 
			$cache->setEntireCache($journals);
		} else if ($journals == "[]" or is_null($journals)) {
			$journals = $this->_getjournals() ; 
			$cache->setEntireCache($journals);
		} 
		

		if (!$journals)
			return '';

		$templateMgr->assign(
			array(
				'count' => count($journals),
				'journals' => $journals,
				'journal' => $journal->getID()

			)
		);
		return parent::getContents($templateMgr, $request);
	}

	function _getJournals()
	{
		$plugin = $this->_plugin;
		$contextDao = Application::getContextDAO(); /* @var $contextDao JournalDAO */
		$journalFactory = $contextDao->getAll(true);

		$journals = [];



		while ($journal = $journalFactory->next()) {

			$journals[$journal->getid()] = [
				'name' => $journal->getLocalizedName('name'),
				'url' => $journal->_data['urlPath']
			];

		}
		$cjournals = collect($journals)->sortBy('name');

		return $cjournals->toArray();
	}
	/**
	 * fallBack for getFileCache
	 */
	function cacheDismiss()
	{
		return null;
	}
}