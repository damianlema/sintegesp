<?
extract($_POST);

if ($ejecutar == "consultar") {
    if (isset($_COOKIE["contador"])) {
        echo $_COOKIE["contador"];
    } else {
        setcookie("contador", 1);
        echo $_COOKIE["contador"];
    }

}

if ($ejecutar == "sumar") {
    $_COOKIE["contador"]++;
    setcookie("contador", $_COOKIE["contador"]);
    echo $_COOKIE["contador"];
}
