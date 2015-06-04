<script type="text/javascript">
    //<![CDATA[
    var MsgSummary = {
        inventory: {
            slots: {
                1: "Голова",
                2: "Шея",
                3: "Плечи",
                4: "Рубашка",
                5: "Грудь",
                6: "Пояс",
                7: "Ноги",
                8: "Ступни",
                9: "Запястья",
                10: "Руки",
                11: "Палец",
                12: "Аксессуар",
                15: "Дальний бой",
                16: "Спина",
                19: "Гербовая накидка",
                21: "Правая рука",
                22: "Левая рука",
                28: "Реликвия",
                empty: "Эта ячейка пуста"
            }
        },
        audit: {
            whatIsThis: "С помощью этой функции вы можете узнать, как улучшить характеристики своего персонажа. Функция ищет:<br /\><br /\>- пустые ячейки символов;<br /\>- неиспользованные очки талантов;<br /\>- незачарованные предметы;<br /\>- пустые гнезда для самоцветов;<br /\>- неподходящую броню;<br /\>- отсутствующую пряжку в поясе;<br /\>- отсутствующие бонусы за профессии.",
            missing: "Не хватает: {ldelim}0{rdelim}",
            enchants: {
                tooltip: "Не зачаровано"
            },
            sockets: {
                singular: "пустое гнездо",
                plural: "пустых гнезда"
            },
            armor: {
                tooltip: "Не {ldelim}0{rdelim}",
                1: "Ткань",
                2: "Кожа",
                3: "Кольчуга",
                4: "Латы"
            },
            lowLevel: {
                tooltip: "Низкий уровень"
            },
            blacksmithing: {
                name: "Кузнечное дело",
                tooltip: "Отсутствует гнездо"
            },
            enchanting: {
                name: "Наложение чар",
                tooltip: "Не зачаровано"
            },
            engineering: {
                name: "Инженерное дело",
                tooltip: "Нет улучшения"
            },
            inscription: {
                name: "Начертание",
                tooltip: "Не зачаровано"
            },
            leatherworking: {
                name: "Кожевенное дело",
                tooltip: "Не зачаровано"
            }
        },
        talents: {
            specTooltip: {
                title: "Специализация",
                primary: "Основная:",
                secondary: "Второстепенная:",
                active: "Активная"
            }
        },
        stats: {
            toggle: {
                all: "Показать все характеристики",
                core: "Показать только основные характеристики"
            },
            increases: {
                attackPower: "Увеличивает силу атаки на {ldelim}0{rdelim}.",
                critChance: "Увеличивает шанс критического удара {ldelim}0{rdelim}%.",
                spellCritChance: "Увеличивает шанс нанесения критического урона магией на {ldelim}0{rdelim}%.",
                spellPower: "Увеличивает силу заклинаний на {ldelim}0{rdelim}.",
                health: "Увеличивает здоровье на {ldelim}0{rdelim}.",
                mana: "Увеличивает количество маны на {ldelim}0{rdelim}.",
                manaRegen: "Увеличивает восполнение маны на {ldelim}0{rdelim} ед. каждые 5 сек., пока не произносятся заклинания.",
                meleeDps: "Увеличивает урон, наносимый в ближнем бою, на {ldelim}0{rdelim} ед. в секунду.",
                rangedDps: "Увеличивает урон, наносимый в дальнем бою, на {ldelim}0{rdelim} ед. в секунду.",
                petArmor: "Увеличивает броню питомца на {ldelim}0{rdelim} ед.",
                petAttackPower: "Увеличивает силу атаки питомца на {ldelim}0{rdelim} ед.",
                petSpellDamage: "Увеличивает урон от заклинаний питомца на {ldelim}0{rdelim} ед.",
                petAttackPowerSpellDamage: "Увеличивает силу атаки питомца на {ldelim}0{rdelim} ед. и урон от его заклинаний на {ldelim}1{rdelim} ед."
            },
            decreases: {
                damageTaken: "Снижает получаемый физический урон на {ldelim}0{rdelim}%.",
                enemyRes: "Снижает сопротивляемость противника на {ldelim}0{rdelim} ед.",
                dodgeParry: "Снижает вероятность того, что ваш удар будет парирован или от вашего удара уклонятся, на {ldelim}0{rdelim}%."
            },
            noBenefits: "Не предоставляет бонусов вашему классу.",
            beforeReturns: "(До снижения действенности повторяющихся эффектов)",
            damage: {
                speed: "Скорость атаки (сек.):",
                damage: "Урон:",
                dps: "Урон в сек.:"
            },
            averageItemLevel: {
                title: "Уровень предмета {ldelim}0{rdelim}",
                description: "Средний уровень вашего лучшего снаряжения. С его повышением вы сможете вставать в очередь в более сложные для прохождения подземелья."
            },
            health: {
                title: "Здоровье {ldelim}0{rdelim}",
                description: "Максимальный запас здоровья. Когда запас здоровья падает до нуля, вы погибаете."
            },
            mana: {
                title: "Мана {ldelim}0{rdelim}",
                description: "Максимальный запас маны. Мана расходуется на произнесение заклинаний."
            },
            rage: {
                title: "Ярость {ldelim}0{rdelim}",
                description: "Максимальный запас ярости. Ярость расходуется при применении способностей и накапливается, когда персонаж атакует врагов или получает урон."
            },
            focus: {
                title: "Концентрация {ldelim}0{rdelim}",
                description: "Максимальный уровень концентрации. Концентрация понижается при применении способностей и повышается со временем."
            },
            energy: {
                title: "Энергия {ldelim}0{rdelim}",
                description: "Максимальный запас энергии. Энергия расходуется при применении способностей и восстанавливается со временем."
            },
            runic: {
                title: "Сила рун {ldelim}0{rdelim}",
                description: "Максимальный запас силы рун."
            },
            strength: {
                title: "Сила {ldelim}0{rdelim}"
            },
            agility: {
                title: "Ловкость {ldelim}0{rdelim}"
            },
            stamina: {
                title: "Выносливость {ldelim}0{rdelim}"
            },
            intellect: {
                title: "Интеллект {ldelim}0{rdelim}"
            },
            spirit: {
                title: "Дух {ldelim}0{rdelim}"
            },
            mastery: {
                title: "Искусность {ldelim}0{rdelim}",
                description: "Рейтинг искусности {ldelim}0{rdelim} увеличивает значение искусности на {ldelim}1{rdelim}% ед.",
                unknown: "Вы должны сперва изучить искусность у учителя.",
                unspecced: "Выберите специализацию, чтобы активировать бонус рейтинга искусности. "
            },
            crit: {
                title: "Критический удар {ldelim}0{rdelim}%",
                description: "Вероятность нанести дополнительный урон и восстановить дополнительное здоровье.",
                description2: "Показатель критического удара: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            haste: {
                title: "Скорость +{ldelim}0{rdelim}%",
                description: "Увеличивает скорость атаки и применения заклинаний.",
                description2: "Скорость: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            meleeDps: {
                title: "Урон в секунду"
            },
            meleeAttackPower: {
                title: "Сила атаки в ближнем бою {ldelim}0{rdelim}"
            },
            meleeSpeed: {
                title: "Скорость атаки в ближнем бою {ldelim}0{rdelim}"
            },
            meleeHaste: {
                title: "Скорость в ближнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает скорость атаки на {ldelim}1{rdelim}%.",
                description2: "Увеличивает скорость атаки в ближнем бою."
            },
            meleeHit: {
                title: "Рейтинг меткости в ближнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает шанс попадания на {ldelim}1{rdelim}%."
            },
            meleeCrit: {
                title: "Рейтинг критического удара в ближнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает шанс нанести критический удар {ldelim}1{rdelim}%.",
                description2: "Шанс нанести дополнительный урон в ближнем бою."
            },
            expertise: {
                title: "Мастерство {ldelim}0{rdelim}",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает значение мастерства на {ldelim}1{rdelim} ед."
            },
            rangedDps: {
                title: "Урон в секунду"
            },
            rangedAttackPower: {
                title: "Сила атаки в дальнем бою {ldelim}0{rdelim}"
            },
            rangedSpeed: {
                title: "Скорость атаки в дальнем бою {ldelim}0{rdelim}"
            },
            rangedHaste: {
                title: "Скорость в дальнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает скорость атаки на {ldelim}1{rdelim}%.",
                description2: "Увеличивает скорость атаки в дальнем бою."
            },
            rangedHit: {
                title: "Рейтинг меткости в дальнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает шанс попадания на {ldelim}1{rdelim}%."
            },
            rangedCrit: {
                title: "Рейтинг критического удара в дальнем бою {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает шанс нанести критический удар {ldelim}1{rdelim}%.",
                description2: "Шанс нанести дополнительный урон в дальнем бою."
            },
            spellPower: {
                title: "Сила заклинаний {ldelim}0{rdelim}",
                description: "Увеличивает урон и исцеляющую силу заклинаний."
            },
            spellHaste: {
                title: "Скорость произнесения заклинаний {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает скорость произнесения заклинаний на {ldelim}1{rdelim}%.",
                description2: "Увеличивает скорость произнесения заклинаний."
            },
            spellHit: {
                title: "Вероятность попадания заклинанием {ldelim}0{rdelim}%",
                description: "Рейтинг меткости {ldelim}0{rdelim} увеличивает шанс попадания на {ldelim}1{rdelim}%."
            },
            spellCrit: {
                title: "Вероятность критического эффекта заклинания {ldelim}0{rdelim}%",
                description: "Рейтинг критического удара {ldelim}0{rdelim} увеличивает шанс нанести критический удар {ldelim}1{rdelim}%.",
                description2: "Шанс нанести заклинанием дополнительный урон или исцеление."
            },
            manaRegen: {
                title: "Восполнение маны",
                description: "{ldelim}0{rdelim} ед. маны восполняется раз в 5 сек. вне боя."
            },
            combatRegen: {
                title: "Восполнение в бою",
                description: "{ldelim}0{rdelim} ед. маны восполняется раз в 5 сек. в бою."
            },
            armor: {
                title: "Броня {ldelim}0{rdelim}"
            },
            dodge: {
                title: "Шанс уклонения {ldelim}0{rdelim}%",
                description: "Рейтинг {ldelim}0{rdelim} увеличивает шанс уклониться от удара на {ldelim}1{rdelim}%."
            },
            parry: {
                title: "Шанс парировать удар {ldelim}0{rdelim}%",
                description: "Рейтинг  {ldelim}0{rdelim} увеличивает шанс парировать удар на {ldelim}1{rdelim}%."
            },
            block: {
                title: "Шанс блокирования {ldelim}0{rdelim}%",
                description: "Рейтинг  {ldelim}0{rdelim} увеличивает шанс блокировать удар на {ldelim}1{rdelim}%.",
                description2: "Блокирование останавливает {ldelim}0{rdelim}% наносимого вам урона."
            },
            resilience: {
                title: "PvP-устойчивость {ldelim}0{rdelim}%",
                description: "Снижает урон, наносимого вам другими игроками и их питомцами или прислужниками.",
                description2: "Рейтинг устойчивости {ldelim}0{rdelim} (увеличивает значение устойчивости на {ldelim}1{rdelim}% ед.)"
            },
            pvppower: {
                title: "PvP-сила {ldelim}0{rdelim}%",
                description: "Увеличивает урон, наносимый игрокам и их питомцам и прислужникам, а также повышает эффективность лечения, применяемого в PvP-зонах.",
                description2: "Рейтинг силы {ldelim}0{rdelim}",
                description3: "+{ldelim}0{rdelim}% к лечению",
                description4: "+{ldelim}0{rdelim}% к урону"
            },
            arcaneRes: {
                title: "Сопротивление тайной магии {ldelim}0{rdelim}",
                description: "Снижает урон от тайной магии в среднем на {ldelim}0{rdelim}%."
            },
            fireRes: {
                title: "Сопротивление магии огня {ldelim}0{rdelim}",
                description: "Снижает урон от магии огня в среднем на {ldelim}0{rdelim}%."
            },
            frostRes: {
                title: "Сопротивление магии льдя {ldelim}0{rdelim}",
                description: "Снижает урон от магии льдя в среднем на {ldelim}0{rdelim}%."
            },
            natureRes: {
                title: "Сопротивление силам природы {ldelim}0{rdelim}",
                description: "Снижает урон от сил природы в среднем на {ldelim}0{rdelim}%."
            },
            shadowRes: {
                title: "Сопротивление темной магии {ldelim}0{rdelim}",
                description: "Снижает урон от темной магии в среднем на {ldelim}0{rdelim}%."
            },
            bonusArmor: {
                title: "Бонус брони {ldelim}0{rdelim}",
                description: "Общее снижение физического урона за счет брони: {ldelim}0{rdelim}%",
                description2: "Повышение силы атаки на {ldelim}0{rdelim}."
            },
            multistrike: {
                title: "Многократная атака {ldelim}0{rdelim}%",
                description: "Вероятность {ldelim}0{rdelim}% произвести дополнительную атаку или исцеление в размере {ldelim}1{rdelim}% от обычного объема, обсчитывается два раза.",
                description2: "Показатель многократной атаки: {ldelim}0{rdelim} [{ldelim}1{rdelim}%]"
            },
            leech: {
                title: "Cамоисцеление {ldelim}0{rdelim}%",
                description: "Часть нанесенного вами урона и произведенного исцеления возвращается вам в виде здоровья.",
                description2: "Cамоисцеление: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            versatility: {
                title: "Универсальность {ldelim}0{rdelim}%/{ldelim}1{rdelim}%",
                description: "Увеличивает наносимый урон и эффективность исцеления на {ldelim}0{rdelim}% и уменьшает получаемый урон на {ldelim}1{rdelim}%.",
                description2: "Показатель универсальности: {ldelim}0{rdelim} [{ldelim}1{rdelim}%/{ldelim}2{rdelim}%]"
            },
            avoidance: {
                title: "Избегание {ldelim}0{rdelim}%",
                description: "Уменьшает урон от заклинаний с действием по области.",
                description2: "Показатель избегания: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            }
        },
        recentActivity: {
            subscribe: "Подписаться на эту ленту новостей"
        },
        raid: {
            tooltip: {
                lfr: "(СПР)",
                flex: "(Гибкий)",
                normal: "(норм.)",
                heroic: "(героич.)",
                mythic: "(эпохальный)",
                complete: "{ldelim}0{rdelim}% завершено ({ldelim}1{rdelim}/{ldelim}2{rdelim})",
                optional: "(на выбор)"
            }
        },
        links: {
            tools: "Инструментарий",
            saveImage: "Сохранить изображение персонажа",
            saveimageTitle: "Сохранить изображение персонажа для дальнейшего использования на кредитной карте World of Warcraft Rewards Visa."
        }
    };
    //]]>
</script>

<script>
    //<![CDATA[
    var xsToken = '';
    var supportToken = '';
    var jsonSearchHandlerUrl = '//eu.battle.net';
    var Msg = Msg || {};
    Msg.support = {
        ticketNew: 'Открыт запрос № {ldelim}0{rdelim}.',
        ticketStatus: 'Запросу № {ldelim}0{rdelim} присвоен статус «{1}».',
        ticketOpen: 'Открыт',
        ticketAnswered: 'Дан ответ',
        ticketResolved: 'Разрешен',
        ticketCanceled: 'Отменен',
        ticketArchived: 'Перемещен в архив',
        ticketInfo: 'Уточнить',
        ticketAll: 'Все запросы'
    };
    Msg.cms = {
        requestError: 'Ваш запрос не может быть завершен.',
        ignoreNot: 'Этот пользователь не в черном списке.',
        ignoreAlready: 'Этот пользователь уже в черном списке.',
        stickyRequested: 'Отправлена просьба прикрепить тему.',
        stickyHasBeenRequested: 'Вы уже отправили просьбу прикрепить эту тему.',
        postAdded: 'Сообщение отслеживается',
        postRemoved: 'Сообщение больше не отслеживается',
        userAdded: 'Сообщения пользователя отслеживаются',
        userRemoved: 'Сообщения пользователя больше не отслеживается',
        validationError: 'Обязательное поле не заполнено',
        characterExceed: 'В сообщении превышено допустимое число символов.',
        searchFor: "Поиск по",
        searchTags: "Помеченные статьи:",
        characterAjaxError: "Возможно, вы вышли из системы. Обновите страницу и повторите попытку.",
        ilvl: "Уровень {ldelim}0{rdelim}",
        shortQuery: "Запрос для поиска должен состоять не менее чем из двух букв.",
        editSuccess: "Success. Reload?",
        postDelete: "Вы точно хотите удалить это сообщение?",
        throttleError: "Вы должны подождать некоторое время, прежде чем вы сможете опубликовать новое сообщение."
    };
    Msg.bml= {
        bold: 'Полужирный',
        italics: 'Курсив',
        underline: 'Подчеркивание',
        list: 'Несортированный список',
        listItem: 'Список',
        quote: 'Цитирование',
        quoteBy: 'Размещено {ldelim}0{rdelim}',
        unformat: 'Отменить форматирование',
        cleanup: 'Исправить переносы строки',
        code: 'Код',
        item: 'Предмет WoW',
        itemPrompt: 'Идентификатор предмета:',
        url: 'Ссылка',
        urlPrompt: 'Ссылка на страницу:'
    };
    Msg.ui= {
        submit: 'Отправить',
        cancel: 'Отмена',
        reset: 'Сброс',
        viewInGallery: 'Галерея',
        loading: 'Подождите, пожалуйста.',
        unexpectedError: 'Произошла ошибка.',
        fansiteFind: 'Найти на…',
        fansiteFindType: '{ldelim}0{rdelim}: поиск на…',
        fansiteNone: 'Нет доступных сайтов.',
        flashErrorHeader: 'Необходимо установить Adobe Flash Player.',
        flashErrorText: 'Загрузить Adobe Flash Player',
        flashErrorUrl: 'http://get.adobe.com/flashplayer/',
        save: 'Сохранить'
    };
    Msg.grammar= {
        colon: '{ldelim}0{rdelim}:',
        first: 'Первая стр.',
        last: 'Последняя стр.',
        ellipsis: '…'
    };
    Msg.fansite= {
        achievement: 'Достижение',
        character: 'Персонаж',
        faction: 'Фракция',
        'class': 'Класс',
        object: 'Объект',
        talentcalc: 'Таланты',
        skill: 'Профессия',
        quest: 'Задание',
        spell: 'Заклинания',
        event: 'Событие',
        title: 'Звание',
        arena: 'Команда Арены',
        guild: 'Гильдия',
        zone: 'Территория',
        item: 'Предмет',
        race: 'Раса',
        npc: 'НПС',
        pet: 'Питомец'
    };
    Msg.search= {
        noResults: 'Нет результатов для отображения',
        kb: 'Поддержка',
        post: 'Форумы',
        article: 'Статьи',
        static: 'Материалы',
        wowcharacter: 'Персонаж',
        wowitem: 'Предмет',
        wowguild: 'Гильдии',
        wowarenateam: 'Команды Арены',
        url: 'Вам может быть интересно',
        friend: 'Друзья',
        product: 'Продукция',
        other: 'Другое'
    };
    //]]>
</script>
