<?php

namespace hflan\InfoBundle\Service;

class hflanDateFormat extends \Twig_Extension
{
    protected $trans;
    protected $locale;

    public function __construct(\Symfony\Bundle\FrameworkBundle\Translation\Translator $trans, $locale)
    {
        $this->trans   = $trans;
        $this->locale  = (string) $locale;
    }

    public function getFilters()
    {
        return array(
            'toHuman' => new \Twig_Filter_Method($this, 'toHuman'),
        );
    }

    public function toHuman(\DateTime $date)
    {
        $now = new \DateTime();
        $fakeNow = new \DateTime();
        $fakeDate = new \DateTime($date->format("Y-m-d\TH:i:sP"));

        $fakeDate->setTime(0,0);
        $fakeNow->setTime(0,0);

        $diff = $date->diff($now);
        $fakeDiff = $fakeDate->diff($fakeNow);

        $time = $diff->invert ? 'future' : 'past';

        if($fakeDiff->m)
        {
            if($fakeDiff->d < 4)
                return $this->trans->trans("date.$time.Xmonth", array('%x%'=> $diff->m));
            elseif($fakeDiff->d >15)
                return $this->trans->trans("date.$time.lessThan.Xmonth", array('%x%'=> $diff->m+1));
            else
                return $this->trans->trans("date.$time.moreThan.Xmonth", array('%x%'=> $diff->m));
        }
        else if($fakeDiff->d >= 14)
        {
            if($fakeDiff->d%7 == 0)
                return $this->trans->trans("date.$time.Xweek", array('%x%'=> $diff->d/7));
            else
                return $this->trans->trans("date.$time.lessThan.Xweek", array('%x%'=> ceil($diff->d/7)));
        }
        else if($fakeDiff->d >= 7)
            return $this->trans->trans("date.$time.Xday", array('%x%'=> $diff->d));
        else if($fakeDiff->d >= 2)
            return $this->trans->trans("date.$time.dayAt", array('%day%'=> $this->trans->trans("date.day.".$date->format("N")), "%at%"=>$date->format("H:i")));
        elseif($fakeDiff->d == 1)
            return $this->trans->trans("date.$time.tomorrow", array("%at%"=>$date->format("H:i")));
        elseif($diff->h > 1)
        {
            if($diff->i < 10)
                return $this->trans->trans("date.$time.Xhour", array('%x%'=> $diff->h));
            elseif($diff->i >30)
                return $this->trans->trans("date.$time.lessThan.Xhour", array('%x%'=> $diff->h+1));
            else
                return $this->trans->trans("date.$time.moreThan.Xhour", array('%x%'=> $diff->h));
        }
        else if($diff->i)
            return $this->trans->trans("date.$time.Xmin", array('%x%'=> $diff->i));
        else
            return $this->trans->trans("date.$time.Xsec", array('%x%'=> $diff->s));
    }

    public function getName()
    {
        return 'hflan_dateFormat';
    }
}