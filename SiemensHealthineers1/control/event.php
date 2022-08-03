<?php
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'getcountries':
            $curr = $_POST['country'];
            $event = new Event();

            $countries = $event->getCountries();
            //var_dump($countries);
            if (!empty($countries)) {

?>
                <select class="input" id="country" name="country" onChange="updateState()">
                    <option value="0" <?= ($curr == 0) ? 'selected' : '' ?>>Select Country</option>
                    <?php
                    foreach ($countries as $country) {
                    ?>
                        <option <?= ($curr == $country['id']) ? 'selected' : '' ?> value="<?= $country['id'] ?>"><?= iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $country['name']) ?></option>
                    <?php
                    }
                    ?>
                </select>
            <?php
            }

            break;

        case 'getstates':
            $cntry = $_POST['country'];
            $event = new Event();
            $states = $event->getStates($cntry);
            //var_dump($states);
            if (!empty($states)) {

            ?>
                <select class="input" id="state" name="state" onChange="updateCity()">
                    <option value="0">Select State</option>
                    <?php
                    foreach ($states as $state) {
                    ?>
                        <option value="<?= $state['id'] ?>"><?= iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $state['name']) ?></option>
                    <?php
                    }
                    ?>
                </select>
            <?php
            }

            break;

        case 'getcities':
            $state = $_POST['state'];
            $event = new Event();
            $cities = $event->getCities($state);
            //var_dump($cities);
            if (!empty($cities)) {

            ?>
                <select class="input" id="city" name="city">
                    <option value="0">Select City</option>
                    <?php
                    foreach ($cities as $city) {
                    ?>
                        <option value="<?= $city['id'] ?>"><?= iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $city['name']) ?></option>
                    <?php
                    }
                    ?>
                </select>
<?php
            }

            break;

        case 'updateFileDLCount':

            $resid = $_POST["resId"];
            $userid = $_POST['userId'];

            $exhib = new Event();
            $exhib->__set('res_id', $resid);
            $exhib->__set('user_id', $userid);
            $vidupd = $exhib->updateFileDLCount();

            //var_dump($vidupd);

            break;
    }
}
