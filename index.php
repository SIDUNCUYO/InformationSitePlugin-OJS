<?php
/**
 * @defgroup plugins_blocks_informationSite InformationSite Block Plugin
 */
 
/**
 * @file plugins/blocks/informationSite/index.php
 *
 * Copyright (c) 2023 SID-UNCuyo
 * Copyright (c) 2003 Horacio Degiorgi
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_blocks_informationSite
 * @brief Wrapper for informationSite block plugin.
 *
 */

require_once('InformationSiteBlockPlugin.inc.php');

return new InformationSiteBlockPlugin();


