<header><a href="action.php">Меню</a></header>
<header>Усі данні роботи</header>
<form class="addAllForm" method="post" action="action.php">
    <?php (isset($_GET['id_u'])) ? list_univers($_GET['id_u'], 1) : list_univers("", 1); ?>
    <br>
    <div class="listsaotursleaders">
    <select id="listautors" size="10" name="id_a">
    <option value="-1" disabled="">Автори університету</option>
    <option value="888" selected>Відсутній у списку</option>
    </select>
    <select id="listleaders" size="10" name="id_l" >
     <option value="-1" disabled="">Керівники університету</option>
     <option value="888" selected >Відсутній у списку</option>
    </select>
    </div>
    <br>
    <fieldset id="data_autor">
        <legend>Данні автора</legend>
        <label>ПІБ</label>
        <input type="text" name="sunameA" title="Прізвище" placeholder="Прізвище" required>
        <input type="text" name="nameA" title="Ім'я" placeholder="Ім'я" id="name" required>
        <input type="text" name="lnameA" title="По-батькові" placeholder="По-батькові" id="lname">
        <select size="1" name="curse" title="Курс навчання">
            <option value="0" disabled selected>Курс...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select><br>
        <label>Электронна скринька:</label>
        <input type="email" name="emailA" title="Наприклад:user@mail.ru" placeholder="Электронна скринька">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phoneA" title="Наприклад:0985622012"
               placeholder="Номер телефону">
    </fieldset>
    <br>
    
 
    <fieldset id="data_leader">
        <legend>Данні про керівника</legend>
        <label>ПІБ</label>
        <input type="text" name="sunameL" title="Прізвище" placeholder="Прізвище" required>
        <input type="text" name="nameL" title="Ім'я" placeholder="Ім'я" id="name" required>
        <input type="text" name="lnameL" title="По-батькові" placeholder="По-батькові" id="lname" required>
        <br>
        <?php list_("positions", "position", "", 1, "Посада...") ?>
        <?php list_("statuses", "statusfull", "", 1, "Вчене звання...") ?>
        <?php list_("degrees", "degree", "", 1, "Науковий ступінь...") ?>

        <br>
        <label>Електронна скринька:</label>
        <input type="email" name="emailL" title="Наприклад:user@mail.ru" placeholder="Електронна скринька">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phoneL" title="Наприклад:0985622012"
               placeholder="Номер телефону">
    </fieldset>

    <fieldset>
        <legend>Данні роботи</legend>
        <label>Назва роботи:</label><br>
        <textarea name="title" cols="50" rows="4" wrap="virtual" maxlength="255"
                  title="Назва роботи (заповнювати на укр.мові)" placeholder="Назва роботи (заповнювати на укр.мові)"
                  required></textarea>
        <br>
        <?php list_("sections", "section", "", 1, "Секція..."); ?><br>
        <label>Девіз(ШИФР):</label>
        <input type="text" name="motto" title="Дивіз роботи." placeholder="Девіз..." required autocomplete="off"><br>
        <label>Результати публікації:</label>
        <input type="text" name="public" title="Наприклад: 1 патент, 2 статті"
               placeholder="Наприклад: 1 патент, 2 статті"><br>
        <label>Результати впровадження:</label>
        <input type="text" name="introduction" title="Наприклад: навч.процес,НИП &quot;Дія&quot;"
               placeholder="Наприклад: навч.процес,НIП &quot;Дія&quot;"><br/>
        <fieldset>
            <legend>Службова інформація</legend>
            <label>Тезиси:</label>
            <input type="checkbox" name="tesis" title="Відмітити якщо є тезиси">
            <label>Мертва душа:</label>
            <input type="checkbox" name="dead" title="Відмітити якщо робота фіктивна"><br>
            <textarea name="comments" wrap="virtual" rows="4" cols="80" maxlength="255" placeholder="Зауваження та коментарії"></textarea>
        </fieldset>
    </fieldset>
    <br>
    <input type="submit" value="Записати">
    <input type="hidden" name="action" value="all_add">
    <?php print_datalist_name("name");
    print_datalist_name("lname"); ?>
</form>
<!-- Окончание Форма добавления всех сведений-->
