<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByChat(User $sender, User $receiver)
    {
        return $this->createQueryBuilder('m')
            ->where('(m.sender = :sender AND m.receiver = :receiver) OR (m.sender = :receiver AND m.receiver = :sender)')
            ->setParameter('sender', $sender)
            ->setParameter('receiver', $receiver)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * recuperer les conversations recentes d'un utilisateur
     * @param $userId
     * @return array
     * 
     */
    public function findRecentConversations_($userId){
        $qb = $this->createQueryBuilder('m');
        $qb->select('u.id AS contactId, u.username AS contactName, MAX(m.createdAt) AS lastMessageTime, SUBSTRING(m.content, 1, 50) AS lastMessage')
        ->innerJoin('m.receiver', 'u')
        ->where('m.receiver = :userId')
        ->setParameter('userId', $userId)
        ->groupBy('contactName')
        ->orderBy('lastMessageTime', 'DESC')
        ->setMaxResults(10);
        return $qb->getQuery()->getResult();
    }
    // public function findRecentConversations($userId){
    //     return $this->createQueryBuilder('m')
    //     ->select('u.id AS contactId, u.username AS contactName, MAX(m.createdAt) AS lastMessageTime, SUBSTRING(m.content, 1, 50) AS lastMessage')
    //     ->innerJoin('m.receiver', 'u')
    //     ->where('m.receiver = :userId')
    //     ->setParameter('userId', $userId)
    //     ->groupBy('u.id')
    //     ->orderBy('lastMessageTime', 'DESC')
    //     ->setMaxResults(10)
    //     ->getQuery()
    //     ->getResult();

    // }

    public function findRecentConversations(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->select(
                'CASE 
                WHEN m.sender = :user THEN receiver.username 
                ELSE sender.username 
             END AS contactUsername',
                'CASE 
                WHEN m.sender = :user THEN m.receiver 
                ELSE m.sender 
             END AS contactId',
                'MAX(m.createdAt) AS lastMessageTime',
                'SUBSTRING(m.content, 1, 50) AS lastMessage',
                'SUM(CASE WHEN m.isRead = 0 AND m.receiver = :user THEN 1 ELSE 0 END) AS unreadCount'
            )
            ->join('m.sender', 'sender')
            ->join('m.receiver', 'receiver')
            ->where('m.sender = :user OR m.receiver = :user')
            ->groupBy('contactId', 'contactUsername')
            ->orderBy('lastMessageTime', 'DESC')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }





}
