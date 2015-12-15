<?php
namespace Acme\EcoBundle\Controller;


use Acme\EcoBundle\Entity\Category;
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

class TransactionController extends Controller
{
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

    public function onlyDate($item)
    {
        return $item = preg_replace('/[^-0-9]/iu', '', $item);
    }

    //  Создадим новую категорию транзакции
    public function newCategoryAction(Request $request)
    {
        $categoryName = $this->onlyNumeralAndString(
            $request->request->get('categoryName')
        );

        $categoryType = $this->onlyString(
            $request->request->get('categoryType')
        );

        if($categoryName){

            $familyId = $this->get('session')->get('fmId');
            $memberId = $this->get('session')->get('memId');

            if($familyId){
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery(
                    'SELECT c.categoryId FROM AcmeEcoBundle:Category c
                  WHERE c.categoryName = :categoryName
                  AND c.familyId = :familyId'
                )->setParameters(array(
                    'categoryName' => $categoryName,
                    'familyId' => $familyId
                ));
                $exist = $query->getResult();
            }else{
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery(
                    'SELECT c.categoryId FROM AcmeEcoBundle:Category c
                  WHERE c.categoryName = :categoryName
                  AND c.memberId = :memberId'
                )->setParameters(array(
                    'categoryName' => $categoryName,
                    'memberId' => $memberId
                ));
                $exist = $query->getResult();
            }



            if(!$exist){
                $category = new Category();

                $category->setCategoryName($categoryName);
                $category->setCategoryType($categoryType);
                if($familyId)$category->setFamilyId($familyId);
                $category->setMemberId($memberId);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($category);
                $em->flush();
//
                return new Response('Ok!');
//                return new Response(var_dump($exist));
            }else{
                return new Response('Bad!');
            }
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Удаление категории транзакции
    public function deleteCategoryAction(Request $request)
    {
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));

        $em = $this->getDoctrine()->getEntityManager();

        $deleteCategory = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:Category')
            ->find($categoryId);

        $em->remove($deleteCategory);

        $em->flush();

        return new Response('Ok!');
    }

    //  Изменение категории транзакции
    public function changeCategoryAction(Request $request)
    {
        $categoryId = $this->onlyNumeral(
            $request->request->get('categoryId')
        );

        $categoryName = $this->onlyNumeralAndString(
            $request->request->get('categoryName')
        );

        $categoryType = $this->onlyString(
            $request->request->get('categoryType')
        );

        if($categoryId && $categoryName && $categoryType){
            $changeCategory = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Category')
                ->find($categoryId);

            $changeCategory->setCategoryName($categoryName);
            $changeCategory->setCategoryType($categoryType);
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Создание списка категорий
    public function listCategoryAction(Request $request)
    {
        $familyId = $this->get('session')->get('fmId');
        $memberId = $this->get('session')->get('memId');

        //  Если есть семья у пользователя, то загружаем категории для семьи
        if ($familyId) {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.categoryId,
                        t.categoryName,
                        t.categoryType,
                        t.familyId,
                        t.memberId
                  FROM AcmeEcoBundle:Category t WHERE t.familyId = :familyId OR t.memberId = :memberId'
            )->setParameters(array(
                'familyId' => $familyId,
                'memberId' => $memberId,
            ));
            //  Если семьи нет загружаем категории самого пользователя
        } else {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.categoryId,
                        t.categoryName,
                        t.categoryType,
                        t.memberId,
                        t.familyId
                  FROM AcmeEcoBundle:Category t WHERE t.memberId = :memberId'
            )->setParameters(array(
                'memberId' => $memberId,
            ));
        }
        $listCategory = $query->getResult();

        if($listCategory){
            $count = count($listCategory);
            for ($i = 0; $i < $count; $i++) {
                $listCategory{$i} = $listCategory[$i];
            }

            return new Response(json_encode($listCategory));
        }else{
            return new Response(null);
        }
    }

    //  Информация по одной категории
    public function showCategoryAction(Request $request)
    {
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.categoryId,
                    c.categoryName,
                    c.categoryType,
                    c.familyId,
                    c.memberId
              FROM AcmeEcoBundle:Category c
              WHERE c.categoryId = :categoryId'
        )->setParameters(array(
            'categoryId' => $categoryId,
        ));
        $showCategory = $query->getResult();

        $count = count($showCategory);
        for ($i = 0; $i < $count; $i++) {
            $showCategory{$i} = $showCategory[$i];
        }

        return new Response(json_encode($showCategory));
    }

    //  Создание новой транзакции
    public function newTransactionAction(Request $request)
    {
        $transactionName = $this->onlyNumeralAndString($request->request->get('transactionName'));
        $transactionType = $this->onlyString($request->request->get('transactionType'));
        $sum = $this->onlyNumeral($request->request->get('sum'));
        $date = $this->onlyDate($request->request->get('date'));
        $date = date_create_from_format('Y-m-d', $date);

        $memberId = $this->get('session')->get('memId');
        $familyId = $this->get('session')->get('fmId');

        if($transactionName && $transactionType && $sum && $date){
            $trasaction = new Transaction();

            $trasaction->setTransactionName($transactionName);
            $trasaction->setTransactionType($transactionType);
            $trasaction->setSum((int)$sum);
            $trasaction->setDate($date);
            $trasaction->setMemberId($memberId);
            $trasaction->setFamilyId($familyId);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($trasaction);
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Изменение транзакции
    public function changeTransactionAction(Request $request)
    {
        $transactionId = $this->onlyNumeral($request->request->get('transactionId'));
        $transactionName = $this->onlyNumeralAndString($request->request->get('transactionName'));
        $transactionType = $this->onlyString($request->request->get('transactionType'));
        $sum = $this->onlyNumeral($request->request->get('sum'));
        $date = $this->onlyDate($request->request->get('date'));
        $date = date_create_from_format('Y-m-d', $date);

        if($transactionId && $transactionName && $transactionType && $sum && $date){
            $changeTransaction = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Transaction')
                ->find($transactionId);

            $changeTransaction->setTransactionName($transactionName);
            $changeTransaction->setTransactionType($transactionType);
            $changeTransaction->setSum((int)$sum);
            $changeTransaction->setDate($date);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($changeTransaction);
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Удаление транзакции
    public function deleteTransactionAction(Request $request)
    {
        $transactionId = $this->onlyNumeral($request->request->get('transactionId'));

        $deleteTransaction = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:Transaction')
            ->find($transactionId);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($deleteTransaction);
        $em->flush();
        return new Response('Ok!');
    }

    //  Создание списка транзакций
    public function listTransactionAction(Request $request)
    {
        $who = null; $id = null;
        //  Если запрашивается определнный человек who и id не будут null
        //  в других случаях, подразумевается, что запрос всех транзакции
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyId = $this->onlyNumeral($request->request->get('familyId'));


        if($memberId){
            $who = 'memberId'; $id = $memberId;
        }elseif($familyId){
            $who = 'familyId'; $id = $familyId;
        }


        if($who && $id){
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.transactionId, t.transactionName, t.sum,
                        t.transactionType,t.memberId, t.familyId,t.date
                  FROM AcmeEcoBundle:Transaction t
                  WHERE t.'.$who.' = :id'
            )->setParameters(array(
                'id' => $id,
            ));
        }else{
            //  Если запрос был пустым, находим все транзакции
            $memberId = $this->get('session')->get('memId');
            $familyId = $this->get('session')->get('fmId');

            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.transactionId, t.transactionName, t.sum,
                        t.transactionType,t.memberId, t.familyId,t.date
                  FROM AcmeEcoBundle:Transaction t
                  WHERE t.familyId = :familyId OR t.memberId = :memberId'
            )->setParameters(array(
                'familyId' => $familyId,
                'memberId' => $memberId,
            ));
        }
        $listTransaction = $query->getResult();

        //  Если транзакций нет, возвращаем null
        if ($listTransaction) {
            $count = count($listTransaction);
            for ($i = 0; $i < $count; $i++) {
                $listTransaction{$i} = $listTransaction[$i];
            }

            return new Response(json_encode($listTransaction));
            return new Response(var_dump($listTransaction));
        } else {
            return new Response(null);
        }






//        //  Если запрашивается определнный человек who и id не будут null
//        $who = $this->onlyString($request->request->get('who'));
//        $id = $this->onlyNumeral($request->request->get('id'));
//        //  В других случаях, подразумевается, что запрос всех транзакции
//        $memberId = $this->get('session')->get('memId');
//        $familyId = $this->get('session')->get('fmId');
//
//        if($who && $id){
//            $em = $this->getDoctrine()->getEntityManager();
//            $query = $em->createQuery(
//                'SELECT t.transactionId, t.transactionName, t.sum,
//                        t.transactionType,t.memberId, t.familyId,t.date
//                  FROM AcmeEcoBundle:Transaction t
//                  WHERE t.'.$who.' = :id'
//            )->setParameters(array(
//                'id' => $id,
//            ));
//        }else{
//            $em = $this->getDoctrine()->getEntityManager();
//            $query = $em->createQuery(
//                'SELECT t.transactionId, t.transactionName, t.sum,
//                        t.transactionType,t.memberId, t.familyId,t.date
//                  FROM AcmeEcoBundle:Transaction t
//                  WHERE t.familyId = :familyId OR t.memberId = :memberId'
//            )->setParameters(array(
//                'familyId' => $familyId,
//                'memberId' => $memberId,
//            ));
//        }
//        $listTransaction = $query->getResult();
//
//        //  Если транзакций нет, возвращаем null
//        if ($listTransaction) {
//            $count = count($listTransaction);
//            for ($i = 0; $i < $count; $i++) {
//                $listTransaction{$i} = $listTransaction[$i];
//            }
//
//            return new Response(json_encode($listTransaction));
//        } else {
//            return new Response(null);
//        }
    }

    //  Информация по отдельной транзакции
    public function showTransactionAction(Request $request)
    {
        $transactionId = $this->onlyNumeral($request->request->get('transactionId'));
        $transactionId = 47;

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.transactionId, t.transactionName, t.sum,
                    t.transactionType,t.memberId, t.familyId,t.date
              FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionId = :transactionId'
        )->setParameters(array(
            'transactionId' => $transactionId,
        ));
        $showTransaction = $query->getResult();

        $count = count($showTransaction);
        for ($i = 0; $i < $count; $i++) {
            $showTransaction{$i} = $showTransaction[$i];
        }
        return new Response(json_encode($showTransaction));
    }

}