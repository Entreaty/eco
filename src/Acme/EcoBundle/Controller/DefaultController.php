<?php

namespace Acme\EcoBundle\Controller;


use Acme\EcoBundle\Entity\NewFamily;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\EcoBundle\Entity\Transaction;
use Acme\EcoBundle\Entity\Member;
use Acme\EcoBundle\Entity\Family;
use Acme\EcoBundle\Entity\NewMember;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
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
        return $item = preg_replace('/[^a-zа-я0-9 ]/iu', '', $item);
    }

    //  Создание новой семьи
    public function newFamilyAction(Request $request)
    {
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyName = $this->onlyString($request->request->get('familyName'));

        if($familyName && $memberId){
        $family = new Family();
        $family->setFamilyName($familyName);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($family);
        $em->flush();

        //  Создаем familyId переменную в сессии
        $familyId = $family->getFamilyId();
        $this->get('session')->set('fmId', ($familyId));
        setcookie('familyId', $familyId, null, '/web/index.php');

        //  Заполняем familyId пользователя
        $changeFamilyId = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:Member')
            ->find($memberId);
        $changeFamilyId->setFamilyId($familyId);
        $em->flush();

        return new Response('Ok!');
        }else{
            return new Response('Bad name!');
        }
    }

    //  Создание нового члена семьи
    public function newMemberAction(Request $request)
    {
        $surname = $this->onlyString($request->request->get('surname'));
        $name = $this->onlyString($request->request->get('name'));
        $secondname = $this->onlyString($request->request->get('secondname'));
        $login = $this->onlyNumeralAndString($request->request->get('login'));
        $password = $this->hidePass($this->onlyNumeralAndString($request->request->get('password')));

        if(($surname && $name && $secondname && $login && $password)!==null){

        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.memberId FROM AcmeEcoBundle:Member m WHERE m.login = :login'
        )->setParameter('login', $login);

        $exist = $query->getResult();
        if (!$exist) {
            $member = new Member;
            $member->setName($name);
            $member->setSurname($surname);
            $member->setSecondname($secondname);
            $member->setPassword($password);
            $member->setLogin($login);
            $member->setFamilyId($familyId);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($member);
            $em->flush();

            return new Response('Ok!');
        } else {
            return new Response('Login already exist!');
        }
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Изменение личных данных члена семьи
    public function changeMemberAction(Request $request)
    {
        $surname = $this->onlyString($request->request->get('surname'));
        $name = $this->onlyString($request->request->get('name'));
        $secondname = $this->onlyString($request->request->get('secondname'));
        $login = $this->onlyNumeralAndString($request->request->get('login'));
        $password = $this->hidePass($this->onlyNumeralAndString($request->request->get('password')));
        $memberId = $this->onlyNumeral($request->request->get('memberId'));

        if($surname && $name && $secondname && $login && $password && $memberId){
            $changeMember = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($memberId);
            $changeMember->setName($name);
            $changeMember->setSurname($surname);
            $changeMember->setSecondname($secondname);
            $changeMember->setPassword($password);
            $changeMember->setLogin($login);
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Удаление члена семьи
    public function deleteMemberAction(Request $request)
    {
        $memberId = $this->onlyNumeral($request->request->get('memberId'));

        // Если пользователь удаляет себя - чистим сессионные переменные
        if ($this->get('session')->get('memId') == $memberId) {
            $deleteMember = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($this->get('session')->get('memId'));
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($deleteMember);
            $em->flush();

            $this->get('session')->set('fmId', null);
            $this->get('session')->set('memId', null);
            setcookie('memberId', null, 0, '/web/index.php');
            setcookie('familyId', null, 0, '/web/index.php');

            return new Response('Bye!');

        } else {
            $deleteMember = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($memberId);
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($deleteMember);
            $em->flush();

            return new Response('Ok!');
        }
    }

    //  Список существующих членов семьи
    public function listMemberAction()
    {
        $familyId = $this->get('session')->get('fmId');
        $memberId = $this->get('session')->get('memId');

        if($familyId){
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.memberId, m.name, m.surname,
                    m.secondname,
                    m.familyId
              FROM AcmeEcoBundle:Member m
              WHERE m.familyId = :familyId'
        )->setParameters(array(
            'familyId' => $familyId,
        ));
        $listMember = $query->getResult();
        }else{
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT m.memberId, m.name, m.surname,
                    m.secondname,
                    m.familyId
              FROM AcmeEcoBundle:Member m
              WHERE m.memberId = :memberId'
            )->setParameters(array(
                'memberId' => $memberId,
            ));
            $listMember = $query->getResult();
        }
        $count = count($listMember);
        for ($i = 0; $i < $count; $i++) {
            $listMember{$i} = $listMember[$i];
        }
        //  Возвращаем результат в удобной форме json
        return new Response(json_encode($listMember));
    }

    //  Информация о конкретном пользователе
    public function showMemberAction(Request $request)
    {
        $memberId = $this->onlyNumeral($request->request->get('memberId'));

        //  Если у пользователь нет семьи, он может запрашивать данные
        //  только о себе
        if(!$this->get('session')->get('fmId'))
        {
            //  Проверяем одинаковые ли переданный id и хранящийся в сессии memId
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery(
                    'SELECT m.memberId, m.name, m.surname,
                    m.secondname,
                    m.familyId, m.login
              FROM AcmeEcoBundle:Member m
              WHERE m.memberId = :memberId'
                )->setParameters(array(
                    'memberId' => $this->get('session')->get('memId'),
                ));
                $listMember = $query->getResult();
        }else{
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT m.memberId, m.name, m.surname,
                    m.secondname,
                    m.familyId, m.login
              FROM AcmeEcoBundle:Member m
              WHERE m.memberId = :memberId
              AND m.familyId = :familyId'
            )->setParameters(array(
                'memberId' => $memberId,
                'familyId' => $this->get('session')->get('fmId'),
            ));
            $listMember = $query->getResult();
        }

        if($listMember){
            $count = count($listMember);
            for ($i = 0; $i < $count; $i++) {
                $listMember{$i} = $listMember[$i];
            }

            return new Response(json_encode($listMember));
        }else{
            return new Response(null);
        }
    }

    public function testAction(){
        // Проверка авторизован ли пользователь
        if (!$this->get('session')->get('memId')) {
            throw $this->createNotFoundException('Авторизуйтесь пожалуйста!!!');
        }
    }

}
