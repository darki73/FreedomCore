<div class="patch-main-menu" xmlns="http://www.w3.org/1999/xhtml">
	<div class="wrapper">
		<div id="patchlist">
			<ul>
				{foreach $MenuData as $Patch}
				<li>
					<a href=
					"/game/patch-notes/{$Patch.patch_version|replace:'.':'-'}"><span class="patch-thumb"
					style=
					"background-image:url('/Uploads/cms/gallery/{$Patch.patch_menu_icon}');">
					</span> <span class="patch-version">{#Patch_Version#} {$Patch.patch_version}</span>
					<span class="patch-title">{$Patch.patch_name}</span>
					<span class="clear"><!-- --></span></a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
</div>