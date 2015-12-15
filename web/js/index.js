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

    if(getCookie('familyId')){
        $('.buttonNewFamily').toggleClass("hide");
    }else{
        $('.buttonNewMember').toggleClass("hide");
    }

    loadDB();
//  Подгрузка данных из БД
    function loadDB() {
        if (getCookie('memberId')) {
            showListTransaction();
            showListCategory();
            showListMember();
            showContent()
        }
    }


    function showContent(){
        //$('.reports').fadeToggle("slow").toggleClass("hide");
        //$('.transaction').fadeToggle("slow").toggleClass("hide");
        //$('.family').fadeToggle("slow").toggleClass("hide");
        //$('.logOut').fadeToggle("slow").toggleClass("hide");

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
                //console.log(msg);
                if (msg == 'Ok!') {
                    $('.showRegistration, .shirm, .auth')
                        .fadeToggle("slow");
                    $('.membersList')
                        .fadeToggle("slow");
                    //location.reload()
                } else {
                    if(msg == 'Login already exist!'){
                        alert('Такой логин уже существует!');
                    }else{
                        alert('Некорректные данные!');
                    }
                }
            }
        });
    });

//  Отображение формы: Создание новой семьи
    $('.buttonNewFamily').on("click", function () {
        $('.newFamily, .shirm').fadeToggle("slow");
    });

//  Создание новой семьи
    $('.agreeNewFamily').on("click", function () {
        var familyName = $('#form_login3').val();
        var memberId = getCookie('memberId');
        $.ajax({
            type: "POST",
            data: {familyName: familyName, memberId: memberId},
            url: "/web/app_dev.php/newFamily",
            success: function (msg) {
                if(msg == 'Ok!'){
                    $('.newFamily, .shirm').fadeToggle("slow");
                    showListMember();
                }else{
                    alert('Некорректное имя семьи');
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
    });

//  Создание нового члена семьи
    $('.agreeNewMember').on("click", function () {
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
                //console.log(msg);
                if (msg == 'Ok!') {
                    $('.newMember, .shirm')
                        .fadeToggle("slow");
                    $('.membersList').html('');
                    showListMember();
                } else {
                    if(msg == 'Login already exist!'){
                        alert('Такой логин уже существует!');
                    }else{
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
                //console.log(msg);

                //  Обнулим все списки членов семьи
                $('.membersList, .chooseMember, .chooseMember1, .chooseMember2').html('');

                //  Заполнение выпадающих списков, первой опицей ставится семья,
                // если она есть у пользователя
                if (familyId) {
                    $('.chooseMember, .chooseMember1, .chooseMember2')
                        .append(
                            '<option selected value="familyId=' + familyId + '">Семья' +
                            '</option>'
                        );
                }

                //  Заполнение выпадающего списка
                if(msg)jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('.chooseMember, .chooseMember1, .chooseMember2')
                        .append(
                            '<option value="memberId=' + val.memberId + '">'+val.name+''+
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
        }else{
            $.ajax({
                type: "POST",
                data: {memberId: memberId},
                url: "/web/app_dev.php/deleteMember",
                success: function (msg) {
                    stringMember.remove();
                    if(msg == 'Bye!'){location.reload();}
                    //if(getCookie('memberId')==memberId){location.reload()}

                }
            });
        }
    });

//  Изменение личных данных члена семьи
    $('.membersList').on("click", " .buttonChangeMember", function (event) {
        var memberId = $(event.target).closest('button.buttonChangeMember')[0].name;

        $.ajax({
            type: "POST",
            data: {memberId: memberId},
            url: "/web/app_dev.php/showMember",
            success: function (msg) {
                //console.log(msg);
                $('.changeMember, .shirm')
                    .fadeToggle("slow");

                jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('#form_surname5').val(val.surname);
                    $('#form_name5').val(val.name);
                    $('#form_secondname5').val(val.secondname);
                    $('#form_login5').val(val.login);
                });

                $('.agreeChangeMember').on("click", function () {
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
                            if(msg == 'Ok!'){
                                showListMember();
                                $('.changeMember, .shirm').fadeToggle('slow');
                            }else{
                                alert('Некорректные данные!');
                            }
                        }
                    });
                });
            }
        });

    });

//  Добавляем новую транзакцию
    $('.agreeTransaction').on("click", function () {
        var transactionName = $('#form_transaction').val();
        var transactionType = $('#form_typeTransaction').val();
        var sum = $('#form_sumTransaction').val();
        var date = $('#datepickerTransaction').val();
        var memberId = getCookie('memberId');
        var familyId = getCookie('familyId');

        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newTransaction",
            data: {
                transactionName: transactionName,
                transactionType: transactionType,
                sum: sum,
                date: date,
                memberId: memberId,
                familyId: familyId
            },
            success: function (msg) {
                if(msg == 'Ok!'){
                    showListTransaction()
                }else{
                    alert('Некорректные данные!');
                }
            }
        });
    });

//  Добавляем новую категорию
    $('.agreeCategory').on("click", function () {
        var categoryName = $('#form_category').val();
        var categoryType = $('#form_typeCategory').val();
        var memberId = getCookie('memberId');
        var familyId = getCookie('familyId');
        $.ajax({
            type: "POST",
            url: "/web/app_dev.php/newCategory",
//                traditional: true,
            data: {categoryName: categoryName, categoryType: categoryType, memberId: memberId, familyId: familyId},
            success: function (msg) {
                if(msg == 'Ok!'){
                    showListCategory();
                }else{
                    if(msg == 'Bad inputs!'){
                        alert('Некооректные данные!');
                    }else{
                        alert('Такая категория уже существует!');
                    }
                }
            }
        });
    });

//  Отображение списка категорий
    function showListCategory() {
        var memberId = getCookie('memberId');
        var familyId = getCookie('familyId');
        $.ajax({
            type: "POST",
            data: {memberId: memberId, familyId: familyId},
            url: "/web/app_dev.php/listCategory",
            success: function (msg) {
                //console.log(msg);
                $('.categoryList, #Categories').html('');
                if(msg)jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('.categoryList').append('<p data-id="' + val.categoryId + '" data-categoryName="' + val.categoryName + '" data-categoryType="' + val.categoryType + '" data-familyId="' + val.familyId + '" data-memberId="' + val.memberId + '" class="' + val.categoryType + '"><button data-id="' + val.categoryId + '" class="deleteCategory"><img class="buttons" src="/web/images/Delete-24.png"></button><button data-id="' + val.categoryId + '" class="changeCategory"><img class="buttons" src="/web/images/Pencil-24.png"></button>  ' + val.categoryName + '</p>');
                    $('#Categories').append('<option>' + val.categoryName + '</option>');
                });
            }
        });

    }

//  Удаление категории
    $('.categoryList').on("click", " .deleteCategory", function (event) {
        var categoryId = $(event.target).closest('button.deleteCategory').data('id');
        var stringCategory = $(event.target).closest('p');
        //console.log($(event.target).closest('button.deleteCategory'));
        $.ajax({
            type: "POST",
            data: {categoryId: categoryId},
            url: "/web/app_dev.php/deleteCategory",
            success: function (msg) {
                stringCategory.remove();
                //console.log(memberId);
            }
        });
    });


//  Изменение категории
    $('.categoryList').on("click", " .changeCategory", function (event) {
        $('.formChangeCategory, .shirm').fadeToggle("slow");
        var categoryId = $(event.target).closest('button.changeCategory').data('id');
        //console.log($(event.target).closest('button.changeCategory'));
        $.ajax({
            type: "POST",
            data: {categoryId: categoryId},
            url: "/web/app_dev.php/showCategory",
            success: function (msg) {
                //console.log(jQuery.parseJSON(msg)[0]);
                $('#form_changeCategory').val(jQuery.parseJSON(msg)[0].categoryName);
                $('#form_typechangeCategory').val(jQuery.parseJSON(msg)[0].categoryType);
                $('.agreeChangeCategory').on("click", function () {
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
                            } else {
                                alert('Некорректные данные!');
                            }
                        }
                    });
                });
            }
        });
    });

//  Отображение списка транзакций пользователя
    function showListTransaction(params)
    {
        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/listTransaction",
            beforeSend: function () {
                $('#transactionBody').html('')
            },
            success: function (msg) {
                if (msg) {
                    if(msg)jQuery.parseJSON(msg).forEach(function (val, i) {
                        //console.log(val.date.date);
                        $('#transactionBody').append('<tr class="' + val.transactionType + '" data-id="' + val.memberId + '"><td class="tdName">' + val.transactionName + '</td><td class="tdSum">' + val.sum + '</td><td class="tdDate">' + val.date.date.substring(10, -10) + '</td><td class="tdButton"><button data-id="' + val.transactionId + '" class="changeTransaction"><img class="buttons" src="/web/images/Pencil-24.png"></button></td><td class="tdButton"><button data-id="' + val.transactionId + '" class="deleteTransaction"><img class="buttons" src="/web/images/Delete-24.png"></button></td></tr>');
                    });
                    //$('.transactionList').fadeToggle("slow").toggleClass("hide");
                } else {
                    $('#transactionBody').append('<tr><td>У пользователя пока нет записей.</td><td></td><td></td><td></td><td></td></tr>');
                }
            }
        });
    }

    //  Выбор в выпадающем списке "для кого" выводить список транзакций
    $('.chooseMember').on("input", function (event) {
        var arr = $('.chooseMember').val().split(/=/);
        var params={};
        params[arr[0]] = arr[1];
        showListTransaction(params);
        //console.log(params,$('.chooseMember option:selected').text());

    });


//  Удаление транзакции
    $('.transactionList').on("click", " .deleteTransaction", function (event) {
        var transactionId = $(event.target).closest('button.deleteTransaction').data('id');
        var stringCategory = $(event.target).closest('tr');
        //console.log($(event.target).closest('button.deleteTransaction'));
        $.ajax({
            type: "POST",
            data: {transactionId: transactionId},
            url: "/web/app_dev.php/deleteTransaction",
            success: function (msg) {
                stringCategory.remove();
                //console.log(memberId);
            }
        });
    });

//  Изменение транзакции
    $('.transactionList').on("click", " .changeTransaction", function (event) {
        $('.formChangeTransaction, .shirm').fadeToggle("slow");
        //$('.formChangeTransaction').toggleClass("hide");
        //$('.shirm').toggleClass("hide");
        var transactionId = $(event.target).closest('button.changeTransaction').data('id');
        //var stringCategory = $(event.target).closest('p');
        //console.log($(event.target).closest('button.changeTransaction'));
        $.ajax({
            type: "POST",
            data: {transactionId: transactionId},
            url: "/web/app_dev.php/showTransaction",
            success: function (msg) {
                //console.log(jQuery.parseJSON(msg)[0]);
                jQuery.parseJSON(msg).forEach(function (val, i) {
                    $('#form_changeTransaction').val(val.transactionName);
                    $('#form_typechangeTransaction').val(val.transactionType);
                    $('#form_sumchangeTransaction').val(val.sum);
                    $('#datepickerchangeTransaction').val(val.date.date.substring(10, -10));
                    var memberId = val.memberId;

                    $('.agreeChangeTransaction').on("click", function () {
                        var transactionName = $('#form_changeTransaction').val();
                        var transactionType = $('#form_typechangeTransaction').val();
                        var sum = $('#form_sumchangeTransaction').val();
                        var date = $('#datepickerchangeTransaction').val();
                        //var memberId = getCookie('memberId');
                        //var familyId = getCookie('familyId');
                        $.ajax({
                            type: "POST",
                            url: "/web/app_dev.php/changeTransaction",
                            data: {
                                memberId: memberId,
                                transactionId: transactionId,
                                transactionName: transactionName,
                                transactionType: transactionType,
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

    var data1=[];
    var data2=[];
    var data3=[];
    var headerPieChart='';
    var container='';

//  Получаем первый отчет за все время
    $('.agreeReport1').on("click", function () {
        var target = $('.chooseMember1 option:selected').text();

        var arr = $('.chooseMember1').val().split(/=/);
        var params={};
        params[arr[0]] = arr[1];

        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/summ",
            success: function (msg) {
                //console.log(msg,target);
                val = jQuery.parseJSON(msg);
                $('#showWastage1').val(val.summWastage);
                $('#showProfit1').val(val.summProfit);
                $('#showEqual1').val(val.equal);
                //  Строим график
                headerPieChart = 'Суммарный отчет для "'+target+'"';
                container = '#container1';
                data1 = [{
                    name: 'Расходы',
                    y: val.summWastage,
                    color:'lightcoral'
                },{
                    name: 'Дохды',
                    y: val.summProfit,
                    color:'lightgreen'
                }];
                //data1.push(data1);
                go(container,headerPieChart,data1);
            }
        });
    });


//  Получаем второй отчет за промежуток времени указанный пользователем
    $('.agreeReport2').on("click", function () {
        var arr = $('.chooseMember2').val().split(/=/);
        var params={};
        params[arr[0]] = arr[1];
        params['dateFrom'] = $('#from').val();
        params['dateTo'] = $('#to').val();
        var target = $('.chooseMember2 option:selected').text();
        console.log(params);
        $.ajax({
            type: "POST",
            data: params,
            url: "/web/app_dev.php/summForDates",
            success: function (msg) {
                val = jQuery.parseJSON(msg);
                $('#showWastage2').val(val.summWastageForDates);
                $('#showProfit2').val(val.summProfitForDates);
                $('#showEqual2').val(val.equal);
                //  Строим график
                headerPieChart = 'Суммарный отчет для "'+target+'" c '+params.dateFrom+' по '+params.dateTo;
                container = '#container2';
                data2 = [{
                    name: 'Расходы',
                    y: val.summWastageForDates,
                    color:'lightcoral'
                },{
                    name: 'Дохды',
                    y: val.summProfitForDates,
                    color:'lightgreen'
                }];
                //data1.push(data1);
                go(container,headerPieChart,data2);
            }
        });
    });

//  Получаем третий отчет за каждый день указанного промежутка времени
    $('.agreeReport3').on("click", function () {
        var arr = $('.chooseMember3').val().split(/=/);
        var params={};
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
                //console.log(jQuery.parseJSON(wastage));

                for (i = 0; i < count(jQuery.parseJSON(wastage)); i++) {
                    $('.reports').append('<p>' + wastage[i].date.date.substring(10, -10) + '= ' + wastage[i].sum + '</p>');
                }
                //console.log(jQuery.parseJSON(profit));
            }
        });
    });


    //  Создание круговой диаграммы из выбранных категорий.
    // Отображает отношение бюджета между выбранными категориями
    $('.categoryList ').on("click"," .wastage, .profit", function(event){
        //var categoryName = $(event.target).each(function(i,val){console.log(val);});
        var categoryName = $(event.target).closest("p").attr('data-categoryname');
        var transactionType = $(event.target).closest("p").attr('data-categoryType');
        //console.log(categoryName,transactionType);
        $.ajax({
            type: "POST",
            data: {categoryName:categoryName,transactionType:transactionType},
            url: "/web/app_dev.php/summByCategory",
            success: function (msg) {
                val = jQuery.parseJSON(msg);
                if(val.type == 'wastage'){ color='lightcoral'}else{ color='lightgreen'}
                headerPieChart = 'Сравнение категорий';
                container = '#container3';
                item = {
                    name: val.name,
                    y: val.y,
                    color:color
                };
                data3.push(item);
                go(container,headerPieChart,data3);
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
                    name: 'Brands',
                    colorByPoint: true,
                    data: data
                }]
            });
    }
    $('.family').on("click", function(){$('.familyList').fadeToggle('slow')});

    //  Кнопка скрытия popup-окошка
    $('.close').on('click', function(event){
        $(event.target).closest('div').fadeToggle('slow');
        $('.shirm').fadeToggle('slow');
    });
    //  Особый случай для авторизации, при закрытии окошка Login
    // или Registration ширма не должна исчезать
    $('.close2').on('click', function(event){
        $(event.target).closest('div').fadeToggle('slow');
    });

});