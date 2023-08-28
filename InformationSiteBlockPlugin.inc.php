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

import('lib.pkp.classes.plugins.BlockPlugin');

class InformationSiteBlockPlugin extends BlockPlugin {
	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	var $_plugin;
	function __construct() {
		PluginRegistry::loadCategory('block');
		$plugin = PluginRegistry::getPlugin('importexport', 'MedraExportPlugin'); /* @var $plugin MedraExportPlugin */
		$this->_plugin = $plugin;

		if (is_a($plugin, 'MedraExportPlugin')) {
			$plugin->addLocaleData();
		}

		parent::__construct();
	}

	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		//return __('plugins.block.informationSite.displayName');
		return 'Información del portal' ; 
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		//return __('plugins.block.informationSite.description');
		return 'Información ampliada del portal de revistas' ; 
	}

	/**
	 * @see BlockPlugin::getContents
	 */
	function getContents($templateMgr, $request = null) {
		
		
		$journal = $request->getJournal();
		
		if (!$journal) return '';

		$journals = $this->_getjournals(); 
		
		if (!$journals)
		return '';
		
		$templateMgr->assign(array(
			'count' => count($journals),
			'journals' => $journals,
			'journal'=>$journal->getID()

		));
		return parent::getContents($templateMgr, $request);
	}
	
	function _getJournals() {
		$plugin = $this->_plugin;
		$contextDao = Application::getContextDAO(); /* @var $contextDao JournalDAO */
		$journalFactory = $contextDao->getAll(true);
			
		$journals = [] ; 
		while($journal = $journalFactory->next()) {
			
			$journals[$journal->getid()] =['name'=> $journal->getLocalizedName('name')  , 
			'url'=>$journal->_data['urlPath']
			] ; 

		}
		$cjournals = collect($journals)->sortBy('name') ; 	
		
		return $cjournals->toArray();
	}

}


