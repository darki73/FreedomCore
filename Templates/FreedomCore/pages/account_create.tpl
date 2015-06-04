<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$Language}" class="{$Language}">
	<head xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
		<meta http-equiv="imagetoolbar" content="false" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>{$Page.pagetitle} {$AppName}</title>
		<link rel="shortcut icon" href="/Templates/{$Template}/images/meta/favicon.ico" />
		<link rel="search" type="application/opensearchdescription+xml" href="http://{$smarty.server.HTTP_HOST}/data/opensearch" title="{#Head_Opensearch_Meta#} {$AppName}" />
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/toolkit/wow-web.min.css" />

		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/login/global.min.css" />
		<script type="text/javascript" src="/Templates/{$Template}/js/third-party/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="/Templates/{$Template}/js/core.min.js"></script>
		<meta name="viewport" content="width=device-width" />

	</head>
	<body class="ru-ru login-template web wow {$ExpansionTemplate}" data-embedded-state="STATE_LOGIN">
		<div class="grid-container wrapper">
			<h1 class="logo">{#Account_Login#}</h1>

			<div class="hide" id="info-wrapper">
				<h2><strong class="info-title"></strong></h2>

				<p class="info-body"></p><button class=
				"btn btn-block hide visible-phone" id="info-phone-close">Close</button>
			</div>

			<div class="input-container" id="registration-wrapper">
				<form action="/account/createaccount" class=" username-required input-focus" id=
				"password-form" method="post" name="password-form">

					<div class="control-group">
						<label class="control-label" for="username" id=
						"username-label">{#Login_Username#}</label>

						<div class="controls">
							<input class="input-block input-large" id="username"
							maxlength="320" name="username" placeholder="{#Login_Username#}"
							spellcheck="false" tabindex="1" title="{#Login_Username#}" type=
							"text" value="">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="email" id=
						"email-label">{#Login_Email#}</label>

						<div class="controls">
							<input class="input-block input-large" id="email"
							maxlength="320" name="email" placeholder="{#Login_Email#}"
							spellcheck="false" tabindex="1" title="{#Login_Email#}" type=
							"text" value="">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="password" id=
						"password-label">{#Login_Password#}</label>

						<div class="controls">
							<input autocomplete="off" class="input-block input-large"
							id="password" maxlength="16" name="password" placeholder=
							"{#Login_Password#}" spellcheck="false" tabindex="1" title=
							"{#Login_Password#}" type="password">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="password" id=
						"password-label">{#Login_RPassword#}</label>

						<div class="controls">
							<input autocomplete="off" class="input-block input-large"
							id="rpassword" maxlength="16" name="rpassword" placeholder=
							"{#Login_RPassword#}" spellcheck="false" tabindex="1" title=
							"{#Login_RPassword#}" type="password">
						</div>
					</div>

					<div class="control-group submit">
						<button class="btn btn-primary btn-large btn-block"
						data-loading-text="" id="submit" tabindex="1" type=
						"submit">{#Login_Registration#} <i class="spinner-battlenet"></i></button>
					</div>

					<ul id="help-links">
						<li>
							<a class="btn btn-block btn-large" href=
							"/account/login"
							rel="external" tabindex="1">{#Login_Already_Have_Account#}
							<i class="icon-external-link"></i></a>
						</li>

						<li>
							<a class="" href=
							"/account/restore"
							rel="external" tabindex="1">{#Login_Cant_Login#} <i class=
							"icon-external-link"></i></a>
						</li>
					</ul><input id="csrftoken" name="csrftoken" type="hidden" value=
					"{$CSRFToken}">
				</form>
			</div>

			<footer class="footer footer-eu">
				<div class="lower-footer-wrapper">
					<div class="lower-footer">
						<div id="copyright">
							© {$AppName}, 2015 г.
						</div>

						<div id="legal">
							<div class="png-fix" id="legal-ratings"></div><span class=
							"clear"><!-- --></span>
						</div>
					</div>

					<div id="marketing-trackers">
						<div class="marketing-cover"></div>
					</div>
				</div>
			</footer>
		</div>
	<script type="text/javascript" src="/Templates/{$Template}/js/embed-0.1.5.min.js"></script>
	<script type="text/javascript" src="/Templates/{$Template}/js/login/toolkit.min.js"></script>
	<script type="text/javascript" src="/Templates/{$Template}/js/login/global.min.js"></script>
	<script type="text/javascript" src="/Templates/{$Template}/js/login/login.min.js"></script>
	</body>
</html>