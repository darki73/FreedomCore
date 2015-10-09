<div class="left-col">
					<div class="story-highlight">
						<p>
							{assign "Class_Personal_Header" $Class.class_description_personal_header}
							{$smarty.config.$Class_Personal_Header}
						</p>
					</div>
					<div class="story-main">
						<div class="story-illustration"></div>
						<p>
							{assign "Class_Personal_Top" $Class.class_description_personal_top}
							{$smarty.config.$Class_Personal_Top}
						</p>
					</div>

					<div class="basic-info-box-list basic-info">
						<div class="basic-info-box-list-title"><span style="padding-left: 60px!important;">{#Description#}</span></div>
						<div class="list-box">
							<div class="wrapper">
							{assign "Class_Desc" $Class.class_description_personal}
							<p >{$smarty.config.$Class_Desc}</p>
									<ul>
										<li>
											<span class="basic-info-title">{#Type#}</span>
												{if $Class.can_be_tank}
													{#Class_Role_Tank#},
												{/if}
												{if $Class.can_be_heal}
													{#Class_Role_Healer#},
												{/if}
												{if $Class.melee_damage}
													{#Class_Role_DPS#} ({#Damage_Type_Melee#}){if $Class.ranged_physical},{/if}{if $Class.ranged_arcane},{/if}
												{/if}
												{if $Class.ranged_physical}
													{#Class_Role_DPS#} ({#Damage_Type_Ranged_Physical#}){if $Class.ranged_arcane},{/if}
												{/if}
												{if $Class.ranged_arcane}
													{#Class_Role_DPS#} ({#Damage_Type_Ranged_Arcane#})
												{/if}
										</li>
										<li>
											<span class="basic-info-title">{#Indicators#}</span>
											{$Class.indicator_first_type}, {$Class.indicator_second_type}
										</li>
									</ul>
									<span class="clear"><!-- --></span>
							</div>
						</div>
					</div>

					<div class="basic-info-box-list talent-info">
						<div class="basic-info-box-list-title"><span style="padding-left: 60px!important;">{#Game_Talents#}</span></div>
						<div class="list-box">
							<div class="wrapper">
							<p></p>
									<div class="talent-info-wrapper">
										<div class="talent-header">{#Class_Talent_Specialization#} — {$Class.class_full_name}</div>
											<span class="clear"><!-- --></span>
											<div class="talent-wrapper">
												<a href="/game/tool/talent-calculator#{$Class.class_name}">
													<span class="talent-block" style="background-image:url(/Templates/{$Template}/images/icons/large/{$Class.first_specialization_image}.jpg)">
													<span class="circle-frame"></span>
													</span>
													{assign "First_Specialization" $Class.class_name|capitalize|replace:'-':'_'|cat:"_First_Spec_Title"}
													{$smarty.config.$First_Specialization}
												</a>
											</div>
											<div class="talent-wrapper">
												<a href="/game/tool/talent-calculator#{$Class.class_name}">
													<span class="talent-block" style="background-image:url(/Templates/{$Template}/images/icons/large/{$Class.second_specialization_image}.jpg)">
													<span class="circle-frame"></span>
													</span>
													{assign "Second_Specialization" $Class.class_name|capitalize|replace:'-':'_'|cat:"_Second_Spec_Title"}
													{$smarty.config.$Second_Specialization}
												</a>
											</div>
											<div class="talent-wrapper">
												<a href="/game/tool/talent-calculator#{$Class.class_name}">
													<span class="talent-block" style="background-image:url(/Templates/{$Template}/images/icons/large/{$Class.third_specialization_image}.jpg)">
													<span class="circle-frame"></span>
													</span>
													{assign "Third_Specialization" $Class.class_name|capitalize|replace:'-':'_'|cat:"_Third_Spec_Title"}
													{$smarty.config.$Third_Specialization}
												</a>
											</div>
											{if $Class.class_name == "druid"}
												<div class="talent-wrapper">
												<a href="/game/tool/talent-calculator#{$Class.class_name}">
													<span class="talent-block" style="background-image:url(/Templates/{$Template}/images/icons/large/spell_nature_healingtouch.jpg)">
													<span class="circle-frame"></span>
													</span>
													{#Druid_Forth_Spec_Title#}
												</a>
											</div>
											{/if}
										<span class="clear"><!-- --></span>
									</div>
									<span class="clear"><!-- --></span>
							</div>
						</div>
					</div>
				</div>