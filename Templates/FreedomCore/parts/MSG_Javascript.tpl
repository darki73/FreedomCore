<script>
    //<![CDATA[
    var xsToken = '';
    var supportToken = '';
    var jsonSearchHandlerUrl = '//{$smarty.server.HTTP_HOST}';
    var Msg = Msg || {};
    Msg.support = {
        ticketNew: '{#MSG_Support_ticketNew#}',
        ticketStatus: '{#MSG_Support_ticketStatus#}',
        ticketOpen: '{#MSG_Support_ticketOpen#}',
        ticketAnswered: '{#MSG_Support_ticketAnswered#}',
        ticketResolved: '{#MSG_Support_ticketResolved#}',
        ticketCanceled: '{#MSG_Support_ticketCanceled#}',
        ticketArchived: '{#MSG_Support_ticketArchived#}',
        ticketInfo: '{#MSG_Support_ticketInfo#}',
        ticketAll: '{#MSG_Support_ticketAll#}'
    };
    Msg.cms = {
        requestError: '{#MSG_CMS_requestError#}',
        ignoreNot: '{#MSG_CMS_ignoreNot#}',
        ignoreAlready: '{#MSG_CMS_ignoreAlready#}',
        stickyRequested: '{#MSG_CMS_stickyRequested#}',
        stickyHasBeenRequested: '{#MSG_CMS_stickyHasBeenRequested#}',
        postAdded: '{#MSG_CMS_postAdded#}',
        postRemoved: '{#MSG_CMS_postRemoved#}',
        userAdded: '{#MSG_CMS_userAdded#}',
        userRemoved: '{#MSG_CMS_userRemoved#}',
        validationError: '{#MSG_CMS_validationError#}',
        characterExceed: '{#MSG_CMS_characterExceed#}',
        searchFor: '{#MSG_CMS_searchFor#}',
        searchTags: '{#MSG_CMS_searchTags#}',
        characterAjaxError: '{#MSG_CMS_characterAjaxError#}',
        ilvl: "{#MSG_CMS_ilvl#}",
        shortQuery: '{#MSG_CMS_shortQuery#}',
        editSuccess: '{#MSG_CMS_editSuccess#}',
        postDelete: '{#MSG_CMS_postDelete#}',
        throttleError: '{#MSG_CMS_throttleError#}',
    };
    Msg.bml= {
        bold: '{#MSG_BML_bold#}',
        italics: '{#MSG_BML_italics#}',
        underline: '{#MSG_BML_underline#}',
        list: '{#MSG_BML_list#}',
        listItem: '{#MSG_BML_listItem#}',
        quote: '{#MSG_BML_quote#}',
        quoteBy: '{#MSG_BML_quoteBy#}',
        unformat: '{#MSG_BML_unformat#}',
        cleanup: '{#MSG_BML_cleanup#}',
        code: '{#MSG_BML_code#}',
        item: '{#MSG_BML_item#}',
        itemPrompt: '{#MSG_BML_itemPrompt#}',
        url: '{#MSG_BML_url#}',
        urlPrompt: '{#MSG_BML_urlPrompt#}'
    };
    Msg.ui= {
        submit: '{#MSG_UI_submit#}',
        cancel: '{#MSG_UI_cancel#}',
        reset: '{#MSG_UI_reset#}',
        viewInGallery: '{#MSG_UI_viewInGallery#}',
        loading: '{#MSG_UI_loading#}',
        unexpectedError: '{#MSG_UI_unexpectedError#}',
        fansiteFind: '{#MSG_UI_fansiteFind#}',
        fansiteFindType: '{#MSG_UI_fansiteFindType#}',
        fansiteNone: '{#MSG_UI_fansiteNone#}',
        flashErrorHeader: '{#MSG_UI_flashErrorHeader#}',
        flashErrorText: '{#MSG_UI_flashErrorText#}',
        flashErrorUrl: '{#MSG_UI_flashErrorUrl#}',
        save: '{#MSG_UI_save#}'
    };
    Msg.grammar= {
        colon: '{#MSG_Grammar_colon#}',
        first: '{#MSG_Grammar_first#}',
        last: '{#MSG_Grammar_last#}',
        ellipsis: '...'
    };
    Msg.fansite= {
        achievement: '{#MSG_FanSite_achievement#}',
        character: '{#MSG_FanSite_character#}',
        faction: '{#MSG_FanSite_faction#}',
        'class': '{#MSG_FanSite_class#}',
        object: '{#MSG_FanSite_object#}',
        talentcalc: '{#MSG_FanSite_talentcalc#}',
        skill: '{#MSG_FanSite_skill#}',
        quest: '{#MSG_FanSite_quest#}',
        spell: '{#MSG_FanSite_spell#}',
        event: '{#MSG_FanSite_event#}',
        title: '{#MSG_FanSite_title#}',
        arena: '{#MSG_FanSite_arena#}',
        guild: '{#MSG_FanSite_guild#}',
        zone: '{#MSG_FanSite_zone#}',
        item: '{#MSG_FanSite_item#}',
        race: '{#MSG_FanSite_race#}',
        npc: '{#MSG_FanSite_npc#}',
        pet: '{#MSG_FanSite_pet#}'
    };
    Msg.search= {
        noResults: '{#MSG_Search_noResults#}',
        kb: '{#MSG_Search_kb#}',
        post: '{#MSG_Search_post#}',
        article: '{#MSG_Search_article#}',
        static: '{#MSG_Search_static#}',
        wowcharacter: '{#MSG_Search_wowcharacter#}',
        wowitem: '{#MSG_Search_wowitem#}',
        wowguild: '{#MSG_Search_wowguild#}',
        wowarenateam: '{#MSG_Search_wowarenateam#}',
        url: '{#MSG_Search_url#}',
        friend: '{#MSG_Search_friend#}',
        product: '{#MSG_Search_product#}',
        other: '{#MSG_Search_other#}'
    };
    //]]>
</script>
