<div class="section secondary-section " id="installation">
    <div class="triangle"></div>
    <div class="container">
        {if $Software.allow}
            <div class=" title">
                <h1>{#Installation_Allowed#}</h1>
                <p>{#Installation_Allowed_Desc#}</p>
            </div>
            <center><h3>{#Installation_MySQL_Settings#}</h3></center>
            <form id="database_settings" onsubmit="return Installation.configcreate();">
                <div class="row-fluid">
                    <div class="span4">
                        <center><h4>Auth</h4></center>
                        <input class="span12" type="text" id="auth_host" name="auth_host" placeholder="{#Installation_MySQL_Host#}">
                        <input class="span12" type="text" id="auth_db" name="auth_db" placeholder="{#Installation_MySQL_DB#}">
                        <input class="span12" type="text" id="auth_user" name="auth_user" placeholder="{#Installation_MySQL_User#}">
                        <input class="span12" type="password" id="auth_password" name="auth_password" placeholder="{#Installation_MySQL_Password#}">
                        <input class="span12" type="text" id="auth_encoding" name="auth_encoding" value="UTF8" placeholder="{#Installation_MySQL_Encoding#}">
                    </div>
                    <div class="span4">
                        <center><h4>Characters</h4></center>
                        <input class="span12" type="text" id="characters_host" name="characters_host" placeholder="{#Installation_MySQL_Host#}">
                        <input class="span12" type="text" id="characters_db" name="characters_db" placeholder="{#Installation_MySQL_DB#}">
                        <input class="span12" type="text" id="characters_user" name="characters_user" placeholder="{#Installation_MySQL_User#}">
                        <input class="span12" type="password" id="characters_password" name="characters_password" placeholder="{#Installation_MySQL_Password#}">
                        <input class="span12" type="text" id="characters_encoding" name="characters_encoding" value="UTF8" placeholder="{#Installation_MySQL_Encoding#}">
                    </div>
                    <div class="span4">
                        <center><h4>World</h4></center>
                        <input class="span12" type="text" id="world_host" name="world_host" placeholder="{#Installation_MySQL_Host#}">
                        <input class="span12" type="text" id="world_db" name="world_db" placeholder="{#Installation_MySQL_DB#}">
                        <input class="span12" type="text" id="world_user" name="world_user" placeholder="{#Installation_MySQL_User#}">
                        <input class="span12" type="password" id="world_password" name="world_password" placeholder="{#Installation_MySQL_Password#}">
                        <input class="span12" type="text" id="world_encoding" name="world_encoding" value="UTF8" placeholder="{#Installation_MySQL_Encoding#}">
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4"></div>
                    <div class="span4">
                        <center><h4>Website</h4></center>
                        <input class="span12" type="text" id="website_host" name="website_host" placeholder="{#Installation_MySQL_Host#}">
                        <input class="span12" type="text" id="website_db" name="website_db" placeholder="{#Installation_MySQL_DB#}">
                        <input class="span12" type="text" id="website_user" name="website_user" placeholder="{#Installation_MySQL_User#}">
                        <input class="span12" type="password" id="website_password" name="website_password" placeholder="{#Installation_MySQL_Password#}">
                        <input class="span12" type="text" id="website_encoding" name="website_encoding" value="UTF8" placeholder="{#Installation_MySQL_Encoding#}">
                    </div>
                    <div class="span4"></div>
                </div>
                <hr>
                <div class="centered">
                    <p class="price-text">{#Installation_Perform_Not_Checking#}</p>
                    <button type="submit" class="button">{#Installation_Perform_Installation#}</button>
                </div>
            </form>
        {else}
            <div class=" title">
                <h1>{#Installation_Check_Failed#}</h1>
                <p>{#Installation_Check_Failed_Desc#}</p>
            </div>
        {/if}
    </div>
</div>

<div class="section primary-section" id="filestoimport" style="display:none;">
    <div class="container">
        <div class=" title">
            <br />
            <h1>{#Installation_FilesToImport_Header#}</h1>
            <p>{#Installation_FileToImport_Desc#}<br />{#Installation_HowToImport#}</p>
        </div>
        <div class="row-fluid">
            {assign 'RowsCount' '0'}
            {assign 'FilesPerBlock' '0'}
            {$FilesPerBlock = (($FilesToImport|count)/3)|ceil}
            {foreach from=$FilesToImport item=File key=i}
                {if $RowsCount == 0}
                    <div class="span4">
                        <a onclick="return Installation.import('{$File.FileName}', '{$File.FileName|replace:'.sql':''}');" class="fileimportlink" id='{$File.FileName|replace:'.sql':''}'>{$File.FileName}</a> <span id="installation_status_{$File.FileName|replace:'.sql':''}"></span><br />
                        {$RowsCount = $RowsCount +1}
                {elseif $RowsCount < $FilesPerBlock-1}
                        <a onclick="return Installation.import('{$File.FileName}', '{$File.FileName|replace:'.sql':''}');" class="fileimportlink" id='{$File.FileName|replace:'.sql':''}'>{$File.FileName}</a> <span id="installation_status_{$File.FileName|replace:'.sql':''}"></span><br />
                        {$RowsCount = $RowsCount +1}
                {elseif $RowsCount == $FilesPerBlock-1}
                        <a onclick="return Installation.import('{$File.FileName}', '{$File.FileName|replace:'.sql':''}');" class="fileimportlink" id='{$File.FileName|replace:'.sql':''}'>{$File.FileName}</a> <span id="installation_status_{$File.FileName|replace:'.sql':''}"></span>
                        {$RowsCount = 0}
                    </div>
                {/if}
            {/foreach}
        </div>
    </div>
    <div class="container">
        <div class=" title"></div>
        <div class=" title">
            <h1>{#Installation_Attention#}</h1>
            <p>{#Installation_Manual_Import#}</p>
        </div>
        <center>
            <h3>
                {#Installation_Finish#}
            </h3>
        </center>
    </div>
</div>