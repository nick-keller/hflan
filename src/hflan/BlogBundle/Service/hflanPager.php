<?php

namespace hflan\BlogBundle\Service;

class hflanPager extends \Twig_Extension
{
    protected $router;

    public function __construct(\Symfony\Bundle\FrameworkBundle\Routing\Router $router)
    {
        $this->router   = $router;
    }

    public function getFunctions()
    {
        return array(
            'pagination' => new \Twig_Function_Method($this, 'pagination'),
        );
    }

    public function pagination($page, $nb_pages, $route, $routePrams = array())
    {
        echo '<nav class="pagination">';
            echo '<ul>';
            if($page != 1)
            {
                echo '<li>';
                    echo '<a href="'.$this->router->generate($route, $this->getParamsForPage($page-1, $routePrams)).'"><i class="icon-chevron-left"></i></a>';
                echo '</li> ';
            }
            for($p=1; $p<=$nb_pages; ++$p)
            {
                echo '<li '.($p == $page ? 'class="active"':'').'>';
                    echo '<a href="'.$this->router->generate($route, $this->getParamsForPage($p, $routePrams)).'">'.$p.'</a>';
                echo '</li> ';
            }
            if($page != $nb_pages)
            {
                echo '<li>';
                    echo '<a href="'.$this->router->generate($route, $this->getParamsForPage($page+1, $routePrams)).'"><i class="icon-chevron-right"></i></a>';
                echo '</li>';
            }
            echo '</ul>';
        echo '</nav>';
    }

    private function getParamsForPage($page, $params)
    {
        $r = array();
        foreach($params as $key => $param)
            $r[$key] = $param;
        $r['page'] = $page;
        return $r;
    }

    public function getName()
    {
        return 'hflan_pager';
    }
}