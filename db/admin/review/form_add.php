<!--Добавление рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $_GET['id_w']?>">Усі роботи</a></header>
<header>Додавання рецензії</header>
<form class="addreviewForm" method="post" action="action.php"
      oninput="summa.value=parseInt(actual.value)
      +parseInt(original.value)
      +parseInt(methods.value)
      +parseInt(theoretical.value)
      +parseInt(practical.value)
      +parseInt(literature.value)
      +parseInt(selfcontained.value)
      +parseInt(design.value)
      +parseInt(publication.value)
      +parseInt(government.value)
      +parseInt(tendentious.value)
">

    <label>Оберіть роботу :</label><?php list_works($_GET['id_w']); ?>
    <fieldset name="descriptionWorks" id="descriptionWorks">
        <legend>Данні з роботи(виключно для рецензента)</legend>
        <p>
            <?php
            global $link;
            $query = "SELECT works.introduction,works.public,works.comments FROM works WHERE id={$_GET['id_w']}";
            mysqli_query($link, "SET NAMES 'utf8'");
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            $result = mysqli_query($link, $query)
            or die("Помилка запиту отримання інформації по роботі. : " . mysqli_error($link));
            $row = mysqli_fetch_array($result);
            $strArray = array();
            if ($row['introduction'] <> "") {
                $strArray[] = "<strong>Впровадженння:</strong>{$row['introduction']}";
            }
            if ($row['public'] <> "") {
                $strArray[] = "<strong>Результати опубліковано:</strong>{$row['public']}";
            }
            if ($row['comments'] <> "") {
                $strArray[] = "<strong>Коментар/зауваження до матеріалів:</strong>{$row['comments']}";
            }
            if (count($strArray) < 1) {
                $str = "<strong>Увага! Без публікації та впровадження. Зауваження з боку офрмлення документів відсутні.</strong>";
            } elseif (count($strArray) == 1) {
                $str = $strArray[0];
            } else {
                $str = implode("<br>", $strArray);
            }
            echo $str;
            ?>
        </p></fieldset>
<table>
    <tr><th>#</th><th>Показник</th><th colspan="2">Бали</th></tr>
    <tr>
        <td>1</td><td>Актуальність проблеми</td>
        <td><input  type="range"  name="actual" min="0" max="10" step="1" title="max 10"  value ="10" oninput="actualOutput.value = actual.value" ></td>
        <td><output class="balls" name="actualOutput">10</output></td>
    </tr>
    <tr>
        <td>2</td><td>Новизна та оригінальність ідей</td>
        <td><input type="range" name="original" min="0" max="15" step="1"   title="max 15" value="15" oninput="originalOutput.value = original.value"></td>
        <td><output class="balls" name="originalOutput">15</output></td>
    </tr>
    <tr>
        <td>3</td><td>Використані методи дослідження</td>
        <td><input type="range" name="methods" min="0" max="15" step="1"   title="max 15" value="15" oninput="methodsOutput.value = methods.value"></td>
        <td><output class="balls" name="methodsOutput">15</output></td></tr>
    <tr>
        <td>4</td><td>Теоретичні наукові результати</td>
        <td><input type="range" name="theoretical" min="0" max="10" step="1"  title="max 10" value="10" oninput="theoreticalOutput.value = theoretical.value"></td>
        <td><output class="balls" name="theoreticalOutput">10</output></td></tr>
    <tr>
        <td>5</td><td>Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи)</td>
        <td><input type="range" name="practical" min="0" max="20" step="1"  title="max 20" value="20" oninput="practicalOutput.value = practical.value"></td>
        <td><output class="balls" name="practicalOutput">20</output></td></tr>
    <tr>
        <td>6</td><td>Рівень використання наукової літератури та інших джерел інформації</td>
        <td><input type="range" name="literature" min="0" max="5" step="1"  title="max 5" value="5" oninput="literatureOutput.value = literature.value"></td>
        <td><output class="balls" name="literatureOutput">5</output></td></tr>
    <tr>
        <td>7</td><td>Ступінь самостійності роботи</td>
        <td><input type="range" name="selfcontained" min="0" max="10" step="1"  title="max 10" value="10" oninput="selfcontainedOutput.value = selfcontained.value"></td>
        <td><output class="balls" name="selfcontainedOutput">10</output></td></tr>
    <tr>
        <td>8</td><td>Якість оформлення</td>
        <td><input type="range" name="design" min="0" max="5" step="1"  title="max 5" value="5" oninput="designOutput.value = design.value"></td>
        <td><output class="balls" name="designOutput">5</output></td></tr>
    <tr>
        <td>9</td><td>Наукові публікації</td>
        <td><input type="range" name="publication" min="0" max="10" step="1"  title="max 10" value="10" oninput="publicationOutput.value = publication.value"></td>
        <td><output class="balls" name="publicationOutput">10</output></td></tr>
    <tr>
        <td>10</td><td>Відповідність роботи Державній програмі<br>пріоритетних напрямків інноваційної діяльності
            <br>
            До пріоритетів віднесено: (<a href="https://ligazakon.net/document/view/kp161056">постановою КМУ від 28.12.2016 р. № 1056.</a>)
            <ol>
                <li> освоєння нових технологій транспортування енергії, упровадження енергоефективних, ресурсозберігаючих технологій, освоєння альтернативних джерел енергії;
                <li> освоєння нових технологій розвитку транспортної системи, ракетно-космічної галузі, авіа- і суднобудування, озброєння та військової техніки;
                <li> освоєння нових технологій виробництва матеріалів, їх оброблення і з'єднання, створення індустрії наноматеріалів та нанотехнологій;
                <li> технологічне оновлення та розвиток агропромислового комплексу;
                <li> упровадження нових технологій та обладнання для якісного медичного обслуговування, лікування, фармацевтики;
                <li> застосування технологій більш чистого виробництва та охорони навколишнього природного середовища;
                <li> розвиток інформаційних і комунікаційних технологій, робототехніки.
            </ol>
        </td>
        <td><input type="range" name="government" min="0" max="10" step="1"  title="max 10" value="10" oninput="governmentOutput.value = government.value"></td>
        <td><output class="balls" name="governmentOutput">10</output></td></tr>
    <tr>
        <td>11</td><td>Відповідність роботи сучасним світовим тенденціям розвитку<br> електроенергетики, електротехніки та електромеханіки.

        </td>
        <td><input type="range" name="tendentious" min="0" max="10" step="1"  title="max 10" value="10" oninput="tendentiousOutput.value = tendentious.value"></td>
        <td><output class="balls" name="tendentiousOutput">10</output></td></tr>
    <tr>
        <td>12</td><td><strong>Сума</strong></td><td colspan="2"><output name="summa" class="balls" >120</output></td>
    </tr>
</table>
    <fieldset><legend>Зауваження та недоліки</legend>
    <textarea name="defects" wrap="virtual" rows="4" cols="40" maxlength="4096"
              placeholder="Недлоліки роботи по пунктах в яких низька кількість балів або об’єктина оцінка. В середньому 2-4 речення."></textarea>
    </fieldset>
    <br><label>Висновок рецензента :</label>
    <select name="conclusion">
        <option value="-1" disabled selected>Зробіть висновок</option>
        <option value="1">Рекомендувати</option>
        <option value="0">Не рекомендувати</option>
    </select><label>до участі у підсумковій конференції.</label><br>
    <label>Рецензент :</label><?php cbo_reviewers_list($id = -1, $_GET['id_u'], $_GET['id_w']); ?>
    <input type="submit" value="Записати"><input type="button" value="Повернутися" name="return"
                                                 onclick="window.location='action.php?action=all_view#id_w'+<?= $_GET['id_w'] ?>">
    <input type="hidden" name="action" value="review_add">
</form>
