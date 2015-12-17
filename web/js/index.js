/**
 * Created by Alex on 12.12.2015.
 */

$(document).ready(function () {
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    //  переменные для графиков
    var data2 = [];
    var data3 = [];
    var data4 = [];
    var headerPieChart = '';
    var container = '';
    var userName;
    var userFamily;

    loadDB();
    //  Подгрузка данных из БД
    function loadDB() {
        if (getCookie('memberId')) {
            showListTransaction();
            showListCategory();
            showListMember();
            showlistTransactionType();
            report1();
        }
    }

    //  предотвращение множественного нажатия
    function prevSpamming(target, time) {
        setTimeout(
            function () {
                $('' + target + '').attr('disabled', false);
            }, time);
        $('' + target + '').attr('disabled', true);
    }

    //  Уведомление о выходе из системы
    $('.logOut').click(function (e) {
        var questConfirm = confirm("Вы уверены, что хотите выйти?");
        if (!questConfirm) {
            e.preventDefault();
        }
    });

    //  Если нет memberId в куках скрыть экран и отобразить авторизацию
    $(document).ready(function () {
        if (!getCookie('memberId')) {
            $('.shirm, .auth').toggleClass("hide")
        }
    });

    //  Авторизация пользователя
    $('.agreeLogin').on("click", function () {
        prevSpamming('.agreeLogin', 2000);
        var login = $('#form_login').val();
        var password = $('#form_password').val();
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/auth",
            data: {login: login, password: password},
            success: function (msg) {
                if (msg == 'Ok!') {
                    $('.showLogin, .shirm, .auth')
                        .fadeToggle("slow");
                    loadDB();
                } else {
                    alert('Неверный логин или пароль!');
                }
            }
        });
    });

    //  Регистрация пользователя
    $('.AgreeRegistration').on("click", function () {
        prevSpamming('.AgreeRegistration', 2000);
        var surname = $('#form_surname').val();
        var name = $('#form_name').val();
        var secondname = $('#form_secondname').val();
        var password = $('#form_password2').val();
        var login = $('#form_login2').val();
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/registration",
            data: {
                name: name,
                surname: surname,
                secondname: secondname,
                password: password,
                login: login
            },
            success: function (msg) {

                if (msg == 'Ok!') {
                    $('.showRegistration, .shirm, .auth')
                        .fadeToggle("slow");
                    showListMember();
                    showListTransaction()
                } else {
                    if (msg == 'Login already exist!') {
                        alert('Такой логин уже существует!');
                    } else {
                        alert('Некорректные данные!');
                    }
                }
            }
        });
    });

    //  Отобразить форму login
    $('.login').on("click", function () {
        $('.showLogin').fadeToggle("slow");
    });

    //  Отобразить форму registration
    $('.reg').on("click", function () {
        $('.showRegistration').fadeToggle("slow");
    });


    //  Отображение формы: Создание нового члена семьи
    $('.buttonNewMember').on("click", function () {
        $('.newMember, .shirm').fadeToggle("slow");
        $('#form_surname4').val(userFamily);
    });

    //  Создание нового члена семьи
    $('.agreeNewMember').on("click", function () {
        prevSpamming('.agreeNewMember', 2000);
        var surname = $('#form_surname4').val();
        var name = $('#form_name4').val();
        var secondname = $('#form_secondname4').val();
        var password = $('#form_password4').val();
        var login = $('#form_login4').val();
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newMember",
            data: {
                name: name,
                surname: surname,
                secondname: secondname,
                password: password,
                login: login
            },
            success: function (msg) {

                if (msg == 'Ok!') {
                    $('.newMember, .shirm')
                        .fadeToggle("slow");
                    $('.membersList').html('');
                    showListMember();
                } else {
                    if (msg == 'Login already exist!') {
                        alert('Такой логин уже существует!');
                    } else {
                        alert('Некорректные данные!');
                    }
                }
            }
        });
    });

    //  Отображение списка членов семьи
    function showListMember() {
        $.ajax({
            type: "get",
            url: "/web/app_dev.php/listMember",
            success: function (msg) {
                var familyId = getCookie('familyId');

                //  Обнулим все списки членов семьи
                $('.membersList, .chooseMember, .chooseMember1, .chooseMember2').html('');

                //  Заполнение выпадающих списков, первой опицей ставится семья,
                if (familyId) {
                    $('.chooseMember, .chooseMember1, .chooseMember2')
                        .append(
                            '<option selected value="familyId=' + familyId + '">Семья' +
                            '</option>'
                        );
                }


                if (msg)jQuery.parseJSON(msg).forEach(function (val, i) {

                    //  Заполнение строки wellcome user!
                    if (val.memberId == getCookie('memberId')) {
                        wellcome = 'Добро пожаловать, ' + val.name + '!';
                        $('.wellcome').text(wellcome);
                        userFamily = val.surname;
                        userName = val.name;
                    }

                    //  Заполнение выпадающего списка
                    $('.chooseMember, .chooseMember1, .chooseMember2')
                        .append(
                            '<option value="memberId=' + val.memberId + '">' + val.name + '' +
                            '</option>'
                        );

                    //  Заполнение списка в таблице memberList
                    //  она располагается в правом верхнем углу экрана и отображает всех членов семьи
                    $('.membersList')
                        .append(
                            '<p class="stringOfMemberList" >'
                            + val.surname + ' ' + val.name + ' ' + val.secondname + ' ' +
                            '<button name="' + val.memberId + '" class="buttonChangeMember">' +
                            '<img class="buttons" src="/web/images/Pencil-24.png"/>' +
                            '</button> ' +
                            '<button name="' + val.memberId + '" class="deleteMember">' +
                            '<img class="buttons" src="/web/images/Delete-24.png"/>' +
                            '</button>' +
                            '</p>'
                        );

                });
            }
        });
    }

    //  Удаление члена семьи
    $('.membersList').on("click", " .deleteMember", function (event) {
        var memberId = $(event.target).closest('button.deleteMember')[0].name;
        var stringMember = $(event.target).closest('p.stringOfMemberList');
        var questConfirm = confirm("Вы уверены, что хотите удалить?");
        if (!questConfirm) {
            e.preventDefault();
        } else {
            $.ajax({
                type: "POST",
                data: {memberId: memberId},
                url: "/web/app_dev.php/deleteMember",
                success: function (msg) {
                    stringMember.remove();
                    if (msg == 'Bye!') {
                        location.reload();
                    }
                    showListMember();
                }
            });
        }
    });

    //  Изменение личных данных члена семьи
    $('.membersList').on("click", " .buttonChangeMember", function (event) {
        var memberId = $(event.target).closest('button.buttonChangeMember')[0].name;
        prevSpamming('.buttonChangeMember', 2000);

        $.ajax({
            type: "POST",
            data: {memberId: memberId},
            url: "/web/app_dev.php/showMember",
            success: function (msg) {

                $('.changeMember, .shirm')
                    .fadeToggle("slow");

                jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('#form_surname5').val(val.surname);
                    $('#form_name5').val(val.name);
                    $('#form_secondname5').val(val.secondname);
                    $('#form_login5').val(val.login);
                    $('#form_password5').val(null);
                });

                $('.agreeChangeMember').one("click", function () {
                    prevSpamming('.agreeChangeMember', 2000);
                    surname = $('#form_surname5').val();
                    name = $('#form_name5').val();
                    secondname = $('#form_secondname5').val();
                    password = $('#form_password5').val();
                    login = $('#form_login5').val();
                    $.ajax({
                        type: "POST",
                        data: {
                            memberId: memberId,
                            name: name,
                            surname: surname,
                            secondname: secondname,
                            login: login,
                            password: password
                        },
                        url: "/web/app_dev.php/changeMember",
                        success: function (msg) {
                            if (msg == 'Ok!') {
                                showListMember();
                                showListTransaction();
                                $('.changeMember, .shirm').fadeToggle('slow');
                            } else {
                                alert('Некорректные данные!');
                            }
                        }
                    });
                });
            }
        });

    });


    //  Добавляем новый ТИП транзакции
    $('.agreeType').on("click", function () {
        var typeName = $('#form_typeName').val();
        var type = $('#form_type').val();

        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newTransactionType",
            data: {typeName: typeName, type: type},
            success: function (msg) {
                if (msg == 'Ok!') {
                    showlistTransactionType();
                } else {
                    if (msg == 'Bad inputs!') {
                        alert('Некооректные данные!');
                    } else {
                        alert('Такой тип уже существует!');
                    }
                }
            }
        });
    });

    //  Отображение списка ТИПОВ транзакций
    function showlistTransactionType() {
        var memberId = getCookie('memberId');
        var familyId = getCookie('familyId');
        $.ajax({
            type: "POST",
            data: {memberId: memberId, familyId: familyId},
            url: "/web/app_dev.php/listTransactionType",
            success: function (msg) {

                $('.typesList, #form_chooseType, #form_chooseType2').html('');
                if (msg !== 'Bad!')jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('.typesList').append('<p data-id="' + val.typeId + '" data-typeName="' + val.typeName + '" class="' + val.type + ' diagramTransactionType"><button data-id="' + val.typeId + '" class="deleteType"><img class="buttons" src="/web/images/Delete-24.png"></button><button data-id="' + val.typeId + '" class="changeType"><img class="buttons" src="/web/images/Pencil-24.png"></button>  ' + val.typeName + '</p>');
                    $('#form_chooseType, #form_chooseType2').append('<option data-id="' + val.typeId + '" data-typeName="' + val.typeName + '" class="' + val.type + '">' + val.typeName + '</option>');
                });
            }
        });
    }

    //  Удаление ТИПА транзакции
    $('.typesList').on("click", " .deleteType", function (event) {
        var typeId = $(event.target).closest('button.deleteType').data('id');
        var stringCategory = $(event.target).closest('p');

        $.ajax({
            type: "POST",
            data: {typeId: typeId},
            url: "/web/app_dev.php/deleteTransactionType",
            success: function (msg) {
                showlistTransactionType()
            }
        });
    });


    //  Изменение ТИПА транзакции
    $('.type').on("click", " .changeType", function (event) {
        $('.formChangeType, .shirm').fadeToggle("slow");
        var typeId = $(event.target).closest('button.changeType').data('id');
        prevSpamming('.changeType', 2000);

        $.ajax({
            type: "POST",
            data: {typeId: typeId},
            url: "/web/app_dev.php/showTransactionType",
            success: function (msg) {

                val = jQuery.parseJSON(msg)[0];

                $('#form_changeTypeName').val(val.typeName);
                if (val.type) {
                    $('#form_changeType').val("true");
                } else {
                    $('#form_changeType').val("false");
                }

                $('.agreeChangeType').one("click", function () {
                    prevSpamming('.agreeChangeType', 2000);
                    var typeName = $('#form_changeTypeName').val();
                    var type = $('#form_changeType').val();
                    $.ajax({
                        type: "POST",
                        url: "/web/app_dev.php/changeTransactionType",
                        data: {
                            typeName: typeName,
                            type: type,
                            typeId: typeId
                        },
                        success: function (msg) {
                            if (msg == 'Ok!') {
                                $('.formChangeType,.shirm')
                                    .fadeToggle("slow");
                                showlistTransactionType();
                                showListTransaction();
                            } else {
                                alert('Некорректные данные!');
                            }
                        }
                    });
                });
            }
        });
    });

    //  Добавляем новую категорию
    $('.agreeCategory').on("click", function () {
        var categoryName = $('#form_category').val();
        var categoryType = $('#form_typeCategory').val();
        var familyId = getCookie('familyId');
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newCategory",
            data: {categoryName: categoryName, categoryType: categoryType, familyId: familyId},
            success: function (msg) {
                if (msg == 'Ok!') {
                    showListCategory();
                } else {
                    if (msg == 'Bad inputs!') {
                        alert('Некооректные данные!');
                    } else {
                        alert('Такая категория уже существует!');
                    }
                }
            }
        });
    });

    //  Отображение списка категорий
    function showListCategory() {
        var familyId = getCookie('familyId');
        $.ajax({
            type: "POST",
            data: {familyId: familyId},
            url: "/web/app_dev.php/listCategory",
            success: function (msg) {

                $('.categoryList, #form_chooseCategory, #form_chooseCategory2').html('');
                if (msg !== 'Bad!')jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('.categoryList').append('<p data-id="' + val.categoryId + '" data-categoryName="' + val.categoryName + '" class="diagramCategory"><button data-id="' + val.categoryId + '" class="deleteCategory"><img class="buttons" src="/web/images/Delete-24.png"></button><button data-id="' + val.categoryId + '" class="changeCategory"><img class="buttons" src="/web/images/Pencil-24.png"></button>  ' + val.categoryName + '</p>');
                    $('#form_chooseCategory, #form_chooseCategory2').append('<option data-id="' + val.categoryId + '">' + val.categoryName + '</option>');
                });
            }
        });
    }

    //  Удаление категории
    $('.categoryList').on("click", " .deleteCategory", function (event) {
        var categoryId = $(event.target).closest('button.deleteCategory').data('id');
        var stringCategory = $(event.target).closest('p');

        $.ajax({
            type: "POST",
            data: {categoryId: categoryId},
            url: "/web/app_dev.php/deleteCategory",
            success: function (msg) {
                showListCategory();
            }
        });
    });


    //  Изменение категории
    $('.categoryList').on("click", " .changeCategory", function (event) {
        $('.formChangeCategory, .shirm').fadeToggle("slow");
        var categoryId = $(event.target).closest('button.changeCategory').data('id');
        prevSpamming('.changeCategory', 2000);

        $.ajax({
            type: "POST",
            data: {categoryId: categoryId},
            url: "/web/app_dev.php/showCategory",
            success: function (msg) {

                $('#form_changeCategory').val(jQuery.parseJSON(msg)[0].categoryName);
                $('#form_typechangeCategory').val(jQuery.parseJSON(msg)[0].categoryType);
                $('.agreeChangeCategory').one("click", function () {
                    prevSpamming('.agreeChangeCategory', 2000);
                    var categoryName = $('#form_changeCategory').val();
                    var categoryType = $('#form_typechangeCategory').val();
                    var memberId = getCookie('memberId');
                    var familyId = getCookie('familyId');
                    $.ajax({
                        type: "POST",
                        url: "/web/app_dev.php/changeCategory",
                        data: {
                            categoryName: categoryName,
                            categoryType: categoryType,
                            memberId: memberId,
                            familyId: familyId,
                            categoryId: categoryId
                        },
                        success: function (msg) {
                            if (msg == 'Ok!') {
                                $('.formChangeCategory,.shirm')
                                    .fadeToggle("slow");
                                showListCategory();
                                showListTransaction();
                            } else {
                                alert('Некорректные данные!');
                            }
                        }
                    });
                });
            }
        });
    });

    //  Добавляем новую транзакцию
    $('.agreeTransaction').on("click", function (event) {
        prevSpamming('.agreeTransaction', 1500);
        var categoryId = $('.transSelect option:selected ').data('id');
        var typeId = $('.transSelect2 option:selected ').data('id');
        var type = $('transSelect').data('type');
        var sum = $('#form_chooseSum').val();
        var date = $('#datepickerTransaction').val();

        var arr = $('.chooseMember').val().split(/=/);
        var params = {};
        params[arr[0]] = arr[1];
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newTransaction",
            data: {
                categoryId: categoryId,
                typeId: typeId,
                sum: sum,
                date: date
            },
            success: function (msg) {
                if (msg == 'Ok!') {
                    showListTransaction()
                } else {
                    alert('Некорректные данные!');
                }
            }
        });
    });

    //  Отображение списка транзакций пользователя
    function showListTransaction(params) {
        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/listTransaction",
            beforeSend: function () {
                $('#transactionBody').html('')
            },
            success: function (msg) {
                if (msg) {
                    if (msg !== 'Bad!')jQuery.parseJSON(msg).forEach(function (val, i) {
                        $('#transactionBody').append(
                            '<tr class="' + val.type + '" data-id="' + val.transactionId + '">' +
                            '<td align="center" class="tdName">' + val.typeName + '</td>' +
                            '<td align="center" class="tdName">' + val.categoryName + '</td>' +
                            '<td align="center" class="tdSum">' + val.sum + ' руб</td>' +
                            '<td class="tdDate">' + val.date.date.substring(10, -10) + '</td>' +
                            '<td align="center" class="tdName">' + val.name + '</td>' +
                            '<td class="tdButton">' +
                            '<button data-id="' + val.transactionId + '" class="changeTransaction">' +
                            '<img   class="buttons" src="/web/images/Pencil-24.png">' +
                            '</button>' +
                            '</td>' +
                            '<td class="tdButton">' +
                            '<button data-id="' + val.transactionId + '" class="deleteTransaction">' +
                            '<img   class="buttons" src="/web/images/Delete-24.png">' +
                            '</button>' +
                            '</td>' +
                            '</tr>'
                        );
                    });
                } else {
                    $('#transactionBody').append(
                        '<div class="noTransactions">' +
                        'У пользователя пока нет записей.' +
                        '</div>'
                    );
                }
            }
        });
    }

    //  Выбор "для кого" и выбор сортировки для вывода списка транзакций
    $('.chooseMember, #orederBy').on("input", function (event) {
        var arr = $('.chooseMember').val().split(/=/);
        var params = {};
        var orderBy = $('#orederBy option:selected').val();
        params[arr[0]] = arr[1];
        params['orderBy'] = orderBy;
        showListTransaction(params);
    });

    //  Выбор отображения по убыванию / по возрастанию
    $('.down').on('click', function () {
        $('.up, .down').fadeToggle();
        var arr = $('.chooseMember').val().split(/=/);
        var params = {};
        var orderBy = $('#orederBy option:selected').val();
        params[arr[0]] = arr[1];
        params['orderBy'] = orderBy;
        params['up'] = 'up';
        showListTransaction(params);

    });
    $('.up').on('click', function () {
        var arr = $('.chooseMember').val().split(/=/);
        var params = {};
        var orderBy = $('#orederBy option:selected').val();
        params[arr[0]] = arr[1];
        params['orderBy'] = orderBy;
        params['down'] = 'down';
        showListTransaction(params);
        $('.up, .down').fadeToggle();
    });

    //  Удаление транзакции
    $('.transactionList').on("click", " .deleteTransaction", function (event) {
        var transactionId = $(event.target).closest('button.deleteTransaction').data('id');
        var stringCategory = $(event.target).closest('tr');
        $.ajax({
            type: "POST",
            data: {transactionId: transactionId},
            url: "/web/app_dev.php/deleteTransaction",
            success: function (msg) {
                stringCategory.remove();
            }
        });
    });

    //  Изменение транзакции
    $('.transactionList').on("click", " .changeTransaction", function (event) {

        $('.formChangeTransaction, .shirm').fadeToggle("slow");

        var transactionId = $(event.target).closest('button.changeTransaction').data('id');
        prevSpamming('.changeTransaction', 2000);

        $.ajax({
            type: "POST",
            data: {transactionId: transactionId},
            url: "/web/app_dev.php/showTransaction",
            success: function (msg) {

                jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('#form_chooseCategory2').val(val.categoryName);
                    $('#form_chooseType2').val(val.typeName);
                    $('#form_sumchangeTransaction').val(val.sum);
                    $('#datepickerchangeTransaction').val(val.date.date.substring(10, -10));
                    var memberId = val.memberId;

                    $('.agreeChangeTransaction').one("click", function () {
                        prevSpamming('.agreeChangeTransaction', 2000);
                        var categoryId = $('#form_chooseCategory2 option:selected ').data('id');
                        var typeId = $('#form_chooseType2 option:selected ').data('id');
                        var memberId = val.memberId;
                        var sum = $('#form_sumchangeTransaction').val();
                        var date = $('#datepickerchangeTransaction').val();

                        $.ajax({
                            type: "POST",
                            url: "/web/app_dev.php/changeTransaction",
                            data: {
                                transactionId: transactionId,
                                categoryId: categoryId,
                                typeId: typeId,
                                memberId: memberId,
                                sum: sum,
                                date: date
                            },
                            success: function (msg) {
                                if (msg == 'Ok!') {
                                    $('.formChangeTransaction, .shirm')
                                        .fadeToggle("slow");
                                    //.toggleClass("hide");
                                    showListTransaction();
                                } else {
                                    alert('Некорректные данные!');
                                }
                            }
                        });
                    });
                });

            }
        });
    });


    //  добавим jquery ui calendar
    $(function () {
        $("#datepickerTransaction, #datepickerchangeTransaction").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

    //  Сдвоенный календарь
    $(function () {
        $("#from").datepicker({
            defaultDate: "+1w",
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#to").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#to").datepicker({
            defaultDate: "+1w",
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#from").datepicker("option", "maxDate", selectedDate);
            }
        });
    });



    $('.agreeReport1').on("click", function () {
        prevSpamming('.agreeReport1', 2000);
        report1();
    });
    //  Получаем первый Отчет. Отчет составлен за все время
    function report1() {
        var data1 = [];

        var target = $('.chooseMember1 option:selected').text();

        if ($('.chooseMember1').val()) var arr = $('.chooseMember1').val().split(/=/);
        var params = {};
        if (arr) {
            params[arr[0]] = arr[1];
        } else {
            params['familyId'] = getCookie('familyId')
            target = 'Семьи';
        }

        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/summ",
            success: function (msg) {

                val = jQuery.parseJSON(msg);
                if (val.equal || val.summProfitForDates || val.summWastageForDates) {
                    $('#showWastage1').val(val.summWastage);
                    $('#showProfit1').val(val.summProfit);
                    $('#showEqual1').val(val.equal);

                    //  Строим график
                    headerPieChart = 'Суммарный отчет для "' + target + '"';
                    container = '#container1';
                    data1 = [{
                        name: 'Расходы = ' + val.summWastage + ' руб',
                        y: val.summWastage,
                        color: 'lightcoral'
                    }, {
                        name: 'Дохды = ' + val.summProfit + ' руб',
                        y: val.summProfit,
                        color: 'lightgreen'
                    }];

                    go(container, headerPieChart, data1);
                }
            }
        });
    }


    //  Получаем второй отчет за промежуток времени указанный пользователем
    $('.agreeReport2').on("click", function () {
        prevSpamming('.agreeReport2', 2000);
        var arr = $('.chooseMember2').val().split(/=/);
        var params = {};
        params[arr[0]] = arr[1];
        params['dateFrom'] = $('#from').val();
        params['dateTo'] = $('#to').val();
        var target = $('.chooseMember2 option:selected').text();

        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/summForDates",
            success: function (msg) {
                val = jQuery.parseJSON(msg);
                if (val.equal || val.summProfitForDates || val.summWastageForDates) {
                    $('#showWastage2').val(val.summWastageForDates);
                    $('#showProfit2').val(val.summProfitForDates);
                    $('#showEqual2').val(val.equal);
                    //  Строим график
                    headerPieChart = 'Суммарный отчет для "' + target + '" c ' + params.dateFrom + ' по ' + params.dateTo;
                    container = '#container2';
                    data2 = [{
                        name: 'Расходы = ' + val.summWastageForDates + ' руб',
                        y: val.summWastageForDates,
                        color: 'lightcoral'
                    }, {
                        name: 'Дохды = ' + val.summProfitForDates + ' руб',
                        y: val.summProfitForDates,
                        color: 'lightgreen'
                    }];
                    go(container, headerPieChart, data2);

                    //  Скроллим вниз
                    var curPos = $(document).scrollTop();
                    var height = $("body").height();
                    var scrollTime = (height - curPos) / 1.73;
                    $("body,html").animate({"scrollTop": height}, scrollTime);
                } else {
                    alert('Данных нет. Удостоверьтесь, что вы выбрали дату!');
                }
            }
        });
    });

    //  Получаем третий отчет за каждый день указанного промежутка времени
    $('.agreeReport3').on("click", function () {
        prevSpamming('.agreeReport3', 2000);
        var arr = $('.chooseMember3').val().split(/=/);
        var params = {};
        params[arr[0]] = arr[1];
        params['dateFrom'] = $('#from').val();
        params['dateTo'] = $('#to').val();
        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/summForEachDay",
            success: function (msg) {
                var arr = msg.split(/~/);
                var wastage = arr[0];
                var profit = arr[1];

                for (i = 0; i < count(jQuery.parseJSON(wastage)); i++) {
                    $('.reports').append('<p>' + wastage[i].date.date.substring(10, -10) + '= ' + wastage[i].sum + '</p>');
                }
            }
        });
    });


    //  Создание круговой диаграммы из выбранных категорий.
    // Отображает отношение бюджета между выбранными категориями
    $('.categoryList ').on("click", " .diagramCategory", function (event) {
        var categoryId = $(event.target).closest("p").data('id');
        var categoryName = $(event.target).closest("p").attr('data-categoryname');

        $.ajax({
            type: "POST",
            data: {categoryId: categoryId, categoryName: categoryName},
            url: "/web/app_dev.php/summByCategory",
            success: function (msg) {
                val = jQuery.parseJSON(msg);
                if (val.y) {
                    headerPieChart = 'Сравнение категорий';
                    container = '#container3';
                    item = {
                        name: val.name + ' = ' + val.y + ' руб',
                        y: val.y
                    };
                    data3.push(item);
                    go(container, headerPieChart, data3);
                }
            }
        });
    });

    //  Создание круговой диаграммы из выбранных типов транзакции.
    $('.type ').on("click", ".diagramTransactionType", function (event) {
        var typeId = $(event.target).closest("p").data('id');
        var typeName = $(event.target).closest("p").attr('data-typename');

        $.ajax({
            type: "POST",
            data: {typeId: typeId, typeName: typeName},
            url: "/web/app_dev.php/summByType",
            success: function (msg) {
                val = jQuery.parseJSON(msg);
                if (val.y) {
                    headerPieChart = 'Сравнение по типам';
                    container = '#container4';
                    item = {
                        name: val.name + '=' + val.y + ' руб',
                        y: val.y
                    };
                    data4.push(item);
                    go(container, headerPieChart, data4);
                }
            }
        });
    });


    //  Функция создания круговых диаграм Highcharts.com (c)
    function go(container, headerPieChart, data) {

        // Build the chart
        $(container).highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: headerPieChart
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'В процентах',
                colorByPoint: true,
                data: data
            }]
        });
        $('.highcharts-button').fadeOut();
    }

    $('.family').on("click", function () {
        $('.familyList').fadeToggle('slow')
    });

    //  Кнопка скрытия popup-окошка
    $('.close').on('click', function (event) {
        $(event.target).closest('div').fadeToggle('slow');
        $('.shirm').fadeToggle('slow');
    });

    //  Особый случай для авторизации, при закрытии окошка Login
    // или Registration ширма не должна исчезать
    $('.close2').on('click', function (event) {
        $(event.target).closest('div').fadeToggle('slow');
    });

});