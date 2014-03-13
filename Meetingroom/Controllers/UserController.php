<?php

namespace Meetingroom\Controllers;

class UserController extends AbstractController
{
    public function indexAction($page, $asd) 
    {
       print_r(func_get_args());
    }

    public function testAction()
    {
        echo "<h1>User2!</h1>";
    }

    public function testloginAction()
    {
        echo "<h3>Проверочный запрос к LDAP</h3>";
        echo "Подключение ...";
        $ds=ldap_connect("ldap.syneforge.com");  // Необходимо указать корректный LDAP сервер
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        echo "Результат подключения: " . $ds . "<br />";

        $pass="";
        $nickname="";
        if ($ds) {
            echo "Привязка ...";
            $r=ldap_bind($ds,"uid=".$nickname.",ou=people,dc=syneforge,dc=com",$pass);
            //$r=ldap_bind($ds);     // "анонимная" привязка,
            // доступ только для чтения
            echo "Результат привязки: " . $r . "<br />";

            echo "Поиск (sn=S*) ...";
            // Поиск по фамилиям записей
            $sr=ldap_search($ds, "ou=people,dc=syneforge,dc=com", "uid=barif");
            echo "Результат поиска: " . $sr . "<br />";

            echo "Получено количество записей " . ldap_count_entries($ds, $sr) . "<br />";

            echo "Получение элементов ...<p>";
            $info = ldap_get_entries($ds, $sr);
            echo "Элемент: " . $info["count"] . " Данные:<p>";

            for ($i=0; $i<$info["count"]; $i++) {
                //var_dump('<pre>',$info[$i]);
                //die();
                echo "dn: " . $info[$i]["dn"] . "<br />";
                echo "первый cn: " . $info[$i]["cn"][0] . "<br />";
                echo "первый email: " . $info[$i]["mail"][0] . "<br />";
                echo "title: " . $info[$i]["title"][0] . "<br /><hr />";
            }

            echo "Закрытие соединения";
            ldap_close($ds);

        } else {
            echo "<h4>Невозможно подключиться к серверу LDAP</h4>";
        }

    }


}
