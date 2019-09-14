<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 10.11.17
 * Time: 22:45
 */
$query = "SELECT reviews.*, works.title FROM reviews
          JOIN works ON works.id = reviews.id_w 
          WHERE `reviews`.`id` ='{$_GET['id']}'";
global $link;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query) or die("Invalid query in function list_reviews_for_one_work : " . mysqli_error($link));
$row = mysqli_fetch_array($result);
//print_r(array_slice($row,4,22));
?>
<header><a href="action.php?action=all_view#id_w<?= $row['id_w']?>">Усі роботи</a></header>
<header>Редагування рецензії</header>

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
      +parseInt(tendentious.value)">
    <h1><?= $row['title']?></h1>
    <fieldset name="descriptionWorks" id="descriptionWorks">
        <legend>Данні з роботи(виключно для рецензента)</legend>
        <p>
            <?php
            $query2 = "SELECT works.introduction,works.public,works.comments FROM works WHERE id={$row['id_w']}";
            mysqli_query($link, "SET NAMES 'utf8'");
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            $result2 = mysqli_query($link, $query2)
            or die("Помилка запиту отримання інформації по роботі. : " . mysqli_error($link));
            $row2 = mysqli_fetch_array($result2);
            $strArray = array();
            if ($row2['introduction'] <> "") {
                array_push($strArray, "<strong>Впровадженння:</strong>{$row2['introduction']}");
            }
            if ($row2['public'] <> "") {
                array_push($strArray, "<strong>Результати опубліковано:</strong>{$row2['public']}");
            }
            if ($row2['comments'] <> "") {
                array_push($strArray, "<strong>Коментар/зауваження до матеріалів:</strong>{$row2['comments']}");
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
            <td><input  type="range"  name="actual" min="0" max="10" step="1" title="max 10"  value ="<?= $row['actual']?>" oninput="actualOutput.value = actual.value" ></td>
            <td><output class="balls" name="actualOutput"><?= $row['actual']?></output></td>
        </tr>
        <tr>
            <td>2</td><td>Новизна та оригінальність ідей</td>
            <td><input type="range" name="original" min="0" max="15" step="1"   title="max 15" value="<?= $row['original']?>" oninput="originalOutput.value = original.value"></td>
            <td><output class="balls" name="originalOutput"><?= $row['original']?></output></td>
        </tr>
        <tr>
            <td>3</td><td>Використані методи дослідження</td>
            <td><input type="range" name="methods" min="0" max="15" step="1"   title="max 15" value="<?= $row['methods']?>" oninput="methodsOutput.value = methods.value"></td>
            <td><output class="balls" name="methodsOutput"><?= $row['methods']?></output></td></tr>
        <tr>
            <td>4</td><td>Теоретичні наукові результати</td>
            <td><input type="range" name="theoretical" min="0" max="10" step="1"  title="max 10" value="<?= $row['theoretical']?>" oninput="theoreticalOutput.value = theoretical.value"></td>
            <td><output class="balls" name="theoreticalOutput"><?= $row['theoretical']?></output></td></tr>
        <tr>
            <td>5</td><td>Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи)</td>
            <td><input type="range" name="practical" min="0" max="20" step="1"  title="max 20" value="<?= $row['practical']?>" oninput="practicalOutput.value = practical.value"></td>
            <td><output class="balls" name="practicalOutput"><?= $row['practical']?></output></td></tr>
        <tr>
            <td>6</td><td>Рівень використання наукової літератури та інших джерел інформації</td>
            <td><input type="range" name="literature" min="0" max="5" step="1"  title="max 5" value="<?= $row['literature']?>" oninput="literatureOutput.value = literature.value"></td>
            <td><output class="balls" name="literatureOutput"><?= $row['literature']?></output></td></tr>
        <tr>
            <td>7</td><td>Ступінь самостійності роботи</td>
            <td><input type="range" name="selfcontained" min="0" max="10" step="1"  title="max 10" value="<?= $row['selfcontained']?>" oninput="selfcontainedOutput.value = selfcontained.value"></td>
            <td><output class="balls" name="selfcontainedOutput"><?= $row['selfcontained']?></output></td></tr>
        <tr>
            <td>8</td><td>Якість оформлення</td>
            <td><input type="range" name="design" min="0" max="5" step="1"  title="max 5" value="<?= $row['design']?>" oninput="designOutput.value = design.value"></td>
            <td><output class="balls" name="designOutput"><?= $row['design']?></output></td></tr>
        <tr>
            <td>9</td><td>Наукові публікації</td>
            <td><input type="range" name="publication" min="0" max="10" step="1"  title="max 10" value="<?= $row['publication']?>" oninput="publicationOutput.value = publication.value"></td>
            <td><output class="balls" name="publicationOutput"><?= $row['publication']?></output></td></tr>
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
            <td><input type="range" name="government" min="0" max="10" step="1"  title="max 10" value="<?= $row['government']?>" oninput="governmentOutput.value = government.value"></td>
            <td><output class="balls" name="governmentOutput"><?= $row['government']?></output></td></tr>
        <tr>
            <td>11</td><td>Відповідність роботи сучасним світовим тенденціям розвитку<br> електроенергетики, електротехніки та електромеханіки.
            </td>
            <td><input type="range" name="tendentious" min="0" max="10" step="1"  title="max 10" value="<?= $row['tendentious']?>" oninput="tendentiousOutput.value = tendentious.value"></td>
            <td><output class="balls" name="tendentiousOutput"><?= $row['tendentious']?></output></td></tr>
        <tr>
            <td>12</td><td><strong>Сума</strong></td><td colspan="2"><output name="summa" class="balls" ><?php echo array_sum(array_slice($row,4,22))/2;?></output></td>
        </tr>
    </table>

    <fieldset><legend>Зауваження та недоліки</legend>
    <textarea name="defects" wrap="virtual" rows="4" cols="40" maxlength="4096" placeholder="Недлоліки роботи"><?php echo(htmlspecialchars($row['defects'])); ?></textarea>
    </fieldset>
    <br>
    <?php
    $selected1 = ($row['conclusion'] == 1) ?"selected":"";
    $selected0 = ($row['conclusion'] == 0) ?"selected":"";
    ?><label>Висновок рецензента :</label>
    <select name="conclusion">
        <option value="1" <?= $selected1?>>Рекомендувати</option>
        <option value="0" <?= $selected0?>>Не рекомендувати</option>
    </select><label>до участі у підсумковій конференції.</label><br>
    <label>Рецензент :</label><?php cbo_reviewers_list($row['review1']);?>

    <br>
    <?php
    $query = "SELECT leaders.id_tzmember FROM leaders WHERE leaders.id = {$row['review1']}";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query ag_form_edit_reviewer.pnp: " . mysqli_error($link));
    $row = mysqli_fetch_assoc($result);
    if ($row['id_tzmember'] == $_SESSION['id'] OR $_SESSION['id'] == 1 OR $_SESSION['id'] == 2){
        //Коментить
        printf("<input type=\"submit\" value=\"Записати\">");
    }
    else{
        printf("<mark>Шановний користувач Ви не є автором цієї рецензії і не можете її редагувати!</mark><br>\n");
    }
    ?>

    <input type="button" value="Повернутися" onclick="window.location='action.php?action=all_view#id_w'+<?= $row['id_w']?>">
    <input type="hidden" name="action" value="review_edit">
    <input type="hidden" name="id" value="<?= $_GET['id']?>">
    <input type="hidden" name="id_w" value="<?= $row['id_w']?>">

</form>
