<?php
namespace Acme\EcoBundle\Controller;


use Acme\EcoBundle\Entity\NewFamily;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\EcoBundle\Entity\Transaction;
use Acme\EcoBundle\Entity\Member;
use Acme\EcoBundle\Entity\Family;
use Acme\EcoBundle\Entity\NewMember;
use Acme\EcoBundle\Entity\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthController extends Controller
{
    public function hidePass($item)
    {
        if($item !== null) {
            $item = hash('sha1', $item);
            $item = substr($item, 20, 20) . substr($item, 0, 20);
        }
        return $item;
    }

    public function onlyString($item)
    {
        return $item = preg_replace('/[^a-zа-я]/iu', '', $item);
    }

    public function onlyNumeral($item)
    {
        return $item = preg_replace('/[^0-9]/iu', '', $item);
    }

    public function onlyNumeralAndString($item)
    {
        return $item = preg_replace('/[^a-zа-я0-9]/iu', '', $item);
    }

    //  Регистрация пользователя
    public function registrationAction(Request $request)
    {
        $surname = $this->onlyString($request->request->get('surname'));
        $name = $this->onlyString($request->request->get('name'));
        $secondname = $this->onlyString($request->request->get('secondname'));
        $password = $this->hidePass($this->onlyNumeralAndString($request->request->get('password')));
        $login = $this->onlyNumeralAndString($request->request->get('login'));

        if($surname && $name && $secondname && $password && $login)
        {
        //  Проверка на совпадение логина (логин должен быть уникален!)
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT m.memberId
                  FROM AcmeEcoBundle:Member m
                  WHERE m.login = :login'
            )->setParameter('login', $login);

            //  Если логин оригинальный, регистрируем нового пользователя
            $exist = $query->getResult();
            if (!$exist) {
                $member = new Member;
                $member->setName($name);
                $member->setSurname($surname);
                $member->setSecondname($secondname);
                $member->setPassword($password);
                $member->setLogin($login);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($member);
                $em->flush();

                // Очищаем переменные сессиии старых пользователей
                $this->get('session')->set('memId', null);
                $this->get('session')->set('fmId', null);
                // Создаем переменную личного идентификатора memId в сессии
                $memberId = $member->getMemberId();
                $this->get('session')->set('memId', ($memberId));
                setcookie('memberId', $memberId, null, '/web/index.php');
                setcookie('familyId', null, 0, '/web/index.php');

                return new Response('Ok!');
            } else {
                return new Response('Login already exist!');
            }
        }else{
            return new Response('Bad!');
        }
    }

    //  Авторизация пользователя
    public function authAction(Request $request)
    {
        $login = $this->onlyNumeralAndString($request->request->get('login'));
        $password = $this->hidePass($this->onlyNumeralAndString($request->request->get('password')));

        //  Проверка введеных данных
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.memberId, m.familyId
              FROM AcmeEcoBundle:Member m
              WHERE m.login = :login
              AND m.password = :password'
        )->setParameters(array(
            'login' => $login,
            'password' => $password
        ));
        $exist = $query->getResult();

        if ($exist) {
            // Добавляем в переменные сессии familyId и memberId
            if ($exist[0]["memberId"]) {
                $this->get('session')->set('memId', ($exist[0]["memberId"]));
                setcookie('memberId', $exist[0]["memberId"], null, '/web/index.php');
            }
            if ($exist[0]["familyId"]) {
                $this->get('session')->set('fmId', ($exist[0]["familyId"]));
                setcookie('familyId', $exist[0]["familyId"], null, '/web/index.php');
            }

            return new Response('Ok!');

        } else

            return new Response('No!');

    }

    public function logoutAction(Request $request)
    {
        $this->get('session')->set('fmId', null);
        $this->get('session')->set('memId', null);
        setcookie('memberId', null, 0, '/web/index.php');
        setcookie('familyId', null, 0, '/web/index.php');
        return $this->redirect('/web/index.php');
    }

    public function checkAuthAction()
    {
        $fmId = $this->get('session')->get('fmId');
        $memId = $this->get('session')->get('memId');
        $checkAuth = json_encode(array('memberId' => $memId, 'familyId' => $fmId));
        return new Response("$checkAuth");
    }

}