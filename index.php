<?php
function getuserslist() {
    return array(
        array('login' => 'user1', 'password' => 'hash1'),
        array('login' => 'user2', 'password' => 'hash2'),
        array('login' => 'user3', 'password' => 'hash3')
    );
}

function encryptpassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function authenticateuser($login, $password)
{
    $users = file_get_contents('users.txt');
    $users = explode(PHP_EOL, $users);

    foreach ($users as $user) {
        list($storedlogin, $storedpassword) = explode('|', $user);
        if ($login === $storedlogin && password_verify($password, $storedpassword)) {
            return true;
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    if (authenticateuser($login, $password)) {
        header('Location: /personal_area.php');
        exit;
    } else {
        echo 'неправильный логин или пароль';
    }
}

function existsuser($login) {
    $users = getuserslist();

    foreach($users as $user) {
        if ($user['login'] == $login) {
            return true;
        }
    }
    return false;
}

function checkpassword($login, $password) {
    $users = getuserslist();

    foreach($users as $user) {
        if ($user['login'] == $login && $user['password'] == $password) {
            return true;
        }
    }
    return false;
}

function getcurrentuser() {
    if(isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }
    return null;
}

if(getcurrentuser() != null) {
    header('Location: index.php');
    exit();
}

echo '<form method="POST" action="authenticate.php">
    <input type="text" name="login" placeholder="логин"><br>
    <input type="password" name="password" placeholder="пароль"><br>
    <input type="submit" value="войти">
</form>';

require_once('users.php');

$login = $_POST['login'];
$password = $_POST['password'];

if(existsuser($login)) {
    if(checkpassword($login, $password)) {
        session_start();
        $_SESSION['username'] = $login;

        header('Location: index.php');
        exit();
    }
}
echo 'ошибка авторизации!';

$userbirthday = '1990-06-15';
$currentdate = date('y-m-d');
$nextbirthday = date('Y') . '-' . date('m-d', strtotime($userbirthday));
$daysuntilbirthday = floor((strtotime($nextbirthday) - strtotime($currentdate)) / (60*60*24));

if ($currentdate == $nextbirthday) {
    echo 'поздравляем! сегодня ваш день рождения!';
    echo 'у вас персональная скидка 5% на все услуги салона.';
} else {
    echo 'до вашего дня рождения осталось ' . $daysuntilbirthday . ' дней.';
    echo 'насладитесь скидкой 5% на все услуги салона в этот особенный период!';
}

$start_time = strtotime("now");
$end_time = $start_time + 24*60*60;
$time_in = strtotime("now");

if (isset($_COOKIE['last_visit'])) {
    $last_visit = $_COOKIE['last_visit'];
    $time_remaining = $end_time - strtotime($last_visit);
} else {
    $time_remaining = $end_time - $time_in;
    setcookie('last_visit', date('y-m-d h:i:s'), time()+60*60*24);
}

$remaining_hours = floor($time_remaining / 3600);
$remaining_minutes = floor(($time_remaining % 3600) / 60);
$remaining_seconds = $time_remaining % 60;

echo "до истечения персональной скидки осталось: " . $remaining_hours . " часов " .
    $remaining_minutes . " минут " . $remaining_seconds . " секунд";

require_once('users.php');

if(getcurrentuser() == null) {
    header('Location: login.php');
    exit();
}

echo 'добро пожаловать, ' . getcurrentuser() . '!';

echo 'актуальные услуги:<br>
    - пилинг тела<br>
    - гигигеническая чистка лица<br>
    - нежный шелк<br><br>

    акции:<br>
    - классический массаж<br>
    - оформление бровей<br>
    - окраска ресниц<br><br>

фото салона:<br>
    <img src=https://ru.freepik.com/free-photo/woman-taking-the-bath-salt-to-put-some-in-the-water-before-taking-a-bath_21913193.htm alt="фото 1"><br>
    <img src="https://ru.freepik.com/free-photo/young-woman-relaxing-in-spa-salon_8224695.htm#query=spa&position=19&from_view=keyword&track=sph&uuid=ef0b31a3-45f7-4ebd-b009-984bdf3523f3" alt="фото 2"><br>
    <img src="https://ru.freepik.com/premium-photo/spa-towels-stack-in-front-of-a-wooden-counter_48824672.htm#query=spa&position=32&from_view=keyword&track=sph&uuid=ef0b31a3-45f7-4ebd-b009-984bdf3523f3" alt="фото 3"><br>';

session_start();

if(isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
}
header("Location: index.php");
exit;
?>