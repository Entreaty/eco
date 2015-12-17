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

class ReportsController extends Controller
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

    //  Полный отчет суммы расходов, доходов и их разности
    //      для любого члена семьи, всей семьи сразу
    public function summAction(Request $request)
    {
        $who = null; $id = null;
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyId = $this->onlyNumeral($request->request->get('familyId'));

        if($memberId){
            $who = 'member'; $id = $memberId;
        }elseif($familyId){
            $who = 'family'; $id = $familyId;
        }

        //  Находим сумму расходов
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) 
              FROM AcmeEcoBundle:Transaction t
              join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
              WHERE tt.type = false
              AND t.' . $who . ' = :id
              AND t.isDeleted = false'
        )->setParameters(array(
            'id' => $id
        ));
        $summWastage = $query->getResult();

        //  Находим сумму доходов
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum)
              FROM AcmeEcoBundle:Transaction t
              join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
              WHERE tt.type = true
              AND t.' . $who . ' = :id
              AND t.isDeleted = false'
        )->setParameters(array(
            'id' => $id
        ));
        $summProfit = $query->getResult();

        //  Находим разницу между доходом и расходом
        $equal = $summProfit[0][1] - $summWastage[0][1];

        $json = json_encode(array(
            'summWastage' => (int)$summWastage[0][1],
            'summProfit' => (int)$summProfit[0][1],
            'equal' => (int)$equal
        ));

        return new Response("$json");
    }

    public function summForDatesAction(Request $request)
    {
        $who = null; $id = null;
        $memberId = $this->onlyNumeral($request->request->get('memberId'));
        $familyId = $this->onlyNumeral($request->request->get('familyId'));
        $dateFrom = $this->onlyDate($request->request->get('dateFrom'));
        $dateTo = $this->onlyDate($request->request->get('dateTo'));

        if($memberId){
            $who = 'member'; $id = $memberId;
        }elseif($familyId){
            $who = 'family'; $id = $familyId;
        }
        //  Находим сумму расходов за выбранный промежуток времени
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum)
              FROM AcmeEcoBundle:Transaction t
              join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
              WHERE tt.type = false
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd
              AND t.isDeleted = false'

        )->setParameters(array(
            'id' => $id,
            'dateFrom' => $dateFrom,
            'dateEnd' => $dateTo
        ));
        $summWastageForDates = $query->getResult();

        //  Находим сумму доходов за выбранный промежуток времени
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum)
              FROM AcmeEcoBundle:Transaction t
              join AcmeEcoBundle:TransactionType tt WITH t.type = tt.typeId
              WHERE tt.type = true
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd
              AND t.isDeleted = false'
        )->setParameters(array(
            'id' => $id,
            'dateFrom' => $dateFrom,
            'dateEnd' => $dateTo
        ));
        $summProfitForDates = $query->getResult();

        //  Находим разницу между доходом и расходом
        $equal = $summProfitForDates[0][1] - $summWastageForDates[0][1];

        $json = json_encode(array(
                'summWastageForDates' => (int)$summWastageForDates[0][1],
                'summProfitForDates' => (int)$summProfitForDates[0][1],
                'equal' => (int)$equal)
        );

        return new Response("$json");
    }

    //  Отчет по категории транзакции
    public function summByCategoryAction(Request $request)
    {
        $categoryId = $this->onlyNumeral($request->request->get('categoryId'));
        $categoryName = $this->onlyNumeralAndString($request->request->get('categoryName'));
        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum)
             FROM AcmeEcoBundle:Transaction t
             WHERE t.category = :categoryId
             AND t.family = :familyId
             AND t.isDeleted = false'
        )->setParameters(array(
            'categoryId' => $categoryId,
            'familyId' => $familyId
        ));
        $summWastage = $query->getResult();

        $json = json_encode(array('name'=>$categoryName, 'y' => (int)$summWastage[0][1]));
        return new Response($json);
    }
    //  Отчет по типу транзакции
    public function summByTypeAction(Request $request)
    {
        $typeId = $this->onlyNumeral($request->request->get('typeId'));
        $typeName = $this->onlyNumeralAndString($request->request->get('typeName'));
        $familyId = $this->get('session')->get('fmId');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum)
             FROM AcmeEcoBundle:Transaction t
             WHERE t.type = :typeId
             AND t.family = :familyId
             AND t.isDeleted = false'
        )->setParameters(array(
            'typeId' => $typeId,
            'familyId' => $familyId
        ));
        $summWastage = $query->getResult();

        $json = json_encode(array('name'=>$typeName, 'y' => (int)$summWastage[0][1]));
        return new Response($json);
    }
}