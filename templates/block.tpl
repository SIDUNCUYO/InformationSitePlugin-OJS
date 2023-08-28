{**
 * plugins/blocks/informationSite/block.tpl
 *
 * Copyright (c) 2023 SID-UNCuyo
 * Copyright (c) 2003 Horacio Degiorgi
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- informationSite links.
 *
 *}
 
{if !empty($journals)}
<div class="pkp_block block_informationSite">
	<h2 class="title">Hay {$count}  revistas más en este portal</h2>
	
	<div class="content">
		<select name="otherjournal" onchange="window.location.href=(this.value)" style="width:100%">
		{foreach from=$journals item=localjournal}
			<option value="/ojs3/index.php/{$localjournal['url']}/index">{$localjournal['name']}</option>
		{/foreach}
		</select>
	</div>
</div>
{/if}
