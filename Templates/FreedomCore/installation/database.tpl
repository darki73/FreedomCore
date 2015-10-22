{include file = 'installation/step_header.tpl'}
<div id="wrap">
    <div class="container">

        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-12">
                    <h1>{#Installation_Setup_HO#}</h1>
                    <p class="lead">{#Installation_Allowed#}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <form class="form-horizontal" method="post" action="/Install/complete">
                <fieldset>
                    <legend>{#Installation_MySQL_Website#}</legend>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_host" class="control-label">{#Installation_MySQL_Host#}</label>
                            <input type="text" class="form-control" name="website_db_host" id="website_db_host" placeholder="{#Installation_MySQL_Host#} (localhost)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_port" class="control-label">{#Installation_MySQL_Port#}</label>
                            <input type="text" class="form-control" name="website_db_port" id="website_db_port" placeholder="{#Installation_MySQL_Port#} (3306)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_database" class="control-label">{#Installation_MySQL_DB#}</label>
                            <input type="text" class="form-control" name="website_db_database" id="website_db_database" placeholder="{#Installation_MySQL_DB#} (freedomcore)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_username" class="control-label">{#Installation_MySQL_User#}</label>
                            <input type="text" class="form-control" name="website_db_username" id="website_username" placeholder="{#Installation_MySQL_User#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_password" class="control-label">{#Installation_MySQL_Password#}</label>
                            <input type="text" class="form-control" name="website_db_password" id="website_db_password" placeholder="{#Installation_MySQL_Password#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website_db_encoding" class="control-label">{#Installation_MySQL_Encoding#}</label>
                            <input type="text" class="form-control" name="website_db_encoding" id="website_db_encoding" placeholder="{#Installation_MySQL_Encoding#}" value="UTF8" required />
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>{#Installation_MySQL_Auth#}</legend>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_host" class="control-label">{#Installation_MySQL_Host#}</label>
                            <input type="text" class="form-control" name="auth_db_host" id="auth_db_host" placeholder="{#Installation_MySQL_Host#} (localhost)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_port" class="control-label">{#Installation_MySQL_Port#}</label>
                            <input type="text" class="form-control" name="auth_db_port" id="auth_db_port" placeholder="{#Installation_MySQL_Port#} (3306)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_database" class="control-label">{#Installation_MySQL_DB#}</label>
                            <input type="text" class="form-control" name="auth_db_database" id="auth_db_database" placeholder="{#Installation_MySQL_DB#} (auth)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_username" class="control-label">{#Installation_MySQL_User#}</label>
                            <input type="text" class="form-control" name="auth_db_username" id="website_username" placeholder="{#Installation_MySQL_User#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_password" class="control-label">{#Installation_MySQL_Password#}</label>
                            <input type="text" class="form-control" name="auth_db_password" id="auth_db_password" placeholder="{#Installation_MySQL_Password#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="auth_db_encoding" class="control-label">{#Installation_MySQL_Encoding#}</label>
                            <input type="text" class="form-control" name="auth_db_encoding" id="auth_db_encoding" placeholder="{#Installation_MySQL_Encoding#}" value="UTF8" required />
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>{#Installation_MySQL_Characters#}</legend>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_host" class="control-label">{#Installation_MySQL_Host#}</label>
                            <input type="text" class="form-control" name="characters_db_host" id="characters_db_host" placeholder="{#Installation_MySQL_Host#} (localhost)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_port" class="control-label">{#Installation_MySQL_Port#}</label>
                            <input type="text" class="form-control" name="characters_db_port" id="characters_db_port" placeholder="{#Installation_MySQL_Port#} (3306)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_database" class="control-label">{#Installation_MySQL_DB#}</label>
                            <input type="text" class="form-control" name="characters_db_database" id="characters_db_database" placeholder="{#Installation_MySQL_DB#} (characters)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_username" class="control-label">{#Installation_MySQL_User#}</label>
                            <input type="text" class="form-control" name="characters_db_username" id="website_username" placeholder="{#Installation_MySQL_User#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_password" class="control-label">{#Installation_MySQL_Password#}</label>
                            <input type="text" class="form-control" name="characters_db_password" id="characters_db_password" placeholder="{#Installation_MySQL_Password#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="characters_db_encoding" class="control-label">{#Installation_MySQL_Encoding#}</label>
                            <input type="text" class="form-control" name="characters_db_encoding" id="characters_db_encoding" placeholder="{#Installation_MySQL_Encoding#}" value="UTF8" required />
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>{#Installation_MySQL_World#}</legend>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_host" class="control-label">{#Installation_MySQL_Host#}</label>
                            <input type="text" class="form-control" name="world_db_host" id="world_db_host" placeholder="{#Installation_MySQL_Host#} (localhost)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_port" class="control-label">{#Installation_MySQL_Port#}</label>
                            <input type="text" class="form-control" name="world_db_port" id="world_db_port" placeholder="{#Installation_MySQL_Port#} (3306)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_database" class="control-label">{#Installation_MySQL_DB#}</label>
                            <input type="text" class="form-control" name="world_db_database" id="world_db_database" placeholder="{#Installation_MySQL_DB#} (world)" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_username" class="control-label">{#Installation_MySQL_User#}</label>
                            <input type="text" class="form-control" name="world_db_username" id="website_username" placeholder="{#Installation_MySQL_User#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_password" class="control-label">{#Installation_MySQL_Password#}</label>
                            <input type="text" class="form-control" name="world_db_password" id="world_db_password" placeholder="{#Installation_MySQL_Password#}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="world_db_encoding" class="control-label">{#Installation_MySQL_Encoding#}</label>
                            <input type="text" class="form-control" name="world_db_encoding" id="world_db_encoding" placeholder="{#Installation_MySQL_Encoding#}" value="UTF8" required />
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>{#Installation_SOAP_Header#}</legend>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="soap_host" class="control-label">{#Installation_SOAP_IP#}</label>
                            <input type="text" class="form-control" name="soap_host" placeholder="127.0.0.1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="soap_port" class="control-label">{#Installation_SOAP_Port#}</label>
                            <input type="text" class="form-control" name="soap_port" placeholder="7878" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="soap_sender_name" class="control-label">{#Installation_SOAP_Account#}</label>
                            <input type="text" class="form-control" name="soap_sender_name" placeholder="{#Installation_SOAP_Account#}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="soap_sender_pass" class="control-label">{#Installation_SOAP_Password#}</label>
                            <input type="password" class="form-control" name="soap_sender_pass" placeholder="{#Installation_SOAP_Password#}" required>
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>{#Installation_Site_Data#}</legend>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="site_name" class="control-label">{#Installation_Site_Title#}</label>
                            <input type="text" class="form-control" name="site_name" placeholder="FreedomCore" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="site_description" class="control-label">{#Installation_Site_Description#}</label>
                            <input type="text" class="form-control" name="site_description" placeholder="Best site... 4eva!!!!" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="site_keywords" class="control-label">{#Installation_Site_Keywords#}</label>
                            <input type="text" class="form-control" name="site_keywords" placeholder="WoW, FreedomCore, Darki73" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="site_tempalte" class="control-label">{#Installation_Site_Template#}</label>
                            <select class="form-control" id="select" name="site_tempalte" required>
                                <option value="WoD" selected="selected">Warlords of Draenor</option>
                                <option value="MoP">Mists of Pandaria</option>
                                <option value="Cata">Cataclysm</option>
                                <option value="WotLK">Wrath of the Lich King</option>
                                <option value="TBC">The Burning Crusade</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="Submit">{#Installation_Finalize_Install#}</button>
                    <button type="reset" class="btn btn-default">{#Installation_Site_Cancel#}</button>
                </div>
                <br />
            </form>
        </div>
    </div>
</div>
{include file = 'installation/step_footer.tpl'}