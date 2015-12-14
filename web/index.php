<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/javascript"  src="/web/bundles/acmeeco/js/jquery-2.1.4.js"></script>
    <script type="text/javascript"  src="/web/bundles/acmeeco/js/jquery-ui.min.js"></script>
    <script type="text/javascript"  src="/web/bundles/acmeeco/js/index.js"></script>
    <script type="text/javascript"  src="/web/bundles/acmeeco/js/highcharts.js"></script>
    <script type="text/javascript"  src="/web/bundles/acmeeco/js/exporting.js"></script>
    <link rel="stylesheet"  href="/web/bundles/acmeeco/css/index.css">
    <link rel="stylesheet"  href="/web/bundles/acmeeco/css/jquery-ui.min.css">
</head>
<body>
<style type="text/css">
    ${demo.css}
</style>
<div class="header" style="z-index: 10">
<!--    <a href="#"><div class="logo">Planning household budget</div></a>-->




    <div class="logOut "><a href="http://127.0.0.1/web/app_dev.php/logout">Выйти</a></div>

    <div class="family ">Семья</div>
    <div class="familyList ">
        <p class="buttonNewFamily Доход ">Создать семью</p>
        <p class="buttonNewMember ">Создать члена семьи</p>
        <div class="membersList "></div>
    </div>

</div>
<div class="container">



    <div class="transaction ">
        <strong>Записать доходы и расходы</strong>
        <div>
            <label class="transLabel" for="form_transaction" >Категория</label>
            <input class="transInput" type="text" id="form_transaction" name="form[transaction]" required="required" list="Categories">
            <datalist id="Categories">
                <option>Покупка еды</option>
                <option>Заработная плата</option>
                <option>Долг</option>
            </datalist>
        </div>
        <div>
            <label class="transLabel" for="form_typeTransaction" >Тип</label>
            <select class="transInput" id="form_typeTransaction" name="form[typeTransaction]" required>
                <option value="profit">Доходы</option>
                <option value="wastage">Расходы</option>
            </select>
        </div>
        <div>
            <label class="transLabel" for="form_sumTransaction" >Сумма</label>
            <input class="transInput" type="text" id="form_sumTransaction" name="form[sumTransaction]" required="required">
        </div>
        <div>
            <label class="transLabel" for="datepickerTransaction" >Дата</label>
            <input class="transInput" type="text" id="datepickerTransaction" name="form[dateTransaction]" required="required">
        </div>
        <button class="agreeTransaction">Добавить</button>
    </div>

    <div class="category ">
        <strong>Создать категорию</strong>
        <div>
            <label class="transLabel" for="form_category" >Категория</label>
            <input class="transInput" type="text" id="form_category" name="form[category]" required="required">
        </div>
        <div>
            <label class="transLabel" for="form_typeCategory" >Тип</label>
            <select class="transInput" id="form_typeCategory" name="form[typeCategory]" required>
                <option value="profit">Доходы</option>
                <option value="wastage">Расходы</option>
            </select>
        </div>
        <button class="agreeCategory">Добавить</button>
    </div>

    <div class="categoryList "></div>

    <div class="shirm hide"></div>

    <div class="auth hide">
        <div class="str1"><button class="login">Войти</button></div>
        <div class="str2"><button class="reg">Регистрация</button></div>
    </div>

    <div class="newFamily hide">
        <strong>Создание семьи</strong>
            <div id="form">
                <div>
                    <label for="form_login3" class="required">Имя семьи</label>
                    <input type="text" id="form_login3" name="form[login]" required="required">
                </div>
            </div>
            <button class="agreeNewFamily">Создать</button>
    </div>

    <div class="newMember hide">
        <strong>Создание члена семьи</strong>
            <div id="form">
                <div>
                    <label for="form_surname4" class="required">Surname</label>
                    <input type="text" id="form_surname4" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name4" class="required">Name</label>
                    <input type="text" id="form_name4" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname4" class="required">Secondname</label>
                    <input type="text" id="form_secondname4" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login4" class="required">Login</label>
                    <input type="text" id="form_login4" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password4" class="required">Password</label>
                    <input type="text" id="form_password4" name="form[password]" required="required">
                </div>
            </div>
            <button class="agreeNewMember">Создать</button>
    </div>

    <div class="changeMember hide">
        <strong>Изменение </strong>
            <div id="form">
                <div>
                    <label for="form_surname5" class="required">Surname</label>
                    <input type="text" id="form_surname5" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name4" class="required">Name</label>
                    <input type="text" id="form_name5" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname4" class="required">Secondname</label>
                    <input type="text" id="form_secondname5" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login5" class="required">Login</label>
                    <input type="text" id="form_login5" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password5" class="required">Password</label>
                    <input type="text" id="form_password5" name="form[password]" required="required">
                </div>
            </div>
            <button class="agreeChangeMember">Создать</button>
    </div>


    <div class="showLogin hide">
        <strong>Авторизация</strong>
        <div id="form">
            <div>
                <label for="form_login" class="required">Login</label>
                <input type="text" id="form_login" name="form[login]" required="required">
            </div>
            <div>
                <label for="form_password" class="required">Password</label>
                <input type="text" id="form_password" name="form[password]" required="required">
            </div>
        </div>
        <button class="agreeLogin">Войти</button>
    </div>

    <div class="showRegistration hide">
        <button class="close">X</button>
        <strong>Регистрация</strong>
            <div id="form">
                <div>
                    <label for="form_surname" class="required">Surname</label>
                    <input type="text" id="form_surname" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name" class="required">Name</label>
                    <input type="text" id="form_name" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname" class="required">Secondname</label>
                    <input type="text" id="form_secondname" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login2" class="required">Login</label>
                    <input type="text" id="form_login2" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password" class="required">Password</label>
                    <input type="text" id="form_password2" name="form[password]" required="required">
                </div>
            </div>
            <button class="AgreeRegistration">Зарегестрировать</button>
    </div>

    <div class="formChangeCategory hide">
        <div>
            <label class="transLabel" for="form_changeCategory" >Категория</label>
            <input class="transInput" type="text" id="form_changeCategory" name="form[changeCategory]" required="required">
        </div>
        <div>
            <label class="transLabel" for="form_typechangeCategory" >Тип</label>
            <select class="transInput" id="form_typechangeCategory" name="form[typechangeCategory]" required>
                <option value="profit">Доходы</option>
                <option value="wastage">Расходы</option>
            </select>
        </div>
        <button class="agreeChangeCategory">Добавить</button>
    </div>

    <div class="formChangeTransaction hide">
        <div>
            <label class="transLabel" for="form_changeTransaction" >Категория</label>
            <input class="transInput" type="text" id="form_changeTransaction" name="form[changeTransaction]" required="required" list="Categories">
            <datalist id="Categories">
                <option>Покупка еды</option>
                <option>Заработная плата</option>
                <option>Долг</option>
            </datalist>
        </div>
        <div>
            <label class="transLabel" for="form_typechangeTransaction" >Тип</label>
            <select class="transInput" id="form_typechangeTransaction" name="form[typechangeTransaction]" required>
                <option value="profit">Доходы</option>
                <option value="wastage">Расходы</option>
            </select>
        </div>
        <div>
            <label class="transLabel" for="form_sumchangeTransaction" >Сумма</label>
            <input class="transInput" type="text" id="form_sumchangeTransaction" name="form[sumchangeTransaction]" required="required">
        </div>
        <div>
            <label class="transLabel" for="datepickerchangeTransaction" >Дата</label>
            <input class="transInput" type="text" id="datepickerchangeTransaction" name="form[datechangeTransaction]" required="required">
        </div>
        <button class="agreeChangeTransaction">Добавить</button>
    </div>


        <div class="reports ">
            <div class="report1">
                <header><strong>Полный отчет</strong></header>
                <p><select class="chooseMember1" id=""></select></p>
                <p><button class="agreeReport1">Получить отчет</button></p>

                <p><span>Сумма расходов: </span><input id="showWastage1" class="inputDisabled" disabled type="text" value="0"></p>
                <p><span>Сумма доходов: </span><input id="showProfit1" class="inputDisabled" disabled type="text" value="0"></p>
                <p><span>Итого: </span><input id="showEqual1" class="inputDisabled" disabled type="text" value="0"></p>
            </div>
            <div class="report2">
                <header><strong>Отчет за выбранный промежуток времени</strong></header>
                <div>
                    <label for="from">с</label>
                    <input type="text" id="from" name="from">
                    <label for="to">по</label>
                    <input type="text" id="to" name="to">
                </div>
                <p><select class="chooseMember2" id=""></select></p>
                <p><button class="agreeReport2" >Получить отчет</button></p>
                <p><span>Сумма расходов: </span><input id="showWastage2" class="inputDisabled" disabled type="text" value="0"></p>
                <p><span>Сумма доходов: </span><input id="showProfit2" class="inputDisabled" disabled type="text" value="0"></p>
                <p><span>Итого: </span><input id="showEqual2" class="inputDisabled" disabled type="text" value="0"></p>
            </div>
        </div>
    <div class="transactionList ">
        <header class="transactionListMenu">
            <strong>Общая ведомость расходов и доходов</strong>
            <select class="chooseMember" id="">
            </select>
            <!--            <select class="chooseType" id="">-->
            <!--                <option selected value="all">Все транзакции</option>-->
            <!--                <option value="profit">Только доход</option>-->
            <!--                <option value="wastage">Только рaсход</option>-->
            <!--            </select>-->
        </header>
        <table id="transactionTable" class="tablesorter">
            <thead>
            <tr>
                <th>Название</th>
                <th>Сумма</th>
                <th>Дата</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody id="transactionBody">

            </tbody>
        </table>
    </div>

    <div class="pieCharts">
        <div id="container1" style="min-width: 390px; height: 400px; margin: 0 auto; display: inline-block"></div>
        <div id="container2" style="min-width: 390px; height: 400px; margin: 0 auto; display: inline-block"></div>
        <div id="container3" style="min-width: 390px; height: 400px; margin: 0 auto; display: inline-block"></div>
    </div>

</div>


<div class="footer">
    <a href=""><div>Homepage</div></a>   <a href=""><div>Git</div></a>
</div>
<!--<h1>Wellcome!!!</h1>-->
<!--<div>//После регистрации юзер сразу авторизуется=ему присваивается membId(его идентификатор в бд) => следовательно<br>-->
<!--    необходимо скрыть кнопки авторизация и регистрация и вывести кнопку разлогиниться.<br>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/unlogin"><button>Разлогиниться</button></a>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/reg"><button>Регистрация</button></a>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/auth"><button>Авторизация</button></a>-->
<!--</div>-->
<!--<div>//Если fmID есть в сессии то спрятать кнопку Зарегать семью и показать зарегать членов семьи и наоборот <br>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/new_family"><button>Зарегистрировать семью</button></a>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/new_member"><button>Зарегистрировать члена семьи</button></a>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/change"><button>Редактировать свой профиль</button></a>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/delete"><button>Удалить свой профиль</button></a>-->
<!--</div>-->
<!--<div>// Каждой категории присваивается familyId из сессии пользователя => Скрыть кнопку, если нет переменной в сесси <br>-->
<!--    <a href="http://127.0.0.1/web/app_dev.php/newCategory"><button>Добавить категорию транзакции</button></a>-->
<!--</div>-->

<!--<script type="text/javascript">-->
<!--    $(function () {-->
<!---->
<!--        $(document).ready(function () {-->
<!---->
<!--            // Build the chart-->
<!--            $('#container2').highcharts({-->
<!--                chart: {-->
<!--                    plotBackgroundColor: null,-->
<!--                    plotBorderWidth: null,-->
<!--                    plotShadow: false,-->
<!--                    type: 'pie'-->
<!--                },-->
<!--                title: {-->
<!--                    text: 'Полный отчет'-->
<!--                },-->
<!--                tooltip: {-->
<!--                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'-->
<!--                },-->
<!--                plotOptions: {-->
<!--                    pie: {-->
<!--                        allowPointSelect: true,-->
<!--                        cursor: 'pointer',-->
<!--                        dataLabels: {-->
<!--                            enabled: false-->
<!--                        },-->
<!--                        showInLegend: true-->
<!--                    }-->
<!--                },-->
<!--                series: [{-->
<!--                    name: 'Brands',-->
<!--                    colorByPoint: true,-->
<!--                    data: [{-->
<!--                        name: 'Расход',-->
<!--                        y: 46700,-->
<!--                        color:'lightcoral'-->
<!--                    }, {-->
<!--                        name: 'Доход',-->
<!--                        y: 70000,-->
<!--                        color:'lightgreen'-->
<!--                    }]-->
<!--                }]-->
<!--            });-->
<!--        });-->
<!--    });-->
<!--</script>-->


</body>
</html>