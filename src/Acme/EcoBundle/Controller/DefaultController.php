<?php

namespace Acme\EcoBundle\Controller;


use Acme\EcoBundle\Entity\NewFamily;
use Acme\EcoBundle\Entity\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\EcoBundle\Entity\Transaction;
use Acme\EcoBundle\Entity\Member;
use Acme\EcoBundle\Entity\Family;
use Acme\EcoBundle\Entity\Category;
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

    //  Создание нового члена семьи
    public function newMemberAction(Request $request)
    {
        $surname = $this->onlyString($request->request->get('surname'));
        $name = $this->onlyString($request->request->get('name'));
        $secondname = $this->onlyString($request->request->get('secondname'));
        $login = $this->onlyNumeralAndString($request->request->get('login'));
        $password = $this->hidePass($this->onlyNumeralAndString($request->request->get('password')));
        $familyId = $this->get('session')->get('fmId');

        if($surname && $name && $secondname && $login && $password && $familyId)
        {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT m.memberId FROM AcmeEcoBundle:Member m WHERE m.login = :login
                  AND m.isDeleted = false'
            )->setParameter('login', $login);
            $exist = $query->getResult();

            if (!$exist) {
                $family = $this->getDoctrine()
                    ->getRepository('AcmeEcoBundle:Family')
                    ->find($familyId);

                $member = new Member;
                $member->setName($name);
                $member->setSurname($surname);
                $member->setSecondname($secondname);
                $member->setPassword($password);
                $member->setLogin($login);
                $member->setFamily($family);
                $member->setIsDeleted(false);
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
            $deleteMember->setIsDeleted(true);
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
            $deleteMember->setIsDeleted(true);
            $em->flush();

            return new Response('Ok!');
        }
    }

    //  Список существующих членов семьи
    public function listMemberAction()
    {
        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.memberId, m.name, m.surname,
                    m.secondname
              FROM AcmeEcoBundle:Member m
              WHERE m.family = :familyId
              AND m.isDeleted = false'
        )->setParameters(array(
            'familyId' => $familyId,
        ));
        $listMember = $query->getResult();

        if($listMember){
            $count = count($listMember);
            for ($i = 0; $i < $count; $i++) {
                $listMember{$i} = $listMember[$i];
            }
            return new Response(json_encode($listMember));
        }else{
            return new Response('Bad!');
        }
    }

    //  Информация о конкретном пользователе
    public function showMemberAction(Request $request)
    {
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.memberId, m.name, m.surname,
              m.secondname, m.login
              FROM AcmeEcoBundle:Member m
              WHERE m.memberId = :memberId
              AND m.family = :familyId
              AND m.isDeleted = false'
        )->setParameters(array(
            'memberId' => $memberId,
            'familyId' => $familyId,
        ));
        $listMember = $query->getResult();

        if($listMember){
            $count = count($listMember);
            for ($i = 0; $i < $count; $i++) {
                $listMember{$i} = $listMember[$i];
            }

            return new Response(json_encode($listMember));
        }else{
            return new Response('Bad!');
        }
    }



}
