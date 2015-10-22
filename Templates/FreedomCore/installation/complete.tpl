{include file = 'installation/step_header.tpl'}
<div id="wrap">
    <div class="container">
        <script>
            var Success = "{#Installation_Final_Success#}";
            var Failed = "{#Installation_Final_Failed#}";
            var Wait = "{#Installation_Final_Please_Wait#}";
        </script>
        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-12">
                    <h1>{#Installation_Setup_HC#}</h1>
                    <p class="lead">{#Installation_Setup_HCF#}</p>
                </div>
            </div>
        </div>
        <header>
            <h2>{#Installation_Finalizing#}</h2>
        </header>
        <div class="alert alert-success">
            <strong>{#Installation_Final_Config_Creation#}</strong><br /> {#Installation_Final_Success#}
        </div>
        <div class="alert alert-success" id="database_connection" style="display: none;">
            <strong>{#Installation_Final_Database_Connection#}</strong><br /> <span id="database_connection_status"></span>
        </div>
        <div class="alert alert-success" id="database_import" style="display: none;">
            <strong>{#Installation_Final_Database_Import#}</strong><br /> <span id="database_import_status"></span>
        </div>
        <center>
            <a href="/data/ifandrename" class="btn btn-primary" id="finalize_install" style="display: none;"> {#Installation_Final_Complete#}</a>
        </center>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function() { PerformAjaxRequest('try_database', 'database_connection', 'database_connection_status', 500, false); }, 3000);
    setTimeout(function() { PerformAjaxRequest('import_database', 'database_import', 'database_import_status', 500, 10000); }, 5000);

    function PerformAjaxRequest(action, block, status, delay, preshow){
        if(preshow != false){
            $('#'+status+'').html(Wait);
            $('#'+block+'').fadeIn(delay);
            $('#'+block+'').removeClass('alert-success');
            $('#'+block+'').addClass('alert-warning');
            var FadeOut = preshow - delay*3;
            $('#'+block+'').fadeOut(FadeOut);
            $('#'+block+'').removeClass('alert-warning');
            $('#'+block+'').addClass('alert-success');
        }
        $.ajax({
            type: "POST",
            url: '/Install/ajax',
            data: ({ action : action}),
            success: function(response) {
                if(response == '1')
                    if(preshow != false){
                        $('#'+block+'').stop().fadeOut();
                        $('#'+block+'').hide();
                        $('#finalize_install').fadeIn(500);
                    }
                    $('#'+status+'').html('');
                    $('#'+status+'').html(Success);
                    $('#'+block+'').fadeIn(delay);
            },
            error: function() {
                $('#'+status+'').html(Failed);
            }
        });
    }
</script>
{include file = 'installation/step_footer.tpl'}