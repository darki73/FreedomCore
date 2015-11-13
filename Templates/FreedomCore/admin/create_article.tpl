{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a> >
                    <a href="/admin/dashboard/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Title#}</span>
                    </a> >
                    <a href="/admin/articles/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Articles#}</span>
                    </a> >
                    <a href="/admin/articles/add" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Articles_Add#}</span>
                    </a>
                </h2>
                <br />
                <h3 class="headline">{#Administrator_Articles_Add#}</h3>
            </div>
            <div id="page-content" class="page-content">
                    <div class="columns-2-1 settings-content">
                        <form id="new-post" action="/admin/articles/create-article" novalidate="novalidate" method="post" class="topic-form ajax-update" data-max-post-length="50000">
                            <input type="hidden" name="imageName" id="imageName">
                            <div class="column column-left">
                                <div id="post-errors" class="topic-form-errors"></div>
                                <div class="topic-form-wrapper">
                                    <div class="topic-form-controls">
                                        <a href="javascript:;" class="control-button preview-post" style="width: 130px;">{#Administrator_Articles_Preview#}</a>
                                        <a href="javascript:;" class="control-button edit-post selected" style="width: 130px;">{#Administrator_Articles_Edit#}</a>
                                    </div>
                                    <div id="post-edit">
                                        <input type="text" id="subject" name="subject" value class="post-subject" maxlength="45"/>
                                        <textarea id="postCommand.detail" name="postCommand.detail" class="post-editor" maxlength="10000"></textarea>
                                        <div class="post-detail" id="post-preview"></div>
                                    </div>
                                    <table class="dynamic-center submit-post">
                                        <tr>
                                            <td>
                                                <button class="ui-button button1" type="submit">
                                                    <span class="button-left">
                                                        <span class="button-right">
                                                            {#Administrator_Articles_Post#}
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
                            </div>
                        </form>
                        <div class="column column-right">
                            <div class="article-data">
                                <div class="article-image-upload">
                                    <span style="font-size: 15px; color: black; font-weight: bold;">{#Administrator_Articles_AddImage#}</span>
                                    <div id="imagePreviewDiv" style="display: none;">

                                    </div>
                                    <form id="TMPImageForm" action="" method="post" enctype="multipart/form-data" style="margin-top: 10px;">
                                        <input name="file_upload" id="file_upload" type="file" required/><br />
                                        <center style="margin-top: 10px;">
                                            <button class="ui-button button1" onclick="return Administrator.tmp_article_image(this.parentNode.parentNode);">
                                            <span class="button-left">
                                                <span class="button-right">
                                                    {#Administrator_Articles_UploadImage#}
                                                </span>
                                            </span>
                                            </button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}