<div xmlns="http://www.w3.org/1999/xhtml" class="available-info-box ">
		<div class="available-info-box-title">{#Game_Classes#}</div>
		<span class="available-info-box-desc">{#Game_Classes_Avl#}:</span>
		<div class="list-box">
			<div class="wrapper">
					<ul>
					{foreach $Race.classes as $Class}
						{if $Class.can_be}
							<li>
								<a href="/game/class/{$Class.class_link}">
									<span class="icon-frame frame-36 class-icon-36 class-icon-36-{$Class.class_link}">
										<span class="frame"></span>
									</span>
									<span class="list-title">{$Class.class_name}</span>
								</a>
							</li>
						{/if}
					{/foreach}
					</ul>
	<span class="clear"><!-- --></span>
	<span class="clear"><!-- --></span>
			</div>
		</div>
	</div>