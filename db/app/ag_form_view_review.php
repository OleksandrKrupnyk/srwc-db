<?php
$query = "SELECT `reviews`.*, works.title,
          SUM(`actual`+`original`+`methods`+`theoretical`+`practical`+`literature`+`selfcontained`+`design`+`publication`+`government`+`tendentious`) AS sumball
          FROM `reviews`
          JOIN `works` ON `works`.`id` = `reviews`.`id_w` 
          WHERE `reviews`.`id` ='{$_GET['id']}'";
$result = mysqli_query($link, $query) or die('Invalid query in function list_reviews_for_one_work : ' . mysqli_error($link));
$row = mysqli_fetch_array($result);
echo '<h1>Реєстр робіт Всеукраїнського конкурсу студентських наукових робіт<br>&quot;Електротехніка та електромеханіка&quot;</h1>'
    . '<h2>Рецензія на студентську наукову роботу</h2>'
    . "<h3>&laquo;{$row['title']}&raquo;</h3>";
?>

    <table style="box-sizing: border-box; border-collapse: collapse">
        <tr><th>&numero;</th><th>Показник</th><th>Бали</th></tr>
        <tr>
            <td>1</td><td>Актуальність проблеми</td>
            <td class='balls'><?= $row['actual']?></td>
        </tr>
        <tr>
            <td>2</td><td>Новизна та оригінальність ідей</td>
            <td class='balls'><?= $row['original']?></td></tr>
        <tr>
            <td>3</td><td>Використані методи дослідження</td>
            <td class='balls'><?= $row['methods']?></td></tr>
        <tr>
            <td>4</td><td>Теоретичні наукові результати</td>
            <td class='balls'><?= $row['theoretical']?></td></tr>
        <tr>
            <td>5</td><td>Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи)</td>
            <td class='balls'><?= $row['practical']?></td></tr>
        <tr>
            <td>6</td><td>Рівень використання наукової літератури та інших джерел інформації</td>
            <td class='balls'><?= $row['literature']?></td></tr>
        <tr>
            <td>7</td><td>Ступінь самостійності роботи</td>
            <td class='balls'><?= $row['selfcontained']?></td></tr>
        <tr>
            <td>8</td><td>Якість оформлення</td>
            <td class='balls'><?= $row['design']?></td></tr>
        <tr>
            <td>9</td><td>Наукові публікації</td>
            <td class='balls'><?= $row['publication']?></td></tr>
        <tr>
            <td>10</td><td>Відповідність роботи Державній програмі пріоритетних напрямків інноваційної діяльності
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
            <td class='balls'><?= $row['government'] ?></td>
        </tr>
        <tr>
            <td>11</td>
            <td>Відповідність роботи сучасним світовим тенденціям розвитку<br> електроенергетики, електротехніки та
                електромеханіки.
            </td>
            <td class='balls'><?= $row['tendentious'] ?></td>
        </tr>
        <tr>
            <td>12</td>
            <td>Сума.</td>
            <td class='balls'><?= $row['sumball'] ?></td>
        </tr>
    </table>
<fieldset>
    <legend>Зауваження та недоліки</legend>
    <p>
        <?= htmlspecialchars($row['defects']); ?>
    </p>
</fieldset>
<?php $conclusion = ($row['conclusion'] == 0) ? 'Не рекомендується ' : 'Рекомендується'; ?>
<p>Загальний висновок : <?= $conclusion ?> для захисту на науково-практичній конференції</p>