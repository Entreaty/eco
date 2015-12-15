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
            $who = 'memberId'; $id = $memberId;
        }elseif($familyId){
            $who = 'familyId'; $id = $familyId;
        }

        //  Находим сумму расходов
        $transactionType = 'wastage';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType and t.' . $who . ' = :id'
        )->setParameters(array(
            'transactionType' => $transactionType,
            'id' => $id
        ));
        $summWastage = $query->getResult();

        //  Находим сумму доходов
        $transactionType = 'profit';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType and t.' . $who . ' = :id'
        )->setParameters(array(
            'transactionType' => $transactionType,
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

        if($memberId){
            $who = 'memberId'; $id = $memberId;
        }elseif($familyId){
            $who = 'familyId'; $id = $familyId;
        }

        $dateFrom = $this->onlyDate($request->request->get('dateFrom'));
        $dateFrom = date_create_from_format('Y-m-d', $dateFrom);
        $dateTo = $this->onlyDate($request->request->get('dateTo'));
        $dateTo = date_create_from_format('Y-m-d', $dateTo);

        //  Находим сумму расходов за выбранный промежуток времени
        $transactionType = 'wastage';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd'
        )->setParameters(array('transactionType' => $transactionType, 'id' => $id, 'dateFrom' => $dateFrom, 'dateEnd' => $dateTo));
        $summWastageForDates = $query->getResult();

        //  Находим сумму доходов за выбранный промежуток времени
        $transactionType = 'profit';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd'
        )->setParameters(array('transactionType' => $transactionType, 'id' => $id, 'dateFrom' => $dateFrom, 'dateEnd' => $dateTo));
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

    public function summForEachDayAction(Request $request)
    {
        $id = $this->onlyNumeral($request->request->get('id'));
        $who = $this->onlyString($request->request->get('who'));
        $dateFrom = $this->onlyDate($request->request->get('dateFrom'));
        $dateFrom = date_create_from_format('Y-m-d', $dateFrom);
        $dateTo = $this->onlyDate($request->request->get('dateTo'));
        $dateTo = date_create_from_format('Y-m-d', $dateTo);

        $id=20; $who='familyId';$dateFrom='2015-11-01';$dateTo='2015-12-31';

        $transactionType = 'wastage';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.transactionType, t.transactionName, t.sum, t.date
              FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd
              ORDER BY t.date ASC'
        )->setParameters(array(
            'transactionType' => $transactionType,
            'id' => $id,
            'dateFrom' => $dateFrom,
            'dateEnd' => $dateTo
        ));
        $wastageForEachDay = $query->getResult();

        $transactionType = 'profit';
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.transactionType, t.transactionName, t.sum, t.date
              FROM AcmeEcoBundle:Transaction t
              WHERE t.transactionType = :transactionType
              AND t.' . $who . ' = :id
              AND t.date >= :dateFrom
              AND t.date <= :dateEnd
              ORDER BY t.date ASC'
        )->setParameters(array(
            'transactionType' => $transactionType,
            'id' => $id,
            'dateFrom' => $dateFrom,
            'dateEnd' => $dateTo
        ));
        $profitForEachDay = $query->getResult();

        //  Подготовим полученные данные для конвертации в json
        $count = count($wastageForEachDay);
        for ($i = 0; $i < $count; $i++) {
            $wastageForEachDay{$i} = $wastageForEachDay[$i];
        }
        $wastage = json_encode($wastageForEachDay);

        $count = count($profitForEachDay);
        for ($i = 0; $i < $count; $i++) {
            $profitForEachDay{$i} = $profitForEachDay[$i];
        }
        $profit = json_encode($profitForEachDay);

        //  Для удобства парсинга ответа добавим разделитель
        return new Response($wastage . '~' . $profit);

//$a= substr(($wastageForEachDay[0]['date']->date),0, 10);
//        $dateFrom = date_create_from_format('Y-m-d', $a);
//$b= substr(($wastageForEachDay[count($wastageForEachDay)-1]['date']->date),0, 10);
//        $dateTo = date_create_from_format('Y-m-d', $b);
//        $c=$dateTo->diff($dateFrom);
//        $days= $c->y*365 + $c->m*30 + $c->d;
//        return new Response(var_dump($a));
    }


    public function summByCategoryAction(Request $request)
    {
        $categoryName = $this->onlyNumeralAndString($request->request->get('categoryName'));
        $transactionType = $this->onlyString($request->request->get('transactionType'));


        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(t.sum) FROM AcmeEcoBundle:Transaction t WHERE t.transactionType = :transactionType AND t.transactionName = :categoryName'
        )->setParameters(array(
            'transactionType' => $transactionType,
            'categoryName' => $categoryName
        ));
        $summWastage = $query->getResult();

        $json = json_encode(array('name'=>$categoryName, 'y' => (int)$summWastage[0][1], 'type' => $transactionType));
        return new Response($json);
    }
}