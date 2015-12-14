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

        $familyId = $this->get('session')->get('fmId');
        $memberId = $this->get('session')->get('memId');

        $category = new Category();

        $category->setCategoryName($categoryName);
        $category->setCategoryType($categoryType);
        $category->setFamilyId($familyId);
        $category->setMemberId($memberId);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($category);
        $em->flush();

        return new Response('Ok!');
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

        $changeCategory = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:Category')
            ->find($categoryId);
        $changeCategory->setCategoryName($categoryName);
        $changeCategory->setCategoryType($categoryType);
        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        return new Response('Ok!');
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
                  FROM AcmeEcoBundle:Category t WHERE t.familyId = :familyId'
            )->setParameters(array(
                'familyId' => $familyId,
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
        $memberId = $this->get('session')->get('memId');
        $familyId = $this->get('session')->get('fmId');

        $changeTransaction = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:Transaction')
            ->find($transactionId);

        $changeTransaction->setTransactionName($transactionName);
        $changeTransaction->setTransactionType($transactionType);
        $changeTransaction->setSum((int)$sum);
        $changeTransaction->setDate($date);
        $changeTransaction->setMemberId($memberId);
        $changeTransaction->setFamilyId($familyId);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($changeTransaction);
        $em->flush();

        return new Response('Ok!');
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
        $familyId = $this->onlyNumeral($request->request->get('familyId'));
        $memberId = $this->onlyNumeral($request->request->get('memberId'));

        //  Если memberId не был передан, создаем список для всей семьи
        if (!$memberId) {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.transactionId, t.transactionName, t.sum,
                        t.transactionType,t.memberId, t.familyId,t.date
                  FROM AcmeEcoBundle:Transaction t
                  WHERE t.familyId = :familyId'
            )->setParameters(array(
                'familyId' => $familyId,
            ));
            $member = null;

        } else {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.transactionId, t.transactionName, t.sum,
                        t.transactionType,t.memberId, t.familyId,t.date
                  FROM AcmeEcoBundle:Transaction t
                  WHERE t.memberId = :memberId'
            )->setParameters(array(
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
        } else {
            return new Response(null);
        }
    }

    //  Информация по отдельной транзакции
    public function showTransactionAction(Request $request)
    {
        $transactionId = $this->onlyNumeral($request->request->get('transactionId'));

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