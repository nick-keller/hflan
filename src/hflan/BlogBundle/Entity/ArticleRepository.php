<?php

namespace hflan\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function count($lang = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a)');

        if($lang != null)
            $qb->where('a.lang = :lang')
                ->setParameter('lang', $lang);

        return (int) $qb->getQuery()
            ->getSingleScalarResult();
    }

    public function countPublished($lang = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.published = true');

        if($lang != null)
            $qb->andWhere('a.lang = :lang')
                ->setParameter('lang', $lang);

        return (int) $qb->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalPages($articlesPerPage, $onlyVisible = false, $lang = null)
    {
        return max(1, ceil(
            ($onlyVisible ? $this->countPublished($lang) : $this->count($lang))
                / $articlesPerPage
        ));
    }

    public function getPage($page, $articlesPerPage, $onlyVisible = false, $lang = null)
    {
        if( $page < 1 OR $page > $this->getTotalPages($articlesPerPage, $onlyVisible, $lang) )
            return false;

        $where = array();
        if($onlyVisible)
            $where['published'] = true;
        if($lang != null)
            $where['lang'] = $lang;

        return $this->findBy(
            $where,
            array('created_at' => 'desc'),
            $articlesPerPage,
            ($page-1) * $articlesPerPage
        );
    }
}
