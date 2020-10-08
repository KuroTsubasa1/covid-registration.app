<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method Guest|null find($id, $lockMode = NULL, $lockVersion = NULL)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = NULL)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL)
 */
class GuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    // /**
    //  * @return Guest[] Returns an array of Guest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Guest
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function validation(TranslatorInterface $translator, $formData)
    {
        if (empty($formData['form_name']) || !isset($formData['form_name']))
        {
            return $translator->trans('guest.form.validation.name');
        }

        if (empty($formData['form_street_number']) || !isset($formData['form_street_number']))
        {
            return $translator->trans('guest.form.validation.street_number');
        }

        if (empty($formData['form_zip_city']) || !isset($formData['form_zip_city']))
        {
            return $translator->trans('guest.form.validation.zip_city');
        }

        if (empty($formData['form_email']) || !isset($formData['form_email']))
        {
            return $translator->trans('guest.form.validation.email');
        }

        if (empty($formData['form_phone']) || !isset($formData['form_phone']))
        {
            return $translator->trans('guest.form.validation.phone');
        }

        if (empty($formData['form_policy']) || !isset($formData['form_policy']))
        {
            return $translator->trans('guest.form.validation.policy');
        }

        return TRUE;
    }

    public function save(TranslatorInterface $translator, $formData, $sessionID)
    {
        $conn = $this->getEntityManager()
                     ->getConnection();

        // prepare data
        if ($formData['form_policy'] == 'on')
        {
            $formData['form_policy'] = TRUE;
        }
        else
        {
            $formData['form_policy'] = FALSE;
        }

        $sql = 'INSERT INTO guest (name, street, zip_code, phone_number, email, note, _time_created, accept_policy, session_id, log_out)
VALUES (:name, :street, :zip_code, :phone_number, :email, :note, :time_created, :accept_policy, :session_id, :log_out)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(
            [
                'name'          => $formData['form_name'],
                'street'        => $formData['form_street_number'],
                'zip_code'      => $formData['form_zip_city'],
                'phone_number'  => $formData['form_phone'],
                'email'         => $formData['form_email'],
                'note'          => $formData['form_note'],
                'time_created'  => date('Y-m-d H:i:s'),
                'accept_policy' => $formData['form_policy'],
                'session_id'    => $sessionID,
                'log_out'       => FALSE,
            ]
        );
    }

    public function getUserBySession($sessionID)
    {
        $conn = $this->getEntityManager()
                     ->getConnection();

        $sql = 'SELECT * FROM guest WHERE session_id = :sessionID';
        $stmt = $conn->prepare($sql);
        $stmt->execute
        (
            [
                'sessionID' => $sessionID,
            ]
        );
        $result = $stmt->fetchAllAssociative();

        if ($result)
        {
            return $result;
        }
        else
        {
            return FALSE;
        }
    }
}
