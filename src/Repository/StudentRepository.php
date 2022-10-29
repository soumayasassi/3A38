<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function add(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Student[] Returns an array of Student objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

 public function findOneByNSC($value): ?Student
    {
       return $this->createQueryBuilder('s')
           ->andWhere('s.nsc = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
      ;
  }
  //Question 1 QB
    public function TriparEmail(): array
    {return $this->createQueryBuilder('s')
        ->orderBy('s.email', 'ASC')
        ->getQuery()
        ->getResult() ;
    }
/*
 * Question 5 QB
 */
    public function findEtat($value): array
    {return $this->createQueryBuilder('s')
        ->where('s.enabled = :val')
         ->setParameter('val', $value)
        ->getQuery()
        ->getResult() ;
    }
    public function listStudentsByClasse($id): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.class','c')
            ->addSelect('c')
            ->Where('c.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult()
            ;
    }
/*
 * Question 2 DQL
 */
    public function findByMoyenne($id){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT avg(s.moyenne) FROM App\Entity\Student  s JOIN s.class  c where c.id =:id")
            ->setParameter('id',$id) ;
        return $query->getSingleResult();
    }
    /**
     * Question 3 DQL
     */
    public function findStudentByAVG($min,$max){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT s FROM APP\Entity\Student 
            s WHERE s.moyenne BETWEEN :min AND :max")
            ->setParameter('min',$min)
            ->setParameter('max',$max);
        return $query->getResult();
    }

        //Question 4 -DQL
        public function findRedoublants($id){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT s, c FROM App\Entity\Student 
            s INNER JOIN s.class c WHERE s.moyenne <=8 AND c.id = :id")
           ->setParameter('id',$id) ;
        return $query->getResult();
    }

}
