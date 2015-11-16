$(function() {
    Lobby.loadPaymentInfo();
    Lobby.loadSecurityInfo();
    Lobby.loadGameAccounts();
    Lobby.bindListLinks();
    Lobby.updateListLinks();
    Lobby.localizeTime();
});

var Lobby = {
    /*
     * Sends e-mail verification to server, shows confirmation box
     */
    sendEmailVerification: function(url) {
        $.ajax({
            type: "POST",
            url: url,
            beforeSend: function() {
                $("#email-sent-loading").hide();
                $("#email-sent-wait").show();
            },
            success: function() {
                $("#email-sent-wait").fadeOut(250, function() {
                    $("#email-sent-success").fadeIn(250);
                });
            },
            error: function() {
                $("#email-sent-wait").fadeOut(250, function() {
                    $("#email-sent-error").fadeIn(250);
                });
            }
        });
    },

    closeGameList: function(node, target) {
        target = $('#'+ target);
        node = $(node);

        target.slideUp(0);
        node.removeClass('opened').addClass('closed');

        return false;
    },

    toggleGameList: function(node, target) {
        var targetElement = $('#' + target),
            nodeElement = $(node);

        if (targetElement.is(':visible')) {
            Cookie.create('bam.' + target,'closed',{ expires: 336 });
            (!Core.isIE(6) && !Core.isIE(7)) ? targetElement.slideUp() : targetElement.toggle();
            nodeElement.removeClass('opened').addClass('closed');
        } else {
            Cookie.erase("bam." + target);
            (!Core.isIE(6) && !Core.isIE(7)) ? targetElement.slideDown() : targetElement.toggle();
            nodeElement.removeClass('closed').addClass('opened');
        }

        return false;
    },

    bindListLinks: function() {
        $("#games-list ul li a").unbind('click').bind({
            'click': function(e) {
                if (!$(this).hasClass('info'))
                    e.stopPropagation();
            }
        });
        $("#games-list ul li").unbind('click').bind({
            'click': function() {
                if (!$(this).hasClass('info')) {
                    Lobby.createDashboardLink(this);
                }
            }
        });

        $('.games-title').bind({
            'click': function() {
                return Lobby.toggleGameList(this, $(this).attr('rel'));
            }
        });
    },

    createDashboardLink: function(target) {
        var linkUrl = $(target).find('a').attr('href');
        if ( linkUrl !== undefined && linkUrl !== '') {
            document.location.href = linkUrl;
        }
    },

    updateListLinks: function() {
        if (Cookie.read('bam.game-list-d3') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-d3"]'), 'game-list-d3');
        }
        if (Cookie.read('bam.game-list-s2') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-s2"]'), 'game-list-s2');
        }
        if (Cookie.read('bam.game-list-wow') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-wow"]'), 'game-list-wow');
        }
        if (Cookie.read('bam.game-list-classic') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-classic"]'), 'game-list-classic');
        }
        if (Cookie.read('bam.game-list-battlenetapp') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-battlenetapp"]'), 'game-list-battlenetapp');
        }
        if (Cookie.read('bam.game-list-bas') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-bas"]'), 'game-list-bas');
        }
        if (Cookie.read('bam.game-list-hearthstone') == 'closed') {
            Lobby.closeGameList($('.games-title[rel="game-list-hearthstone"]'), 'game-list-hearthstone');
        }
    },

    loadPaymentInfo: function() {
        var unloaded = $('#lobby-account .payment-box.unloaded');

        if (!unloaded.length) {
            return false;
        }

        var length = unloaded.length;
        var i = length - 1;

        if (i >= 0) { do {
            Lobby.getPaymentDetails($(unloaded[i]));
        } while (i--);}
    },

    loadSecurityInfo : function() {
        var unloaded = $('#lobby-account .security-box.unloaded');

        if (!unloaded.length) {
            return false;
        }

        var length = unloaded.length;
        var i = length - 1;

        if (i >= 0) { do {
            Lobby.getSecurityDetails($(unloaded[i]));
        } while (i--);}
    },

    lightLoadGameAccounts: function() {
        var unloaded = $('#games-list ul li.unloaded');
        var length = unloaded.length;
        var i = length - 1;

        if (i >= 0) { do {
            $(unloaded[i]).removeClass('disabled');
        } while (i--);}
    },

    loadGameAccounts: function() {
        var unloaded = $('#games-list ul li.unloaded');
        var length = unloaded.length;
        var i = length - 1;
        var region = [];

        if (i >= 0) {
            do {
                region[i] = $(unloaded[i]).attr("id").split('::')[1];
            } while (i--);
        }

        if (region.length > 0) {
            region = $.unique(region);
            length = region.length;
            i = length - 1;

            if (i >= 0) {
                do {
                    if (region[i] == "NA") {
                        region[i] = "US";
                    }
                    Lobby.getAccountDetails(unloaded, region[i]);
                } while (i--);
            }
        }

    },

    getSecurityDetails: function(div) {
        var postData = { };

        $.ajax({
            type: 'POST',
            timeout: 60000,
            data: postData,
            url: Core.baseUrl + '/data/phone-secure-status.html',
            dataType: 'json',
            beforeSend: function() {
                $(div).removeClass('unloaded').addClass('loading');
            },
            success: function(msg) {
                if (msg.callInNumber !== undefined) {
                    if (msg.status === 'pending') {
                        $('#ps-user-number').html(msg.phoneNumber);
                        $('#ps-enroll-number').html(SecurityStrings.PENDING.part1 + ' ' + msg.callInNumber + ' ' + SecurityStrings.PENDING.part2);
                        $(this).removeClass('loading').removeClass('disabled');
                        $('#ps-edit').html(SecurityStrings.EDIT.cancel);
                        $('#ps-edit').parent('span.edit').addClass('edit-block');
                        $('#manage-security').remove();
                        $('#ps-status').show();
                    } else if (msg.status === 'active') {
                        $('#ps-user-number').html(msg.phoneNumber);
                        $('#ps-enroll-number').remove();
                        $(this).removeClass('loading').removeClass('disabled');
                        $('#ps-edit').html(SecurityStrings.EDIT.remove);
                        $('#manage-security').remove();
                        $('#ps-status').show();
                    }
                    $(div).animate({
                        opacity: 1
                    }, 250, function() {
                        $(this).removeClass('loading').removeClass('disabled');
                    });
                } else { // No Battle.net Dial-in Authenticator
                    $(div).animate({
                        opacity: 1
                    }, 250, function() {
                        $(this).removeClass('loading').removeClass('disabled');
                    });
                }
            },
            error: function() {
                $(div).removeClass('loading').addClass('unavailable').animate({
                    opacity: 1
                }, 250, function() {
                    $(this).removeClass('disabled').addClass('unavailable');
                });
                $(div).html('<h4 class="subcategory">' + SecurityStrings.ERROR.title + '</h4><p>' + SecurityStrings.ERROR.desc + '</p>');
            }
        });
    },

    getPaymentDetails: function(div) {
        var postData = { };

        $.ajax({
            type: 'POST',
            timeout: 60000,
            data: postData,
            url: Core.baseUrl + '/data/payment-details.html',
            dataType: 'json',
            beforeSend: function() {
                $(div).removeClass('unloaded').addClass('loading');
            },
            success: function(msg) {
                if (msg !== '') {
                    var paymentInfo = '';
                    if (msg.status === 'GOOD') {
                        if (msg.paymentType === 'CREDIT_CARD') {
                            paymentInfo = '<h4 class="subcategory">' + PaymentStrings.GOOD.CREDIT_CARD.title + '</h4><p>';
                            if (msg.label !== '') {
                                paymentInfo +=  msg.label + ' ';
                            } else {
                                paymentInfo += PaymentStrings.GOOD.CREDIT_CARD.label;
                            }
                            paymentInfo += '(' + PaymentStrings.GOOD.CREDIT_CARD.details.replace('PAYMENTSUBTYPE',msg.paymentSubType).replace('XXX',msg.number) + ') <span class="edit">[<a href="' + Core.baseUrl + '/management/edit-payment-method.html?id=' + msg.id + '">' + PaymentStrings.GOOD.CREDIT_CARD.button + '</a>]</span> ';
                            paymentInfo += ' <span class="help-note" onmouseover="Tooltip.show(this, \'' + PaymentStrings.GOOD.desc + '\');"><img height="16" width="16" src="' + Core.staticUrl + '/images/icons/tooltip-help.gif" alt="?" /></span></p>';
                        } else if (msg.paymentType === 'DIRECT_DEBIT') {
                            paymentInfo = '<h4 class="subcategory">' + PaymentStrings.GOOD.DIRECT_DEBIT.title + '</h4><p>';
                            if (msg.label !== '') {
                                paymentInfo +=  msg.label + ' (' + PaymentStrings.GOOD.DIRECT_DEBIT.label + ')';
                            } else {
                                paymentInfo += PaymentStrings.GOOD.DIRECT_DEBIT.label;
                            }
                            paymentInfo += ' <span class="edit">[<a href="' + Core.baseUrl + '/management/edit-payment-method.html?id=' + msg.id + '">' + PaymentStrings.GOOD.DIRECT_DEBIT.button + '</a>]</span>';
                            paymentInfo += ' <span class="help-note" onmouseover="Tooltip.show(this, \'' + PaymentStrings.GOOD.desc + '\');"><img height="16" width="16" src="' + Core.staticUrl + '/images/icons/tooltip-help.gif" alt="?" /></span></p>';
                        }
                        $(div).removeClass('loading').animate({
                            opacity: 1
                        }, 250, function() {
                            $(this).removeClass('disabled');
                            $(div).html(paymentInfo);
                        });
                    } else {
                        paymentInfo = '<p><em>' + PaymentStrings.NONE.desc + '</em>';
                        paymentInfo += '<br /><em><a href="' + Core.baseUrl + '/management/wallet.html">' + PaymentStrings.NONE.button + '</a></em></p>';
                        $(div).removeClass('loading').removeClass('disabled');
                        $(div).html(paymentInfo);
                    }
                } else {
                    $(div).removeClass('loading').addClass('unavailable').animate({
                        opacity: 1
                    }, 250, function() {
                        $(this).removeClass('disabled');
                    });
                    $(div).html('<h4 class="subcategory">' + PaymentStrings.ERROR.title + '</h4><p>' + PaymentStrings.ERROR.desc + '</p>');
                }
            },
            error: function() {
                $(div).removeClass('loading').addClass('unavailable').animate({
                    opacity: 1
                }, 250, function() {
                    $(this).removeClass('disabled').addClass('unavailable');
                });
                $(div).html('<h4 class="subcategory">' + PaymentStrings.ERROR.title + '</h4><p>' + PaymentStrings.ERROR.desc + '</p>');
            }
        });
    },

    getAccountDetails: function(unloaded, region) {
        region = region.trim();
        if (Turbo.enabled || region == "PTR") {
            // don't load detail in turbo mode, just highlight boxes as ready
            $(unloaded).removeClass('unloaded').animate({
                opacity: 1
            }, 250, function() {
                $(this).removeClass('disabled').removeClass('loading');
            });
        }else{

            var postData = { region: region };

            $.ajax({
                type: 'POST',
                timeout: 60000,
                url: Core.baseUrl + '/data/wow-licenses-details.html',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    $(unloaded).removeClass('unloaded').addClass('loading');
                },
                success: function(msg) {
                    if (msg === 'THROTTLED') {
                        // remove 'loading' icon from WoW accounts if request was throttled
                        $(".loading").each(function() {
                            if (this.id.indexOf('WoW') == 0) {
                                $(this).removeClass('loading');
                            }
                        });
                        return;
                    }

                    for (var i = 0, length = msg.length; i < length; i++) {
                        if (msg[i] !== '' && msg[i].status !== 'ERROR') {
                            var message = msg[i],
                                tip = '',
                                label = '',
                                icon = '',
                                boxLevel = 0,
                                boxID = '',
                                gameID = message.gameId,
                                accountName = message.accountName,
                                gameRegion = message.gameRegion;

                            var splitString = '::';
                            var liId = accountName + splitString + region;
                            var li = document.getElementById(liId);

                            if (gameRegion !== 'CN' && message.trial && message.trialDaysLeft === 0 && message.boxLevel > 0) {
                                boxLevel = message.boxLevel - 1;
                                if (boxLevel > 0) {
                                    boxID = 'WOWX' + boxLevel;
                                } else {
                                    boxID = 'WOWC';
                                }
                            } else {
                                boxID = gameID;
                            }
                            if (gameID === 'WOW_UNKNOWN') {
                                $(li).removeClass('loading').addClass('unavailable');
                            } else {
                                $(li).removeClass('loading').animate({
                                    opacity: 1
                                }, 250, function() {
                                    $(this).removeClass('disabled');
                                });
                            }
                            if (message.hasChargeback === true) {
                                $(li).removeClass('loading').addClass('chargeback').attr({
                                    'data-tooltip-options':'{"location": "mouse"}',
                                    'data-tooltip':chargebackTooltip['chargeback']
                                });
                            }
                            if (typeof(Promotion) != "undefined") {
                                if (Promotion.enabled && message.boxLevel < MaxBoxLevel[gameID]) {
                                    $(li).addClass('promotion-target');
                                }
                                if (Promotion.available && message.promoEligible && !$('#d3-account-link').is(':visible')) {
                                    $('#d3-community-link').hide();
                                    $('#d3-promotion-link').show();
                                }
                            }
                            $(li).unbind('click').bind({
                                'click': function() {
                                    Lobby.createDashboardLink(this);
                                }
                            });
                            $(li).find('img').attr('src', '/account/static/local-common/images/game-icons/' + boxID.toLowerCase() + '-32.png');
                            if (gameID !== 'WOW_UNKNOWN') {
                                $(li).find('a').text(GameId[boxID][0]);
                            }

                            if (gameID !== 'WOW_UNKNOWN') {
                                $(li).find('a').text(GameId[boxID][0]);
                            }
                            if (region !== 'PTR' && gameRegion !== 'NA') {
                                $(li).find('.account-region').html(GameRegions[message.gameRegion]);
                            }
                            if (message.starter) {
                                tip = IconTag['starterUpgrade'];
                                label = ' <span class="account-edition">(' + IconTag['starter'] + ')</span>';
                                icon = '<span class="flag upgrade" data-tooltip-options=\'{"location": "mouse"}\' data-tooltip="' + IconTag['starterUpgrade'] + '"></span>';
                            } else if (message.trial || message.trialDaysLeft > 0) {
                                label = ' <span class="account-edition">(' + IconTag['trial'] + ')</span>';
                                if (message.trialDaysLeft <= 0 && message.trialMinutesLeft <= 0) {
                                    icon = '<span class="flag upgrade" data-tooltip-options=\'{"location": "mouse"}\' data-tooltip="' + IconTag['trialExpired'] + '"></span>';
                                } else {
                                    if (message.trialDaysLeft === 1) {
                                        tip = IconTag['trialSingular'];
                                    } else if (message.trialMinutesLeft > 0) {
                                        tip = IconTag['trialPluralMinutes'].replace("XXX", message.trialMinutesLeft);
                                    } else {
                                        tip = IconTag['trialPlural'].replace("XXX", message.trialDaysLeft);
                                    }
                                    icon = '<span class="flag trial" data-tooltip-options=\'{"location": "mouse"}\' data-tooltip="' + tip + '"></span>';
                                }
                            } else if (message.boxLevel < MaxBoxLevel[gameID]) {
                                icon = '<span class="flag upgrade" data-tooltip-options=\'{"location": "mouse"}\' data-tooltip="' + IconTag['upgrade'] + '"></span>';
                            }
                            $(li).children('.game-icon').append(icon);
                            $(li).find('.account-id').append(label);
                        } else {
                            $(li).removeClass('loading').addClass('unavailable').unbind().bind({
                                'mouseover': function() {
                                    Tooltip.show(this, Maintenance.ERROR, {'location': 'mouse'});
                                },
                                'click': function() {
                                    return false;
                                }
                            }).find('a').attr('href', '#').css('cursor', 'default').unbind().bind({
                                'click': function() {
                                    return false;
                                }
                            });
                        }
                    }
                },
                error: function() {
                    $(li).removeClass('loading').addClass('unavailable').unbind().bind({
                        'mouseover': function() {
                            Tooltip.show(this, Maintenance.ERROR, {'location': 'mouse'});
                        },
                        'click': function() {
                            return false;
                        }
                    }).find('a').attr('href', '#').css('cursor', 'default').unbind().bind({
                        'click': function() {
                            return false;
                        }
                    });
                }
            });

        }
    },
    localizeTime: function() {
        var timeHolders = $(".time-localization");
        if (!timeHolders.length) {
            return false;
        }
        var format = 'dd-MM-yyyy HH:mm',
            locale = (Core.region === 'eu' && Core.locale === 'en-us') ? 'en-gb' : Core.locale;
        switch (locale) {
            default:
            case 'cs-cz':
            case 'de-de':
            case 'pl-pl':
                format = 'dd.MM.yyyy HH:mm';
                break;
            case 'en-us':
                format = 'MM/dd/yyyy hh:mm a';
                break;
            case 'en-gb':
            case 'es-es':
            case 'es-mx':
            case 'fr-fr':
            case 'pt-br':
            case 'it-it':
            case 'ru-ru':
                format = 'dd/MM/yyyy HH:mm';
                break;
            case 'en-sg':
                format = 'dd/MM/yyyy hh:mm a';
                break;
            case 'ja-ja':
            case 'ko-kr':
                format = 'yyyy/MM/dd HH:mm';
                break;
            case 'zh-cn':
                format = 'yyyyе№ґMMжњ€ddж—Ґ HH:mm';
                break;
            case 'zh-tw':
                format = 'yyyy-MM-dd HH:mm';
                break;
        }
        timeHolders.each(function() {
            var el = $(this);
            var formated = Core.formatDatetime(format, el.text());
            el.text(formated ? formated : el.text());
        });
    }

};

function openAddTrial() {
    $("#freeWow").slideDown(1000);
}

$(document).ready(function (){
    openAddTrial();
});