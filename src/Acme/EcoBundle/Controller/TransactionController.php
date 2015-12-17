<?php
namespace Acme\EcoBundle\Controller;


use Acme\EcoBundle\Entity\Category;
use Acme\EcoBundle\Entity\NewFamily;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\EcoBundle\Entity\Transaction;
use Acme\EcoBundle\Entity\TransactionType;
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

    public function onlyFloat($item)
    {
        return $item = preg_replace('/[^0-9.]/iu', '', $item);
    }

    public function onlyNumeralAndString($item)
    {
        return $item = preg_replace('/[^a-zа-я0-9 ]/iu', '', $item);
    }

    public function onlyDate($item)
    {
        return $item = preg_replace('/[^-0-9]/iu', '', $item);
    }

    public function onlyBoolean($item)
    {
        return filter_var($item, FILTER_VALIDATE_BOOLEAN);
    }

    //  Создадим новый тип транзакции
    public function newTransactionTypeAction(Request $request)
    {
        $typeName = $this->onlyNumeralAndString(
            $request->request->get('typeName')
        );

        $type = $this->onlyBoolean($request->request->get('type'));

        if($typeName){

            $familyId = $this->get('session')->get('fmId');

                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery(
                    'SELECT t.typeName
                      FROM AcmeEcoBundle:TransactionType t
                      WHERE t.typeName = :typeName
                      AND t.isDeleted = false
                      AND t.family = :familyId'
                )->setParameters(array(
                    'typeName' => $typeName,
                    'familyId' => $familyId
                ));
                $exist = $query->getResult();

            if(!$exist){
                $family = $this->getDoctrine()
                    ->getRepository('AcmeEcoBundle:Family')
                    ->find($familyId);

                $transactionType = new TransactionType();
                $transactionType->setTypeName($typeName);
                $transactionType->setType($type);
                $transactionType->setIsDeleted(false);
                $transactionType->setFamily($family);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($family);
                $em->persist($transactionType);
                $em->flush();

                return new Response('Ok!');
            }else{
                return new Response('Bad!');
            }
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Удаление типа транзакции
    public function deleteTransactionTypeAction(Request $request)
    {
        $typeId = $this->onlyNumeral($request->request->get('typeId'));

        $em = $this->getDoctrine()->getEntityManager();

        $deleteTransactionType = $this->getDoctrine()
            ->getRepository('AcmeEcoBundle:TransactionType')
            ->find($typeId);

        $deleteTransactionType->setIsDeleted(true);

        $em->flush();

        return new Response('Ok!');
    }

    //  Создание списка всех типов транзакций
    public function listTransactionTypeAction(Request $request)
    {
        $familyId = $this->get('session')->get('fmId');

            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.typeId,
                        t.typeName,
                        t.type
                  FROM AcmeEcoBundle:TransactionType t
                  WHERE t.family = :familyId
                  AND t.isDeleted = false'
            )->setParameters(array(
                'familyId' => $familyId,
            ));
        $listTransactionType = $query->getResult();

        if($listTransactionType){
            $count = count($listTransactionType);
            for ($i = 0; $i < $count; $i++) {
                $listTransactionType{$i} = $listTransactionType[$i];
            }

            return new Response(json_encode($listTransactionType));
        }else{
            return new Response('Bad!');
        }
    }

    //  Информация по одному типу транзакции
    public function showTransactionTypeAction(Request $request)
    {
        $familyId = $this->get('session')->get('fmId');

        $typeId = $this->onlyNumeral($request->request->get('typeId'));

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.typeId,
                    t.typeName,
                    t.type
                  FROM AcmeEcoBundle:TransactionType t
                  WHERE t.family = :familyId
                  AND t.typeId = :typeId
                  AND t.isDeleted = false'
        )->setParameters(array(
            'typeId' => $typeId,
            'familyId' => $familyId,
        ));
        $showTransactionType = $query->getResult();

        if($showTransactionType) {
            $count = count($showTransactionType);
            for ($i = 0; $i < $count; $i++) {
                $showTransactionType{$i} = $showTransactionType[$i];
            }
            return new Response(json_encode($showTransactionType));
        }else{
            return new Response('Bad!');
        }
    }

    //  Изменение типаТранзакции
    public function changeTransactionTypeAction(Request $request)
    {
        $typeId = $this->onlyNumeral(
            $request->request->get('typeId')
        );

        $typeName = $this->onlyNumeralAndString(
            $request->request->get('typeName')
        );

        $type = $this->onlyBoolean(
            $request->request->get('type')
        );

        if($typeName && $typeId){
            $changeTransactionType = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:TransactionType')
                ->find($typeId);

            $changeTransactionType->setTypeName($typeName);
            $changeTransactionType->setType($type);
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }



    //  Создание новой категории транзакции
    public function newCategoryAction(Request $request)
    {
        $categoryName = $this->onlyNumeralAndString(
            $request->request->get('categoryName')
        );

        if($categoryName){
            $familyId = $this->get('session')->get('fmId');

                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery(
                    'SELECT c.categoryId FROM AcmeEcoBundle:Category c
                  WHERE c.categoryName = :categoryName
                   AND c.isDeleted = 0
                  AND c.family = :familyId'
                )->setParameters(array(
                    'categoryName' => $categoryName,
                    'familyId' => $familyId
                ));
                $exist = $query->getResult();

            if(!$exist){

                $family = $this->getDoctrine()
                    ->getRepository('AcmeEcoBundle:Family')
                    ->find($familyId);

                $category = new Category();

                $category->setCategoryName($categoryName);
                $category->setFamily($family);
                $category->setIsDeleted(false);
                $em->persist($category);
                $em->flush();

                return new Response('Ok!');

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

        $deleteCategory->setIsDeleted(true);

        $em->flush();

        return new Response('Ok!');
    }

    //  Создание списка категорий
    public function listCategoryAction(Request $request)
    {
        $familyId = $this->get('session')->get('fmId');

            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT c.categoryId,
                        c.categoryName
                  FROM AcmeEcoBundle:Category c
                  WHERE c.family = :familyId AND c.isDeleted = false'
            )->setParameters(array(
                'familyId' => $familyId,
            ));
        $listCategory = $query->getResult();

        if($listCategory){
            $count = count($listCategory);
            for ($i = 0; $i < $count; $i++) {
                $listCategory{$i} = $listCategory[$i];
            }

            return new Response(json_encode($listCategory));
        }else{
            return new Response('Bad!');
        }
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

        if($categoryId && $categoryName){
            $changeCategory = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Category')
                ->find($categoryId);

            $changeCategory->setCategoryName($categoryName);
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();

            return new Response('Ok!');
        }else{
            return new Response('Bad inputs!');
        }
    }

    //  Информация по одной категории
    public function showCategoryAction(Request $request)
    {
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));
        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.categoryId,
                    c.categoryName
              FROM AcmeEcoBundle:Category c
              WHERE c.categoryId = :categoryId
              AND c.family =:familyId
              AND c.isDeleted = false'
        )->setParameters(array(
            'categoryId' => $categoryId,
            'familyId' => $familyId,
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
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));
//        $type = $this->onlyBoolean($request->request->get('type'));
        $typeId = $this->onlyNumeral($request->request->get('typeId'));
        $sum = $this->onlyFloat($request->request->get('sum'));
        $date = $this->onlyDate($request->request->get('date'));
        $date = date_create_from_format('Y-m-d', $date);

        $memberId = $this->get('session')->get('memId');
        $familyId = $this->get('session')->get('fmId');

        if($categoryId && $sum && $date){
            $family = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Family')
                ->find($familyId);

            $transactionCategory = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Category')
                ->find($categoryId);

            $transactionType = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:TransactionType')
                ->find($typeId);

            $member = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($memberId);


            $trasaction = new Transaction();

            $trasaction->setSum($sum);
            $trasaction->setDate($date);
            $trasaction->setIsDeleted(false);
            $trasaction->setCategory($transactionCategory);
            $trasaction->setType($transactionType);
            $trasaction->setMember($member);
            $trasaction->setFamily($family);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($family);
            $em->persist($transactionCategory);
            $em->persist($transactionType);
            $em->persist($member);
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
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));
        $typeId = $this->onlyNumeral($request->request->get('typeId'));
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $sum = $this->onlyFloat($request->request->get('sum'));
        $date = $this->onlyDate($request->request->get('date'));
        $date = date_create_from_format('Y-m-d', $date);

        $familyId = $this->get('session')->get('fmId');

        if($transactionId && $categoryId && $typeId && $sum && $date){
            $family = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Family')
                ->find($familyId);

            $transactionCategory = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Category')
                ->find($categoryId);

            $transactionType = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:TransactionType')
                ->find($typeId);

            $member = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($memberId);


            $trasaction = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Transaction')
                ->find($transactionId);

            $trasaction->setSum($sum);
            $trasaction->setDate($date);
            $trasaction->setIsDeleted(false);
            $trasaction->setCategory($transactionCategory);
            $trasaction->setType($transactionType);
            $trasaction->setMember($member);
            $trasaction->setFamily($family);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($family);
            $em->persist($transactionCategory);
            $em->persist($transactionType);
            $em->persist($member);
            $em->persist($trasaction);
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
        $deleteTransaction->setIsDeleted(true);
        $em->flush();
        return new Response('Ok!');
    }

    //  Создание списка транзакций
    public function listTransactionAction(Request $request)
    {
        $who = null; $id = null;

        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyId = $this->get('session')->get('fmId');

        //  Если запрос на конкретного человека получаем memeberId
        if($memberId){
            $who = 'member';
            $id = $memberId;
        }else {
            $who = 'family';
            $id = $familyId;
        }

            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT t.transactionId,t.sum,t.date,tt.typeName,tt.type, c.categoryName, m.name
                  FROM AcmeEcoBundle:Transaction t
                  join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
                  join AcmeEcoBundle:Category c WITH t.category = c.categoryId
                  join AcmeEcoBundle:Member m WITH t.member = m.memberId
                  WHERE t.'.$who.' = :id
                  AND t.isDeleted = false'
            )->setParameters(array(
                'id' => $id,
            ));

        $listTransaction = $query->getResult();

        //  Если транзакций нет, возвращаем null
        if ($listTransaction) {
            $count = count($listTransaction);
            for ($i = 0; $i < $count; $i++) {
                $listTransaction{$i} = $listTransaction[$i];
            }
            return new Response(json_encode($listTransaction));
        } else {
            return new Response('Bad!');
        }
    }

    //  Информация по отдельной транзакции
    public function showTransactionAction(Request $request)
    {
        $transactionId = $this->onlyNumeral($request->request->get('transactionId'));

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.transactionId,t.sum,t.date,
                    c.categoryId, c.categoryName,
                    tt.typeId,tt.typeName,m.memberId
                  FROM AcmeEcoBundle:Transaction t
                  join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
                  join AcmeEcoBundle:Category c WITH t.category = c.categoryId
                  join AcmeEcoBundle:Member m WITH t.member = m.memberId
                  WHERE t.transactionId = :transactionId
                  AND t.isDeleted = false'
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

    public function demoAction(Request $request)
    {
        $memberId = $this->get('session')->get('memId');
        $familyId = $this->get('session')->get('fmId');

            $family = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Family')
                ->find($familyId);

            $transactionCategory = new Category();
            $transactionCategory->setIsDeleted(false);
            $transactionCategory->setFamily($family);
            $transactionCategory->setCategoryName('Свет');

            $transactionType = new TransactionType();
            $transactionType->setIsDeleted(false);
            $transactionType->setFamily($family);
            $transactionType->setType(false);
            $transactionType->setTypeName('Оплата ЖКХ');

            $member = $this->getDoctrine()
                ->getRepository('AcmeEcoBundle:Member')
                ->find($memberId);


            $trasaction = new Transaction();

            $trasaction->setSum('2200');
            $trasaction->setDate(new \DateTime('today'));
            $trasaction->setIsDeleted(false);
            $trasaction->setCategory($transactionCategory);
            $trasaction->setType($transactionType);
            $trasaction->setMember($member);
            $trasaction->setFamily($family);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($family);
            $em->persist($transactionCategory);
            $em->persist($transactionType);
            $em->persist($member);
            $em->persist($trasaction);
            $em->flush();

            return new Response('Ok!');
    }

}