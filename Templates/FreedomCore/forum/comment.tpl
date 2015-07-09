<form id="new-post" action="/forum/topic/{$TopicData.topic.id}/post" novalidate="novalidate" method="post" class="topic-form ajax-update" data-max-post-length="50000">
    <fieldset>
        <input type="hidden" name="csrftoken" value="{$CSRFToken}" />
        <input type="hidden" name="sessionPersist" value="forum.topic.form" />
    </fieldset>
    <div class="form-left-col">
        <div class="form-title">
            <h3 class="header-3">{#Forum_Create_Topic#}</h3>
        </div>
        {include file = 'forum/forum_user.tpl'}
    </div>
    <div id="post-errors" class="topic-form-errors"></div>
    <div class="topic-form-wrapper">
        <div class="topic-form-controls">
            <a href="javascript:;" class="control-button preview-post" style="width: 130px;">{#Forum_Preview#}</a>
            <a href="javascript:;" class="control-button edit-post selected" style="width: 130px;">{#Forum_Edit_Topic#}</a>
        </div>
        <div id="post-edit">
            <input type="text" id="subject" name="subject" value class="post-subject" maxlength="55"/>
            <textarea id="commentTextArea" name="postCommand.detail" class="post-editor" maxlength="10000"></textarea>
            <div class="post-detail" id="post-preview"></div>
        </div>
        <table class="dynamic-center submit-post">
            <tr>
                <td>
                    <button class="ui-button button1" type="submit">
                                        <span class="button-left">
                                            <span class="button-right">
                                                {#Messages_Send#}
                                            </span>
                                        </span>
                    </button>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            //<![CDATA[
            $(function() {
                BML.initialize('#post-edit', false);
            });
            //]]>
        </script>
    </div>
    <span class="clear"></span>
</form>