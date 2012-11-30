<?php

namespace hflan\BlogBundle\Service;

class hflanSlugify
{
    public function slugify($text)
    {
        $return = preg_replace('#[^a-zA-Z0-9 ]#', '', $text);
        $return = trim($return);
        $return = preg_replace('# +#', '-', $return);
        return strtolower($return);
    }
}