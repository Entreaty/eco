<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Расчет семейного бюджета</title>
    <script type="text/javascript"  src="/web/js/jquery-2.1.4.js"></script>
    <script type="text/javascript"  src="/web/js/jquery-ui.min.js"></script>
    <script type="text/javascript"  src="/web/js/index.js"></script>
    <link rel="stylesheet"  href="/web/css/index.css">
    <!--Календарь-->
    <link rel="stylesheet"  href="/web/css/jquery-ui.min.css">
    <!--Графики-->
    <script type="text/javascript"  src="/web/js/highcharts.js"></script>
    <script type="text/javascript"  src="/web/js/exporting.js"></script>
    <!--Для подключаемых графиков-->
    <style type="text/css">
        ${demo.css}
    </style>
</head>
<body>
<!--Меню-->
<div class="header" style="z-index: 10">

    <div class="logOut "><a href="http://127.0.0.1/web/app_dev.php/logout">Выйти</a></div>

    <div class="family ">Семья</div>
    <div class="familyList ">
        <p class="buttonNewMember ">Создать члена семьи</p>
        <div class="membersList "></div>
    </div>

    <div class="wellcome"></div>

</div>
<!--Контент-->
<div class="container">
    <!--Меню для создания транзакций -->
    <div class="transaction ">
        <strong>Записать доходы и расходы</strong>
        <div>
            <label class="transLabel" for="form_chooseCategory" >Категория</label>
            <select class="transSelect" id="form_chooseCategory" required>
            </select>
        </div>
        <div>
            <label class="transLabel" for="form_chooseType" >Тип</label>
            <select class="transSelect2" id="form_chooseType" required>
            </select>
        </div>
        <div>
            <label class="transLabel" for="form_chooseSum" >Сумма, руб.</label>
            <input class="transInput" type="text" id="form_chooseSum" required="required">
        </div>
        <div>
            <label class="transLabel" for="datepickerTransaction" >Дата</label>
            <input class="transInput" type="text" id="datepickerTransaction" required="required">
        </div>
        <button class="agreeTransaction">Добавить</button>
    </div>
    <!--Меню для создания категорий транзакции -->
    <div class="category ">
        <strong>Создать категорию</strong>
        <div>
            <label class="transLabel" for="form_category" >Категория</label>
            <input class="transInput" type="text" id="form_category" name="form[category]" required="required">
        </div>
        <button class="agreeCategory">Добавить</button>
        <div class="categoryList "></div>
    </div>
    <!--Меню для создания типов транзакции -->
    <div class="type">
        <strong>Создать тип операции</strong>
        <div>
            <label class="transLabel" for="form_typeName" >Название</label>
            <input class="transInput" type="text" id="form_typeName" name="form[typeName]" required="required">
        </div>
        <div>
            <label class="transLabel" for="form_type" >Тип</label>
            <select class="typeSelect" id="form_type" name="form[type]" required>
                <option value=true>Доходы</option>
                <option value=false>Расходы</option>
            </select>
        </div>
        <button class="agreeType">Добавить</button>
        <div class="typesList"></div>
    </div>
    <!--Темный фон "ширмочка" -->
    <div class="shirm hide"></div>
    <!--Меню выбора авторизации/регистрации -->
    <div class="auth hide">
        <div class="str1"><button class="login">Войти</button></div>
        <div class="str2"><button class="reg">Регистрация</button></div>
    </div>
    <!--График первого отчета - Суммарного отчета -->
    <div id="container1" style="min-width: 290px; height: 400px; margin: 0 auto; display: inline-block"></div>
    <!--Меню для создания нового члена семьи -->
    <div class="newMember hide">
        <button class="close">X</button>
        <strong>Создание члена семьи</strong>
            <div id="form">
                <div>
                    <label for="form_surname4" class="required"><strong class="red">*</strong>Фамилия</label>
                    <input autocomplete="off" type="text" id="form_surname4" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name4" class="required"><strong class="red">*</strong>Имя</label>
                    <input autocomplete="off" type="text" id="form_name4" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname4" class="required"><strong class="red">*</strong>Отчество</label>
                    <input autocomplete="off" type="text" id="form_secondname4" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login4" class="required"><strong class="red">*</strong>Login</label>
                    <input autocomplete="off" type="text" id="form_login4" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password4" class="required"><strong class="red">*</strong>Password</label>
                    <input type="password" autocomplete="off" id="form_password4" name="form[password]" required="required">
                </div>
            </div>
            <button class="agreeNewMember">Создать</button>
    </div>
    <!--Меню для изменения члена семьи -->
    <div class="changeMember hide">
        <button class="close">X</button>
        <strong>Изменение </strong>
            <div id="form">
                <div>
                    <label for="form_surname5" class="required">Фамилия</label>
                    <input autocomplete="off" type="text" id="form_surname5" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name4" class="required">Имя</label>
                    <input autocomplete="off" type="text" id="form_name5" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname4" class="required">Отчество</label>
                    <input autocomplete="off" type="text" id="form_secondname5" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login5" class="required">Login</label>
                    <input autocomplete="off" type="text" id="form_login5" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password5" class="required">Password</label>
                    <input type="password" autocomplete="off" id="form_password5" name="form[password]" required="required">
                </div>
            </div>
            <button class="agreeChangeMember">Изменить</button>
    </div>
    <!--Меню авторизации -->
    <div class="showLogin hide">
        <button class="close2">X</button>
        <strong>Авторизация</strong>
        <div id="form">
            <div>
                <label for="form_login" class="required">Login</label>
                <input autocomplete="off" type="text" id="form_login" name="form[login]" required="required">
            </div>
            <div>
                <label for="form_password" class="required">Password</label>
                <input type="password" autocomplete="off" id="form_password" name="form[password]" required="required">
            </div>
        </div>
        <button class="agreeLogin">Войти</button>
    </div>
    <!--Меню регистрации -->
    <div class="showRegistration hide">
        <button class="close2">X</button>
        <strong>Регистрация</strong>
            <div id="form">
                <div>
                    <label for="form_surname" class="required"><strong class="red">*</strong>Фамилия</label>
                    <input autocomplete="off" type="text" id="form_surname" name="form[surname]" required="required">
                </div>
                <div>
                    <label for="form_name" class="required"><strong class="red">*</strong>Имя</label>
                    <input autocomplete="off" type="text" id="form_name" name="form[name]" required="required">
                </div>
                <div>
                    <label for="form_secondname" class="required"><strong class="red">*</strong>Отчество</label>
                    <input autocomplete="off" type="text" id="form_secondname" name="form[secondname]" required="required">
                </div>
                <div>
                    <label for="form_login2" class="required"><strong class="red">*</strong>Login</label>
                    <input autocomplete="off" type="text" id="form_login2" name="form[login]" required="required">
                </div>
                <div>
                    <label for="form_password" class="required"><strong class="red">*</strong>Password</label>
                    <input autocomplete="off" type="password" id="form_password2" name="form[password]" required="required">
                </div>
            </div>
            <button class="AgreeRegistration">Зарегистрировать</button>
    </div>
    <!--Меню для изменения типа транзакции -->
    <div class="formChangeType hide">
        <button class="close">X</button>
        <div>
            <label class="transLabe2" for="form_changeTypeName" >Название типа</label>
            <input class="transInput2" type="text" id="form_changeTypeName" required="required">
        </div>
        <div>
            <label class="transLabe2" for="form_changeType" >Тип</label>
            <select class="changeType2" id="form_changeType" required>
                <option value=true>Доходы</option>
                <option value=false>Расходы</option>
            </select>
        </div>
        <button class="agreeChangeType">Изменить</button>
    </div>
    <!--Меню для изменения категории транзакции -->
    <div class="formChangeCategory hide">
        <button class="close">X</button>
        <div>
            <label class="transLabel" for="form_changeCategory" >Категория</label>
            <input class="transInput" type="text" id="form_changeCategory" name="form[changeCategory]" required="required">
        </div>
        <button class="agreeChangeCategory">Изменить</button>
    </div>
    <!--Меню для изменения транзакции -->
    <div class="formChangeTransaction hide">
        <button class="close">X</button>
        <div>
            <label class="transLabel" for="form_chooseCategory2" >Категория</label>
            <select class="transSelect" id="form_chooseCategory2" required>
            </select>
        </div>
        <div>
            <label class="transLabel" for="form_chooseType2" >Тип</label>
            <select class="transSelect2" id="form_chooseType2" required>
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
        <button class="agreeChangeTransaction">Изменить</button>
    </div>
    <!--Меню списка транзакций -->
    <div class="transactionList ">
        <header class="transactionListMenu">
            <strong>Общая ведомость расходов и доходов</strong>
            <select class="chooseMember" id="">
            </select>
        </header>
        <table id="transactionTable" class="tablesorter">
            <thead>
            <tr>
                <th class="tdName">Тип</th>
                <th class="tdName">Категория</th>
                <th class="tdSum">Сумма</th>
                <th class="tdDate">Дата</th>
                <th class="tdName">Член семьи</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody id="transactionBody"></tbody>
        </table>
    </div>
    <!--Контейнер для отчетов -->
    <div class="reports ">
        <!--Меню суммарного отчета -->
        <div class="report1">
            <header><strong>Суммарный отчет</strong></header>
            <p><select class="chooseMember1" id=""></select></p>
            <p><button class="agreeReport1">Получить</button></p>
            <p><span class="span">Сумма расходов: </span><input id="showWastage1" class="inputDisabled" disabled type="text" value="0"></p>
            <p><span class="span">Сумма доходов: </span><input id="showProfit1" class="inputDisabled" disabled type="text" value="0"></p>
            <p><span class="span">Итого: </span><input id="showEqual1" class="inputDisabled" disabled type="text" value="0"></p>
        </div>
        <!--Меню отчета за промежуток времени-->
        <div class="report2">
            <header><strong>Отчет за промежуток времени</strong></header>
            <div>
                <label for="from">с</label>
                <input type="text" id="from" name="from">
                <label for="to">по</label>
                <input type="text" id="to" name="to">
            </div>
            <p><select class="chooseMember2" id=""></select></p>
            <p><button class="agreeReport2" >Получить</button></p>
            <p><span class="span">Сумма расходов: </span><input id="showWastage2" class="inputDisabled" disabled type="text" value="0"></p>
            <p><span class="span">Сумма доходов: </span><input id="showProfit2" class="inputDisabled" disabled type="text" value="0"></p>
            <p><span class="span">Итого: </span><input id="showEqual2" class="inputDisabled" disabled type="text" value="0"></p>
        </div>
    </div>

    <!--Контайнер для графиков -->
    <div class="pieCharts">
        <div id="container2" style="min-width: 390px; height: 430px; margin: 0 auto; display: inline-block"></div>
        <div id="container3" style="min-width: 390px; height: 400px; margin: 0 auto; display: inline-block"></div>
        <div id="container4" style="min-width: 390px; height: 400px; margin: 0 auto; display: inline-block"></div>
    </div>

</div>


<div class="footer">
    <div class="email">Все вопросы вы можете задать по почте <a>ItmSapce@gmail.com</a></div>
    <div class="git"><a href="https://github.com/Entreaty/eco">Git</a></div>
</div>

</body>
</html>